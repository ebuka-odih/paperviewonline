<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    // Show the checkout form
    public function show(Request $request)
    {
        $cart = Session::get('cart', []);
        $cartTotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        return view('pages.checkout', compact('cart', 'cartTotal'));
    }

    // Process the checkout form
    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
        ]);

        // Here you would create the order, send emails, etc.
        // For now, just clear the cart and show a success message
        Session::forget('cart');
        return redirect()->route('index')->with('success', 'Order placed successfully!');
    }
} 