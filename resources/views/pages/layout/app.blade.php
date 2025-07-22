<x-splade-layout class="h-screen overflow-hidden">
    <x-slot:title>
        @yield('title', env('APP_NAME'))
    </x-slot>

    <!-- Alpine.js for interactivity - Load before Splade -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            });
        </script>
        <!-- Logo -->
        <div class="flex items-center p-8">
            <img src="/img/logo.png" alt="Logo" class="h-40 w-auto mr-4" style="object-fit:contain;" />
        </div>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col items-center justify-start px-4 mt-2">
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
    <main class="h-screen bg-black pt-8 pb-32 overflow-hidden">
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
</x-splade-layout>
