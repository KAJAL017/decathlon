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
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
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
            

            <div id="cart-summary-container" class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-5 relative">
                @include('partials.cart-summary', ['cart' => $cart])
            </div>

            <!-- Help / Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-gray-400"></i>
                    <span class="text-[11px] font-bold text-gray-700 uppercase tracking-wider">Need help? 1800 123 4567</span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-gray-400"></i>
                    <span class="text-[11px] font-bold text-gray-700 uppercase tracking-wider">Secure Payment & Trust</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
