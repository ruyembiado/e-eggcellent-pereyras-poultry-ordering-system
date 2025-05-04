<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report');
    }

    public function dailyReport(Request $request)
    {
        $date = $request->input('date') ?? Carbon::today()->toDateString();

        $orders = Order::with(['items.product', 'user'])
            ->whereDate('created_at', $date)
            ->where('status', 'Accepted')
            ->orWhere('status', 'Delivered')
            ->get();

        $report = $orders->groupBy('user_id')->map(function ($userOrders) {
            $customerName = $userOrders->first()->user->name;

            $products = [];
            $totalQuantity = 0;
            $totalAmount = 0;

            foreach ($userOrders as $order) {
                foreach ($order->items as $item) {
                    $products[] = $item->product->product_name;
                    $totalQuantity += $item->quantity;
                }
                $totalAmount += $order->total_amount;
            }

            return [
                'customer' => $customerName,
                'products' => implode(', ', array_unique($products)),
                'quantity' => $totalQuantity,
                'total' => $totalAmount,
            ];
        });

        $dayName = Carbon::parse($date)->format('l');

        return view('admin.daily_report', [
            'report' => $report,
            'date' => $date,
            'day' => $dayName,
        ]);
    }

    public function weeklyReport(Request $request)
    {
        $selectedYear = $request->input('year') ?? Carbon::now()->year;
        $selectedMonth = $request->input('month') ?? Carbon::now()->month;
        $selectedWeek = $request->input('week');  // Week selection input

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Retrieve orders within the selected month
        $orders = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Accepted')
            ->orWhere('status', 'Delivered')
            ->get();

        // Group orders by week within the selected month
        $report = collect([]);
        foreach ($orders as $order) {
            $weekNumber = Carbon::parse($order->created_at)->weekOfMonth; // Get the week number of the month
            $dayName = Carbon::parse($order->created_at)->format('l'); // Get the day of the week (e.g., Monday)

            $products = [];
            $totalQuantity = 0;
            $totalAmount = 0;

            foreach ($order->items as $item) {
                $products[$item->product->product_name] = true;
                $totalQuantity += $item->quantity;
                $totalAmount += $item->quantity * $item->price;
            }

            // Initialize report as a Collection for each week and day
            if (!$report->has($weekNumber)) {
                $report->put($weekNumber, collect([]));
            }

            $weekData = $report->get($weekNumber);

            if (!$weekData->has($dayName)) {
                $weekData->put($dayName, []);
            }

            $weekData->put($dayName, $weekData->get($dayName, []) + [
                [
                    'products' => implode(', ', array_keys($products)),
                    'quantity' => $totalQuantity,
                    'total' => $totalAmount,
                    'day' => $dayName,
                ]
            ]);
        }

        // Calculate the total for each week and day
        $weeklyTotal = $report->map(function ($weekDays) {
            $weekData = [];
            foreach ($weekDays as $dayOrders) {
                $weekData[] = [
                    'quantity' => collect($dayOrders)->sum('quantity'),
                    'total' => collect($dayOrders)->sum('total'),
                ];
            }
            return $weekData;
        });

        // Filter by selected week if applicable
        if ($selectedWeek) {
            $report = $report->only([$selectedWeek]);
            $weeklyTotal = $weeklyTotal->only([$selectedWeek]);
        }

        // Calculate the grand total across all weeks
        $grandTotalQuantity = $weeklyTotal->flatten(1)->sum('quantity');
        $grandTotalAmount = $weeklyTotal->flatten(1)->sum('total');

        return view('admin.weekly_report', [
            'report' => $report,
            'weeklyTotal' => $weeklyTotal,
            'start_date' => $startDate->format('F d, Y'),
            'end_date' => $endDate->format('F d, Y'),
            'selected_year' => $selectedYear,
            'selected_month' => $selectedMonth,
            'selected_week' => $selectedWeek,
            'month_name' => $startDate->format('F'),
            'grandTotalQuantity' => $grandTotalQuantity,
            'grandTotalAmount' => $grandTotalAmount,
        ]);
    }

    /**
     * Generate week options for dropdown
     */
    protected function getWeekOptions($year = null)
    {
        $options = [];
        $year = $year ?? Carbon::now()->year;

        $startOfYear = Carbon::createFromDate($year)->startOfYear();
        $endOfYear = Carbon::createFromDate($year)->endOfYear();

        $currentWeek = $startOfYear;

        while ($currentWeek <= $endOfYear) {
            $weekNum = $currentWeek->format('W');
            $key = "{$year}-{$weekNum}";
            $display = "Week {$weekNum} (" . $currentWeek->startOfWeek()->format('M d') . ' - ' . $currentWeek->endOfWeek()->format('M d') . ')';
            $options[$key] = $display;
            $currentWeek->addWeek();
        }

        return $options;
    }

    public function monthlyReport(Request $request)
    {
        $selectedYear = $request->input('year') ?? Carbon::now()->year;
        $selectedMonth = $request->input('month') ?? Carbon::now()->month;

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $orders = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Accepted')
            ->orWhere('status', 'Delivered')
            ->get();

        $report = $orders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('W'); // Group by week number
        })->map(function ($weekOrders, $weekNumber) {
            $products = [];
            $totalQuantity = 0;
            $totalAmount = 0;

            foreach ($weekOrders as $order) {
                foreach ($order->items as $item) {
                    $products[$item->product->product_name] = true;
                    $totalQuantity += $item->quantity;
                    $totalAmount += $item->quantity * $item->price;
                }
            }

            return [
                'week_number' => $weekNumber,
                'products' => implode(', ', array_keys($products)),
                'quantity' => $totalQuantity,
                'total' => $totalAmount,
            ];
        });

        return view('admin.monthly_report', [
            'report' => $report,
            'selected_year' => $selectedYear,
            'selected_month' => $selectedMonth,
            'month_name' => Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F'),
            'start_date' => $startDate->format('F d, Y'),
            'end_date' => $endDate->format('F d, Y'),
        ]);
    }

    public function yearlyReport(Request $request)
    {
        $selectedYear = $request->input('year') ?? Carbon::now()->year;

        $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        $orders = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Accepted')
            ->orWhere('status', 'Delivered')
            ->get();

        $report = $orders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('m'); // Group by month
        })->map(function ($monthOrders, $monthNumber) {
            $products = [];
            $totalQuantity = 0;
            $totalAmount = 0;

            foreach ($monthOrders as $order) {
                foreach ($order->items as $item) {
                    $products[$item->product->product_name] = true;
                    $totalQuantity += $item->quantity;
                    $totalAmount += $item->quantity * $item->price;
                }
            }

            return [
                'month_number' => $monthNumber,
                'products' => implode(', ', array_keys($products)),
                'quantity' => $totalQuantity,
                'total' => $totalAmount,
            ];
        });

        return view('admin.yearly_report', [
            'report' => $report,
            'selected_year' => $selectedYear,
            'year_name' => $selectedYear,
            'start_date' => $startDate->format('F d, Y'),
            'end_date' => $endDate->format('F d, Y'),
        ]);
    }
}
