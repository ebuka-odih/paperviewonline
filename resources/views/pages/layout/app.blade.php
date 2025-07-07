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
    <!-- Coming Soon Popover Modal -->
    <div x-data="comingSoonModal()" 
         x-show="isVisible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-sm">
        <div class="relative w-full max-w-lg mx-4 my-2 sm:my-8">
            <!-- Modal Container -->
            <div class="bg-black rounded-2xl shadow-2xl overflow-hidden border border-[#65644A] text-white max-h-[98vh] sm:max-h-[90vh] py-4 sm:py-8 overflow-y-auto">
                <!-- Header with Brand -->
                <div class="px-6 py-4" style="background: linear-gradient(90deg, #65644A 0%, #65644A 100%);">
                    <div class="flex items-center justify-center">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-[#65644A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white">{{ config('app.name', 'PaperView Online') }}</h1>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Message -->
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-semibold text-white mb-3">Coming Soon</h2>
                        <p class="text-white leading-relaxed">
                            {{ $settings['message'] }}
                        </p>
                    </div>

                    <!-- Password Section (only if password is set) -->
                    @if(!empty($settings['password']))
                    <div class="space-y-4">
                        <div class="border-t border-[#65644A] pt-4">
                            <p class="text-sm text-[#bdbdbd] mb-3 text-center">Have access? Enter the password to continue</p>
                            <div class="space-y-3">
                                <input 
                                    type="password" 
                                    x-model="password"
                                    @keyup.enter="checkPassword()"
                                    :placeholder="error ? 'Incorrect password' : 'Enter password'"
                                    :class="error ? 'border-red-500 bg-red-50' : 'border-[#65644A]'"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:border-transparent text-black placeholder-gray-400 transition-colors"
                                >
                                <button 
                                    @click="checkPassword()"
                                    class="w-full bg-[#65644A] text-white font-semibold py-3 px-6 rounded-lg hover:bg-[#65644A] focus:bg-[#65644A] transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-[#65644A] focus:ring-offset-2"
                                >
                                    Access Site
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- No password set - just show message -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#65644A]/30 mb-4">
                            <svg class="w-8 h-8 text-[#65644A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-[#bdbdbd]">We'll be back soon with something amazing!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    function comingSoonModal() {
        return {
            isVisible: true,
            password: '',
            error: false,
            correctPassword: '{{ $settings['password'] }}',
            
            init() {
                // Check if user has already bypassed
                if (sessionStorage.getItem('coming-soon-bypassed') === 'true') {
                    this.isVisible = false;
                }
            },
            
            checkPassword() {
                if (this.password === this.correctPassword) {
                    // Store in session storage to remember the bypass
                    sessionStorage.setItem('coming-soon-bypassed', 'true');
                    // Hide the modal
                    this.isVisible = false;
                } else {
                    // Show error
                    this.error = true;
                    this.password = '';
                    setTimeout(() => {
                        this.error = false;
                    }, 2000);
                }
            }
        }
    }
    </script>
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
