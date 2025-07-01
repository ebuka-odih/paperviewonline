<x-splade-layout>
    <x-slot:title>
        @yield('title', env('APP_NAME'))
    </x-slot>

    <!-- Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Sticky Header -->
    <header x-data="{ cartOpen: false }" class="sticky top-0 z-50 bg-black bg-opacity-90 w-full flex items-center justify-between px-8 py-4 shadow-md">
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
        <div class="flex items-center relative">
            <button @click="cartOpen = true" class="text-white hover:text-purple-400 transition-colors relative focus:outline-none">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                @endif
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen bg-black pt-8 pb-32">
        @yield('content')
    </main>

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

    <!-- Static Sidebar Slideover -->
    <div x-data="{ cartOpen: false }" x-cloak>
        <!-- Overlay -->
        <div x-show="cartOpen" class="fixed inset-0 bg-black bg-opacity-60 z-40" @click="cartOpen = false"></div>
        <!-- Sidebar Panel -->
        <aside x-show="cartOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed top-0 right-0 w-full max-w-md h-full bg-black text-white shadow-2xl z-50 flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-800">
                <h2 class="text-2xl font-bold">Your Cart</h2>
                <button @click="cartOpen = false" class="text-white text-2xl">&times;</button>
            </div>
            <div class="flex-1 flex flex-col items-center justify-center text-center py-12">
                <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <div class="text-lg font-semibold mb-2">Your cart is empty</div>
                <div class="text-gray-400 text-sm mb-4">Add some products to your cart to see them here.</div>
                <a href="{{ route('index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">Continue Shopping</a>
            </div>
        </aside>
    </div>

    @yield('scripts')
    <x-splade-flash />
</x-splade-layout> 