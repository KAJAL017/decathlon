@extends('customer.layouts.mobile')

@section('title', 'Track Order #' . $order->order_number)
@section('page-title', 'Track Order')

@section('mobile-back')
    <a href="{{ route('customer.orders.show', $order->order_number) }}" class="p-2 -ml-2 text-surface-500 active:text-surface-800 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
@endsection

@section('content')
<div class="space-y-3">

    {{-- Estimated Delivery --}}
    @if(!in_array($order->status, ['delivered', 'cancelled']))
        <div class="bg-white rounded-xl p-4 border border-surface-100 text-center animate-slide-up">
            <p class="text-[10px] text-surface-400 font-medium mb-0.5">Estimated Delivery</p>
            <p class="text-base font-bold text-surface-900">
                @if($order->estimated_delivery)
                    {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d M Y') }}
                @else
                    Within 5-7 business days
                @endif
            </p>
        </div>
    @endif

    {{-- Timeline --}}
    <div class="bg-white rounded-xl p-4 border border-surface-100 animate-slide-up stagger-1 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-3">Tracking Status</h3>
        <div class="relative">
            @foreach($trackingSteps as $index => $step)
                @php
                    $stepIndex = array_search($step['key'], ['confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered']);
                    $isCompleted = $stepIndex !== false && $currentIndex !== false && $currentIndex >= $stepIndex;
                    $isCurrent = $stepIndex !== false && $stepIndex === $currentIndex;
                    $isLast = $index === count($trackingSteps) - 1;
                @endphp

                <div class="flex gap-3 {{ !$isLast ? 'pb-5' : '' }}">
                    {{-- Indicator --}}
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all
                            {{ $isCompleted ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'bg-surface-100 text-surface-400' }}
                            {{ $isCurrent ? 'ring-3 ring-brand-100' : '' }}">
                            @if($isCompleted && !$isCurrent)
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            @else
                                <i data-lucide="{{ $step['icon'] }}" class="w-3.5 h-3.5"></i>
                            @endif
                        </div>
                        @if(!$isLast)
                            <div class="w-0.5 flex-1 {{ $isCompleted ? 'bg-brand-600' : 'bg-surface-200' }} mt-1.5"></div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="pt-1">
                        <p class="text-[11px] font-semibold {{ $isCompleted ? 'text-surface-900' : 'text-surface-400' }}">{{ $step['label'] }}</p>
                        @if($isCurrent)
                            <p class="text-[10px] text-brand-600 font-medium mt-0.5">Current status</p>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Cancelled State --}}
            @if($order->status === 'cancelled')
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="pt-1">
                        <p class="text-[11px] font-semibold text-red-700">Order Cancelled</p>
                        <p class="text-[10px] text-surface-400 mt-0.5">This order has been cancelled</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Shipping Details --}}
    @if($order->tracking_number)
        <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up stagger-2 opacity-0">
            <h3 class="text-xs font-bold text-surface-900 mb-2">Shipping Details</h3>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <div>
                    <p class="text-surface-400 text-[10px]">Courier</p>
                    <p class="font-medium text-surface-800">{{ $order->shipping_carrier ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-surface-400 text-[10px]">Tracking #</p>
                    <p class="font-medium text-surface-800 break-all">{{ $order->tracking_number }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Order Items --}}
    <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up stagger-3 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-2">Order Items</h3>
        <div class="space-y-2">
            @foreach($order->items as $item)
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-surface-50 rounded-lg overflow-hidden shrink-0">
                        @php $img = $item->product->featuredImage ?? null; @endphp
                        <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-medium text-surface-800 truncate">{{ $item->product_name }}</p>
                        <p class="text-[10px] text-surface-400">Qty: {{ $item->quantity }}</p>
                    </div>
                    <p class="text-[11px] font-bold text-surface-900 shrink-0">₹{{ number_format($item->total_price, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
