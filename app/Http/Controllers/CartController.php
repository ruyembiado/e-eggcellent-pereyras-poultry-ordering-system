<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        $cartTotal = $cartItems->sum('subtotal');

        return view('customer.cart', compact('cartItems', 'cartTotal'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $subtotal = $product->product_price * $request->quantity;

        $cart = Cart::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $request->product_id],
            ['quantity' => $request->quantity, 'subtotal' => $subtotal]
        );

        if ($cart->wasRecentlyCreated) {
            return back()->with('success', 'Product added to cart!');
        } else {
            return back()->with('success', 'Product quantity updated in cart!');
        }
    }

    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Product removed from cart!');
    }

    public function checkout(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to proceed with checkout.');
        }

        $cartItems = Cart::where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->product_price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'EGG-' . strtoupper(uniqid()),
            'total_amount' => $totalAmount,
            'shipping_address' => $request->shipping_address,
            'type_of_service' => $request->type_of_service,
            'pick_up_datetime' => $request->pick_up_datetime ?? null,
            'delivery_notes' => $request->delivery_notes ?? null,
            'status' => 'Pending',
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

        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('order.index')->with('success', 'Order placed successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::findOrFail($id);
        $product = $cartItem->product;

        $cartItem->quantity = $request->quantity;
        $cartItem->subtotal = $product->product_price * $request->quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }
}
