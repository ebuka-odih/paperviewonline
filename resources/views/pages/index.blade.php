<x-splade-layout>
    <x-slot:title>Welcome</x-slot:title>

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-black bg-opacity-90 w-full flex items-center justify-between px-8 py-4 shadow-md">
        <!-- Logo -->
        <div class="flex items-center">
            <h1 style="color: white; font-size: 24px;">{{ env('APP_NAME') }}</h1>
        </div>
        <!-- Center (empty or site name) -->
        <div class="flex-1 text-center">
            <!-- Optionally add site name here -->

        </div>
        <!-- Cart -->
        <div class="flex items-center">
            <a href="#" class="text-white hover:text-purple-400 transition-colors">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-col items-center justify-center min-h-screen bg-black pt-8 pb-32">
        <!-- Centered Video/Slider -->
        <div class="w-full max-w-6xl flex justify-center mb-12">
            <video class="rounded-lg w-full max-w-5xl shadow-lg" controls poster="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=facearea&w=800&h=400">
                <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <!-- Product Grid -->
        <div class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 gap-12 px-4 pb-16">
            @foreach($products as $product)
                <div class="flex flex-col items-center bg-black rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-center w-[400px] h-[500px] mb-2">
                        @php
                            $firstImage = $product->images()->first();
                        @endphp
                        <img src="{{ $firstImage ? $firstImage->url : asset('assets/images/product/placeholder.svg') }}" alt="{{ $product->name }}" class="object-contain w-full h-full">
                    </div>
                    <div class="text-center">
                        <a href="{{ route('product.show', $product) }}" class="text-lg font-bold text-white mb-2 hover:text-purple-400 transition-colors block">{{ $product->name }}</a>
                        <p class="text-gray-300 text-base mb-2">£{{ number_format($product->price, 2) }} GBP</p>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $product->category->name ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Sticky Footer Menu -->
    <footer class="fixed bottom-0 left-0 w-full bg-black bg-opacity-90 py-4 z-50">
        <div class="flex flex-wrap justify-center items-center gap-8 text-white text-sm font-semibold">
            <a href="#" class="hover:text-purple-400 transition-colors">HELP</a>
            <a href="#" class="hover:text-purple-400 transition-colors">PRIVACY</a>
            <a href="#" class="hover:text-purple-400 transition-colors">TERMS</a>
            <a href="#" class="hover:text-purple-400 transition-colors">DO NOT SELL MY PERSONAL INFORMATION</a>
            <a href="#" class="hover:text-purple-400 transition-colors">ACCESSIBILITY</a>
        </div>
    </footer>
</x-splade-layout> 