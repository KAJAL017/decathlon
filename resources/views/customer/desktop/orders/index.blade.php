@extends('customer.layouts.desktop')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="space-y-6">

    {{-- Filter Tabs --}}
    <div class="flex gap-1.5 bg-white rounded-2xl p-1.5 border border-surface-100 w-fit">
        @php
            $tabs = [
                'all' => 'All',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ];
        @endphp
        @foreach($tabs as $key => $label)
            <a href="{{ route('customer.orders', ['status' => $key]) }}"
               class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $status === $key ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/20' : 'text-surface-500 hover:text-surface-700 hover:bg-surface-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Orders List --}}
    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-surface-50 rounded-3xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="package" class="w-10 h-10 text-surface-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">No orders found</h3>
            <p class="text-sm text-surface-400 mb-6 max-w-sm mx-auto">
                @if($status !== 'all')
                    No {{ strtolower($status) }} orders to show.
                @else
                    You haven't placed any orders yet. Start shopping to see your orders here.
                @endif
            </p>
            <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-8 py-3.5 rounded-xl text-sm font-semibold">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Browse Products
            </a>
        </div>
    @else
        <div class="space-y-4" id="ordersList">
            @foreach($orders as $index => $order)
                @php
                    $statusColors = [
                        'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                        'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500'],
                        'processing' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'dot' => 'bg-purple-500'],
                        'shipped' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'dot' => 'bg-indigo-500'],
                        'delivered' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'dot' => 'bg-green-500'],
                        'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'dot' => 'bg-red-500'],
                    ];
                    $sc = $statusColors[$order->status] ?? $statusColors['pending'];
                @endphp
                <a href="{{ route('customer.orders.show', $order->order_number) }}"
                   class="block bg-white rounded-2xl p-6 border border-surface-100 hover:shadow-lg hover:shadow-surface-200/50 hover:-translate-y-0.5 transition-all duration-200 animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold border {{ $sc['bg'] }} {{ $sc['text'] }} {{ $sc['border'] }}">
                                <span class="w-2 h-2 rounded-full {{ $sc['dot'] }}"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="text-sm text-surface-400 font-medium">#{{ $order->order_number }}</span>
                        </div>
                        <span class="text-sm text-surface-400">{{ $order->created_at->format('d M Y') }}</span>
                    </div>

                    {{-- Items --}}
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex -space-x-3">
                            @foreach($order->items->take(3) as $item)
                                @php $img = $item->product->featuredImage ?? null; @endphp
                                <div class="w-14 h-14 rounded-xl border-2 border-white bg-surface-50 overflow-hidden">
                                    <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div class="flex-1">
                            <p class="text-base font-medium text-surface-800 truncate">
                                {{ $order->items->first()?->product_name ?? 'Product' }}
                                @if($order->items->count() > 1)
                                    <span class="text-surface-400">+{{ $order->items->count() - 1 }}</span>
                                @endif
                            </p>
                            <p class="text-sm text-surface-400 mt-0.5">{{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-4 border-t border-surface-100">
                        <p class="text-xl font-bold text-surface-900">₹{{ number_format($order->total_amount, 2) }}</p>
                        <div class="flex items-center gap-3">
                            @if(in_array($order->status, ['shipped', 'processing']))
                                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600">
                                    <i data-lucide="truck" class="w-4 h-4"></i> Track
                                </span>
                            @endif
                            <i data-lucide="chevron-right" class="w-5 h-5 text-surface-300"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $orders->withQueryString()->links('pagination::tailwind') }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
