<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (auth()->user()->user_type == 'admin') {
            $orders = Order::orderBy('created_at', 'desc')->with('items')->get();
            return view('admin.request_order', compact('orders'));
        } else {
            $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->with('user')->get();
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
