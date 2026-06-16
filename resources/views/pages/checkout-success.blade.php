@extends('layouts.app')

@section('title', 'Order Placed - Decathlon')

@section('content')
<div class="w-full px-5 sm:px-10 lg:px-20 py-16">
    <div class="max-w-3xl mx-auto text-center">
    <div class="mb-8 flex justify-center">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
            <i data-lucide="circle-check" class="w-12 h-12"></i>
        </div>
    </div>

    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight mb-2">Order Successfully Placed!</h1>
    <p class="text-gray-500 mb-8 font-medium">Thank you for shopping with Decathlon. Your order is being processed.</p>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm mb-8 text-left">
        <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-100">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Order Number</p>
                <p class="text-lg font-black text-gray-900">{{ $order->order_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Date</p>
                <p class="text-sm font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="space-y-4 mb-8">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Order Details</h3>
            @foreach($order->items as $item)
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 bg-gray-100 rounded text-[10px] font-bold flex items-center justify-center">{{ $item->quantity }}x</span>
                        <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">{{ $item->product_name }}</span>
                    </div>
                    <span class="text-sm font-black text-gray-900">₹{{ number_format($item->total_price, 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="pt-6 border-t border-gray-100 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 font-medium">Payment Method</span>
                <span class="text-gray-900 font-bold uppercase tracking-widest text-xs">{{ strtoupper($order->payment_method) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 font-medium">Shipping</span>
                <span class="text-green-600 font-bold uppercase tracking-widest text-xs">Free</span>
            </div>
            <div class="flex justify-between pt-4">
                <span class="text-lg font-black text-gray-900 uppercase tracking-tight">Total Amount</span>
                <span class="text-xl font-black text-[#0082C3]">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Guest: Account Creation CTA --}}
    @if($isGuest)
    <div class="bg-[#f0f5ff] border border-[#dce6fa] rounded-2xl p-8 mb-8 text-left">
        <div class="flex items-start gap-4">
            <div class="bg-[#1c4bbf] text-white p-3 rounded-full flex-shrink-0">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-black text-gray-900 uppercase tracking-wide mb-1">Create an account for faster checkout</h3>
                <p class="text-sm text-gray-500 mb-4">Track your orders, save addresses, and get exclusive rewards.</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('register') }}" class="bg-[#183a9e] hover:bg-[#0c246b] text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Create Account
                    </a>
                    <a href="{{ route('order.track') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                        Track Your Order
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('home') }}" class="bg-[#0082C3] hover:bg-[#006699] text-white px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest shadow-lg transition-all">
            Continue Shopping
        </a>
        <a href="{{ route('order.track') }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest transition-all">
            Track My Order
        </a>
    </div>
    </div>
    </div>
@endsection
