<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.product', compact('products'));
    }

    public function create()
    {
        return view('admin.product_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_price' => 'required|numeric',
            'quantity' => 'nullable|integer|min:0',
            'status' => 'required',
        ]);

        $imagePath = null;

        if ($request->hasFile('product_image')) {
            $imageName = time() . '.' . $request->product_image->extension();
            $request->file('product_image')->move(public_path('img/product_uploads'), $imageName);
            $imagePath = 'img/product_uploads/' . $imageName;
        }

        Product::create([
            'product_name' => $request->product_name,
            'product_image' => $imagePath,
            'product_price' => number_format((float) $request->product_price, 2, '.', ''),
            'quantity' => $request->quantity ?? 0,
            'status' => $request->status,
        ]);

        return redirect('/products/all')->with('success', 'Product added successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product_edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_price' => 'required|numeric',
            'quantity' => 'nullable|integer|min:0',
            'status' => 'required|string',
        ]);

        $product = Product::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            // Delete old image
            if (file_exists(public_path($product->product_image))) {
                unlink(public_path($product->product_image));
            }

            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('img/product_uploads'), $imageName);

            $product->product_image = 'img/product_uploads/' . $imageName;
        }

        $product->update([
            'product_name' => $request->product_name,
            'product_price' => number_format((float) $request->product_price, 2, '.', ''),
            'quantity' => $request->quantity ?? 0,
            'status' => $request->status,
        ]);

        return redirect('/products/all')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->product_image && file_exists(public_path($product->product_image))) {
            unlink(public_path($product->product_image));
        }

        $product->delete();

        return redirect('/products/all')->with('success', 'Product deleted successfully.');
    }
}
