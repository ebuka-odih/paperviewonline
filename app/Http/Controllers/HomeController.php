<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->take(4)->get();
        return view('pages.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['images' => function($query) {
            $query->orderBy('sort_order');
        }, 'category']);
        
        return view('pages.product-show', compact('product'));
    }
}
