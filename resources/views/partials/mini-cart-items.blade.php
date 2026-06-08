@forelse($cart->items as $item)
<div class="flex items-center gap-4 py-4 border-b border-gray-100 last:border-0 group">
    <div class="w-16 h-16 rounded-lg bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0">
        @php
            $featuredImage = $item->product->featuredImage ?? $item->product->images->first();
        @endphp
        <img src="{{ $featuredImage?->image_url ?? 'https://images.unsplash.com/photo-1560362614-890275988ce7?w=400' }}" 
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </div>
    </div>
</div>
@empty
<div class="py-10 flex flex-col items-center justify-center text-center space-y-3">
    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center">
        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h12l1 12H4L5 9z" /></svg>
    </div>
    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Your cart is empty</p>
    <a href="{{ route('shop') }}" class="text-[10px] font-black text-[#0082C3] uppercase hover:underline">Start Shopping</a>
</div>
@endforelse
