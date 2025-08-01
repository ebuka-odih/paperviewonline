<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        // Check if coming soon is enabled
        $settings = Setting::getComingSoonSettings();
        
        if ($settings['enabled'] && !session('coming-soon-bypassed')) {
            return view('pages.coming-soon', compact('settings'));
        }
        
        // Normal index page
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
    
    public function verifyComingSoonPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);
        
        $settings = Setting::getComingSoonSettings();
        
        if ($settings['enabled'] && $request->password === $settings['password']) {
            // Store in session that user has bypassed coming soon
            session(['coming-soon-bypassed' => true]);
            
            return redirect()->route('index')->with('success', 'Welcome! You now have access to the site.');
        }
        
        return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
    }
}
