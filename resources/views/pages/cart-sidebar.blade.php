<div class="h-full bg-black text-white flex flex-col" id="cart-sidebar">
    <!-- Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-700">
        <h2 class="text-xl font-bold">Shopping Cart</h2>
        <button onclick="window.closeSlideover()" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Flash Messages -->
    @if(session('cart_message'))
        <div class="p-4 m-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('cart_message') }}
        </div>
    @endif
    
    @if(session('cart_error'))
        <div class="p-4 m-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('cart_error') }}
        </div>
    @endif

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-6">
        @if($cart && count($cart) > 0)
            <div class="space-y-4">
                @foreach($cart as $productId => $item)
                    <div class="flex items-center space-x-3 p-3 border border-gray-700 rounded-lg">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" 
                                 class="w-12 h-12 object-cover rounded">
                        </div>

                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-white truncate">{{ $item['name'] }}</h3>
                            <p class="text-[#65644A] font-bold text-sm">₦{{ number_format($item['price'], 2) }}</p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-1">
                            <form action="{{ route('cart.update') }}" method="POST" class="inline cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}" />
                                <input type="hidden" name="action" value="decrease" />
                                <button type="submit" class="w-6 h-6 bg-gray-700 hover:bg-gray-600 text-white rounded flex items-center justify-center transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                            </form>
                            
                            <span class="w-8 text-center text-sm font-semibold">{{ $item['quantity'] }}</span>
                            
                            <form action="{{ route('cart.update') }}" method="POST" class="inline cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}" />
                                <input type="hidden" name="action" value="increase" />
                                <button type="submit" class="w-6 h-6 bg-gray-700 hover:bg-gray-600 text-white rounded flex items-center justify-center transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Item Total -->
                        <div class="text-right">
                            <p class="font-semibold text-white text-sm">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>

                        <!-- Remove Button -->
                        <div class="flex-shrink-0">
                            <form action="{{ route('cart.remove') }}" method="POST" class="inline cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}" />
                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Your cart is empty</h3>
                <p class="text-gray-400 text-sm">Add some items to get started</p>
            </div>
        @endif
    </div>

    <!-- Cart Summary -->
    @if($cart && count($cart) > 0)
        <div class="border-t border-gray-700 p-6">
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-gray-400 text-sm">
                    <span>Subtotal ({{ $cartCount }} items):</span>
                    <span>₦{{ $cartTotal }}</span>
                </div>
                <div class="flex justify-between text-gray-400 text-sm">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <div class="flex justify-between text-gray-400 text-sm">
                    <span>Tax:</span>
                    <span>₦0.00</span>
                </div>
                <div class="border-t border-gray-700 pt-2 flex justify-between font-semibold text-white">
                    <span>Total:</span>
                    <span class="text-[#65644A]">₦{{ $cartTotal }}</span>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('checkout.show') }}" 
                   class="w-full bg-[#65644A] hover:bg-[#65644A]/90 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center block">
                    Proceed to Checkout
                </a>
                
                <a href="{{ route('cart.index') }}" 
                   class="w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center block">
                    View Full Cart
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle cart form submissions
    const cartForms = document.querySelectorAll('.cart-form');
    
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = form.action;
            const method = form.method;
            
            // Show loading state on the button
            const submitButton = form.querySelector('button[type="submit"]');
            const originalContent = submitButton.innerHTML;
            submitButton.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            submitButton.disabled = true;
            
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/html, */*'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Success - refresh the cart sidebar content
                    return fetch('{{ route("cart.sidebar") }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html, */*'
                        }
                    });
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .then(response => response.text())
            .then(html => {
                // Update the cart sidebar content
                const cartSidebar = document.getElementById('cart-sidebar');
                if (cartSidebar) {
                    cartSidebar.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message
                const cartSidebar = document.getElementById('cart-sidebar');
                if (cartSidebar) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'p-4 m-4 bg-red-900/80 text-red-200 rounded';
                    errorDiv.textContent = 'Error updating cart. Please try again.';
                    cartSidebar.insertBefore(errorDiv, cartSidebar.firstChild);
                    
                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        if (errorDiv.parentNode) {
                            errorDiv.remove();
                        }
                    }, 3000);
                }
            })
            .finally(() => {
                // Restore button state
                submitButton.innerHTML = originalContent;
                submitButton.disabled = false;
            });
        });
    });
});
</script>
 