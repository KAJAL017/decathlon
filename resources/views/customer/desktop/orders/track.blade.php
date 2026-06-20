@extends('customer.layouts.desktop')

@section('title', 'Track Order #' . $order->order_number)
@section('page-title', 'Track Order')

@section('content')
<div class="space-y-6">

    <a href="{{ route('customer.orders.show', $order->order_number) }}" class="inline-flex items-center gap-2 text-sm font-medium text-surface-500 hover:text-surface-800 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Order
    </a>

    <div class="grid grid-cols-3 gap-6">
        {{-- Tracking Timeline - spans 2 columns --}}
        <div class="col-span-2">
            <div class="bg-white rounded-2xl p-8 border border-surface-100 animate-slide-up">
                {{-- Estimated Delivery --}}
                @if(!in_array($order->status, ['delivered', 'cancelled']))
                    <div class="text-center mb-10">
                        <p class="text-sm text-surface-400 font-medium mb-1">Estimated Delivery</p>
                        <p class="text-2xl font-bold text-surface-900">
                            @if($order->estimated_delivery)
                                {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d M Y') }}
                            @else
                                Within 5-7 business days
                            @endif
                        </p>
                    </div>
                @endif

                {{-- Timeline --}}
                <div class="relative">
                    @foreach($trackingSteps as $index => $step)
                        @php
                            $stepIndex = array_search($step['key'], ['confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered']);
                            $isCompleted = $stepIndex !== false && $currentIndex !== false && $currentIndex >= $stepIndex;
                            $isCurrent = $stepIndex !== false && $stepIndex === $currentIndex;
                            $isLast = $index === count($trackingSteps) - 1;
                        @endphp

                        <div class="flex gap-5 {{ !$isLast ? 'pb-8' : '' }}">
                            {{-- Indicator --}}
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 transition-all
                                    {{ $isCompleted ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'bg-surface-100 text-surface-400' }}
                                    {{ $isCurrent ? 'ring-4 ring-brand-100' : '' }}">
                                    @if($isCompleted && !$isCurrent)
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    @else
                                        <i data-lucide="{{ $step['icon'] }}" class="w-5 h-5"></i>
                                    @endif
                                </div>
                                @if(!$isLast)
                                    <div class="w-0.5 flex-1 {{ $isCompleted ? 'bg-brand-600' : 'bg-surface-200' }} mt-2"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="pt-2">
                                <p class="text-base font-semibold {{ $isCompleted ? 'text-surface-900' : 'text-surface-400' }}">{{ $step['label'] }}</p>
                                @if($isCurrent)
                                    <p class="text-sm text-brand-600 font-medium mt-0.5">Current status</p>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- If status is cancelled --}}
                    @if($order->status === 'cancelled')
                        <div class="flex gap-5">
                            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </div>
                            <div class="pt-2">
                                <p class="text-base font-semibold text-red-700">Order Cancelled</p>
                                <p class="text-sm text-surface-400 mt-0.5">This order has been cancelled</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-5">
            {{-- Courier Details --}}
            @if($order->tracking_number)
                <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-2 opacity-0">
                    <h3 class="text-base font-bold text-surface-900 mb-4">Shipping Details</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-surface-400 mb-1 text-xs font-medium uppercase tracking-wider">Courier</p>
                            <p class="font-medium text-surface-800">{{ $order->shipping_carrier ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-surface-400 mb-1 text-xs font-medium uppercase tracking-wider">Tracking Number</p>
                            <p class="font-medium text-surface-800 break-all">{{ $order->tracking_number }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-3 opacity-0">
                <h3 class="text-base font-bold text-surface-900 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-surface-50 rounded-xl overflow-hidden shrink-0">
                                @php $img = $item->product->featuredImage ?? null; @endphp
                                <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-surface-800 truncate">{{ $item->product_name }}</p>
                                <p class="text-xs text-surface-400">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-bold text-surface-900 shrink-0">₹{{ number_format($item->total_price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
