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

    @php
        $settings = App\Models\Setting::getComingSoonSettings();
    @endphp

    @if($settings['enabled'])
    <!-- Coming Soon Full-Page Cover -->
    <div id="coming-soon-modal" class="fixed inset-0 z-[9999] flex flex-col min-h-screen min-w-full bg-black text-white">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('coming-soon-modal');
                const passwordToggle = document.getElementById('password-toggle');
                const passwordSection = document.getElementById('password-section');
                const passwordInput = document.getElementById('password-input');
                const accessButton = document.getElementById('access-button');
                const emailInput = document.getElementById('email-input');
                const sendButton = document.getElementById('send-button');
                
                const correctPassword = @js($settings['password']);
                let showPassword = false;
                let error = false;
                
                // Check if already bypassed
                if (sessionStorage.getItem('coming-soon-bypassed') === 'true') {
                    modal.style.display = 'none';
                }
                
                // Password toggle
                passwordToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    showPassword = !showPassword;
                    passwordSection.style.display = showPassword ? 'flex' : 'none';
                    console.log('Password toggle clicked, showPassword:', showPassword);
                });
                
                // Check password
                function checkPassword() {
                    const password = passwordInput.value;
                    if (password === correctPassword) {
                        sessionStorage.setItem('coming-soon-bypassed', 'true');
                        modal.style.display = 'none';
                    } else {
                        error = true;
                        passwordInput.value = '';
                        passwordInput.placeholder = 'Incorrect password';
                        passwordInput.classList.add('border-red-500', 'bg-red-50');
                        setTimeout(() => {
                            error = false;
                            passwordInput.placeholder = 'Enter password';
                            passwordInput.classList.remove('border-red-500', 'bg-red-50');
                        }, 2000);
                    }
                }
                
                // Access button
                accessButton.addEventListener('click', checkPassword);
                
                // Enter key on password input
                passwordInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        checkPassword();
                    }
                });
                
                // Email submission
                sendButton.addEventListener('click', function() {
                    alert('Thank you! You will be notified.');
                    emailInput.value = '';
                });
                
                // Mobile keyboard handling
                function handleMobileKeyboard() {
                    const isMobile = window.innerWidth <= 768;
                    if (!isMobile) return;
                    
                    // Handle email input focus
                    emailInput.addEventListener('focus', function() {
                        // Add class to body for additional padding
                        document.body.classList.add('mobile-keyboard-active');
                        
                        // Scroll to input with delay to ensure keyboard is shown
                        setTimeout(() => {
                            this.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center',
                                inline: 'nearest'
                            });
                        }, 300);
                    });
                    
                    // Handle email input blur
                    emailInput.addEventListener('blur', function() {
                        // Remove class when input loses focus
                        document.body.classList.remove('mobile-keyboard-active');
                    });
                    
                    // Handle password input focus
                    passwordInput.addEventListener('focus', function() {
                        document.body.classList.add('mobile-keyboard-active');
                        setTimeout(() => {
                            this.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center',
                                inline: 'nearest'
                            });
                        }, 300);
                    });
                    
                    // Handle password input blur
                    passwordInput.addEventListener('blur', function() {
                        document.body.classList.remove('mobile-keyboard-active');
                    });
                    
                    // Handle viewport height changes (keyboard show/hide)
                    let initialViewportHeight = window.innerHeight;
                    window.addEventListener('resize', function() {
                        const currentHeight = window.innerHeight;
                        if (currentHeight < initialViewportHeight) {
                            // Keyboard is likely visible
                            document.body.classList.add('mobile-keyboard-active');
                        } else {
                            // Keyboard is likely hidden
                            document.body.classList.remove('mobile-keyboard-active');
                        }
                    });
                }
                
                // Initialize mobile keyboard handling
                handleMobileKeyboard();
            });
        </script>
        <!-- Logo -->
        <div class="flex items-center p-2 pt-4 -mt-16">
            <img src="/img/logo.png" alt="Logo" class="h-24 w-auto mr-4" style="object-fit:contain;" />
        </div>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col items-center justify-start px-4 -mt-8">
            <div class="w-full max-w-md flex flex-col items-center">
                <div class="mb-6 text-center mt-0">
                    <p class="text-lg md:text-xl font-mono tracking-wide mb-4">{{ strtoupper($settings['message']) }}</p>
                </div>
                <!-- Password Toggle -->
                <div class="mb-6 w-full flex flex-col items-center">
                    <button id="password-toggle" type="button" class="flex items-center gap-2 text-white text-base font-mono underline mb-4 focus:outline-none">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104.896-2 2-2s2 .896 2 2-.896 2-2 2-2-.896-2-2zm0 0V7m0 4v4m0-4h4m-4 0H8"/></svg>
                        <span>ENTER USING PASSWORD</span>
                    </button>
                    <div id="password-section" class="w-full flex flex-col items-center" style="display: none;">
                        <input id="password-input" type="password" placeholder="Enter password" class="w-full px-4 py-3 border border-[#65644A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:border-transparent text-black placeholder-gray-400 transition-colors mb-3" />
                        <button id="access-button" class="w-full bg-[#65644A] text-white font-bold py-3 px-6 rounded-lg hover:bg-[#65644A]/90 focus:bg-[#65644A] transition-all duration-200 transform focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:ring-offset-2">Access Site</button>
                    </div>
                </div>
                <!-- Email Signup -->
                <div class="w-full flex flex-col items-center mt-4">
                    <p class="text-center text-base font-mono mb-3">BE THE FIRST TO RECEIVE THE PASSWORD WHEN '{{ strtoupper(config('app.name', 'PaperView Online')) }}' DROPS</p>
                    <div class="flex w-full flex-col sm:flex-row gap-2 items-center">
                        <input id="email-input" type="email" placeholder="EMAIL" class="flex-1 px-4 py-3 border border-white rounded-lg bg-transparent text-white placeholder-white font-mono focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:border-transparent" />
                        <button id="send-button" class="mt-2 sm:mt-0 w-full sm:w-auto bg-army text-white font-bold py-3 px-8 rounded-lg hover:bg-army focus:bg-army transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-army focus:ring-offset-2">SEND</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
