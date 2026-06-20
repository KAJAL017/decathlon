@extends('layouts.app')

@section('title', 'Cart - Decathlon')

@section('content')
<div class="w-full bg-gray-50/50 py-6 md:py-10 px-4 lg:px-6">

    {{-- Page Header --}}
    <div class="max-w-7xl mx-auto mb-6 md:mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-950 tracking-tight">Shopping Cart</h1>
                <p class="text-sm text-gray-500 font-medium mt-1" id="cart-item-count">
                    {{ $cart->total_quantity }} {{ Str::plural('item', $cart->total_quantity) }} in your cart
                </p>
            </div>
            @if($cart->items->count() > 0)
            <button onclick="Cart.clear()" 
                    class="hidden md:flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 hover:border-red-300 text-gray-500 hover:text-red-500 text-xs font-bold uppercase tracking-wider transition-all bg-white shadow-sm hover:shadow">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                Clear All
            </button>
            @endif
        </div>
    </div>

    {{-- Grid Container --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">
        
        {{-- Left Side: Cart Items --}}
        <div class="lg:col-span-2 space-y-4">
            <div id="cart-items-container" class="space-y-4 relative min-h-[200px]">
                @include('partials.cart-items', ['cart' => $cart])
            </div>

            {{-- Mobile Clear Button --}}
            @if($cart->items->count() > 0)
            <div class="md:hidden pt-2">
                <button onclick="Cart.clear()" 
                        class="flex items-center gap-2 text-red-500 text-xs font-bold uppercase tracking-wider">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    Clear All Items
                </button>
            </div>
            @endif
        </div>

        {{-- Right Side: Order Summary --}}
        <div class="lg:sticky lg:top-24 space-y-5">
            
            {{-- Summary Card --}}
            <div id="cart-summary-container" class="bg-white rounded-2xl border border-gray-200/80 p-5 md:p-6 shadow-sm">
                @include('partials.cart-summary', ['cart' => $cart])
            </div>

            {{-- Delivery Estimate --}}
            @if($cart->items->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center">
                        <i data-lucide="truck" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <span class="text-xs font-black text-gray-900 uppercase tracking-wider">Delivery Estimate</span>
                </div>
                <div class="space-y-2 ml-11">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium">Standard:</span>
                        <span class="text-xs font-bold text-gray-900">5-7 business days</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium">Express:</span>
                        <span class="text-xs font-bold text-gray-900">2-3 business days</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Trust Badges --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm">
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                        </div>
                        <span class="text-[11px] font-bold text-gray-700 leading-tight">Secure<br>Payment</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="refresh-cw" class="w-4 h-4 text-emerald-600"></i>
                        </div>
                        <span class="text-[11px] font-bold text-gray-700 leading-tight">Easy<br>Returns</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                        </div>
                        <span class="text-[11px] font-bold text-gray-700 leading-tight">Quality<br>Assured</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="headphones" class="w-4 h-4 text-emerald-600"></i>
                        </div>
                        <span class="text-[11px] font-bold text-gray-700 leading-tight">24/7<br>Support</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
    @keyframes cartSpin { to { transform: rotate(360deg); } }
    .cart-btn-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: cartSpin 0.6s linear infinite;
        vertical-align: middle;
        margin-right: 6px;
    }
    .cart-bounce {
        animation: cartBounce 0.4s ease;
    }
    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    @keyframes summaryShimmer {
        0% { background-position: -468px 0; }
        100% { background-position: 468px 0; }
    }
    #cart-summary-container { position: relative; }
    .summary-overlay {
        position: absolute;
        inset: 0;
        z-index: 10;
        border-radius: inherit;
        padding: inherit;
        opacity: 0;
        transition: opacity 150ms ease-in-out;
        pointer-events: none;
    }
    .summary-overlay.active {
        pointer-events: auto;
    }
    .summary-skel-bar {
        height: 12px;
        border-radius: 6px;
        background: linear-gradient(to right, #e5e7eb 8%, #f3f4f6 18%, #e5e7eb 33%);
        background-size: 800px 104px;
        animation: summaryShimmer 1.5s ease-in-out infinite;
    }
    .summary-skel-bar-lg {
        height: 16px;
        border-radius: 8px;
        background: linear-gradient(to right, #e5e7eb 8%, #f3f4f6 18%, #e5e7eb 33%);
        background-size: 800px 104px;
        animation: summaryShimmer 1.5s ease-in-out infinite;
    }
    .summary-skel-btn {
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(to right, #e5e7eb 8%, #f3f4f6 18%, #e5e7eb 33%);
        background-size: 800px 104px;
        animation: summaryShimmer 1.5s ease-in-out infinite;
    }
</style>
@endsection
