<x-splade-modal slideover class="!bg-black !text-white">
    <div class="p-6 flex-1 flex flex-col overflow-y-auto min-h-[400px]">
        <h2 class="text-2xl font-bold mb-6">Your Cart</h2>
        <div class="flex-1 flex flex-col items-center justify-center text-center py-12">
            <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
            <div class="text-lg font-semibold mb-2">Your cart is empty</div>
            <div class="text-gray-400 text-sm mb-4">Add some products to your cart to see them here.</div>
            <a href="{{ route('index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">Continue Shopping</a>
        </div>
    </div>
    <style>
        /* Make the close button white */
        .splade-modal [data-headlessui-state] button.absolute {
            color: #fff !important;
        }
    </style>
</x-splade-modal> 