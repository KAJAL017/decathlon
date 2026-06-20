@extends('customer.layouts.mobile')

@section('title', 'Invoice #' . $order->order_number)
@section('page-title', 'Invoice')

@section('mobile-back')
    <a href="{{ route('customer.orders.show', $order->order_number) }}" class="p-2 -ml-2 text-surface-500 active:text-surface-800 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
@endsection

@section('content')
<div class="space-y-3">

    {{-- Print Button --}}
    <div class="flex justify-end">
        <button onclick="window.print()" class="px-3 py-2 text-[11px] font-medium text-brand-600 border border-brand-200 rounded-lg active:bg-brand-50 transition-colors flex items-center gap-1.5">
            <i data-lucide="printer" class="w-3.5 h-3.5"></i> Print
        </button>
    </div>

    {{-- Invoice Card --}}
    <div class="bg-white rounded-xl p-4 border border-surface-100" id="invoiceContent">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-surface-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-5"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-surface-900">DECATHLON</p>
                    <p class="text-[9px] text-surface-400">Sports Equipment & Sportswear</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-base font-bold text-surface-900">INVOICE</p>
                <p class="text-[10px] text-surface-500">#{{ $order->invoice->invoice_number ?? $order->order_number }}</p>
            </div>
        </div>

        {{-- Billing Info --}}
        <div class="grid grid-cols-1 gap-3 mb-4">
            <div>
                <p class="text-[9px] font-semibold text-surface-400 uppercase tracking-wider mb-1">Bill To</p>
                <p class="text-[11px] font-medium text-surface-800">{{ $order->shipping_name ?? $order->customer_name }}</p>
                <p class="text-[10px] text-surface-600">{{ $order->shipping_address ?? '' }}</p>
                <p class="text-[10px] text-surface-600">{{ $order->shipping_city ?? '' }}, {{ $order->shipping_state ?? '' }} {{ $order->shipping_pincode ?? '' }}</p>
                @if($order->customer_phone)
                    <p class="text-[10px] text-surface-400 mt-0.5">{{ $order->customer_phone }}</p>
                @endif
            </div>
            <div>
                <p class="text-[9px] font-semibold text-surface-400 uppercase tracking-wider mb-1">Order Info</p>
                <p class="text-[10px] text-surface-600">Order: <span class="font-medium text-surface-800">#{{ $order->order_number }}</span></p>
                <p class="text-[10px] text-surface-600">Date: <span class="font-medium text-surface-800">{{ $order->created_at->format('d M Y') }}</span></p>
                <p class="text-[10px] text-surface-600">Payment: <span class="font-medium text-surface-800">{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span></p>
                <p class="text-[10px] text-surface-600">Status: <span class="font-medium {{ ($order->payment_status ?? '') === 'paid' ? 'text-green-600' : 'text-amber-600' }}">{{ ucfirst($order->payment_status ?? 'N/A') }}</span></p>
            </div>
        </div>

        {{-- Items Table (Horizontal Scroll) --}}
        <div class="mb-4 overflow-x-auto -mx-4 px-4">
            <table class="w-full text-[11px] min-w-[380px]">
                <thead>
                    <tr class="border-b border-surface-200">
                        <th class="text-left py-2 text-[9px] font-semibold text-surface-400 uppercase tracking-wider">Item</th>
                        <th class="text-center py-2 text-[9px] font-semibold text-surface-400 uppercase tracking-wider">Qty</th>
                        <th class="text-right py-2 text-[9px] font-semibold text-surface-400 uppercase tracking-wider">Price</th>
                        <th class="text-right py-2 text-[9px] font-semibold text-surface-400 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-50">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-2">
                                <p class="font-medium text-surface-800">{{ $item->product_name }}</p>
                                @if($item->variant_name)
                                    <p class="text-[10px] text-surface-400">{{ $item->variant_name }}</p>
                                @endif
                            </td>
                            <td class="py-2 text-center text-surface-600">{{ $item->quantity }}</td>
                            <td class="py-2 text-right text-surface-600">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-2 text-right font-medium text-surface-800">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="border-t border-surface-200 pt-3 ml-auto w-full">
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
                <div class="flex justify-between font-bold text-surface-900 text-xs pt-1.5 border-t border-surface-200">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-4 pt-3 border-t border-surface-100 text-center">
            <p class="text-[10px] text-surface-400">Thank you for shopping with Decathlon!</p>
            <p class="text-[9px] text-surface-300 mt-0.5">For queries, contact support@decathlon.com</p>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #invoiceContent, #invoiceContent * { visibility: visible; }
        #invoiceContent { position: absolute; left: 0; top: 0; width: 100%; padding: 16px; }
    }
</style>
@endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
