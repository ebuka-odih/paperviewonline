@php
    $productImages = $product->images()->get();
    $firstImage = $productImages->first();
@endphp

@extends('pages.layout.app')

@section('title', $product->name . ' - ' . env('APP_NAME'))

@section('content')
<div class="min-h-screen bg-black pt-8 pb-32 mb-5">
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-5">
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
                    <div class="flex space-x-2 mt-4">
                        @foreach($productImages as $index => $image)
                            <button type="button" class="w-16 h-16 rounded border-2 {{ $index === 0 ? 'border-[#65644A]' : 'border-gray-700' }} hover:border-[#65644A] transition-colors" data-index="{{ $index }}">
                                <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded">
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
                        <span class="text-[#65644A] font-medium">{{ $product->category->name }}</span>
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
                        <span class="text-green-400 font-medium">In Stock </span>
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
                        <!-- Alert Message -->
                        @if(session('cart_message'))
                            <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded-lg">
                                {{ session('cart_message') }}
                            </div>
                        @endif

                        @if(session('cart_error'))
                            <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg">
                                {{ session('cart_error') }}
                            </div>
                        @endif

                        <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}" />
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="flex items-center border border-gray-700 rounded-lg">
                                    <button id="decreaseQty" type="button" class="px-4 py-2 text-white hover:bg-gray-800 transition-colors">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center bg-transparent text-white border-none focus:outline-none">
                                    <button id="increaseQty" type="button" class="px-4 py-2 text-white hover:bg-gray-800 transition-colors">+</button>
                                </div>
{{--                                <span class="text-gray-400 text-sm">of {{ $product->stock }} available</span>--}}
{{--                                <span class="text-xs text-gray-500">Form Qty: <span id="debugQuantity">1</span></span>--}}
                            </div>
                            <button type="submit" class="w-full bg-[#65644A] hover:bg-[#65644A] text-white font-semibold py-4 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.45A1 1 0 007 17h12m-5 4a2 2 0 100-4 2 2 0 000 4zm-6 0a2 2 0 100-4 2 2 0 000 4z" /></svg>
                                <span>Add to Cart</span>
                            </button>
                        </form>

                        <!-- Debug info -->

                    @else
                        <button disabled class="w-full bg-gray-600 text-gray-400 font-semibold py-4 px-6 rounded-lg cursor-not-allowed">
                            Out of Stock
                        </button>
                    @endif
                </div>

                <!-- Product Info -->

            </div>
        </div>
    </div>
</div>

@endsection

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
                btn.classList.toggle('border-[#65644A]', i === index);
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

    // Quantity controls - ensure DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route('cart.add') }}"]');
        if (!form) return;
        const quantityInput = form.querySelector('#quantity');
        const decreaseBtn = form.querySelector('#decreaseQty');
        const increaseBtn = form.querySelector('#increaseQty');
        const debugElement = form.querySelector('#debugQuantity');

        function updateQuantity(value) {
            if (quantityInput) quantityInput.value = value;
            if (debugElement) debugElement.textContent = value;
        }

        // Initialize
        if (quantityInput) {
            updateQuantity(parseInt(quantityInput.value));
        }

        decreaseBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            const currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue > 1) {
                updateQuantity(currentValue - 1);
            }
        });

        increaseBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            const currentValue = parseInt(quantityInput.value) || 1;
            const maxValue = parseInt(quantityInput.max);
            if (currentValue < maxValue) {
                updateQuantity(currentValue + 1);
            }
        });

        quantityInput?.addEventListener('input', function() {
            let value = parseInt(quantityInput.value) || 1;
            const maxValue = parseInt(quantityInput.max);
            const minValue = parseInt(quantityInput.min);
            if (value < minValue) value = minValue;
            if (value > maxValue) value = maxValue;
            updateQuantity(value);
        });
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
