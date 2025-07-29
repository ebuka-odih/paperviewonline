@extends('pages.layout.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-4xl mx-auto bg-black rounded-2xl shadow-lg p-8 mt-8 text-white">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    @if(session('cart_message'))
        <div class="mb-6 p-4 bg-green-900/80 text-green-200 rounded-lg">
            {{ session('cart_message') }}
        </div>
    @endif

    @if(session('cart_error'))
        <div class="mb-6 p-4 bg-red-900/80 text-red-200 rounded-lg">
            {{ session('cart_error') }}
        </div>
    @endif

    @if($cart && count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-gray-900 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Cart Items ({{ $cartCount }})</h2>
                    <div class="space-y-4">
                        @foreach($cart as $productId => $item)
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 border border-gray-700 rounded-lg">
                                <!-- Product Image and Details -->
                                <div class="flex items-center space-x-4 min-w-0 flex-1">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" 
                                             class="w-16 h-16 object-cover rounded">
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-semibold text-white truncate">{{ $item['name'] }}</h3>
                                        <p class="text-[#65644A] font-bold">₦{{ number_format($item['price'], 2) }}</p>
                                    </div>
                                </div>

                                <!-- Controls and Total Row -->
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 min-w-0">
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('cart.update') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $productId }}" />
                                            <input type="hidden" name="action" value="decrease" />
                                            <button type="submit" 
                                                    class="w-8 h-8 bg-gray-700 hover:bg-gray-600 text-white rounded flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        
                                        <span class="w-12 text-center font-semibold">{{ $item['quantity'] }}</span>
                                        
                                        <form action="{{ route('cart.update') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $productId }}" />
                                            <input type="hidden" name="action" value="increase" />
                                            <button type="submit" 
                                                    class="w-8 h-8 bg-gray-700 hover:bg-gray-600 text-white rounded flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Item Total and Remove Button -->
                                    <div class="flex items-center justify-between sm:justify-end gap-4">
                                        <!-- Item Total -->
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-white">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                        </div>

                                        <!-- Remove Button -->
                                        <div class="flex-shrink-0">
                                            <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="product_id" value="{{ $productId }}" />
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-gray-900 rounded-lg p-6 sticky top-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-white">Cart Summary</h2>
                        <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal ({{ $cartCount }} items):</span>
                            <span>₦{{ $cartTotal }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Tax:</span>
                            <span>₦0.00</span>
                        </div>
                        <div class="border-t border-gray-700 pt-3 flex justify-between text-lg font-semibold text-white">
                            <span>Total:</span>
                            <span class="text-[#65644A]">₦{{ $cartTotal }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('checkout.show') }}" 
                           class="w-full bg-[#65644A] hover:bg-[#65644A]/90 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center block">
                            Proceed to Checkout
                        </a>
                        
                        <a href="{{ route('index') }}" 
                           class="w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center block">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
            <p class="text-gray-400 mb-6">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('index') }}" 
               class="inline-block bg-[#65644A] hover:bg-[#65644A]/90 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection 