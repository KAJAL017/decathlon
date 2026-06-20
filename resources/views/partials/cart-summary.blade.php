<h2 class="text-sm font-black text-gray-950 uppercase tracking-wider pb-4 border-b border-gray-100">
    Order Summary
</h2>

<div class="space-y-3">
    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase tracking-wide">
        <span>Subtotal ({{ $cart->total_quantity }} {{ Str::plural('item', $cart->total_quantity) }})</span>
        <span class="text-gray-900">₹{{ number_format($cart->total_amount, 2) }}</span>
    </div>
    <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wide">
        <span class="text-gray-500">Shipping</span>
        <span class="text-emerald-600">FREE</span>
    </div>
    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase tracking-wide">
        <span>Tax (Included)</span>
        <span class="text-gray-400">—</span>
    </div>
</div>

<div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
    <span class="text-sm font-black text-gray-950 uppercase tracking-wide">Total</span>
    <span class="text-xl font-black text-[#1c4bbf]">₹{{ number_format($cart->total_amount, 2) }}</span>
</div>

<div class="mt-5 space-y-3">
    @if($cart->items->count() > 0)
    <a href="{{ route('checkout') }}" 
       class="block w-full bg-[#183a9e] hover:bg-[#0c246b] text-white text-center font-black text-xs py-4 rounded-xl shadow-lg transition-all uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98]">
        Proceed to Checkout
    </a>
    @else
    <button disabled 
            class="w-full bg-gray-200 text-gray-400 font-black text-xs py-4 rounded-xl cursor-not-allowed uppercase tracking-widest">
        Proceed to Checkout
    </button>
    @endif

    <a href="{{ route('shop') }}" 
       class="block w-full text-center text-[#0082C3] font-bold text-xs py-3 rounded-xl hover:bg-[#0082C3]/5 transition-colors uppercase tracking-wider">
        Continue Shopping
    </a>
</div>
