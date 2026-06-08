@extends('layouts.app')

@section('title', 'Cart - Decathlon')

@section('content')
<div class="w-full bg-white py-8 px-4 lg:px-6 space-y-8">

    <!-- Grid Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Side: Cart Items (Col span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Header Row -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h1 class="text-2xl md:text-3xl font-black text-gray-950 tracking-tight">Cart Items</h1>
                
                <button onclick="Cart.clear()" class="w-9 h-9 rounded-full border border-gray-200 hover:border-gray-950 text-gray-600 hover:text-gray-950 flex items-center justify-center transition-colors shadow-sm bg-white" title="Clear All">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Delivery Location Address Bar -->
            <div class="bg-[#f0f5ff] rounded-2xl border border-[#dce6fa] p-4 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 text-white p-2.5 rounded-full shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm text-[#1c4bbf] font-bold text-center sm:text-left">
                        Delivery to <span class="text-gray-900 font-extrabold">Default Location</span>
                    </p>
                </div>
                @guest
                <a href="{{ route('login') }}" class="text-xs md:text-sm text-[#1c4bbf] hover:underline font-black uppercase tracking-wider whitespace-nowrap">
                    Login
                </a>
                @endguest
            </div>

            <!-- Cart Item List -->
            <div id="cart-items-container" class="space-y-4 relative min-h-[200px]">
                @include('partials.cart-items', ['cart' => $cart])
            </div>

            {{-- Skeleton Loader Template (Hidden by default) --}}
            <template id="cart-skeleton-template">
                <div class="animate-pulse bg-white rounded-2xl border border-gray-100 p-4 md:p-6 flex gap-4 md:gap-6">
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-xl bg-gray-100 flex-shrink-0"></div>
                    <div class="flex-grow flex flex-col justify-between py-1">
                        <div>
                            <div class="h-3 w-16 bg-gray-100 rounded mb-2"></div>
                            <div class="h-6 w-3/4 bg-gray-100 rounded mb-2"></div>
                            <div class="h-3 w-1/4 bg-gray-100 rounded"></div>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <div class="h-10 w-24 bg-gray-100 rounded-lg"></div>
                            <div class="h-10 w-24 bg-gray-100 rounded-lg ml-auto"></div>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        <!-- Right Side: Order Summary / Sidebar -->
        <div class="space-y-6">
            
            @guest
            <div class="bg-[#f3f2fc] rounded-2xl p-6 flex flex-col items-center text-center space-y-4 shadow-sm border border-[#e2e0f9]">
                <div class="bg-indigo-600/10 text-indigo-700 p-3.5 rounded-full">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="text-xs text-[#2c266a] font-bold leading-relaxed max-w-[240px]">
                    Oops... You're not logged in! Log in now to unlock more exclusive Rewards and Vouchers!
                </p>
                <a href="{{ route('login') }}" class="bg-white hover:bg-gray-50 text-gray-900 border border-gray-300 font-black text-xs px-8 py-2.5 rounded-full shadow-sm transition-all uppercase tracking-wider w-full text-center">
                    Login
                </a>
            </div>
            @endguest

            <div id="cart-summary-container" class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-5 relative">
                @include('partials.cart-summary', ['cart' => $cart])
            </div>

            <!-- Help / Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-[11px] font-bold text-gray-700 uppercase tracking-wider">Need help? 1800 123 4567</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    <span class="text-[11px] font-bold text-gray-700 uppercase tracking-wider">Secure Payment & Trust</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
