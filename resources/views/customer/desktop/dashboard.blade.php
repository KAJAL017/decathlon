@extends('customer.layouts.desktop')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-brand-600 via-brand-500 to-emerald-500 rounded-3xl p-10 text-white animate-slide-up">
        <div class="relative z-10 max-w-2xl">
            <p class="text-brand-100 text-sm font-medium mb-2">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},</p>
            <h2 class="text-4xl font-bold mb-3">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
            <p class="text-brand-100 text-base max-w-lg leading-relaxed">Manage your orders, track deliveries, and explore exclusive rewards — all in one place.</p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-56 h-56 bg-white/10 rounded-full"></div>
        <div class="absolute right-40 -top-8 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="absolute right-60 bottom-0 w-20 h-20 bg-white/5 rounded-full"></div>
    </div>

    {{-- Stats Grid - 4 columns --}}
    <div class="grid grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-6 border border-surface-100 card-hover animate-slide-up stagger-1 opacity-0">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
            </div>
            <p class="text-3xl font-bold text-surface-900">{{ $activeOrdersCount }}</p>
            <p class="text-sm text-surface-400 font-medium mt-1">Active Orders</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-surface-100 card-hover animate-slide-up stagger-3 opacity-0">
            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mb-4">
                <i data-lucide="heart" class="w-6 h-6 text-red-500"></i>
            </div>
            <p class="text-3xl font-bold text-surface-900">{{ $wishlistCount }}</p>
            <p class="text-sm text-surface-400 font-medium mt-1">Wishlist Items</p>
        </div>
    </div>

    {{-- Main Grid - 3 columns (2 + 1 sidebar) --}}
    <div class="grid grid-cols-3 gap-6">

        {{-- Recent Orders - spans 2 columns --}}
        <div class="col-span-2 space-y-4 animate-slide-up stagger-3 opacity-0">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-surface-900">Recent Orders</h3>
                <a href="{{ route('customer.orders') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">View All →</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="bg-white rounded-2xl p-12 border border-surface-100 text-center">
                    <div class="w-16 h-16 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="package" class="w-8 h-8 text-surface-300"></i>
                    </div>
                    <p class="text-surface-500 font-medium mb-4">No orders yet</p>
                    <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-6 py-3 rounded-xl text-sm font-semibold">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i> Start Shopping
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
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
                        <a href="{{ route('customer.orders.show', $order->order_number) }}"
                           class="block bg-white rounded-2xl p-5 border border-surface-100 hover:shadow-lg hover:shadow-surface-200/50 hover:-translate-y-0.5 transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-surface-50 rounded-2xl overflow-hidden shrink-0">
                                        @if($order->items->first() && $order->items->first()->product)
                                            @php $img = $order->items->first()->product->featuredImage; @endphp
                                            <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"><i data-lucide="package" class="w-5 h-5 text-surface-300"></i></div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-surface-900">#{{ $order->order_number }}</p>
                                        <p class="text-sm text-surface-400 mt-0.5">{{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-surface-900">₹{{ number_format($order->total_amount, 0) }}</p>
                                    <span class="inline-block mt-1 px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $statusColors[$order->status] ?? 'bg-surface-50 text-surface-600 border-surface-200' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-5">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-4 opacity-0">
                <h3 class="text-sm font-bold text-surface-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('shop') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl hover:bg-surface-50 transition-colors">
                        <div class="w-11 h-11 bg-brand-50 rounded-xl flex items-center justify-center"><i data-lucide="shopping-bag" class="w-5 h-5 text-brand-600"></i></div>
                        <span class="text-xs font-medium text-surface-600">Shop</span>
                    </a>
                    <a href="{{ route('customer.orders') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl hover:bg-surface-50 transition-colors">
                        <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center"><i data-lucide="package" class="w-5 h-5 text-blue-600"></i></div>
                        <span class="text-xs font-medium text-surface-600">Orders</span>
                    </a>
                    <a href="{{ route('customer.wishlist') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl hover:bg-surface-50 transition-colors">
                        <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center"><i data-lucide="heart" class="w-5 h-5 text-red-500"></i></div>
                        <span class="text-xs font-medium text-surface-600">Wishlist</span>
                    </a>
                    <a href="{{ route('customer.support') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl hover:bg-surface-50 transition-colors">
                        <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center"><i data-lucide="headphones" class="w-5 h-5 text-purple-600"></i></div>
                        <span class="text-xs font-medium text-surface-600">Support</span>
                    </a>
                </div>
            </div>

            {{-- Default Address --}}
            @if($defaultAddress)
                <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-5 opacity-0">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-surface-900">Default Address</h3>
                        <a href="{{ route('customer.addresses') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">Edit</a>
                    </div>
                    <div class="text-sm text-surface-600 space-y-1">
                        <p class="font-medium text-surface-900">{{ $defaultAddress->full_name }}</p>
                        <p>{{ $defaultAddress->address_line1 }}</p>
                        @if($defaultAddress->address_line2)
                            <p>{{ $defaultAddress->address_line2 }}</p>
                        @endif
                        <p>{{ $defaultAddress->city }}, {{ $defaultAddress->state }} {{ $defaultAddress->pincode }}</p>
                        @if($defaultAddress->phone)
                            <p class="text-surface-400">{{ $defaultAddress->phone }}</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Recently Viewed --}}
    @if($recentlyViewed->isNotEmpty())
        <div class="animate-slide-up stagger-5 opacity-0">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-surface-900">Recently Viewed</h3>
                <a href="{{ route('customer.recently-viewed') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">View All →</a>
            </div>
            <div class="grid grid-cols-5 gap-5">
                @foreach($recentlyViewed->take(5) as $product)
                    <a href="{{ route('product', $product->slug) }}" class="bg-white rounded-2xl border border-surface-100 overflow-hidden hover:shadow-lg hover:shadow-surface-200/50 hover:-translate-y-1 transition-all duration-200">
                        <div class="aspect-square bg-surface-50 p-4">
                            @php $img = $product->featuredImage; @endphp
                            <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-contain" alt="{{ $product->name }}">
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-surface-400 font-medium">{{ $product->brand->name ?? '' }}</p>
                            <p class="text-sm font-medium text-surface-800 line-clamp-2 mt-0.5">{{ $product->name }}</p>
                            @php
                                $variant = $product->variants->first();
                                $price = $variant ? $variant->price : $product->price ?? 0;
                            @endphp
                            <p class="text-base font-bold text-surface-900 mt-1.5">₹{{ number_format($price, 0) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
