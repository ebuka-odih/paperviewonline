<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Debug the incoming request
        \Log::info('Add to cart request', [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'all_data' => $request->all()
        ]);

        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if product exists manually
        $product = Product::find($request->product_id);
        if (!$product) {
            \Log::error('Product not found', ['product_id' => $request->product_id]);
            return back()->with('cart_error', 'Product not found.');
        }
        
        // Check if product is in stock
        if ($product->stock < $request->quantity) {
            return back()->with('cart_error', 'Not enough stock available. Only ' . $product->stock . ' items left.');
        }

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        // If product already exists in cart, update quantity
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;
            
            // Check if new total quantity exceeds stock
            if ($newQuantity > $product->stock) {
                return back()->with('cart_error', 'Cannot add more items. Only ' . $product->stock . ' items available in total.');
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            // Add new product to cart
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->sale_price ?? $product->price,
                'quantity' => $request->quantity,
                'image' => $product->images()->first()?->url ?? asset('assets/images/product/placeholder.svg'),
                'stock' => $product->stock,
            ];
        }

        Session::put('cart', $cart);

        \Log::info('Product added to cart successfully', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $request->quantity,
            'cart_count' => $this->getCartCount()
        ]);

        return back()->with('cart_message', $product->name . ' added to cart successfully!');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        if (!$product) {
            return back()->with('cart_error', 'Product not found.');
        }
        
        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (!isset($cart[$productId])) {
            return back()->with('cart_error', 'Product not found in cart.');
        }

        // Check if quantity exceeds stock
        if ($request->quantity > $product->stock) {
            return back()->with('cart_error', 'Cannot update quantity. Only ' . $product->stock . ' items available.');
        }

        $cart[$productId]['quantity'] = $request->quantity;
        Session::put('cart', $cart);

        return back()->with('cart_message', 'Quantity updated successfully!');
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $productName = $cart[$productId]['name'];
            unset($cart[$productId]);
            Session::put('cart', $cart);

            return back()->with('cart_message', $productName . ' removed from cart successfully!');
        }

        return back()->with('cart_error', 'Product not found in cart.');
    }

    public function view()
    {
        $cart = Session::get('cart', []);
        $cartCount = $this->getCartCount();
        $cartTotal = $this->getCartTotal();
        
        return view('pages.cart', compact('cart', 'cartCount', 'cartTotal'));
    }

    public function getCart()
    {
        $cart = Session::get('cart', []);
        
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
        ]);
    }

    public function clearCart()
    {
        Session::forget('cart');
        
        return back()->with('cart_message', 'Cart cleared successfully!');
    }

    private function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    private function getCartTotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return number_format($total, 2);
    }

    public function sidebar(Request $request)
    {
        if (!$request->header('X-Splade-Modal')) {
            return redirect('/');
        }
        $cart = Session::get('cart', []);
        $cartCount = $this->getCartCount();
        $cartTotal = $this->getCartTotal();
        return view('pages.cart-sidebar', compact('cart', 'cartCount', 'cartTotal'));
    }
} 