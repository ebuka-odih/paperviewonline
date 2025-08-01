<x-splade-layout class="min-h-screen bg-black">
    <x-slot:title>
        @yield('title', env('APP_NAME'))
    </x-slot>

    <!-- Alpine.js for interactivity - Load before Splade -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Mobile Browser Fixes -->
    <style>
        /* Ensure black background */
        html, body {
            background: black;
            min-height: 100vh;
        }
        
        /* Ensure Splade layout covers full viewport */
        [data-splade] {
            background: black;
            min-height: 100vh;
        }
        
        /* Prevent any white background flashes */
        * {
            background-color: inherit;
        }
        
        /* Mobile-specific fixes */
        @media (max-width: 768px) {
            html, body {
                background: black;
            }
            
            /* Prevent pull-to-refresh but allow scrolling */
            body {
                overscroll-behavior: contain;
            }
            
            /* Mobile keyboard fixes */
            .mobile-keyboard-active {
                padding-bottom: 300px !important;
            }
            
            /* Ensure inputs are visible when keyboard is active */
            input:focus {
                scroll-margin-top: 100px;
            }
            
            /* Adjust viewport height for mobile keyboards */
            .min-h-screen {
                min-height: 100vh;
                min-height: -webkit-fill-available;
            }
        }
        
        /* iOS Safari specific fixes */
        @supports (-webkit-touch-callout: none) {
            .min-h-screen {
                min-height: -webkit-fill-available;
            }
        }
    </style>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-black bg-opacity-90 w-full flex items-center justify-between px-8 py-4 shadow-md">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('index') }}" class="text-white hover:text-[#65644A] transition-colors">
                <h1>{{ env('APP_NAME') }}</h1>
            </a>
        </div>
        <!-- Center (empty or site name) -->
        <div class="flex-1 text-center">
            <!-- Optionally add site name here -->
        </div>
        <!-- Cart -->
        <div class="flex items-center relative">
            <x-splade-link slideover href="{{ route('cart.sidebar') }}" class="text-white hover:text-[#65644A] transition-colors relative focus:outline-none">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-[#65644A] text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                @endif
            </x-splade-link>
        </div>
    </header>

    <!-- Main Content -->
    <main class="bg-black pt-8 pb-32">
        @yield('content')
    </main>

    <!-- Footer Menu -->
    <footer class="w-full bg-black bg-opacity-90 py-4">
        <div class="flex flex-wrap justify-center items-center gap-8 text-white text-sm font-semibold">
            <a href="#" class="hover:text-[#65644A] transition-colors">HELP</a>
            <a href="#" class="hover:text-[#65644A] transition-colors">PRIVACY</a>
            <a href="#" class="hover:text-[#65644A] transition-colors">TERMS</a>
            <a href="#" class="hover:text-[#65644A] transition-colors">DO NOT SELL MY PERSONAL INFORMATION</a>
            <a href="#" class="hover:text-[#65644A] transition-colors">ACCESSIBILITY</a>
        </div>
    </footer>

    @yield('scripts')
    <x-splade-flash />
    
    <!-- Mobile Browser Behavior Prevention -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent pull-to-refresh but allow scrolling
            document.body.style.overscrollBehavior = 'contain';
            
            // Set viewport meta tag programmatically
            const viewport = document.querySelector('meta[name="viewport"]');
            if (viewport) {
                viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover');
            }
            
            // Force black background on all elements
            document.documentElement.style.backgroundColor = 'black';
            document.body.style.backgroundColor = 'black';
            
            // Prevent any white flashes during page load
            document.addEventListener('visibilitychange', function() {
                document.body.style.backgroundColor = 'black';
            });
            
            // Initialize cart sidebar functionality
            initializeCartSidebar();
        });
        
        // Global cart sidebar functions
        function initializeCartSidebar() {
            // Find all cart forms in the sidebar
            const cartForms = document.querySelectorAll('.cart-form');
            cartForms.forEach(form => {
                // Remove any existing listeners to prevent duplicates
                form.removeEventListener('submit', handleCartFormSubmit);
                // Add the submit listener
                form.addEventListener('submit', handleCartFormSubmit);
            });
        }
        
        async function handleCartFormSubmit(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const url = form.action;
            const method = form.method;
            
            try {
                const response = await fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    // Refresh the cart sidebar content
                    refreshCartSidebar();
                } else {
                    console.error('Error updating cart');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
        
        async function refreshCartSidebar() {
            try {
                const response = await fetch('{{ route("cart.sidebar") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const html = await response.text();
                    
                    // Create a temporary div to parse the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    const cartSidebar = document.getElementById('cart-sidebar');
                    if (!cartSidebar) return;
                    
                    // Find the cart items container in the new HTML
                    const newCartItems = tempDiv.querySelector('.flex-1.overflow-y-auto');
                    const newCartSummary = tempDiv.querySelector('.border-t.border-gray-700');
                    
                    // Find the current cart items container
                    const currentCartItems = cartSidebar.querySelector('.flex-1.overflow-y-auto');
                    const currentCartSummary = cartSidebar.querySelector('.border-t.border-gray-700');
                    
                    // Update only the cart items and summary sections
                    if (newCartItems && currentCartItems) {
                        currentCartItems.innerHTML = newCartItems.innerHTML;
                    }
                    
                    if (newCartSummary && currentCartSummary) {
                        currentCartSummary.innerHTML = newCartSummary.innerHTML;
                    }
                    
                    // Update flash messages if any
                    const newFlashMessages = tempDiv.querySelector('.p-4.m-4');
                    const currentFlashMessages = cartSidebar.querySelector('.p-4.m-4');
                    
                    if (newFlashMessages && currentFlashMessages) {
                        currentFlashMessages.innerHTML = newFlashMessages.innerHTML;
                    } else if (newFlashMessages && !currentFlashMessages) {
                        // Insert flash messages after header
                        const header = cartSidebar.querySelector('.flex.items-center.justify-between');
                        if (header) {
                            header.insertAdjacentElement('afterend', newFlashMessages);
                        }
                    }
                    
                    // Reinitialize event listeners for the new content
                    initializeCartSidebar();
                }
            } catch (error) {
                console.error('Error refreshing cart:', error);
            }
        }
        
        function closeSlideover() {
            // Try multiple methods to close the slideover
            try {
                // Method 1: Try to find and close Splade slideover
                const slideover = document.querySelector('[data-splade-slideover]');
                if (slideover) {
                    slideover.remove();
                    return;
                }
                
                // Method 2: Try to find Splade modal and close it
                const modal = document.querySelector('[data-splade-modal]');
                if (modal) {
                    modal.remove();
                    return;
                }
                
                // Method 3: Try to go back in history
                if (window.history.length > 1) {
                    window.history.back();
                    return;
                }
                
                // Method 4: Try to close any overlay/modal
                const overlay = document.querySelector('.fixed.inset-0');
                if (overlay) {
                    overlay.remove();
                    return;
                }
                
                // Method 5: Remove any backdrop
                const backdrop = document.querySelector('[data-splade-backdrop]');
                if (backdrop) {
                    backdrop.remove();
                    return;
                }
                
            } catch (error) {
                console.error('Error closing slideover:', error);
                // Fallback: try to go back
                window.history.back();
            }
        }
    </script>
</x-splade-layout>
