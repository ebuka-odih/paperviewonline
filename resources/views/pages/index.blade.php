@extends('pages.layout.app')

@section('title', 'Welcome')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-black pt-8 pb-32">
    <!-- Centered Image Slider -->
    <div class="w-full max-w-6xl flex justify-center mb-12">
        <div class="relative w-full max-w-5xl">
            <!-- Slider Container -->
            <div id="imageSlider" class="relative rounded-lg shadow-lg overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out" style="width: 300%;">
                    <!-- Image 1 -->
                    <div class="w-1/3">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&h=400" 
                             alt="Fashion Collection 1" 
                             class="w-full h-[400px] object-cover">
                    </div>
                    <!-- Image 2 -->
                    <div class="w-1/3">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=800&h=400" 
                             alt="Fashion Collection 2" 
                             class="w-full h-[400px] object-cover">
                    </div>
                    <!-- Image 3 -->
                    <div class="w-1/3">
                        <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=800&h=400" 
                             alt="Fashion Collection 3" 
                             class="w-full h-[400px] object-cover">
                    </div>
                </div>
                
                <!-- Navigation Arrows -->
                <button id="prevBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-2 rounded-full transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button id="nextBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-2 rounded-full transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                
                <!-- Dots Indicator -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                    <button class="dot-indicator w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-75 transition-all duration-200" data-slide="0"></button>
                    <button class="dot-indicator w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-75 transition-all duration-200" data-slide="1"></button>
                    <button class="dot-indicator w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-75 transition-all duration-200" data-slide="2"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-2 gap-12 px-4 pb-16">
        @foreach($products as $product)
            <div class="flex flex-col items-center bg-black rounded-lg shadow-lg p-6">
                <a href="{{ route('product.show', $product) }}" class="flex items-center justify-center w-[400px] h-[500px] mb-2 hover:opacity-80 transition-opacity">
                    @php
                        $firstImage = $product->images()->first();
                    @endphp
                    <img src="{{ $firstImage ? $firstImage->url : asset('assets/images/product/placeholder.svg') }}" alt="{{ $product->name }}" class="object-contain w-full h-full">
                </a>
                <div class="text-center">
                    <a href="{{ route('product.show', $product) }}" class="text-lg font-bold text-white mb-2 hover:text-[#65644A] transition-colors block">{{ $product->name }}</a>
                    <p class="text-gray-300 text-base mb-2">£{{ number_format($product->price, 2) }} GBP</p>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $product->category->name ?? '' }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('imageSlider');
    const slideContainer = slider.querySelector('.flex');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const dots = document.querySelectorAll('.dot-indicator');
    
    let currentSlide = 0;
    const totalSlides = 3;
    
    // Function to update slider position
    function updateSlider() {
        slideContainer.style.transform = `translateX(-${currentSlide * 33.333}%)`;
        
        // Update dots
        dots.forEach((dot, index) => {
            if (index === currentSlide) {
                dot.classList.add('bg-opacity-100');
                dot.classList.remove('bg-opacity-50');
            } else {
                dot.classList.remove('bg-opacity-100');
                dot.classList.add('bg-opacity-50');
            }
        });
    }
    
    // Next slide
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateSlider();
    }
    
    // Previous slide
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateSlider();
    }
    
    // Event listeners
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            updateSlider();
        });
    });
    
    // Auto-play (optional)
    setInterval(nextSlide, 5000);
    
    // Initialize first slide
    updateSlider();
});
</script>
@endsection 