@extends('layouts.app')

@section('title', 'Track Your Order - Decathlon')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16">
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-[#0082C3]/10 text-[#0082C3] rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="package" class="w-8 h-8"></i>
        </div>
        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight mb-2">Track Your Order</h1>
        <p class="text-gray-500 font-medium">Enter your order number and email to check your order status.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form id="track-form" class="space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Order Number</label>
                <input type="text" name="order_number" required placeholder="ORD-2026-00001"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm font-bold">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="you@example.com"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm font-bold">
            </div>
            <button type="submit" id="track-btn" class="w-full bg-[#183a9e] hover:bg-[#0c246b] text-white py-3 rounded-xl text-sm font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                <span class="btn-text">Track Order</span>
                <span class="loading-spinner hidden"><i data-lucide="loader" class="animate-spin h-4 w-4 text-white"></i></span>
            </button>
        </form>

        {{-- Result Area --}}
        <div id="track-result" class="hidden mt-8 pt-8 border-t border-gray-100 space-y-6">
            <div id="track-error" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 text-sm font-bold text-red-700"></div>

            <div id="track-success" class="hidden space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Order Number</p>
                        <p id="track-order-number" class="text-lg font-black text-gray-900"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Date</p>
                        <p id="track-date" class="text-sm font-bold text-gray-900"></p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div id="track-status-badge" class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest"></div>
                    <div id="track-payment-badge" class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest"></div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Items</p>
                    <div id="track-items" class="space-y-2"></div>
                </div>

                <div class="flex justify-between pt-4 border-t border-gray-100">
                    <span class="text-lg font-black text-gray-900 uppercase">Total</span>
                    <span id="track-total" class="text-xl font-black text-[#0082C3]"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-8">
        <a href="{{ route('home') }}" class="text-sm font-bold text-[#0082C3] hover:underline">Back to Home</a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('track-form');
    const result = document.getElementById('track-result');
    const errorDiv = document.getElementById('track-error');
    const successDiv = document.getElementById('track-success');
    const btn = document.getElementById('track-btn');
    const btnText = btn.querySelector('.btn-text');
    const btnSpinner = btn.querySelector('.loading-spinner');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        btn.disabled = true;
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');
        result.classList.add('hidden');
        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');

        const formData = new FormData(form);

        fetch('{{ route("order.track.lookup") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(r => r.json())
        .then(data => {
            result.classList.remove('hidden');
            if (data.success) {
                const o = data.order;
                document.getElementById('track-order-number').textContent = o.order_number;
                document.getElementById('track-date').textContent = o.created_at;
                document.getElementById('track-total').textContent = '₹' + o.total_amount;

                const statusBadge = document.getElementById('track-status-badge');
                statusBadge.textContent = o.status;
                statusBadge.className = 'px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest ' +
                    ({ pending: 'bg-yellow-100 text-yellow-800', processing: 'bg-blue-100 text-blue-800', shipped: 'bg-purple-100 text-purple-800', delivered: 'bg-green-100 text-green-800', cancelled: 'bg-red-100 text-red-800' }[o.status] || 'bg-gray-100 text-gray-800');

                const paymentBadge = document.getElementById('track-payment-badge');
                paymentBadge.textContent = o.payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment';
                paymentBadge.className = 'px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest ' +
                    (o.payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600');

                const itemsDiv = document.getElementById('track-items');
                itemsDiv.innerHTML = o.items.map(i =>
                    '<div class="flex justify-between items-center text-sm"><span class="font-bold text-gray-700">' + i.quantity + 'x ' + i.product_name + '</span><span class="font-black text-gray-900">₹' + i.total_price + '</span></div>'
                ).join('');

                successDiv.classList.remove('hidden');
            } else {
                errorDiv.textContent = data.message || 'Order not found.';
                errorDiv.classList.remove('hidden');
            }
        })
        .catch(() => {
            result.classList.remove('hidden');
            errorDiv.textContent = 'Something went wrong. Please try again.';
            errorDiv.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btnText.classList.remove('hidden');
            btnSpinner.classList.add('hidden');
        });
    });
});
</script>
@endpush
@endsection
