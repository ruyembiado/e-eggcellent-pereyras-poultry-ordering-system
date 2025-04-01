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

    public function checkout()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $totalAmount = $cartItems->sum('subtotal');

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'status' => 'Pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ]);
        }

        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
