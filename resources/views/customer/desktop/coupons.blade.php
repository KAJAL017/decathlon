@extends('customer.layouts.desktop')

@section('title', 'My Coupons')
@section('page-title', 'Coupons')

@section('content')
<div class="max-w-5xl">

    <div class="mb-8">
        <p class="text-sm text-surface-400 mt-1">Grab exclusive coupons and save on your next order</p>
    </div>

    @if($availableCoupons->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="ticket" class="w-10 h-10 text-amber-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">No coupons available</h3>
            <p class="text-sm text-surface-400 max-w-sm mx-auto">Check back later for exclusive offers and discounts!</p>
        </div>
    @else
        <div class="grid grid-cols-2 gap-5">
            @foreach($availableCoupons as $index => $coupon)
                <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden card-hover animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">
                    <div class="relative">
                        <div class="bg-gradient-to-r from-brand-500 to-emerald-500 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold">
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
                                    <p class="text-sm text-white/80 mt-1 line-clamp-1">{{ $coupon->description ?? $coupon->name }}</p>
                                </div>
                                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 ml-4">
                                    <i data-lucide="tag" class="w-8 h-8 text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center -mt-3">
                            <div class="w-6 h-6 bg-surface-50 rounded-full border-r border-surface-200"></div>
                            <div class="flex-1 border-b-2 border-dashed border-surface-200"></div>
                            <div class="w-6 h-6 bg-surface-50 rounded-full border-l border-surface-200"></div>
                        </div>
                    </div>

                    <div class="p-5 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-surface-50 border border-dashed border-surface-300 rounded-lg px-4 py-2">
                                <span class="text-base font-mono font-bold text-surface-800 tracking-wider">{{ $coupon->code }}</span>
                            </div>
                            <button onclick="copyCoupon('{{ $coupon->code }}', this)" class="text-sm font-medium text-brand-600 hover:text-brand-700 flex items-center gap-1.5 transition-colors">
                                <i data-lucide="copy" class="w-4 h-4"></i> Copy
                            </button>
                        </div>

                        <div class="space-y-1.5 text-xs text-surface-500">
                            @if($coupon->minimum_order_amount)
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="shopping-cart" class="w-3 h-3 text-surface-400"></i>
                                    Min. order: ₹{{ number_format($coupon->minimum_order_amount, 0) }}
                                </p>
                            @endif
                            @if($coupon->maximum_discount_amount)
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="percent" class="w-3 h-3 text-surface-400"></i>
                                    Max discount: ₹{{ number_format($coupon->maximum_discount_amount, 0) }}
                                </p>
                            @endif
                            @if($coupon->usage_limit)
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="users" class="w-3 h-3 text-surface-400"></i>
                                    {{ $coupon->usage_limit - ($coupon->usage_count ?? 0) }} uses remaining
                                </p>
                            @endif
                            @if($coupon->expires_at)
                                <p class="flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3 h-3 text-surface-400"></i>
                                    Expires: {{ $coupon->expires_at->format('d M Y') }}
                                </p>
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
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

function copyCoupon(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Copied!';
        btn.classList.add('text-green-600');
        lucide.createIcons({ nodes: [btn] });
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('text-green-600');
            lucide.createIcons({ nodes: [btn] });
        }, 2000);
        showToast('Coupon code copied!');
    });
}
</script>
@endpush
@endsection