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
			    ->where(function ($query) {
			        $query->where('status', 'Accepted')
			              ->orWhere('status', 'Delivered');
			    })->get();
			    
        $report = $orders->map(function ($order) {
				    // Determine the correct customer name per order
				    if ($order->user->user_type == 'admin') {
				        $customerName = $order->walkin->customer_name ?? 'Walk-in';
				    } else {
				        $customerName = $order->user->name;
				    }
				
				    return [
				        'customer' => $customerName,
				        'user_id' => $order->user_id,
				        'items' => $order->items,
				        'total_amount' => $order->total_amount,
				    ];
				})
				// Group by the customer name (each walk-in customer name will have their own group)
				->groupBy('customer')
				->map(function ($customerOrders) {
				    $products = [];
				    $totalQuantity = 0;
				    $totalAmount = 0;
				
				    foreach ($customerOrders as $order) {
				        foreach ($order['items'] as $item) {
				            $products[] = $item->product->product_name;
				            $totalQuantity += $item->quantity;
				        }
				        $totalAmount += $order['total_amount'];
				    }
				
				    return [
				        'customer' => $customerOrders->first()['customer'],
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
    $selectedWeek = $request->input('week'); // Optional week selection

    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
    $endDate = $startDate->copy()->endOfMonth();

    // ✅ Retrieve only Accepted or Delivered orders within the month
    $orders = Order::with(['items.product', 'user'])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->where(function ($query) {
            $query->where('status', 'Accepted')
                  ->orWhere('status', 'Delivered');
        })
        ->get();

    // ✅ Group orders by week and day
    $report = collect([]);

    foreach ($orders as $order) {
        $weekNumber = Carbon::parse($order->created_at)->weekOfMonth;
        $dayName = Carbon::parse($order->created_at)->format('l');

        // Initialize week collection
        if (!$report->has($weekNumber)) {
            $report->put($weekNumber, collect([]));
        }

        $weekData = $report->get($weekNumber);

        // Initialize day entry if not exists
        if (!$weekData->has($dayName)) {
            $weekData->put($dayName, [
                'products' => [],
                'quantity' => 0,
                'total' => 0,
                'day' => $dayName,
            ]);
        }

        $dayData = $weekData->get($dayName);

        foreach ($order->items as $item) {
            $productName = $item->product->product_name;

            // If product already exists, just add to its quantity/total
            if (!isset($dayData['products'][$productName])) {
                $dayData['products'][$productName] = [
                    'quantity' => 0,
                    'total' => 0,
                ];
            }

            $dayData['products'][$productName]['quantity'] += $item->quantity;
            $dayData['products'][$productName]['total'] += $item->quantity * $item->price;

            // Add to daily totals
            $dayData['quantity'] += $item->quantity;
            $dayData['total'] += $item->quantity * $item->price;
        }

        $weekData->put($dayName, $dayData);
        $report->put($weekNumber, $weekData);
    }

    // ✅ Calculate total per week/day
    $weeklyTotal = $report->map(function ($weekDays) {
        return $weekDays->map(function ($dayOrders) {
            return [
                'quantity' => $dayOrders['quantity'],
                'total' => $dayOrders['total'],
            ];
        });
    });

    // ✅ Filter by selected week if applicable
    if ($selectedWeek) {
        $report = $report->only([$selectedWeek]);
        $weeklyTotal = $weeklyTotal->only([$selectedWeek]);
    }

    // ✅ Calculate grand totals across all weeks
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

    public function monthlyReport(Request $request)
    {
        $selectedYear = $request->input('year') ?? Carbon::now()->year;
        $selectedMonth = $request->input('month') ?? Carbon::now()->month;

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $orders = Order::with(['items.product', 'user'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
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
