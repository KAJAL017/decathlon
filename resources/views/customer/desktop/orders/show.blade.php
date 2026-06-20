@extends('customer.layouts.desktop')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="space-y-6">

    {{-- Back --}}
    <a href="{{ route('customer.orders') }}" class="inline-flex items-center gap-2 text-sm font-medium text-surface-500 hover:text-surface-800 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Orders
    </a>

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

    <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1.5 rounded-xl text-sm font-semibold border {{ $sc }}">{{ ucfirst($order->status) }}</span>
                    @if($order->payment_status)
                        <span class="px-3 py-1.5 rounded-xl text-sm font-semibold {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-surface-400">Placed on {{ $order->created_at->format('d M Y, g:i A') }}</p>
            </div>
            <div class="flex gap-3">
                @if(in_array($order->status, ['pending', 'confirmed']))
                    <button onclick="showCancelModal()" class="px-4 py-2.5 text-sm font-medium text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition-colors">
                        Cancel Order
                    </button>
                @endif
                @if($order->status === 'delivered')
                    <a href="{{ route('customer.orders.track', $order->order_number) }}" class="px-4 py-2.5 text-sm font-medium text-brand-600 border border-brand-200 rounded-xl hover:bg-brand-50 transition-colors">
                        Return/Exchange
                    </a>
                @endif
                @if(in_array($order->status, ['shipped', 'processing']))
                    <a href="{{ route('customer.orders.track', $order->order_number) }}" class="btn-primary px-4 py-2.5 text-sm font-semibold text-white rounded-xl">
                        Track Order
                    </a>
                @endif
                <a href="{{ route('customer.orders.invoice', $order->order_number) }}" class="px-4 py-2.5 text-sm font-medium text-surface-600 border border-surface-200 rounded-xl hover:bg-surface-50 transition-colors inline-flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Invoice
                </a>
            </div>
        </div>
    </div>

    {{-- 3-Column Grid: Items (2 cols) + Summary Sidebar --}}
    <div class="grid grid-cols-3 gap-6">
        {{-- Order Items - spans 2 columns --}}
        <div class="col-span-2 space-y-4 animate-slide-up stagger-2 opacity-0">
            <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden">
                <div class="p-5 border-b border-surface-100">
                    <h3 class="text-base font-bold text-surface-900">Order Items</h3>
                </div>
                <div class="divide-y divide-surface-50">
                    @foreach($order->items as $item)
                        <div class="p-5 flex items-center gap-4">
                            <div class="w-16 h-16 bg-surface-50 rounded-2xl overflow-hidden shrink-0">
                                @php $img = $item->product->featuredImage ?? null; @endphp
                                <img src="{{ $img ? asset('uploads/' . $img->thumbnail_url) : asset('images/placeholder-product.svg') }}" class="w-full h-full object-cover" alt="">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-base font-semibold text-surface-900 truncate">{{ $item->product_name }}</p>
                                @if($item->variant_name)
                                    <p class="text-sm text-surface-400 mt-0.5">{{ $item->variant_name }}</p>
                                @endif
                                <p class="text-sm text-surface-400 mt-0.5">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</p>
                            </div>
                            <p class="text-base font-bold text-surface-900 shrink-0">₹{{ number_format($item->total_price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-2xl p-5 border border-surface-100 animate-slide-up stagger-3 opacity-0">
                <h3 class="text-base font-bold text-surface-900 mb-3">Shipping Address</h3>
                <div class="text-sm text-surface-600 space-y-1">
                    <p class="font-medium text-surface-800">{{ $order->shipping_name ?? $order->customer_name }}</p>
                    <p>{{ $order->shipping_address ?? '' }}</p>
                    <p>{{ $order->shipping_city ?? '' }}, {{ $order->shipping_state ?? '' }} {{ $order->shipping_pincode ?? '' }}</p>
                    @if($order->customer_phone)
                        <p class="text-surface-400">{{ $order->customer_phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Order Summary Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-3 opacity-0">
                <h3 class="text-base font-bold text-surface-900 mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm">
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
                    <div class="flex justify-between font-bold text-surface-900 text-lg pt-3 border-t border-surface-100">
                        <span>Total</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-4 opacity-0">
                <h3 class="text-base font-bold text-surface-900 mb-4">Payment</h3>
                <div class="text-sm space-y-3">
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
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" class="fixed inset-0 bg-black/50 z-[9998] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full animate-scale-in shadow-2xl">
        <h3 class="text-xl font-bold text-surface-900 mb-2">Cancel Order</h3>
        <p class="text-sm text-surface-500 mb-6">Are you sure you want to cancel this order? This action cannot be undone.</p>
        <form action="{{ route('customer.orders.cancel', $order->order_number) }}" method="POST" id="cancelForm">
            @csrf
            <textarea name="reason" rows="3" placeholder="Please provide a reason for cancellation..." required
                      class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 mb-6 resize-none"></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="hideCancelModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-medium hover:bg-surface-50 transition-colors">Keep Order</button>
                <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">Cancel Order</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
function showCancelModal() { document.getElementById('cancelModal').classList.remove('hidden'); document.getElementById('cancelModal').classList.add('flex'); }
function hideCancelModal() { document.getElementById('cancelModal').classList.add('hidden'); document.getElementById('cancelModal').classList.remove('flex'); }
</script>
@endpush
@endsection
