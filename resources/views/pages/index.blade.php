@extends('pages.layout.app')

@section('title', 'Welcome')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-black pt-8 pb-32">
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
</div>
@endsection 