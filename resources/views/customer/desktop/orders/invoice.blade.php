@extends('customer.layouts.desktop')

@section('title', 'Invoice #' . $order->order_number)
@section('page-title', 'Invoice')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="inline-flex items-center gap-2 text-sm font-medium text-surface-500 hover:text-surface-800 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Order
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 text-sm font-medium text-brand-600 border border-brand-200 rounded-xl hover:bg-brand-50 transition-colors flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i> Print Invoice
        </button>
    </div>

    <div class="bg-white rounded-2xl p-10 border border-surface-100 max-w-4xl mx-auto" id="invoiceContent">
        {{-- Header --}}
        <div class="flex justify-between mb-10 pb-6 border-b border-surface-100">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-brand-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-5"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-surface-900">DECATHLON</p>
                        <p class="text-xs text-surface-400">Sports Equipment & Sportswear</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-surface-900 mb-1">INVOICE</p>
                <p class="text-sm text-surface-500">#{{ $order->invoice->invoice_number ?? $order->order_number }}</p>
                <p class="text-sm text-surface-400 mt-1">{{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Billing --}}
        <div class="grid grid-cols-2 gap-8 mb-10">
            <div>
                <p class="text-xs font-semibold text-surface-400 uppercase tracking-wider mb-3">Bill To</p>
                <p class="text-sm font-medium text-surface-800">{{ $order->shipping_name ?? $order->customer_name }}</p>
                <p class="text-sm text-surface-600 mt-1">{{ $order->shipping_address ?? '' }}</p>
                <p class="text-sm text-surface-600">{{ $order->shipping_city ?? '' }}, {{ $order->shipping_state ?? '' }} {{ $order->shipping_pincode ?? '' }}</p>
                @if($order->customer_phone)
                    <p class="text-sm text-surface-400 mt-1">{{ $order->customer_phone }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-surface-400 uppercase tracking-wider mb-3">Order Info</p>
                <p class="text-sm text-surface-600">Order: <span class="font-medium text-surface-800">#{{ $order->order_number }}</span></p>
                <p class="text-sm text-surface-600">Date: <span class="font-medium text-surface-800">{{ $order->created_at->format('d M Y') }}</span></p>
                <p class="text-sm text-surface-600">Payment: <span class="font-medium text-surface-800">{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span></p>
                <p class="text-sm text-surface-600">Status: <span class="font-medium {{ ($order->payment_status ?? '') === 'paid' ? 'text-green-600' : 'text-amber-600' }}">{{ ucfirst($order->payment_status ?? 'N/A') }}</span></p>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="mb-10">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-surface-200">
                        <th class="text-left py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Item</th>
                        <th class="text-center py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Qty</th>
                        <th class="text-right py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Price</th>
                        <th class="text-right py-4 text-xs font-semibold text-surface-400 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-4">
                                <p class="font-medium text-surface-800">{{ $item->product_name }}</p>
                                @if($item->variant_name)
                                    <p class="text-xs text-surface-400">{{ $item->variant_name }}</p>
                                @endif
                            </td>
                            <td class="py-4 text-center text-surface-600">{{ $item->quantity }}</td>
                            <td class="py-4 text-right text-surface-600">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-4 text-right font-medium text-surface-800">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="border-t-2 border-surface-200 pt-6 ml-auto w-72">
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
                <div class="flex justify-between text-surface-600">
                    <span>Shipping</span>
                    <span>{{ $order->shipping_amount > 0 ? '₹' . number_format($order->shipping_amount, 2) : 'FREE' }}</span>
                </div>
                @if($order->tax_amount > 0)
                    <div class="flex justify-between text-surface-600">
                        <span>Tax</span>
                        <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-surface-900 text-lg pt-3 border-t border-surface-200">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-10 pt-6 border-t border-surface-100 text-center">
            <p class="text-sm text-surface-400">Thank you for shopping with Decathlon!</p>
            <p class="text-xs text-surface-300 mt-1">For queries, contact support@decathlon.com</p>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #invoiceContent, #invoiceContent * { visibility: visible; }
        #invoiceContent { position: absolute; left: 0; top: 0; width: 100%; padding: 20px; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
