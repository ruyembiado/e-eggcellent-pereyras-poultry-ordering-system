<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Walkin;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function showWalkInOrderForm()
    {
        $products = Product::all();
        return view('admin.order_create', compact('products'));
    }

    public function createWalkInOrder(Request $request)
    {
        $cartItems = [];
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[] = (object)[
                    'product_id' => $product->id,
                    'quantity' => $request->quantity[$index],
                    'product' => $product,
                ];
            }
        }

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'order_number' => 'EGG-' . strtoupper(uniqid()),
            'total_amount' => $request->total_amount,
            'shipping_address' => 'Walk-in',
            'type_of_service' =>  'Walk-in',
            'delivery_notes' => null,
            'status' => 'Delivered',
        ]);
        
        $walkin = Walkin::create([
		        'order_id' => $order->id,
		        'customer_name' => $request->customer_name,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'price' => $item->product->product_price,
                'quantity' => $item->quantity,
                'subtotal' => $item->product->product_price * $item->quantity,
            ]);

            $product = Product::find($item->product_id);
            if ($product) {
                // Calculate new stock
                $newStock = $product->quantity - $item->quantity;
                // Prevent negative stock
                $product->quantity = max(0, $newStock);
                $product->save();
            }
        }

        return redirect()->route('order.index')->with('success', 'Walk-in order created successfully.');
    }

    public function index(Request $request)
    {
        if (auth()->user()->user_type == 'admin') {
            $orders = Order::when($request->filled('status') && $request->status !== 'All', function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
                ->orderBy('created_at', 'desc')
                ->with('items', 'rating', 'walkin')
                ->get();

            return view('admin.request_order', compact('orders'));
        } else {
            $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->with('user', 'rating')->get();
            return view('customer.order', compact('orders'));
        }
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        if (auth()->user()->user_type == 'admin') {
            return view('admin.order_view', compact('order'));
        } else {
            return view('customer.order_view', compact('order'));
        }
    }

    public function accept(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->status = 'Accepted';
        $order->delivery_date = $request->delivery_date;
        if ($request->comment) {
            $order->comment = $request->comment;
        }
        $order->save();
        
        $apiKey = env('SEMAPHORE_API_KEY'); 
				$number = preg_replace('/^0/', '63', $order->user->phone_number);
		    $date = Carbon::parse($order->delivery_date)->format('F j, Y, l');
		    $message = "Hi ". $order->user->name .", your order #". $order->order_number ." is scheduled for delivery on ". $date . ".";

				$auth = new AuthController();
				$result = $auth->sendSMSNotification($apiKey, $number, $message);

        return redirect()->back()->with('success', 'Order has been accepted.');
    }

    public function receivedOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'Delivered';
        $order->save();

        return redirect()->back()->with('success', 'Order has been received.');
    }

    public function decline(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->status = 'Cancelled';
        $order->save();

        return redirect()->back()->with('success', 'Order has been cancelled.');
    }
}
