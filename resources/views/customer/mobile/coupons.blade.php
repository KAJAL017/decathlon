@extends('customer.layouts.mobile')

@section('title', 'My Coupons')
@section('page-title', 'Coupons')

@section('content')
<div class="space-y-3">

    @if($availableCoupons->isEmpty())
        <div class="bg-white rounded-2xl p-8 border border-surface-100 text-center animate-fade-in">
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="ticket" class="w-7 h-7 text-amber-300"></i>
            </div>
            <h3 class="text-base font-bold text-surface-900 mb-1">No coupons available</h3>
            <p class="text-xs text-surface-400">Check back later for exclusive offers!</p>
        </div>
    @else
        <div class="space-y-2.5">
            @foreach($availableCoupons as $index => $coupon)
                <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden active:scale-[0.98] transition-transform animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">
                    <div class="relative">
                        <div class="bg-gradient-to-r from-brand-500 to-emerald-500 p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="text-lg font-bold">
                                        @if($coupon->discount_type === 'percentage')
                                            {{ $coupon->discount_value }}% OFF
                                        @elseif($coupon->discount_type === 'fixed_amount')
                                            ₹{{ $coupon->discount_value }} OFF
                                        @elseif($coupon->discount_type === 'free_shipping')
                                            FREE SHIPPING
                                        @else
                                            {{ $coupon->name }}
                                        @endif
                                    </p>
                                    <p class="text-[11px] text-white/80 mt-0.5 line-clamp-1">{{ $coupon->description ?? $coupon->name }}</p>
                                </div>
                                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center shrink-0 ml-3">
                                    <i data-lucide="tag" class="w-5 h-5 text-white"></i>
                                </div>
                            </div>
                        </div>
                        {{-- Dashed divider --}}
                        <div class="flex items-center justify-center -mt-2.5">
                            <div class="w-5 h-5 bg-surface-50 rounded-full border-r border-surface-200"></div>
                            <div class="flex-1 border-b-2 border-dashed border-surface-200"></div>
                            <div class="w-5 h-5 bg-surface-50 rounded-full border-l border-surface-200"></div>
                        </div>
                    </div>

                    <div class="p-4 pt-3">
                        <div class="flex items-center justify-between mb-2">
                            <div class="bg-surface-50 border border-dashed border-surface-300 rounded-lg px-3 py-1.5">
                                <span class="text-xs font-mono font-bold text-surface-800 tracking-wider">{{ $coupon->code }}</span>
                            </div>
                            <button onclick="copyCoupon('{{ $coupon->code }}')" class="flex items-center gap-1 text-[11px] font-medium text-brand-600 active:text-brand-700 px-2 py-1 active:bg-brand-50 rounded-lg transition-colors">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i> Copy
                            </button>
                        </div>

                        <div class="space-y-0.5 text-[10px] text-surface-500">
                            @if($coupon->minimum_order_amount)
                                <p class="flex items-center gap-1"><i data-lucide="indian-rupee" class="w-3 h-3"></i> Min. order: ₹{{ number_format($coupon->minimum_order_amount, 0) }}</p>
                            @endif
                            @if($coupon->maximum_discount_amount)
                                <p class="flex items-center gap-1"><i data-lucide="badge-percent" class="w-3 h-3"></i> Max discount: ₹{{ number_format($coupon->maximum_discount_amount, 0) }}</p>
                            @endif
                            @if($coupon->usage_limit)
                                <p class="flex items-center gap-1"><i data-lucide="repeat" class="w-3 h-3"></i> {{ $coupon->usage_limit - ($coupon->usage_count ?? 0) }} uses remaining</p>
                            @endif
                            @if($coupon->expires_at)
                                <p class="flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3"></i> Expires: {{ $coupon->expires_at->format('d M Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
function copyCoupon(code) {
    navigator.clipboard.writeText(code).then(() => showToast('Coupon code copied!'));
}
</script>
@endpush
@endsection
