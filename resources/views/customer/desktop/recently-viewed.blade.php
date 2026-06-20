@extends('customer.layouts.desktop')

@section('title', 'Recently Viewed')
@section('page-title', 'Recently Viewed')

@section('content')
<div class="max-w-6xl">

    @if($recentlyViewed->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="clock" class="w-10 h-10 text-surface-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">No recently viewed products</h3>
            <p class="text-sm text-surface-400 mb-6 max-w-sm mx-auto">Start browsing to see your viewing history here.</p>
            <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-6 py-3 rounded-xl text-sm font-semibold">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Browse Products
            </a>
        </div>
    @else
        <p class="text-sm text-surface-400 mb-6">{{ $recentlyViewed->count() }} product{{ $recentlyViewed->count() !== 1 ? 's' : '' }} viewed</p>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($recentlyViewed as $index => $product)
                @php
                    $variant = $product->variants->first();
                    $price = $variant ? $variant->price : ($product->price ?? 0);
                    $comparePrice = $variant ? $variant->compare_price : null;
                    $discount = $comparePrice && $comparePrice > $price ? round((($comparePrice - $price) / $comparePrice) * 100) : 0;
                    $img = $product->featuredImage;
                @endphp
                <a href="{{ route('product', $product->slug) }}" class="bg-white rounded-2xl border border-surface-100 overflow-hidden card-hover group animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">
                    <div class="aspect-square bg-surface-50 p-5 relative overflow-hidden">
                        <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" alt="{{ $product->name }}">
                        @if($discount > 0)
                            <span class="absolute top-3 left-3 bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg">{{ $discount }}% OFF</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-surface-400 font-medium tracking-wide uppercase">{{ $product->brand->name ?? '' }}</p>
                        <p class="text-sm font-semibold text-surface-800 line-clamp-2 mt-1 leading-snug">{{ $product->name }}</p>
                        <div class="flex items-center gap-2 mt-2.5">
                            <span class="text-base font-bold text-surface-900">₹{{ number_format($price, 0) }}</span>
                            @if($comparePrice && $comparePrice > $price)
                                <span class="text-sm text-surface-400 line-through">₹{{ number_format($comparePrice, 0) }}</span>
                            @endif
                        </div>
                        @if($discount > 0)
                            <span class="inline-block mt-1.5 text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $discount }}% off</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
