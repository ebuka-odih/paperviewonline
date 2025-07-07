@extends('pages.layout.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-2xl mx-auto bg-black rounded-2xl shadow-lg p-8 mt-8 text-white">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-900/80 text-red-200 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 rounded bg-gray-900 border border-[#65644A] text-white focus:ring-2 focus:ring-[#65644A]">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 rounded bg-gray-900 border border-[#65644A] text-white focus:ring-2 focus:ring-[#65644A]">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Phone (optional)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 rounded bg-gray-900 border border-[#65644A] text-white focus:ring-2 focus:ring-[#65644A]">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" required class="w-full px-4 py-2 rounded bg-gray-900 border border-[#65644A] text-white focus:ring-2 focus:ring-[#65644A]">
            </div>
            <div>
                <label class="block mb-1 font-semibold">City</label>
                <input type="text" name="city" value="{{ old('city') }}" required class="w-full px-4 py-2 rounded bg-gray-900 border border-[#65644A] text-white focus:ring-2 focus:ring-[#65644A]">
            </div>
            <div>
                <label class="block mb-1 font-semibold">State</label>
                <input type="text" name="state" value="{{ old('state') }}" required class="w-full px-4 py-2 rounded bg-gray-900 border border-[#65644A] text-white focus:ring-2 focus:ring-[#65644A]">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Zip</label>
                <input type="text" name="zip" value="{{ old('zip') }}" required class="w-full px-4 py-2 rounded bg-gray-900 border border-[#65644A] text-white focus:ring-2 focus:ring-[#65644A]">
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-2">Order Summary</h2>
            <div class="bg-gray-900 rounded-lg p-4 mb-4">
                @if($cart && count($cart) > 0)
                    <ul class="divide-y divide-gray-800">
                        @foreach($cart as $item)
                            <li class="py-2 flex justify-between items-center">
                                <span>{{ $item['name'] }} <span class="text-xs text-gray-400">x{{ $item['quantity'] }}</span></span>
                                <span class="text-[#65644A] font-bold">£{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-[#65644A]">
                        <span class="font-bold">Total</span>
                        <span class="text-xl font-bold text-[#65644A]">£{{ number_format($cartTotal, 2) }}</span>
                    </div>
                @else
                    <p class="text-gray-400">Your cart is empty.</p>
                @endif
            </div>
        </div>

        <button type="submit" class="w-full bg-[#65644A] hover:bg-[#65644A] text-white font-bold py-3 px-6 rounded-lg transition-colors text-lg">Place Order</button>
    </form>
</div>
@endsection 