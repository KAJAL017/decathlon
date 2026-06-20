@extends('customer.layouts.mobile')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('mobile-back')
    <a href="{{ route('customer.orders') }}" class="p-2 -ml-2 text-surface-500 active:text-surface-800 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
@endsection

@section('content')
<div class="space-y-3">

    {{-- Order Header --}}
    @php
        $statusColors = [
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
            'processing' => 'bg-purple-50 text-purple-700 border-purple-200',
            'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'delivered' => 'bg-green-50 text-green-700 border-green-200',
            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
        ];
        $sc = $statusColors[$order->status] ?? 'bg-surface-50 text-surface-600 border-surface-200';
    @endphp

    <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $sc }}">{{ ucfirst($order->status) }}</span>
                @if($order->payment_status)
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                @endif
            </div>
        </div>
        <p class="text-[10px] text-surface-400">{{ $order->created_at->format('d M Y, g:i A') }}</p>

        {{-- Action Buttons Wrapping --}}
        <div class="flex flex-wrap gap-1.5 mt-3">
            @if(in_array($order->status, ['pending', 'confirmed']))
                <button onclick="showCancelSheet()" class="px-3 py-2 text-[11px] font-medium text-red-600 border border-red-200 rounded-lg active:bg-red-50 transition-colors">
                    Cancel
                </button>
            @endif
            @if(in_array($order->status, ['shipped', 'processing']))
                <a href="{{ route('customer.orders.track', $order->order_number) }}" class="btn-primary px-3 py-2 text-[11px] font-semibold text-white rounded-lg">
                    <i data-lucide="truck" class="w-3 h-3 inline"></i> Track
                </a>
            @endif
            <a href="{{ route('customer.orders.invoice', $order->order_number) }}" class="px-3 py-2 text-[11px] font-medium text-surface-600 border border-surface-200 rounded-lg active:bg-surface-50 transition-colors inline-flex items-center gap-1">
                <i data-lucide="download" class="w-3 h-3"></i> Invoice
            </a>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-xl border border-surface-100 overflow-hidden animate-slide-up stagger-2 opacity-0">
        <div class="p-3 border-b border-surface-100">
            <h3 class="text-xs font-bold text-surface-900">Order Items</h3>
        </div>
        <div class="divide-y divide-surface-50">
            @foreach($order->items as $item)
                <div class="p-3 flex items-center gap-2.5">
                    <div class="w-12 h-12 bg-surface-50 rounded-lg overflow-hidden shrink-0">
                        @php $img = $item->product->featuredImage ?? null; @endphp
                        <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-semibold text-surface-900 truncate">{{ $item->product_name }}</p>
                        @if($item->variant_name)
                            <p class="text-[10px] text-surface-400 mt-0.5">{{ $item->variant_name }}</p>
                        @endif
                        <p class="text-[10px] text-surface-400 mt-0.5">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 0) }}</p>
                    </div>
                    <p class="text-[11px] font-bold text-surface-900 shrink-0">₹{{ number_format($item->total_price, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up stagger-3 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-2">Order Summary</h3>
        <div class="space-y-1.5 text-[11px]">
            <div class="flex justify-between text-surface-600">
                <span>Subtotal</span>
                <span>₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Discount</span>
                    <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
            @endif
            @if($order->shipping_amount > 0)
                <div class="flex justify-between text-surface-600">
                    <span>Shipping</span>
                    <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                </div>
            @else
                <div class="flex justify-between text-green-600">
                    <span>Shipping</span>
                    <span>FREE</span>
                </div>
            @endif
            @if($order->tax_amount > 0)
                <div class="flex justify-between text-surface-600">
                    <span>Tax</span>
                    <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-surface-900 text-xs pt-1.5 border-t border-surface-100">
                <span>Total</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Payment Card --}}
    <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up stagger-4 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-2">Payment</h3>
        <div class="space-y-1.5 text-[11px]">
            <div class="flex justify-between">
                <span class="text-surface-500">Method</span>
                <span class="font-medium text-surface-800">{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-surface-500">Status</span>
                @if($order->payment_status)
                    <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                @else
                    <span class="font-medium text-surface-400">N/A</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Shipping Address --}}
    <div class="bg-white rounded-xl p-3 border border-surface-100 animate-slide-up stagger-4 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-1.5">Shipping Address</h3>
        <div class="text-[11px] text-surface-600 space-y-0.5">
            <p class="font-medium text-surface-800">{{ $order->shipping_name ?? $order->customer_name }}</p>
            <p>{{ $order->shipping_address ?? '' }}</p>
            <p>{{ $order->shipping_city ?? '' }}, {{ $order->shipping_state ?? '' }} {{ $order->shipping_pincode ?? '' }}</p>
            @if($order->customer_phone)
                <p class="text-surface-400">{{ $order->customer_phone }}</p>
            @endif
        </div>
    </div>
</div>

{{-- Cancel Bottom Sheet Modal --}}
<div id="cancelSheet" class="fixed inset-0 bg-black/50 z-[9998] hidden items-end justify-center p-0">
    <div class="bg-white rounded-t-2xl p-5 w-full animate-slide-up-full shadow-2xl pb-safe">
        <div class="w-10 h-1 bg-surface-200 rounded-full mx-auto mb-4"></div>
        <h3 class="text-base font-bold text-surface-900 mb-1">Cancel Order</h3>
        <p class="text-[11px] text-surface-500 mb-4">Are you sure? This action cannot be undone.</p>
        <form action="{{ route('customer.orders.cancel', $order->order_number) }}" method="POST" id="cancelForm">
            @csrf
            <textarea name="reason" rows="3" placeholder="Reason for cancellation..." required
                      class="w-full px-3 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-[13px] focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 mb-4 resize-none"></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="hideCancelSheet()" class="flex-1 py-3 border border-surface-200 rounded-xl text-xs font-medium active:bg-surface-50 transition-colors">Keep Order</button>
                <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-semibold active:bg-red-700 transition-colors">Cancel Order</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

function showCancelSheet() {
    const sheet = document.getElementById('cancelSheet');
    sheet.classList.remove('hidden');
    sheet.classList.add('flex');
}

function hideCancelSheet() {
    const sheet = document.getElementById('cancelSheet');
    sheet.classList.add('hidden');
    sheet.classList.remove('flex');
}

document.getElementById('cancelSheet').addEventListener('click', function(e) {
    if (e.target === this) hideCancelSheet();
});
</script>
@endpush
