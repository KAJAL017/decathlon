@extends('customer.layouts.mobile')

@section('title', 'Recently Viewed')
@section('page-title', 'Recently Viewed')

@section('content')
<div class="space-y-3">

    @if($recentlyViewed->isEmpty())
        <div class="bg-white rounded-2xl p-8 text-center animate-fade-in">
            <div class="w-14 h-14 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="clock" class="w-7 h-7 text-surface-300"></i>
            </div>
            <h3 class="text-base font-bold text-surface-900 mb-1">No recently viewed</h3>
            <p class="text-xs text-surface-400 mb-5">Start browsing to see your history here.</p>
            <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-5 py-3 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 gap-2.5">
            @foreach($recentlyViewed as $index => $product)
                <a href="{{ route('product', $product->slug) }}" class="bg-white rounded-2xl border border-surface-100 overflow-hidden active:scale-[0.98] transition-transform animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">
                    <div class="aspect-square bg-surface-50 p-2.5">
                        @php $img = $product->featuredImage; @endphp
                        <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-contain" alt="{{ $product->name }}">
                    </div>
                    <div class="p-2.5">
                        <p class="text-[9px] text-surface-400 font-medium uppercase tracking-wide">{{ $product->brand->name ?? '' }}</p>
                        <p class="text-[11px] font-medium text-surface-800 line-clamp-2 mt-0.5 leading-tight">{{ $product->name }}</p>
                        @php
                            $variant = $product->variants->first();
                            $price = $variant ? $variant->price : ($product->price ?? 0);
                            $comparePrice = $variant ? $variant->compare_price : null;
                            $discount = $comparePrice && $comparePrice > $price ? round((($comparePrice - $price) / $comparePrice) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="text-xs font-bold text-surface-900">₹{{ number_format($price, 0) }}</span>
                            @if($discount > 0)
                                <span class="px-1.5 py-0.5 bg-green-50 text-green-600 rounded text-[9px] font-semibold">{{ $discount }}% off</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
