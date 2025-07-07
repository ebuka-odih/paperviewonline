<x-splade-layout>
    <x-slot:title>
        @yield('title', env('APP_NAME'))
    </x-slot>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @php
        $settings = App\Models\Setting::getComingSoonSettings();
    @endphp

    @if($settings['enabled'])
    <!-- Coming Soon Full-Page Cover -->
    <div x-data="{
            isVisible: true,
            showPassword: false,
            password: '',
            email: '',
            error: false,
            correctPassword: @js($settings['password']),
            init() {
                if (sessionStorage.getItem('coming-soon-bypassed') === 'true') {
                    this.isVisible = false;
                }
            },
            checkPassword() {
                if (this.password === this.correctPassword) {
                    sessionStorage.setItem('coming-soon-bypassed', 'true');
                    this.isVisible = false;
                } else {
                    this.error = true;
                    this.password = '';
                    setTimeout(() => { this.error = false; }, 2000);
                }
            },
            submitEmail() {
                // TODO: Implement email submission logic (AJAX or form post)
                alert('Thank you! You will be notified.');
                this.email = '';
            }
        }"
        x-show="isVisible"
        x-init="init"
        class="fixed inset-0 z-[9999] flex flex-col min-h-screen min-w-full bg-black text-white">
        <!-- Logo -->
        <div class="flex items-center p-8">
            <img src="/img/logo.png" alt="Logo" class="h-28 w-auto mr-4" style="object-fit:contain;" />
        </div>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col items-center justify-start px-4 mt-24">
            <div class="w-full max-w-md flex flex-col items-center">
                <div class="mb-8 text-center mt-2">
                    <p class="text-lg md:text-xl font-mono tracking-wide mb-6">{{ strtoupper($settings['message']) }}</p>
                </div>
                <!-- Password Toggle -->
                <div class="mb-6 w-full flex flex-col items-center">
                    <button @click="showPassword = !showPassword" class="flex items-center gap-2 text-white text-base font-mono underline mb-4 focus:outline-none">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104.896-2 2-2s2 .896 2 2-.896 2-2 2-2-.896-2-2zm0 0V7m0 4v4m0-4h4m-4 0H8"/></svg>
                        <span>ENTER USING PASSWORD</span>
                    </button>
                    <template x-if="showPassword">
                        <div class="w-full flex flex-col items-center">
                            <input type="password" x-model="password" @keyup.enter="checkPassword()" :placeholder="error ? 'Incorrect password' : 'Enter password'" :class="error ? 'border-red-500 bg-red-50 text-black' : 'border-[#65644A]'" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:border-transparent text-black placeholder-gray-400 transition-colors mb-3" />
                            <button @click="checkPassword()" class="w-full bg-[#65644A] text-white font-bold py-3 px-6 rounded-lg hover:bg-[#65644A]/90 focus:bg-[#65644A] transition-all duration-200 transform focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:ring-offset-2">Access Site</button>
                        </div>
                    </template>
                </div>
                <!-- Email Signup -->
                <div class="w-full flex flex-col items-center mt-4">
                    <p class="text-center text-base font-mono mb-3">BE THE FIRST TO RECEIVE THE PASSWORD WHEN '{{ strtoupper(config('app.name', 'PaperView Online')) }}' DROPS</p>
                    <div class="flex w-full flex-col sm:flex-row gap-2 items-center">
                        <input type="email" x-model="email" placeholder="EMAIL" class="flex-1 px-4 py-3 border border-white rounded-lg bg-transparent text-white placeholder-white font-mono focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:border-transparent" />
                        <button @click="submitEmail()" class="mt-2 sm:mt-0 w-full sm:w-auto bg-army text-white font-bold py-3 px-8 rounded-lg hover:bg-army focus:bg-army transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-army focus:ring-offset-2">SEND</button>
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
    <main class="min-h-screen bg-black pt-8 pb-32">
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
