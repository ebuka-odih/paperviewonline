    @php
        $productImages = $product->images()->get();
        $firstImage = $productImages->first();
    @endphp
    
    <x-splade-layout>
        <x-slot:title>{{ $product->name }} - {{ env('APP_NAME') }}</x-slot:title>

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-black bg-opacity-90 w-full flex items-center justify-between px-8 py-4 shadow-md">
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
        <div class="flex items-center">
            <a href="#" class="text-white hover:text-purple-400 transition-colors">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen bg-black pt-8 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-400">
                    <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ $product->category->name ?? 'Products' }}</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-white">{{ $product->name }}</li>
                </ol>
            </nav>

            <!-- Product Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Left Column - Image Slider -->
                <div class="space-y-4">
                    <!-- Main Image -->
                    <div class="relative">
                        <div id="mainImage" class="aspect-square bg-gray-900 rounded-lg overflow-hidden">
                            @if($firstImage)
                                <img id="currentImage" src="{{ $firstImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('assets/images/product/placeholder.svg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        
                        <!-- Navigation Arrows -->
                        @if($productImages->count() > 1)
                            <button id="prevBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button id="nextBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Thumbnail Navigation -->
                    @if($productImages->count() > 1)
                        <div class="flex space-x-2 overflow-x-auto pb-2">
                            @foreach($productImages as $index => $image)
                                <button class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-purple-500' : 'border-gray-700' }} hover:border-purple-400 transition-colors" data-index="{{ $index }}">
                                    <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Column - Product Details -->
                <div class="space-y-6">
                    <!-- Product Title -->
                    <h1 class="text-3xl font-bold text-white">{{ $product->name }}</h1>

                    <!-- Category -->
                    @if($product->category)
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-400">Category:</span>
                            <span class="text-purple-400 font-medium">{{ $product->category->name }}</span>
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="space-y-2">
                        <div class="flex items-baseline space-x-3">
                            <span class="text-3xl font-bold text-white">£{{ number_format($product->price, 2) }}</span>
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="text-xl text-gray-400 line-through">£{{ number_format($product->price, 2) }}</span>
                                <span class="text-lg text-green-400 font-medium">Save £{{ number_format($product->price - $product->sale_price, 2) }}</span>
                            @endif
                        </div>
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="text-sm text-gray-400">
                                Final Price: <span class="text-green-400 font-medium">£{{ number_format($product->sale_price, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-400">Availability:</span>
                        @if($product->stock > 0)
                            <span class="text-green-400 font-medium">In Stock ({{ $product->stock }} available)</span>
                        @else
                            <span class="text-red-400 font-medium">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($product->description)
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-white">Description</h3>
                            <p class="text-gray-300 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    <!-- Add to Cart Section -->
                    <div class="space-y-4 pt-6 border-t border-gray-800">
                        @if($product->stock > 0)
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border border-gray-700 rounded-lg">
                                    <button id="decreaseQty" class="px-4 py-2 text-white hover:bg-gray-800 transition-colors">-</button>
                                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center bg-transparent text-white border-none focus:outline-none">
                                    <button id="increaseQty" class="px-4 py-2 text-white hover:bg-gray-800 transition-colors">+</button>
                                </div>
                                <span class="text-gray-400 text-sm">of {{ $product->stock }} available</span>
                            </div>
                            
                            <button id="addToCart" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                                </svg>
                                <span>Add to Cart</span>
                            </button>
                        @else
                            <button disabled class="w-full bg-gray-600 text-gray-400 font-semibold py-4 px-6 rounded-lg cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-3 pt-6 border-t border-gray-800">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">SKU:</span>
                            <span class="text-white">{{ $product->sku }}</span>
                        </div>
                        @if($product->weight)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Weight:</span>
                                <span class="text-white">{{ $product->weight }} kg</span>
                            </div>
                        @endif
                        @if($product->dimensions)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Dimensions:</span>
                                <span class="text-white">{{ $product->dimensions }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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

    <script>
        // Image Slider Functionality
        const images = @json($productImages->pluck('url'));
        let currentImageIndex = 0;

        function updateMainImage(index) {
            if (images.length > 0 && index >= 0 && index < images.length) {
                currentImageIndex = index;
                document.getElementById('currentImage').src = images[index];
                
                // Update thumbnail selection
                document.querySelectorAll('.thumbnail-btn').forEach((btn, i) => {
                    btn.classList.toggle('border-purple-500', i === index);
                    btn.classList.toggle('border-gray-700', i !== index);
                });
            }
        }

        // Thumbnail click handlers
        document.querySelectorAll('.thumbnail-btn').forEach((btn, index) => {
            btn.addEventListener('click', () => updateMainImage(index));
        });

        // Navigation buttons
        document.getElementById('prevBtn')?.addEventListener('click', () => {
            const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
            updateMainImage(newIndex);
        });

        document.getElementById('nextBtn')?.addEventListener('click', () => {
            const newIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
            updateMainImage(newIndex);
        });

        // Quantity controls
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decreaseQty');
        const increaseBtn = document.getElementById('increaseQty');

        decreaseBtn?.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        increaseBtn?.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.max);
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });

        // Add to Cart functionality
        document.getElementById('addToCart')?.addEventListener('click', () => {
            const quantity = parseInt(quantityInput.value);
            // TODO: Implement add to cart functionality
            alert(`Added ${quantity} item(s) to cart!`);
        });

        // Keyboard navigation for image slider
        document.addEventListener('keydown', (e) => {
            if (images.length <= 1) return;
            
            if (e.key === 'ArrowLeft') {
                const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
                updateMainImage(newIndex);
            } else if (e.key === 'ArrowRight') {
                const newIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
                updateMainImage(newIndex);
            }
        });
    </script>
</x-splade-layout> 