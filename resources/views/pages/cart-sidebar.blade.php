<x-splade-modal slideover class="!bg-black !text-white">
    <div class="p-6 flex-1 flex flex-col overflow-y-auto min-h-[400px]">
        <h2 class="text-2xl font-bold mb-6">Your Cart</h2>
        
        @if(isset($cart) && count($cart) > 0)
            <div class="space-y-4 flex-1">
                @foreach($cart as $productId => $item)
                    <div class="flex items-center gap-4 bg-gray-900 rounded-lg p-3">
                        <img src="{{ $item['image'] ?? asset('assets/images/product/placeholder.svg') }}" 
                             alt="{{ $item['name'] }}" 
                             class="w-16 h-16 object-cover rounded-lg border border-gray-700">
                        
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-base truncate">{{ $item['name'] }}</div>
                            <div class="text-[#65644A] font-bold">${{ number_format($item['price'], 2) }}</div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-2">
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-2">
                                <x-splade-form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                    <button type="submit" name="action" value="decrease" 
                                            class="w-6 h-6 bg-gray-700 hover:bg-gray-600 text-white rounded flex items-center justify-center text-sm">
                                        -
                                    </button>
                                    <span class="text-white font-semibold min-w-[2rem] text-center">{{ $item['quantity'] }}</span>
                                    <button type="submit" name="action" value="increase" 
                                            class="w-6 h-6 bg-gray-700 hover:bg-gray-600 text-white rounded flex items-center justify-center text-sm">
                                        +
                                    </button>
                                </x-splade-form>
                            </div>
                            
                            <!-- Remove Button -->
                            <x-splade-form method="POST" action="{{ route('cart.remove') }}" class="flex">
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm">
                                    Remove
                                </button>
                            </x-splade-form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Cart Total and Checkout -->
            <div class="border-t border-gray-700 pt-4 mt-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Total:</span>
                    <span class="text-xl font-bold text-[#65644A]">${{ number_format($cartTotal, 2) }}</span>
                </div>
                <a href="{{ route('cart.index') }}" 
                   class="w-full bg-[#65644A] hover:bg-[#65644A] text-white font-bold py-3 px-4 rounded-lg transition-colors text-center block">
                    Checkout
                </a>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="flex-1 flex flex-col items-center justify-center text-center py-12">
                <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <div class="text-lg font-semibold mb-2">Your cart is empty</div>
                <div class="text-gray-400 text-sm mb-4">Add some products to your cart to see them here.</div>
                <a href="{{ route('index') }}" class="bg-[#65644A] hover:bg-[#65644A] text-white font-bold py-2 px-6 rounded-lg transition-colors">Continue Shopping</a>
            </div>
        @endif
    </div>
</x-splade-modal> 