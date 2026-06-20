@extends('customer.layouts.mobile')

@section('title', 'My Wishlist')
@section('page-title', 'Wishlist')

@section('content')
<div class="space-y-3">

    @if($wishlist->isEmpty())
        <div class="bg-white rounded-xl p-6 border border-surface-100 text-center animate-fade-in">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                <i data-lucide="heart" class="w-6 h-6 text-red-300"></i>
            </div>
            <h3 class="text-sm font-bold text-surface-900 mb-1">Your wishlist is empty</h3>
            <p class="text-[11px] text-surface-400 mb-4">Save items you love to your wishlist.</p>
            <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-xs font-semibold">
                <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Discover Products
            </a>
        </div>
    @else
        <p class="text-[11px] text-surface-400 font-medium">{{ $wishlist->count() }} item{{ $wishlist->count() !== 1 ? 's' : '' }} saved</p>

        <div class="grid grid-cols-2 gap-2" id="wishlistGrid">
            @foreach($wishlist as $index => $item)
                @php $product = $item->product; @endphp
                <div class="bg-white rounded-xl border border-surface-100 overflow-hidden active:scale-[0.98] transition-transform animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}" id="wishlist-item-{{ $item->id }}">
                    <div class="relative">
                        <a href="{{ route('product', $product->slug) }}" class="block aspect-square bg-surface-50 p-2">
                            @php $img = $product->featuredImage; @endphp
                            <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-contain" alt="{{ $product->name }}">
                        </a>
                        <button onclick="removeFromWishlist({{ $item->id }})" class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 active:bg-white active:scale-110 transition-all shadow-sm">
                            <i data-lucide="heart" class="w-3.5 h-3.5 fill-current"></i>
                        </button>
                    </div>

                    <div class="p-2">
                        <p class="text-[9px] text-surface-400 font-medium">{{ $product->brand->name ?? '' }}</p>
                        <a href="{{ route('product', $product->slug) }}" class="text-[10px] font-semibold text-surface-800 line-clamp-2 mt-0.5 block active:text-brand-600 transition-colors leading-tight">{{ $product->name }}</a>

                        @php
                            $variant = $product->variants->first();
                            $price = $variant ? $variant->price : ($product->price ?? 0);
                            $comparePrice = $variant ? $variant->compare_price : null;
                            $discount = $comparePrice && $comparePrice > $price ? round((($comparePrice - $price) / $comparePrice) * 100) : 0;
                        @endphp

                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-[11px] font-bold text-surface-900">₹{{ number_format($price, 0) }}</span>
                            @if($comparePrice && $comparePrice > $price)
                                <span class="text-[9px] text-surface-400 line-through">₹{{ number_format($comparePrice, 0) }}</span>
                                <span class="text-[8px] font-semibold text-green-600 bg-green-50 px-1 py-0.5 rounded">{{ $discount }}%</span>
                            @endif
                        </div>

                        <div class="flex gap-1 mt-2">
                            <button onclick="moveToCart({{ $item->id }})" class="flex-1 btn-primary text-white py-1.5 rounded-lg text-[10px] font-semibold text-center active:opacity-90">
                                Move to Cart
                            </button>
                            <a href="{{ route('product', $product->slug) }}" class="px-2 py-1.5 border border-surface-200 rounded-lg text-[10px] font-medium text-surface-600 active:bg-surface-50 transition-colors">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

async function removeFromWishlist(id) {
    const res = await ajax(`{{ url('account/wishlist') }}/${id}`, { method: 'DELETE' });
    const data = await res.json();
    if (data.success) {
        const el = document.getElementById(`wishlist-item-${id}`);
        el.style.transition = 'all 0.3s ease';
        el.style.opacity = '0';
        el.style.transform = 'scale(0.95)';
        setTimeout(() => el.remove(), 300);
        showToast(data.message);
    }
}

async function moveToCart(id) {
    const res = await ajax(`{{ url('account/wishlist/move-to-cart') }}/${id}`, { method: 'POST' });
    const data = await res.json();
    if (data.success) {
        const el = document.getElementById(`wishlist-item-${id}`);
        el.style.transition = 'all 0.3s ease';
        el.style.opacity = '0';
        el.style.transform = 'scale(0.95)';
        setTimeout(() => el.remove(), 300);
        showToast(data.message);
    }
}
</script>
@endpush
