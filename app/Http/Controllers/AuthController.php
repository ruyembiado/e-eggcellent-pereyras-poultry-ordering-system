<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function index()
    {

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->user_type == 'admin' || $user->user_type == 'customer') {
                return redirect('/dashboard');
            }
        }
        return view('welcome');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            if ($user->status === 'active') {
                if ($user->user_type == 'admin') {
                    return redirect('/dashboard')->with('success', 'Login successful');
                } else {
                    return redirect('/')->with('success', 'Login successful');
                }
            } else {
                auth()->logout();
                return back()
                    ->withErrors(['error' => 'Your account is not yet active.'])
                    ->withInput($request->only('email'));
            }
        }

        return back()
            ->withErrors(['error' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email', 'admin_login'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string',
            'home_address' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'confirm_password' => 'required|same:password',
        ]);

        User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'home_address' => $request->home_address,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', 'Registration successful. Admin will verify your account.');
    }

    public function dashboard()
    {
        $user = auth()->user();

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();
        $currentYear = Carbon::now()->year;

        if ($user->user_type == 'admin') {
            $ordersToday = Order::whereDate('created_at', $today)->count();
            $ordersThisWeek = Order::where('created_at', '>=', $startOfWeek)->count();
            $ordersThisMonth = Order::where('created_at', '>=', $startOfMonth)->count();
            $ordersThisYear = Order::where('created_at', '>=', $startOfYear)->count();

            // Get all orders for order table this month
            $ordersMonth = Order::where('created_at', '>=', $startOfMonth)
                ->orderBy('created_at', 'desc')
                ->get();

            // Chart: Monthly Orders
            $monthlyOrders = DB::table('orders')
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                ->whereYear('created_at', $currentYear)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('month')
                ->get();

            $ordersPerMonth = array_fill(1, 12, 0);
            foreach ($monthlyOrders as $row) {
                $ordersPerMonth[$row->month] = $row->total;
            }

            $months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];

            return view('admin.dashboard', [
                'ordersToday' => $ordersToday,
                'ordersThisWeek' => $ordersThisWeek,
                'ordersThisMonth' => $ordersThisMonth,
                'ordersThisYear' => $ordersThisYear,
                'orders' => $ordersMonth,
                'ordersPerMonth' => array_values($ordersPerMonth),
                'months' => $months,
            ]);
        } else {
            $ordersToday = Order::where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->count();

            $ordersThisWeek = Order::where('user_id', $user->id)
                ->where('created_at', '>=', $startOfWeek)
                ->count();

            $ordersThisMonth = Order::where('user_id', $user->id)
                ->where('created_at', '>=', $startOfMonth)
                ->count();

            $ordersThisYear = Order::where('user_id', $user->id)
                ->where('created_at', '>=', $startOfYear)
                ->count();

            return view('customer.dashboard', [
                'ordersToday' => $ordersToday,
                'ordersThisWeek' => $ordersThisWeek,
                'ordersThisMonth' => $ordersThisMonth,
                'ordersThisYear' => $ordersThisYear,
            ]);
        }
    }

    public function contact()
    {
        return view('contact');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'Logout successful');
    }

    public function loginPage()
    {
        return view('login');
    }

    public function RegisterPage()
    {
        return view('register');
    }
    
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $adminEmail = env('MAIL_FROM_ADDRESS');

        Mail::raw(
            "Name: {$validated['name']}\n" .
            "Email: {$validated['email']}\n" .
            "Phone: {$validated['phone']}\n" .
            "Message: {$validated['message']}\n",
            function ($mail) use ($validated, $adminEmail) {
                $mail->to($adminEmail)
                     ->subject('New Contact Message from ' . $validated['name']);
            }
        );

        return back()->with('success', 'Your message has been sent successfully!');
    }
    
    public function sendSMSNotification($apiKey, $number, $message) {
	    // If there's no API key, do not proceed
	    if (empty($apiKey)) {
	        return [
	            'success' => false,
	            'message' => 'API key is missing. SMS not sent.'
	        ];
	    }
	
	    // Semaphore SMS API endpoint
	    $url = 'https://semaphore.co/api/v4/messages';
	
	    // Prepare POST fields
	    $params = [
	        'apikey' => $apiKey,
	        'number' => $number,
	        'message' => $message,
	        'sendername' => 'SEMAPHORE', // Optional: You can register a sender name in Semaphore
	    ];
	
	    // Send SMS using cURL
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	    $response = curl_exec($ch);
	    $error = curl_error($ch);
	    curl_close($ch);
	
	    // Handle response
	    if ($error) {
	        return [
	            'success' => false,
	            'message' => 'cURL Error: ' . $error
	        ];
	    }
	
	    return [
	        'success' => true,
	        'response' => json_decode($response, true)
	    ];
	}

}
