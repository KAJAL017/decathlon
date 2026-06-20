@extends('customer.layouts.mobile')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="space-y-3">

    {{-- Horizontal Scrollable Filter Pills --}}
    <div class="flex gap-1.5 overflow-x-auto scrollbar-none -mx-4 px-4 pb-1">
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
               class="flex-none px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap transition-all {{ $status === $key ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-white text-surface-500 border border-surface-200 active:bg-surface-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Orders List --}}
    @if($orders->isEmpty())
        <div class="bg-white rounded-xl p-6 border border-surface-100 text-center animate-fade-in">
            <div class="w-12 h-12 bg-surface-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                <i data-lucide="package" class="w-6 h-6 text-surface-300"></i>
            </div>
            <h3 class="text-sm font-bold text-surface-900 mb-1">No orders found</h3>
            <p class="text-[11px] text-surface-400 mb-4">
                @if($status !== 'all')
                    No {{ strtolower($status) }} orders to show.
                @else
                    You haven't placed any orders yet.
                @endif
            </p>
            <a href="{{ route('shop') }}" class="btn-primary inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-xs font-semibold">
                <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Browse Products
            </a>
        </div>
    @else
        <div class="space-y-2" id="ordersList">
            @foreach($orders as $index => $order)
                @php
                    $statusColors = [
                        'pending' => ['dot' => 'bg-amber-500', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700'],
                        'confirmed' => ['dot' => 'bg-blue-500', 'bg' => 'bg-blue-50', 'text' => 'text-blue-700'],
                        'processing' => ['dot' => 'bg-purple-500', 'bg' => 'bg-purple-50', 'text' => 'text-purple-700'],
                        'shipped' => ['dot' => 'bg-indigo-500', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-700'],
                        'delivered' => ['dot' => 'bg-green-500', 'bg' => 'bg-green-50', 'text' => 'text-green-700'],
                        'cancelled' => ['dot' => 'bg-red-500', 'bg' => 'bg-red-50', 'text' => 'text-red-700'],
                    ];
                    $sc = $statusColors[$order->status] ?? $statusColors['pending'];
                @endphp
                <a href="{{ route('customer.orders.show', $order->order_number) }}"
                   class="block bg-white rounded-xl p-3 border border-surface-100 active:scale-[0.98] transition-transform animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2 h-2 rounded-full {{ $sc['dot'] }} shrink-0"></span>
                            <span class="text-[11px] font-semibold text-surface-900">#{{ $order->order_number }}</span>
                        </div>
                        <span class="text-[10px] text-surface-400 shrink-0 ml-2">{{ $order->created_at->format('d M') }}</span>
                    </div>

                    {{-- Items --}}
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex -space-x-1.5">
                            @foreach($order->items->take(3) as $item)
                                @php $img = $item->product->featuredImage ?? null; @endphp
                                <div class="w-9 h-9 rounded-lg border-2 border-white bg-surface-50 overflow-hidden">
                                    <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-medium text-surface-800 truncate">
                                {{ $order->items->first()?->product_name ?? 'Product' }}
                                @if($order->items->count() > 1)
                                    <span class="text-surface-400">+{{ $order->items->count() - 1 }}</span>
                                @endif
                            </p>
                            <p class="text-[10px] text-surface-400">{{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-2 border-t border-surface-100">
                        <p class="text-xs font-bold text-surface-900">₹{{ number_format($order->total_amount, 2) }}</p>
                        <div class="flex items-center gap-1.5">
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $sc['bg'] }} {{ $sc['text'] }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            @if(in_array($order->status, ['shipped', 'processing']))
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-medium text-brand-600">
                                    <i data-lucide="truck" class="w-3 h-3"></i> Track
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $orders->withQueryString()->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
