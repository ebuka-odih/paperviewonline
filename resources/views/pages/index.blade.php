<x-splade-layout>
    <x-slot:title>Welcome</x-slot:title>

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-black bg-opacity-90 w-full flex items-center justify-between px-8 py-4 shadow-md">
        <!-- Logo -->
        <div class="flex items-center">
            <h1>{{ env('APP_NAME') }}</h1>
        </div>
        <!-- Center (empty or site name) -->
        <div class="flex-1 text-center">
            <!-- Optionally add site name here -->
        </div>
        <!-- Cart -->
        <div class="flex items-center">
            <a href="#" class="text-white font-semibold hover:text-purple-400 transition-colors">CART</a>
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
            @foreach([
                [
                    'img' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80',
                    'name' => 'Classic Blue Denim Jacket',
                    'price' => '£120.00 GBP',
                ],
                [
                    'img' => 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=600&q=80',
                    'name' => 'White Cotton T-Shirt',
                    'price' => '£30.00 GBP',
                ],
                [
                    'img' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=600&q=80',
                    'name' => 'Black Slim Fit Jeans',
                    'price' => '£80.00 GBP',
                ],
                [
                    'img' => 'https://images.unsplash.com/photo-1469398715555-76331a6c7b29?auto=format&fit=crop&w=600&q=80',
                    'name' => 'Red Hoodie',
                    'price' => '£65.00 GBP',
                ],
                [
                    'img' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=600&q=80',
                    'name' => 'Green Bomber Jacket',
                    'price' => '£150.00 GBP',
                ],
                [
                    'img' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80',
                    'name' => 'Grey Sweatpants',
                    'price' => '£55.00 GBP',
                ],
            ] as $product)
                <div class="flex flex-col items-center bg-black rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-center w-[400px] h-[500px] mb-4">
                        <img src="{{ $product['img'] }}" alt="{{ $product['name'] }}" class="object-contain w-full h-full">
                    </div>
                    <div class="text-center">
                        <a href="#" class="text-lg font-bold text-white mb-2 hover:text-purple-400 transition-colors block">{{ $product['name'] }}</a>
                        <p class="text-gray-300 text-base mb-2">{{ $product['price'] }}</p>
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