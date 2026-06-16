@forelse($cart->items as $item)
<div class="cart-item bg-white rounded-2xl border border-gray-100 p-4 md:p-6 flex gap-4 md:gap-6 group hover:shadow-md transition-shadow relative" data-id="{{ $item->id }}">
    <!-- Image -->
    <div class="w-24 h-24 md:w-32 md:h-32 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0">
        @php
            $featuredImage = $item->product->featuredImage ?? $item->product->images->first();
        @endphp
        <img src="{{ $featuredImage?->image_url ?? asset('images/placeholder-product.svg') }}" 
             alt="{{ $item->product->name }}" class="w-full h-full object-cover">
    </div>

    <!-- Details -->
    <div class="flex-grow flex flex-col justify-between py-1">
        <div>
            <div class="flex justify-between items-start">
                <p class="text-[10px] md:text-xs font-black text-[#0082C3] uppercase tracking-widest">{{ $item->product->brand?->name ?? 'DECATHLON' }}</p>
                <button onclick="Cart.remove({{ $item->id }})" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                    <i data-lucide="trash-2" class="w-4 h-4 md:w-5 md:h-5"></i>
                </button>
            </div>
            <h2 class="text-sm md:text-lg font-bold text-gray-900 leading-tight uppercase tracking-tight mt-1">{{ $item->product->name }}</h2>
            @if($item->variant)
                <p class="text-xs text-gray-500 font-medium mt-1">{{ $item->variant->variant_name }}</p>
            @endif
        </div>

        <div class="flex items-center justify-between mt-4">
            <!-- Qty Control -->
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <button onclick="Cart.update({{ $item->id }}, {{ $item->quantity - 1 }})" 
                        class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center hover:bg-gray-50 text-gray-600 transition-colors {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>—</button>
                <span class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center text-xs md:text-sm font-black text-gray-900 bg-white">{{ $item->quantity }}</span>
                <button onclick="Cart.update({{ $item->id }}, {{ $item->quantity + 1 }})" class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center hover:bg-gray-50 text-gray-600 transition-colors">+</button>
            </div>

            <div class="text-right">
                <p class="text-lg md:text-xl font-black text-gray-950">₹{{ number_format($item->subtotal ?? 0, 2) }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase">₹{{ number_format($item->variant?->price ?? 0, 2) }} / unit</p>
            </div>
        </div>
    </div>
</div>
@empty
<div class="py-20 flex flex-col items-center justify-center text-center space-y-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm">
        <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-300"></i>
    </div>
    <div>
        <h3 class="text-lg font-bold text-gray-900">Your cart is empty</h3>
        <p class="text-gray-500 text-sm">Looks like you haven't added anything yet</p>
    </div>
    <a href="{{ route('shop') }}" class="bg-[#0082C3] text-white px-8 py-2.5 rounded-full font-bold text-sm shadow-md hover:bg-[#006699] transition-all uppercase tracking-wider">
        Start Shopping
    </a>
</div>
@endforelse
