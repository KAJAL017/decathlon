@forelse($cart->items as $item)
<div class="cart-item bg-white rounded-2xl border border-gray-100 p-4 md:p-5 flex gap-4 md:gap-5 group hover:shadow-lg hover:border-gray-200 transition-all relative" data-id="{{ $item->id }}">
    {{-- Image --}}
    <div class="w-24 h-24 md:w-28 md:h-28 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 group-hover:scale-[1.02] transition-transform">
        @php
            $featuredImage = $item->product->featuredImage ?? $item->product->images->first();
        @endphp
        <img src="{{ $featuredImage?->image_url ?? asset('images/placeholder-product.svg') }}" 
             alt="{{ $item->product->name }}" class="w-full h-full object-cover" loading="lazy">
    </div>

    {{-- Details --}}
    <div class="flex-grow flex flex-col justify-between py-0.5 min-w-0">
        <div>
            <div class="flex justify-between items-start gap-2">
                <div class="min-w-0">
                    <p class="text-[10px] md:text-xs font-black text-[#0082C3] uppercase tracking-widest">{{ $item->product->brand?->name ?? 'DECATHLON' }}</p>
                    <h2 class="text-sm md:text-base font-bold text-gray-900 leading-tight uppercase tracking-tight mt-0.5 truncate">{{ $item->product->name }}</h2>
                    @if($item->variant)
                        <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $item->variant->variant_name }}</p>
                    @endif
                </div>
                <button onclick="Cart.remove({{ $item->id }})" 
                        class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100"
                        title="Remove item">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="flex items-end justify-between mt-3">
            {{-- Qty Control --}}
            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <button onclick="Cart.update({{ $item->id }}, {{ $item->quantity - 1 }})" 
                        class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center hover:bg-gray-50 text-gray-600 transition-colors {{ $item->quantity <= 1 ? 'opacity-40 cursor-not-allowed' : '' }}" 
                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                    <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                </button>
                <span class="cart-qty-value w-8 h-8 md:w-9 md:h-9 flex items-center justify-center text-xs md:text-sm font-black text-gray-900 bg-white">{{ $item->quantity }}</span>
                <button onclick="Cart.update({{ $item->id }}, {{ $item->quantity + 1 }})" 
                        class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center hover:bg-gray-50 text-gray-600 transition-colors">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            <div class="text-right">
                <p class="text-base md:text-lg font-black text-gray-950">₹{{ number_format($item->subtotal ?? 0, 2) }}</p>
                @if($item->quantity > 1)
                    <p class="text-[10px] text-gray-400 font-bold uppercase">₹{{ number_format($item->variant?->price ?? 0, 2) }} / unit</p>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="py-20 flex flex-col items-center justify-center text-center space-y-5 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100">
        <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-300"></i>
    </div>
    <div class="space-y-1">
        <h3 class="text-lg font-bold text-gray-900">Your cart is empty</h3>
        <p class="text-gray-500 text-sm max-w-xs">Looks like you haven't added anything yet. Explore our collection and find something you love.</p>
    </div>
    <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#0082C3] text-white px-8 py-3 rounded-full font-bold text-sm shadow-md hover:bg-[#006699] transition-all uppercase tracking-wider hover:scale-[1.02] active:scale-[0.98]">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Start Shopping
    </a>
</div>
@endforelse
