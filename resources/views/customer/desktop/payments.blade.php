@extends('customer.layouts.desktop')

@section('title', 'Payment Methods')
@section('page-title', 'Payments')

@section('content')
<div class="max-w-4xl">

    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-sm text-surface-400 mt-1">Manage your saved payment methods for quick checkout</p>
        </div>
        <button onclick="showPaymentModal()" class="btn-primary inline-flex items-center gap-2 text-white px-5 py-3 rounded-xl text-sm font-semibold">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Method
        </button>
    </div>

    @if($paymentMethods->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="credit-card" class="w-10 h-10 text-surface-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">No payment methods saved</h3>
            <p class="text-sm text-surface-400 mb-8 max-w-sm mx-auto">Add a payment method to enjoy faster and more convenient checkout.</p>
            <button onclick="showPaymentModal()" class="btn-primary inline-flex items-center gap-2 text-white px-8 py-3.5 rounded-xl text-sm font-semibold">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Payment Method
            </button>
        </div>
    @else
        <div class="grid grid-cols-2 gap-4" id="paymentList">
            @foreach($paymentMethods as $index => $method)
                <div class="bg-white rounded-2xl p-5 border {{ $method->is_default ? 'border-brand-300 ring-2 ring-brand-500/10' : 'border-surface-100' }} card-hover animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}" id="payment-{{ $method->id }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-xl bg-surface-50 flex items-center justify-center">
                            @if($method->type === 'card')
                                <i data-lucide="credit-card" class="w-7 h-7 text-surface-600"></i>
                            @elseif($method->type === 'upi')
                                <i data-lucide="smartphone" class="w-7 h-7 text-surface-600"></i>
                            @else
                                <i data-lucide="wallet" class="w-7 h-7 text-surface-600"></i>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($method->is_default)
                                <span class="px-2.5 py-1 bg-brand-50 text-brand-700 rounded-lg text-[11px] font-semibold border border-brand-200 flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Default
                                </span>
                            @else
                                <form action="{{ route('customer.payments.default', $method->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-medium text-brand-600 hover:text-brand-700 flex items-center gap-1">
                                        <i data-lucide="star" class="w-3 h-3"></i> Set Default
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <p class="text-base font-semibold text-surface-900">{{ $method->display_name }}</p>
                        @if($method->cardholder_name)
                            <p class="text-sm text-surface-400 mt-0.5">{{ $method->cardholder_name }}</p>
                        @endif
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="px-2 py-0.5 bg-surface-100 rounded text-[10px] font-semibold text-surface-500 uppercase">{{ $method->type }}</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-surface-100 flex justify-end">
                        <button onclick="deletePayment({{ $method->id }})" class="text-sm font-medium text-red-500 hover:text-red-600 flex items-center gap-1.5">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="paymentModal" class="fixed inset-0 bg-black/50 z-[9998] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md animate-scale-in shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-surface-900">Add Payment Method</h3>
            <button onclick="hidePaymentModal()" class="p-2 text-surface-400 hover:text-surface-600 rounded-lg hover:bg-surface-50 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('customer.payments.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Type</label>
                    <select name="type" required onchange="togglePaymentFields(this.value)" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        <option value="card">Card</option>
                        <option value="upi">UPI</option>
                        <option value="wallet">Wallet</option>
                    </select>
                </div>
                <div id="cardFields">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Provider</label>
                            <input type="text" name="provider" placeholder="Visa, Mastercard" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Last 4 digits</label>
                            <input type="text" name="last_four" maxlength="4" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Cardholder Name</label>
                        <input type="text" name="cardholder_name" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                </div>
                <div id="upiFields" class="hidden">
                    <label class="block text-xs font-medium text-surface-500 mb-1.5">UPI ID</label>
                    <input type="text" name="upi_id" placeholder="username@upi" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div id="walletFields" class="hidden">
                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Wallet Name</label>
                    <input type="text" name="wallet_name" placeholder="Paytm, PhonePe" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer py-2">
                    <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-brand-600 border-surface-300 rounded focus:ring-brand-500">
                    <span class="text-sm text-surface-600">Set as default</span>
                </label>
            </div>
            <div class="flex gap-3 mt-6 pt-6 border-t border-surface-100">
                <button type="button" onclick="hidePaymentModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-medium hover:bg-surface-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 btn-primary text-white py-3 rounded-xl text-sm font-semibold">Save</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

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
            el.style.transform = 'scale(0.95)';
            setTimeout(() => el.remove(), 300);
            showToast(data.message);
        }
    });
}
</script>
@endpush
@endsection