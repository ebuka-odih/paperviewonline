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

        // Nothing to unlock if the frontpage is open, and no passcode to check if the
        // admin has not asked visitors for one -- in both cases just send them home.
        if (! Setting::isComingSoonEnabled() || ! Setting::comingSoonPasscodeRequired()) {
            return redirect()->route('index');
        }

        $settings = Setting::getComingSoonSettings();

        if (hash_equals((string) $settings['password'], (string) $request->password)) {
            // Store in session that user has bypassed coming soon
            session(['coming-soon-bypassed' => true]);
            
            return redirect()->route('index')->with('success', 'Welcome! You now have access to the site.');
        }
        
        return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
    }
}
