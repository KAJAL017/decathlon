@forelse($cart->items as $item)
<div class="flex items-center gap-4 py-4 border-b border-gray-100 last:border-0 group">
    <div class="w-16 h-16 rounded-lg bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0">
        @php
            $featuredImage = $item->product->featuredImage ?? $item->product->images->first();
        @endphp
        <img src="{{ $featuredImage?->image_url ?? asset('images/placeholder-product.svg') }}" 
             alt="{{ $item->product->name }}" class="w-full h-full object-cover">
    </div>
    <div class="flex-grow min-w-0">
        <h4 class="text-xs font-bold text-gray-900 truncate uppercase tracking-tight">{{ $item->product->name }}</h4>
        @if($item->variant)
            <p class="text-[10px] text-gray-500 font-medium mt-0.5">{{ $item->variant->variant_name }}</p>
        @endif
        <div class="flex items-center justify-between mt-2">
            <span class="text-xs font-black text-gray-900">₹{{ number_format($item->variant?->price ?? 0, 2) }} <span class="text-[10px] text-gray-400 font-bold ml-1">x {{ $item->quantity }}</span></span>
            <button onclick="Cart.remove({{ $item->id }})" class="text-gray-400 hover:text-red-500 transition-colors">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>
@empty
<div class="py-10 flex flex-col items-center justify-center text-center space-y-3">
    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center">
        <i data-lucide="shopping-cart" class="w-6 h-6 text-gray-300"></i>
    </div>
    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Your cart is empty</p>
    <a href="{{ route('shop') }}" class="text-[10px] font-black text-[#0082C3] uppercase hover:underline">Start Shopping</a>
</div>
@endforelse
