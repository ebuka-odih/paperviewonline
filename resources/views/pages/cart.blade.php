<x-splade-layout>
    <x-slot:title>
        Shopping Cart - {{ env('APP_NAME') }}
    </x-slot>

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-black bg-opacity-90 w-full flex items-center justify-between px-8 py-4 shadow-md">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('index') }}" class="text-white hover:text-purple-400 transition-colors">
                <h1>{{ env('APP_NAME') }}</h1>
            </a>
        </div>
        <!-- Center (empty or site name) -->
        <div class="flex-1 text-center">
            <!-- Optionally add site name here -->
        </div>
        <!-- Cart -->
        <div class="flex items-center">
            <a href="{{ route('cart.view') }}" class="text-white hover:text-purple-400 transition-colors">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
            </a>
        </div>
    </header>

    <div class="min-h-screen bg-black pt-8 pb-32">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-400">
                    <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-white">Shopping Cart</li>
                </ol>
            </nav>

            <!-- Alert Messages -->
            @if(session('cart_message'))
                <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded-lg mb-6">
                    {{ session('cart_message') }}
                </div>
            @endif
            
            @if(session('cart_error'))
                <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg mb-6">
                    {{ session('cart_error') }}
                </div>
            @endif

            <!-- Cart Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Shopping Cart</h1>
                <p class="text-gray-400 mt-2">{{ $cartCount }} item(s) in your cart</p>
            </div>

            @if(count($cart) > 0)
                <!-- Cart Items -->
                <div class="space-y-6 mb-8">
                    @foreach($cart as $productId => $item)
                        <div class="bg-gray-900 rounded-lg p-6 flex items-center space-x-6">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-white">{{ $item['name'] }}</h3>
                                <p class="text-gray-400 text-sm">SKU: {{ $item['id'] }}</p>
                                <p class="text-purple-400 font-medium">£{{ number_format($item['price'], 2) }}</p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border border-gray-700 rounded-lg">
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="product_id" value="{{ $productId }}" />
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}" />
                                        <button type="submit" class="px-4 py-2 text-white hover:bg-gray-800 transition-colors" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                    </form>
                                    
                                    <span class="px-4 py-2 text-white">{{ $item['quantity'] }}</span>
                                    
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="product_id" value="{{ $productId }}" />
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}" />
                                        <button type="submit" class="px-4 py-2 text-white hover:bg-gray-800 transition-colors" {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}>+</button>
                                    </form>
                                </div>
                                
                                <span class="text-gray-400 text-sm">of {{ $item['stock'] }} available</span>
                            </div>

                            <!-- Item Total -->
                            <div class="text-right">
                                <p class="text-lg font-semibold text-white">£{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
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
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="bg-gray-900 rounded-lg p-6">
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
                    
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal ({{ $cartCount }} items):</span>
                            <span>£{{ $cartTotal }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Tax:</span>
                            <span>£0.00</span>
                        </div>
                        <div class="border-t border-gray-700 pt-2 flex justify-between text-lg font-semibold text-white">
                            <span>Total:</span>
                            <span>£{{ $cartTotal }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors">
                            Proceed to Checkout
                        </button>
                        <a href="{{ route('index') }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold py-4 px-6 rounded-lg transition-colors text-center">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="text-center py-12">
                    <svg class="w-24 h-24 text-gray-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <h2 class="text-2xl font-semibold text-white mb-4">Your cart is empty</h2>
                    <p class="text-gray-400 mb-8">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ route('index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer Menu -->
    <footer class="w-full bg-black bg-opacity-90 py-4">
        <div class="flex flex-wrap justify-center items-center gap-8 text-white text-sm font-semibold">
            <a href="#" class="hover:text-purple-400 transition-colors">HELP</a>
            <a href="#" class="hover:text-purple-400 transition-colors">PRIVACY</a>
            <a href="#" class="hover:text-purple-400 transition-colors">TERMS</a>
            <a href="#" class="hover:text-purple-400 transition-colors">DO NOT SELL MY PERSONAL INFORMATION</a>
            <a href="#" class="hover:text-purple-400 transition-colors">ACCESSIBILITY</a>
        </div>
    </footer>
</x-splade-layout> 