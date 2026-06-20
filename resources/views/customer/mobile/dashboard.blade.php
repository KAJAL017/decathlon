@extends('customer.layouts.mobile')

@section('title', 'Dashboard')
@section('page-title', 'Home')

@section('content')
<div class="space-y-3">

    {{-- Greeting Banner --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-brand-600 via-brand-500 to-emerald-500 rounded-2xl p-4 text-white animate-slide-up">
        <div class="relative z-10">
            <p class="text-brand-100 text-[11px] font-medium mb-0.5">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},</p>
            <h2 class="text-lg font-bold">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
        </div>
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full"></div>
    </div>

    {{-- Stats Grid 2x2 --}}
    <div class="grid grid-cols-2 gap-2 animate-slide-up stagger-1 opacity-0">
        <div class="bg-white rounded-xl p-3 border border-surface-100 active:scale-[0.98] transition-transform">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mb-2">
                <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
            </div>
            <p class="text-lg font-bold text-surface-900">{{ $activeOrdersCount }}</p>
            <p class="text-[10px] text-surface-400 font-medium">Active Orders</p>
        </div>
        <div class="bg-white rounded-xl p-3 border border-surface-100 active:scale-[0.98] transition-transform">
            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center mb-2">
                <i data-lucide="heart" class="w-4 h-4 text-red-500"></i>
            </div>
            <p class="text-lg font-bold text-surface-900">{{ $wishlistCount }}</p>
            <p class="text-[10px] text-surface-400 font-medium">Wishlist Items</p>
        </div>
    </div>

    {{-- Quick Actions Row --}}
    <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up stagger-2 opacity-0">
        <div class="grid grid-cols-4 gap-1">
            <a href="{{ route('shop') }}" class="flex flex-col items-center gap-1.5 py-2 rounded-lg active:bg-surface-50 transition-colors">
                <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center"><i data-lucide="shopping-bag" class="w-5 h-5 text-brand-600"></i></div>
                <span class="text-[10px] font-semibold text-surface-600">Shop</span>
            </a>
            <a href="{{ route('customer.orders') }}" class="flex flex-col items-center gap-1.5 py-2 rounded-lg active:bg-surface-50 transition-colors">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center"><i data-lucide="package" class="w-5 h-5 text-blue-600"></i></div>
                <span class="text-[10px] font-semibold text-surface-600">Orders</span>
            </a>
            <a href="{{ route('customer.wishlist') }}" class="flex flex-col items-center gap-1.5 py-2 rounded-lg active:bg-surface-50 transition-colors">
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center"><i data-lucide="heart" class="w-5 h-5 text-red-500"></i></div>
                <span class="text-[10px] font-semibold text-surface-600">Wishlist</span>
            </a>
            <a href="{{ route('customer.support') }}" class="flex flex-col items-center gap-1.5 py-2 rounded-lg active:bg-surface-50 transition-colors">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center"><i data-lucide="headphones" class="w-5 h-5 text-purple-600"></i></div>
                <span class="text-[10px] font-semibold text-surface-600">Support</span>
            </a>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="animate-slide-up stagger-3 opacity-0">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-bold text-surface-900">Recent Orders</h3>
            <a href="{{ route('customer.orders') }}" class="text-xs font-medium text-brand-600 active:text-brand-700">View All</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="bg-white rounded-xl p-6 border border-surface-100 text-center">
                <div class="w-12 h-12 bg-surface-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i data-lucide="package" class="w-6 h-6 text-surface-300"></i>
                </div>
                <p class="text-surface-500 font-medium text-xs mb-2">No orders yet</p>
                <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-4 py-2 rounded-xl text-xs font-semibold">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Start Shopping
                </a>
            </div>
        @else
            <div class="space-y-2">
                @foreach($recentOrders->take(4) as $order)
                    <a href="{{ route('customer.orders.show', $order->order_number) }}"
                       class="block bg-white rounded-xl p-3 border border-surface-100 active:scale-[0.98] transition-transform">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-10 h-10 bg-surface-50 rounded-lg overflow-hidden shrink-0">
                                    @if($order->items->first() && $order->items->first()->product)
                                        @php $img = $order->items->first()->product->featuredImage; @endphp
                                        <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i data-lucide="package" class="w-4 h-4 text-surface-300"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-surface-900 truncate">#{{ $order->order_number }}</p>
                                    <p class="text-[10px] text-surface-400 mt-0.5">{{ $order->created_at->format('d M') }} · {{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-2">
                                <p class="text-xs font-bold text-surface-900">₹{{ number_format($order->total_amount, 0) }}</p>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'processing' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'delivered' => 'bg-green-50 text-green-700 border-green-200',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    ];
                                @endphp
                                <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[9px] font-semibold border {{ $statusColors[$order->status] ?? 'bg-surface-50 text-surface-600 border-surface-200' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Default Address --}}
    @if($defaultAddress)
        <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up stagger-4 opacity-0">
            <div class="flex items-center justify-between mb-1.5">
                <h3 class="text-xs font-bold text-surface-900">Default Address</h3>
                <a href="{{ route('customer.addresses') }}" class="text-[10px] font-medium text-brand-600">Edit</a>
            </div>
            <p class="text-[11px] text-surface-600 font-medium">{{ $defaultAddress->full_name }}</p>
            <p class="text-[10px] text-surface-400">{{ $defaultAddress->address_line1 }}, {{ $defaultAddress->city }}, {{ $defaultAddress->state }} {{ $defaultAddress->pincode }}</p>
        </div>
    @endif

    {{-- Recently Viewed --}}
    @if($recentlyViewed->isNotEmpty())
        <div class="animate-slide-up stagger-5 opacity-0">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-surface-900">Recently Viewed</h3>
                <a href="{{ route('customer.recently-viewed') }}" class="text-xs font-medium text-brand-600 active:text-brand-700">View All</a>
            </div>
            <div class="flex gap-2 overflow-x-auto scrollbar-none -mx-4 px-4 pb-1">
                @foreach($recentlyViewed->take(6) as $product)
                    <a href="{{ route('product', $product->slug) }}" class="flex-none w-[120px] bg-white rounded-xl border border-surface-100 overflow-hidden active:scale-[0.98] transition-transform">
                        <div class="aspect-square bg-surface-50 p-2">
                            @php $img = $product->featuredImage; @endphp
                            <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-contain" alt="{{ $product->name }}">
                        </div>
                        <div class="p-2">
                            <p class="text-[9px] text-surface-400 font-medium">{{ $product->brand->name ?? '' }}</p>
                            <p class="text-[10px] font-medium text-surface-800 line-clamp-1 mt-0.5">{{ $product->name }}</p>
                            @php
                                $variant = $product->variants->first();
                                $price = $variant ? $variant->price : $product->price ?? 0;
                            @endphp
                            <p class="text-[11px] font-bold text-surface-900 mt-0.5">₹{{ number_format($price, 0) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
