@extends('customer.layouts.mobile')

@section('title', 'Payment Methods')
@section('page-title', 'Payments')

@section('content')
<div class="space-y-3">

    <button onclick="showPaymentModal()" class="w-full btn-primary flex items-center justify-center gap-2 text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Payment Method
    </button>

    @if($paymentMethods->isEmpty())
        <div class="bg-white rounded-2xl p-8 border border-surface-100 text-center animate-fade-in">
            <div class="w-14 h-14 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="credit-card" class="w-7 h-7 text-surface-300"></i>
            </div>
            <h3 class="text-base font-bold text-surface-900 mb-1">No payment methods saved</h3>
            <p class="text-xs text-surface-400 mb-5">Add a payment method for faster checkout.</p>
            <button onclick="showPaymentModal()" class="btn-primary inline-flex items-center gap-2 text-white px-6 py-3 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Payment Method
            </button>
        </div>
    @else
        <div class="space-y-2.5" id="paymentList">
            @foreach($paymentMethods as $index => $method)
                <div class="bg-white rounded-2xl p-4 border {{ $method->is_default ? 'border-brand-300 ring-2 ring-brand-500/10' : 'border-surface-100' }} active:scale-[0.98] transition-transform animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}" id="payment-{{ $method->id }}">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-surface-50 flex items-center justify-center shrink-0">
                            @if($method->type === 'card')
                                <i data-lucide="credit-card" class="w-5 h-5 text-surface-600"></i>
                            @elseif($method->type === 'upi')
                                <i data-lucide="smartphone" class="w-5 h-5 text-surface-600"></i>
                            @else
                                <i data-lucide="wallet" class="w-5 h-5 text-surface-600"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-surface-900 truncate">{{ $method->display_name }}</p>
                            @if($method->cardholder_name)
                                <p class="text-[11px] text-surface-400">{{ $method->cardholder_name }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($method->is_default)
                                <span class="px-2 py-0.5 bg-brand-50 text-brand-700 rounded-md text-[10px] font-semibold border border-brand-200 flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Default
                                </span>
                            @else
                                <form action="{{ route('customer.payments.default', $method->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-medium text-brand-600 active:text-brand-700 px-2 py-1 active:bg-brand-50 rounded-lg transition-colors">Set Default</button>
                                </form>
                            @endif
                            <button onclick="deletePayment({{ $method->id }})" class="p-2 text-surface-400 active:text-red-500 rounded-lg active:bg-red-50 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Payment Bottom Sheet --}}
<div id="paymentModal" class="fixed inset-0 bg-black/50 z-[9998] hidden items-end justify-center p-0" onclick="if(event.target===this)hidePaymentModal()">
    <div class="bg-white rounded-t-2xl w-full max-h-[85vh] overflow-y-auto animate-slide-up-full shadow-2xl">
        <div class="sticky top-0 bg-white z-10 px-5 pt-4 pb-3 border-b border-surface-100">
            <div class="w-10 h-1 bg-surface-200 rounded-full mx-auto mb-3"></div>
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-surface-900">Add Payment Method</h3>
                <button onclick="hidePaymentModal()" class="p-2 text-surface-400 active:text-surface-600 rounded-lg active:bg-surface-100 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('customer.payments.store') }}" method="POST" class="px-5 py-4">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Type</label>
                    <select name="type" required onchange="togglePaymentFields(this.value)" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        <option value="card">Card</option>
                        <option value="upi">UPI</option>
                        <option value="wallet">Wallet</option>
                    </select>
                </div>
                <div id="cardFields">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Provider</label>
                            <input type="text" name="provider" placeholder="Visa, Mastercard" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Last 4 digits</label>
                            <input type="text" name="last_four" maxlength="4" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Cardholder Name</label>
                        <input type="text" name="cardholder_name" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>
                <div id="upiFields" class="hidden">
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">UPI ID</label>
                    <input type="text" name="upi_id" placeholder="username@upi" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
                <div id="walletFields" class="hidden">
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Wallet Name</label>
                    <input type="text" name="wallet_name" placeholder="Paytm, PhonePe" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer py-1">
                    <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-brand-600 border-surface-300 rounded focus:ring-brand-500">
                    <span class="text-xs text-surface-600">Set as default</span>
                </label>
            </div>
            <div class="flex gap-3 mt-5 pb-6">
                <button type="button" onclick="hidePaymentModal()" class="flex-1 py-3.5 border border-surface-200 rounded-xl text-sm font-medium active:bg-surface-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 btn-primary text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">Save</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('paymentModal').classList.add('flex');
}
function hidePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentModal').classList.remove('flex');
}
function togglePaymentFields(type) {
    document.getElementById('cardFields').classList.toggle('hidden', type !== 'card');
    document.getElementById('upiFields').classList.toggle('hidden', type !== 'upi');
    document.getElementById('walletFields').classList.toggle('hidden', type !== 'wallet');
}
async function deletePayment(id) {
    confirmAction('Remove this payment method?', async () => {
        const res = await ajax(`{{ url('account/payments') }}/${id}`, { method: 'DELETE' });
        const data = await res.json();
        if (data.success) {
            const el = document.getElementById(`payment-${id}`);
            el.style.transition = 'all 0.3s ease';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.95) translateY(-8px)';
            setTimeout(() => el.remove(), 300);
            showToast(data.message);
        }
    });
}
</script>
@endpush
@endsection
