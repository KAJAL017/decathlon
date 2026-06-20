@extends('layouts.app')

@section('title', 'My Wishlist - Decathlon')

@section('content')
<div class="w-full bg-white py-6 px-4 lg:px-8">
    <!-- Breadcrumbs -->
    <nav class="text-[11px] text-gray-500 mb-6 flex items-center gap-1.5 flex-wrap uppercase tracking-wider font-semibold">
        <a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-gray-900 font-bold">My Wishlist</span>
    </nav>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-xl font-black text-gray-950 uppercase tracking-tight">My Wishlist</h1>
        @if(!$isGuest && $wishlist->count() > 0)
            <span class="text-sm text-gray-500 font-medium">{{ $wishlist->count() }} {{ Str::plural('item', $wishlist->count()) }}</span>
        @endif
        @if($isGuest)
            <span class="wishlist-page-count text-sm text-gray-500 font-medium hidden"></span>
        @endif
    </div>

    {{-- Server-rendered items (logged-in users) --}}
    @if(!$isGuest && $wishlist->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6" id="wishlist-server-grid">
            @foreach($wishlist as $item)
                @php
                    $product = $item->product;
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
                <div class="bg-white rounded-xl overflow-hidden group hover:shadow-lg transition-all duration-300 border border-gray-100" id="wishlist-item-{{ $product->id }}">
                    <div class="relative bg-gray-100">
                        @if($product->is_new)
                            <span class="absolute top-2 left-2 bg-[#0082C3] text-white text-[10px] font-bold px-2 py-0.5 rounded">New</span>
                        @elseif($discount > 0)
                            <span class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">Price drop</span>
                        @endif

                        <button class="wishlist-btn absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50 z-10"
                                data-product-id="{{ $product->id }}"
                                title="Remove from Wishlist">
                            <span class="heart-outline hidden">
                                <i data-lucide="heart" class="w-4 h-4 text-gray-600"></i>
                            </span>
                            <span class="heart-filled">
                                <i data-lucide="heart" class="w-4 h-4 text-red-500 fill-red-500"></i>
                            </span>
                        </button>

                        <a href="{{ route('product', $product->slug) }}" class="block overflow-hidden">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-[180px] md:h-[280px] object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] font-bold text-gray-900 mb-0.5 uppercase tracking-tighter">{{ $product->brand?->name ?? 'DECATHLON' }}</p>
                        <a href="{{ route('product', $product->slug) }}" class="text-[12px] text-gray-700 mb-1.5 truncate block leading-tight hover:text-[#0082C3] transition-colors">
                            {{ $product->name }}
                        </a>
                        <div class="flex items-center gap-1 mb-1.5">
                            <span class="text-yellow-500 text-sm">&#9733;</span>
                            <span class="text-[11px] font-semibold">{{ number_format($product->average_rating ?? 4.5, 2) }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 mb-3">
                            <span class="text-sm font-bold text-gray-900">&#8377;{{ number_format($price) }}</span>
                            @if($comparePrice && $comparePrice > $price)
                                <span class="text-[10px] text-gray-400 line-through">&#8377;{{ number_format($comparePrice) }}</span>
                                <span class="text-[10px] font-bold text-red-600">{{ $discount }}% Off</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @php
                                $hasVariants = $product->variants->count() > 1;
                                $firstVariant = $product->variants->first();
                                $vId = $firstVariant?->id;
                            @endphp

                            @if($hasVariants)
                                <button onclick="window.QuickView.open('{{ $product->slug }}')"
                                        class="flex-1 border border-gray-900 text-gray-900 text-[11px] font-bold py-2 rounded-lg hover:bg-gray-900 hover:text-white transition-colors">
                                    ADD TO CART
                                </button>
                            @else
                                <button type="button" class="add-to-cart-btn flex-1 border border-gray-900 text-gray-900 text-[11px] font-bold py-2 rounded-lg hover:bg-gray-900 hover:text-white transition-colors"
                                        data-product-id="{{ $product->id }}"
                                        data-variant-id="{{ $vId }}">
                                    ADD TO CART
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#183a9e] hover:bg-[#0c246b] text-white px-8 py-3.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all shadow-md">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Continue Shopping
            </a>
        </div>
    @endif

    {{-- Guest: JavaScript-rendered items --}}
    <div id="wishlist-guest-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6 hidden"></div>

    <div id="wishlist-guest-continue" class="mt-10 text-center hidden">
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#183a9e] hover:bg-[#0c246b] text-white px-8 py-3.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all shadow-md">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Continue Shopping
        </a>
    </div>

    {{-- Empty state (server-rendered for logged-in, JS-managed for guests) --}}
    @if(!$isGuest && $wishlist->count() === 0)
        <div id="wishlist-empty-state">
            <div class="text-center py-20">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i data-lucide="heart" class="w-12 h-12 text-gray-300"></i>
                </div>
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-2">Your Wishlist is Empty</h2>
                <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">Save your favorite products here to buy them later or share with friends.</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#183a9e] hover:bg-[#0c246b] text-white px-8 py-3.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all shadow-md">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    Start Shopping
                </a>
            </div>
        </div>
    @endif

    {{-- Guest empty state (hidden by default, shown via JS) --}}
    <div id="wishlist-guest-empty" class="hidden">
        <div class="text-center py-20">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <i data-lucide="heart" class="w-12 h-12 text-gray-300"></i>
            </div>
            <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-2">Your Wishlist is Empty</h2>
            <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">Save your favorite products here to buy them later or share with friends.</p>
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#183a9e] hover:bg-[#0c246b] text-white px-8 py-3.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all shadow-md">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                Start Shopping
            </a>
        </div>
    </div>
</div>

@if($isGuest)
<script>
(function() {
    const GUEST_WISHLIST_KEY = 'decathlon_wishlist';
    const guestGrid = document.getElementById('wishlist-guest-grid');
    const guestContinue = document.getElementById('wishlist-guest-continue');
    const guestEmpty = document.getElementById('wishlist-guest-empty');
    const serverGrid = document.getElementById('wishlist-server-grid');
    const serverEmpty = document.getElementById('wishlist-empty-state');
    const pageCountEl = document.querySelector('.wishlist-page-count');

    function getGuestIds() {
        try {
            const stored = localStorage.getItem(GUEST_WISHLIST_KEY);
            return stored ? JSON.parse(stored) : [];
        } catch (e) { return []; }
    }

    function formatPrice(p) {
        return new Intl.NumberFormat('en-IN').format(p);
    }

    function renderGuestItem(p) {
        const discountHtml = p.discount > 0
            ? (p.is_new
                ? '<span class="absolute top-2 left-2 bg-[#0082C3] text-white text-[10px] font-bold px-2 py-0.5 rounded">New</span>'
                : '<span class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">Price drop</span>')
            : (p.is_new
                ? '<span class="absolute top-2 left-2 bg-[#0082C3] text-white text-[10px] font-bold px-2 py-0.5 rounded">New</span>'
                : '');

        const compareHtml = p.compare_price && p.compare_price > p.price
            ? '<span class="text-[10px] text-gray-400 line-through">&#8377;' + formatPrice(p.compare_price) + '</span><span class="text-[10px] font-bold text-red-600">' + p.discount + '% Off</span>'
            : '';

        const cartBtn = p.has_variants
            ? '<button onclick="window.QuickView.open(\'' + p.slug + '\')" class="flex-1 border border-gray-900 text-gray-900 text-[11px] font-bold py-2 rounded-lg hover:bg-gray-900 hover:text-white transition-colors">ADD TO CART</button>'
            : '<button type="button" class="add-to-cart-btn flex-1 border border-gray-900 text-gray-900 text-[11px] font-bold py-2 rounded-lg hover:bg-gray-900 hover:text-white transition-colors" data-product-id="' + p.id + '" data-variant-id="' + (p.first_variant_id || '') + '">ADD TO CART</button>';

        const stockLabel = p.manage_stock && p.stock_quantity <= 0
            ? '<span class="text-[10px] font-bold text-red-500">Out of Stock</span>'
            : '';

        return '<div class="bg-white rounded-xl overflow-hidden group hover:shadow-lg transition-all duration-300 border border-gray-100" id="wishlist-item-' + p.id + '">' +
            '<div class="relative bg-gray-100">' + discountHtml +
            '<button class="wishlist-btn absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50 z-10" data-product-id="' + p.id + '" title="Remove from Wishlist">' +
                '<span class="heart-outline hidden"><i data-lucide="heart" class="w-4 h-4 text-gray-600"></i></span>' +
                '<span class="heart-filled"><i data-lucide="heart" class="w-4 h-4 text-red-500 fill-red-500"></i></span>' +
            '</button>' +
            '<a href="/product/' + p.slug + '" class="block overflow-hidden">' +
                '<img src="' + p.image_url + '" alt="' + p.name.replace(/"/g, '&quot;') + '" loading="lazy" class="w-full h-[180px] md:h-[280px] object-cover group-hover:scale-105 transition-transform duration-500">' +
            '</a></div>' +
            '<div class="p-3">' +
                '<p class="text-[10px] font-bold text-gray-900 mb-0.5 uppercase tracking-tighter">' + p.brand + '</p>' +
                '<a href="/product/' + p.slug + '" class="text-[12px] text-gray-700 mb-1.5 truncate block leading-tight hover:text-[#0082C3] transition-colors">' + p.name + '</a>' +
                '<div class="flex items-center gap-1 mb-1.5">' +
                    '<span class="text-yellow-500 text-sm">&#9733;</span>' +
                    '<span class="text-[11px] font-semibold">' + formatPrice(p.average_rating || 4.5) + '</span>' +
                '</div>' +
                '<div class="flex items-center gap-1.5 mb-3">' +
                    '<span class="text-sm font-bold text-gray-900">&#8377;' + formatPrice(p.price) + '</span>' +
                    compareHtml +
                '</div>' +
                '<div class="flex items-center gap-2">' + cartBtn + stockLabel + '</div>' +
            '</div></div>';
    }

    async function loadGuestWishlist() {
        const ids = getGuestIds();
        if (ids.length === 0) {
            if (serverGrid) serverGrid.classList.add('hidden');
            if (serverEmpty) serverEmpty.classList.add('hidden');
            guestEmpty.classList.remove('hidden');
            return;
        }

        try {
            const res = await fetch('{{ route("wishlist.guest-products") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_ids: ids })
            });
            const result = await res.json();

            if (result.success && result.data.length > 0) {
                guestGrid.innerHTML = result.data.map(renderGuestItem).join('');
                guestGrid.classList.remove('hidden');
                guestContinue.classList.remove('hidden');

                if (serverGrid) serverGrid.classList.add('hidden');
                if (serverEmpty) serverEmpty.classList.add('hidden');

                if (pageCountEl) {
                    pageCountEl.textContent = result.data.length + ' item' + (result.data.length > 1 ? 's' : '');
                    pageCountEl.classList.remove('hidden');
                }

                if (typeof lucide !== 'undefined') lucide.createIcons({ nodes: [guestGrid] });

                if (typeof window.Wishlist !== 'undefined') {
                    window.Wishlist.updateAllUI();
                }
            } else {
                if (serverGrid) serverGrid.classList.add('hidden');
                if (serverEmpty) serverEmpty.classList.add('hidden');
                guestEmpty.classList.remove('hidden');
            }
        } catch (e) {
            if (serverGrid) serverGrid.classList.add('hidden');
            if (serverEmpty) serverEmpty.classList.add('hidden');
            guestEmpty.classList.remove('hidden');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadGuestWishlist);
    } else {
        loadGuestWishlist();
    }

    window._reloadGuestWishlist = loadGuestWishlist;
})();
</script>
@endif
@endsection
