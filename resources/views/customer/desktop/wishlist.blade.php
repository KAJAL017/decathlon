@extends('customer.layouts.desktop')

@section('title', 'My Wishlist')
@section('page-title', 'Wishlist')
@section('page-subtitle')
{{ $wishlist->count() }} item{{ $wishlist->count() !== 1 ? 's' : '' }} saved
@endsection

@section('content')
<div class="space-y-6">

    @if($wishlist->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-red-50 rounded-3xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="heart" class="w-10 h-10 text-red-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">Your wishlist is empty</h3>
            <p class="text-sm text-surface-400 mb-6 max-w-sm mx-auto">Save items you love to your wishlist and come back anytime.</p>
            <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-8 py-3.5 rounded-xl text-sm font-semibold">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Discover Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-3 gap-6" id="wishlistGrid">
            @foreach($wishlist as $index => $item)
                @php $product = $item->product; @endphp
                <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden hover:shadow-lg hover:shadow-surface-200/50 hover:-translate-y-1 transition-all duration-200 animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}" id="wishlist-item-{{ $item->id }}">
                    <div class="relative">
                        <a href="{{ route('product', $product->slug) }}" class="block aspect-square bg-surface-50 p-5">
                            @php $img = $product->featuredImage; @endphp
                            <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-contain" alt="{{ $product->name }}">
                        </a>
                        <button onclick="removeFromWishlist({{ $item->id }})" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 hover:bg-white hover:scale-110 transition-all shadow-sm">
                            <i data-lucide="heart" class="w-5 h-5 fill-current"></i>
                        </button>
                    </div>

                    <div class="p-5">
                        <p class="text-xs text-surface-400 font-medium">{{ $product->brand->name ?? '' }}</p>
                        <a href="{{ route('product', $product->slug) }}" class="text-base font-semibold text-surface-800 line-clamp-2 mt-1 block hover:text-brand-600 transition-colors">{{ $product->name }}</a>

                        @php
                            $variant = $product->variants->first();
                            $price = $variant ? $variant->price : ($product->price ?? 0);
                            $comparePrice = $variant ? $variant->compare_price : null;
                            $discount = $comparePrice && $comparePrice > $price ? round((($comparePrice - $price) / $comparePrice) * 100) : 0;
                        @endphp

                        <div class="flex items-center gap-2.5 mt-2">
                            <span class="text-xl font-bold text-surface-900">₹{{ number_format($price, 0) }}</span>
                            @if($comparePrice && $comparePrice > $price)
                                <span class="text-sm text-surface-400 line-through">₹{{ number_format($comparePrice, 0) }}</span>
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded">{{ $discount }}%</span>
                            @endif
                        </div>

                        <div class="flex gap-3 mt-4">
                            <button onclick="moveToCart({{ $item->id }})" class="flex-1 btn-primary text-white py-2.5 rounded-xl text-sm font-semibold text-center hover:opacity-90">
                                Move to Cart
                            </button>
                            <a href="{{ route('product', $product->slug) }}" class="px-5 py-2.5 border border-surface-200 rounded-xl text-sm font-medium text-surface-600 hover:bg-surface-50 transition-colors">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

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
@endsection
