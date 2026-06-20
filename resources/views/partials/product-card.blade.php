@php
    $featuredImage = $product->featuredImage ?? $product->images->first();
    $imageUrl = $featuredImage?->image_url ?? asset('images/placeholder-product.svg');
    $variant = $product->variants->first();
    $price = $variant?->price ?? 0;
    $comparePrice = $variant?->compare_price;
    $discount = 0;
    if ($comparePrice && $comparePrice > $price) {
        $discount = round((($comparePrice - $price) / $comparePrice) * 100);
    }
@endphp

<div class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
    <div class="relative bg-gray-100">
        @if($product->is_new)
            <span class="absolute top-2 left-2 bg-[#0082C3] text-white text-[10px] font-bold px-2 py-0.5 rounded">New</span>
        @elseif($discount > 0)
            <span class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">Price drop</span>
        @endif
        
        <button class="wishlist-btn absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50 z-10"
                data-product-id="{{ $product->id }}"
                title="Add to Wishlist">
            <span class="heart-outline">
                <i data-lucide="heart" class="w-4 h-4 text-gray-600"></i>
            </span>
            <span class="heart-filled hidden">
                <i data-lucide="heart" class="w-4 h-4 text-red-500 fill-red-500"></i>
            </span>
        </button>
        
        <a href="{{ route('product', $product->slug) }}" class="block overflow-hidden">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-[160px] md:h-[250px] object-cover group-hover:scale-105 transition-transform duration-500">
        </a>
    </div>
    <div class="p-2">
        <p class="text-[10px] font-bold text-gray-900 mb-0.5 uppercase tracking-tighter">{{ $product->brand?->name ?? 'DECATHLON' }}</p>
        <a href="{{ route('product', $product->slug) }}" class="text-[11px] text-gray-700 mb-1.5 truncate block leading-tight hover:text-[#0082C3] transition-colors">
            {{ $product->name }}
        </a>
        <div class="flex items-center gap-1 mb-1.5">
            <span class="text-yellow-500 text-sm">★</span>
            <span class="text-[11px] font-semibold">{{ number_format($product->average_rating ?? 4.5, 2) }}</span>
            <span class="text-[10px] text-gray-500">| {{ $product->reviews_count > 1000 ? number_format($product->reviews_count/1000, 1) . 'k' : ($product->reviews_count ?? '0') }}</span>
        </div>
        <div class="flex items-center gap-1.5 mb-2">
            <span class="text-sm font-bold text-gray-900">₹{{ number_format($price) }}</span>
            @if($comparePrice && $comparePrice > $price)
                <span class="text-[10px] text-gray-400 line-through">₹{{ number_format($comparePrice) }}</span>
                <span class="text-[10px] font-bold text-red-600">{{ $discount }}% Off</span>
            @endif
        </div>
        <div class="flex items-center gap-1.5 mt-2">
            @php
                $hasVariants = $product->variants->count() > 1;
                $firstVariant = $product->variants->first();
                $vId = $firstVariant?->id;
            @endphp
            
            @if($hasVariants)
                <button onclick="window.QuickView.open('{{ $product->slug }}')" 
                        class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                    ADD TO CART
                </button>
            @else
                <button type="button" class="add-to-cart-btn w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors"
                        data-product-id="{{ $product->id }}"
                        data-variant-id="{{ $vId }}">
                    ADD TO CART
                </button>
            @endif
        </div>
    </div>
</div>
