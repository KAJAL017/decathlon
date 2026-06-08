<h2 class="text-base font-black text-gray-950 uppercase tracking-wider pb-3 border-b border-gray-100">
    Order Summary
</h2>

<div class="space-y-3">
    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase tracking-wide">
        <span>Items Subtotal</span>
        <span>₹{{ number_format($cart->total_amount, 2) }}</span>
    </div>
    <div class="flex justify-between items-center text-xs font-bold text-emerald-600 uppercase tracking-wide">
        <span>Discounts</span>
        <span>- ₹0.00</span>
    </div>
    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase tracking-wide">
        <span>Shipping</span>
        <span class="text-emerald-600">FREE</span>
    </div>
</div>

<div class="pt-4 border-t border-gray-100 flex items-center justify-between">
    <span class="text-sm font-black text-gray-950 uppercase tracking-wide">Total (Incl. Taxes)</span>
    <span class="text-xl font-black text-[#1c4bbf]">₹{{ number_format($cart->total_amount, 2) }}</span>
</div>

<div class="pt-3">
    @if($cart->items->count() > 0)
    <a href="{{ route('checkout') }}" class="block w-full bg-[#183a9e] hover:bg-[#0c246b] text-white text-center font-black text-xs py-4 rounded-xl shadow-lg transition-all uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98]">
        Proceed to Checkout
    </a>
    @else
    <button disabled class="w-full bg-gray-200 text-gray-400 font-black text-xs py-4 rounded-xl cursor-not-allowed uppercase tracking-widest">
        Proceed to Checkout
    </button>
    @endif
</div>
