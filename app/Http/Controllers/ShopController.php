<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::OrderBy('created_at', 'desc')->get();
        return view('shop', compact('products'));
    }
}
