@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manage all orders manually</p>
    </div>
    <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3] transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Order
    </button>
</div>

{{-- Stats --}}
<div id="statsRow" class="grid grid-cols-2 md:grid-cols-5 gap-4">
    @foreach([
        ['id'=>'stat-total',     'label'=>'Total Orders',  'color'=>'#0369a1'],
        ['id'=>'stat-pending',   'label'=>'Pending',       'color'=>'#d97706'],
        ['id'=>'stat-processing','label'=>'Processing',    'color'=>'#4f46e5'],
        ['id'=>'stat-shipped',   'label'=>'Shipped',       'color'=>'#7c3aed'],
        ['id'=>'stat-delivered', 'label'=>'Delivered',     'color'=>'#047857'],
    ] as $s)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs font-semibold uppercase tracking-wider" style="color:{{ $s['color'] }}">{{ $s['label'] }}</p>
        <p id="{{ $s['id'] }}" class="text-2xl font-black text-gray-900 mt-1">—</p>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
    <div class="flex flex-wrap gap-3 items-center">
        <div class="flex-1 min-w-[200px] relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input type="text" id="searchInput" placeholder="Search order #, customer name, email..." onkeyup="debounceLoad()" class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <select id="statusFilter" onchange="loadOrders()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
            <option value="refunded">Refunded</option>
        </select>
        <select id="paymentFilter" onchange="loadOrders()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Payment</option>
            <option value="pending">Unpaid</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
        </select>
        <input type="date" id="dateFrom" onchange="loadOrders()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <input type="date" id="dateTo" onchange="loadOrders()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Payment</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody" class="divide-y divide-gray-50">
                <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">Loading orders...</td></tr>
            </tbody>
        </table>
    </div>
    {{-- Pagination --}}
    <div id="pagination" class="flex items-center justify-between px-4 py-3 border-t border-gray-100 hidden">
        <p id="paginationInfo" class="text-xs text-gray-500"></p>
        <div class="flex gap-2">
            <button id="prevBtn" onclick="changePage(-1)" class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">← Prev</button>
            <button id="nextBtn" onclick="changePage(1)" class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">Next →</button>
        </div>
    </div>
</div>

</div>{{-- end space-y-6 --}}

{{-- ══ CREATE ORDER MODAL ══ --}}
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900">Create New Order</h2>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="createOrderForm" onsubmit="submitOrder(event)" class="p-6 space-y-6">

            {{-- Customer Info --}}
            <div>
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#0082C3] text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                    Customer Information
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Name *</label>
                        <input type="text" name="customer_name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Customer name">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Email *</label>
                        <input type="email" name="customer_email" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                        <input type="text" name="customer_phone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="+91 XXXXX XXXXX">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="cod">Cash on Delivery</option>
                            <option value="razorpay">Razorpay</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div>
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#0082C3] text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                    Shipping Address
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Full Name *</label>
                        <input type="text" name="shipping_name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Address *</label>
                        <textarea name="shipping_address" required rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Street, Area, Landmark"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">City *</label>
                        <input type="text" name="shipping_city" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">State</label>
                        <input type="text" name="shipping_state" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Pincode</label>
                        <input type="text" name="shipping_pincode" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Shipping Method</label>
                        <select name="shipping_method" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="standard">Standard Delivery</option>
                            <option value="express">Express Delivery</option>
                            <option value="same_day">Same Day</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div>
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#0082C3] text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                    Order Items
                </h3>
                {{-- Product Search --}}
                <div class="relative mb-3">
                    <input type="text" id="productSearch" placeholder="Search product by name or SKU..." oninput="searchProducts(this.value)"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <div id="productDropdown" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-20 hidden max-h-48 overflow-y-auto"></div>
                </div>
                {{-- Items list --}}
                <div id="orderItemsList" class="space-y-2 mb-3">
                    <p class="text-xs text-gray-400 text-center py-4 border-2 border-dashed border-gray-200 rounded-lg" id="emptyItemsMsg">No items added yet. Search and add products above.</p>
                </div>
                {{-- Totals --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="calcSubtotal" class="font-semibold">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-gray-600">Shipping</span>
                        <input type="number" name="shipping_amount" id="shippingInput" value="0" min="0" step="0.01" onchange="recalcTotals()" class="w-24 text-right px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-gray-600">Discount</span>
                        <input type="number" name="discount_amount" id="discountInput" value="0" min="0" step="0.01" onchange="recalcTotals()" class="w-24 text-right px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-gray-600">Tax (GST)</span>
                        <input type="number" name="tax_amount" id="taxInput" value="0" min="0" step="0.01" onchange="recalcTotals()" class="w-24 text-right px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                    </div>
                    <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2 mt-2">
                        <span>Total</span>
                        <span id="calcTotal" class="text-[#0082C3]">₹0.00</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Customer Note</label>
                    <textarea name="customer_note" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Note from customer..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Admin Note</label>
                    <textarea name="admin_note" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Internal note..."></textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50">Cancel</button>
                <button type="submit" id="submitOrderBtn" class="flex-1 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3] transition-colors">
                    Create Order
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ ORDER DETAIL MODAL ══ --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDetailModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900" id="detailTitle">Order Details</h2>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="detailContent" class="p-6">
            <p class="text-gray-400 text-sm text-center py-8">Loading...</p>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="fixed bottom-5 right-5 z-50 hidden">
    <div id="toastInner" class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white min-w-[220px]">
        <span id="toastIcon"></span><span id="toastMsg"></span>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Inject courier modal directly into body (avoids stacking context issues) ──
(function() {
    // Add spin keyframe
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
    document.head.appendChild(style);

    const el = document.createElement('div');
    el.id = 'courierModal';
    el.style.cssText = 'position:fixed;inset:0;z-index:9999;display:none;';
    el.innerHTML = `
        <div style="position:absolute;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);" onclick="closeCourierModal()"></div>
        <div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:100%;max-width:580px;background:#fff;border-radius:16px;box-shadow:0 25px 50px rgba(0,0,0,0.25);overflow:hidden;display:flex;flex-direction:column;max-height:90vh;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f3f4f6;background:#fff;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#f97316;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:20px;height:20px;color:#fff;" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/></svg>
                    </div>
                    <div>
                        <h2 id="courierModalTitle" style="font-size:15px;font-weight:700;color:#111827;margin:0;">Select Courier</h2>
                        <p style="font-size:12px;color:#9ca3af;margin:0;">Choose the best courier for this shipment</p>
                    </div>
                </div>
                <button onclick="closeCourierModal()" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;color:#9ca3af;border:none;background:none;cursor:pointer;border-radius:8px;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="courierModalBody" style="flex:1;overflow-y:auto;">
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:64px 20px;gap:16px;">
                    <p style="font-size:14px;color:#6b7280;">Loading couriers...</p>
                </div>
            </div>
        </div>`;
    document.body.appendChild(el);
})();
const STATUS_COLORS = {
    pending:'yellow', confirmed:'blue', processing:'indigo',
    shipped:'purple', delivered:'green', cancelled:'red', refunded:'gray'
};
const PAY_COLORS = {
    pending:'yellow', paid:'green', failed:'red', refunded:'gray', partial_refund:'orange'
};

function pill(text, color) {
    const map = {
        yellow:'bg-yellow-100 text-yellow-700', blue:'bg-blue-100 text-blue-700',
        indigo:'bg-indigo-100 text-indigo-700', purple:'bg-purple-100 text-purple-700',
        green:'bg-green-100 text-green-700', red:'bg-red-100 text-red-700',
        gray:'bg-gray-100 text-gray-600', orange:'bg-orange-100 text-orange-700'
    };
    return `<span class="px-2 py-0.5 rounded-full text-xs font-semibold ${map[color]||map.gray}">${esc(text)}</span>`;
}
function esc(s) { return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function fmt(n) { return '₹' + Number(n||0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}); }
function showToast(type, msg) {
    const t = document.getElementById('toast');
    document.getElementById('toastInner').className = 'flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white min-w-[220px] ' + (type==='success'?'bg-green-600':'bg-red-600');
    document.getElementById('toastIcon').textContent = type==='success'?'✓':'✕';
    document.getElementById('toastMsg').textContent = msg;
    t.classList.remove('hidden');
    setTimeout(()=>t.classList.add('hidden'), 3500);
}

// ── Pagination state ──────────────────────────────────────────────
let currentPage = 1, lastPage = 1, totalOrders = 0;
let debounceTimer;

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => { currentPage = 1; loadOrders(); }, 400);
}

function changePage(dir) {
    const newPage = currentPage + dir;
    if (newPage < 1 || newPage > lastPage) return;
    currentPage = newPage;
    loadOrders();
}

// ── Load orders ───────────────────────────────────────────────────
function loadOrders() {
    const params = new URLSearchParams({
        page:           currentPage,
        search:         document.getElementById('searchInput').value,
        status:         document.getElementById('statusFilter').value,
        payment_status: document.getElementById('paymentFilter').value,
        date_from:      document.getElementById('dateFrom').value,
        date_to:        document.getElementById('dateTo').value,
    });

    const tbody = document.getElementById('ordersTableBody');
    tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">Loading...</td></tr>';

    fetch('{{ route("admin.orders.list") }}?' + params, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            const orders = res.data;
            lastPage    = res.meta.last_page;
            totalOrders = res.meta.total;
            currentPage = res.meta.current_page;

            if (!orders.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">No orders found</td></tr>';
                document.getElementById('pagination').classList.add('hidden');
                return;
            }

            tbody.innerHTML = orders.map(o => `
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="viewOrder(${o.id})">
                    <td class="px-4 py-3 font-mono font-bold text-[#0082C3] text-xs">${esc(o.order_number)}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-900 text-sm">${esc(o.customer_name)}</p>
                        <p class="text-xs text-gray-400">${esc(o.customer_email)}</p>
                    </td>
                    <td class="px-4 py-3 text-center text-sm text-gray-600">${o.items_count ?? 0}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900">${fmt(o.total_amount)}</td>
                    <td class="px-4 py-3 text-center">${pill(o.status, STATUS_COLORS[o.status]||'gray')}</td>
                    <td class="px-4 py-3 text-center">${pill(o.payment_status, PAY_COLORS[o.payment_status]||'gray')}</td>
                    <td class="px-4 py-3 text-right text-xs text-gray-400">${new Date(o.created_at).toLocaleDateString('en-IN')}</td>
                    <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="viewOrder(${o.id})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button onclick="deleteOrder(${o.id})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            // Pagination
            const pg = document.getElementById('pagination');
            pg.classList.remove('hidden');
            document.getElementById('paginationInfo').textContent = `Showing ${orders.length} of ${totalOrders} orders`;
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= lastPage;
        })
        .catch(err => {
            tbody.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-red-500 text-sm">Error: ${esc(err.message)}</td></tr>`;
        });
}

// ── Load stats ────────────────────────────────────────────────────
function loadStats() {
    fetch('{{ route("admin.orders.stats") }}', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const d = res.data;
            document.getElementById('stat-total').textContent     = d.total;
            document.getElementById('stat-pending').textContent   = d.pending;
            document.getElementById('stat-processing').textContent= d.processing;
            document.getElementById('stat-shipped').textContent   = d.shipped;
            document.getElementById('stat-delivered').textContent = d.delivered;
        });
}

// ── View order detail ─────────────────────────────────────────────
function viewOrder(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = '<p class="text-gray-400 text-sm text-center py-8">Loading...</p>';

    fetch(`/admin/orders/${id}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            const o = res.data;
            document.getElementById('detailTitle').textContent = 'Order ' + o.order_number;
            document.getElementById('detailContent').innerHTML = renderOrderDetail(o);
        })
        .catch(err => {
            document.getElementById('detailContent').innerHTML = `<p class="text-red-500 text-sm text-center py-8">${esc(err.message)}</p>`;
        });
}

function renderOrderDetail(o) {
    const items = (o.items||[]).map(i => `
        <tr class="border-b border-gray-50">
            <td class="py-2 pr-4 text-sm font-medium text-gray-900">${esc(i.product_name)}</td>
            <td class="py-2 pr-4 text-xs text-gray-500">${esc(i.variant_name||'—')}</td>
            <td class="py-2 pr-4 text-sm text-center">${i.quantity}</td>
            <td class="py-2 pr-4 text-sm text-right">${fmt(i.unit_price)}</td>
            <td class="py-2 text-sm text-right font-semibold">${fmt(i.total_price)}</td>
        </tr>
    `).join('');

    const hasSR = o.admin_note && o.admin_note.includes('Shiprocket Order ID:');
    const srCancellable = hasSR && !['delivered','cancelled','refunded'].includes(o.status);

    return `
    <div class="space-y-5">
        {{-- Status bar --}}
        <div class="flex flex-wrap gap-2 items-center">
            ${pill(o.status, STATUS_COLORS[o.status]||'gray')}
            ${pill(o.payment_status, PAY_COLORS[o.payment_status]||'gray')}
            <span class="text-xs text-gray-400">${new Date(o.created_at).toLocaleString('en-IN')}</span>
            <div class="ml-auto flex gap-2 flex-wrap">
                <select onchange="updateOrderStatus(${o.id}, this.value)" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                    <option value="">Change Status</option>
                    ${['pending','confirmed','processing','shipped','delivered','cancelled','refunded'].map(s=>`<option value="${s}">${s.charAt(0).toUpperCase()+s.slice(1)}</option>`).join('')}
                </select>
                <button onclick="generateInvoice(${o.id})" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">📄 Invoice</button>
                ${hasSR
                    ? (srCancellable
                        ? `<button onclick="cancelShiprocket(${o.id})" class="px-3 py-1.5 text-xs bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold flex items-center gap-1">❌ Cancel Shiprocket</button>`
                        : `<span class="px-3 py-1.5 text-xs bg-gray-100 text-gray-500 rounded-lg font-semibold flex items-center gap-1">✓ On Shiprocket</span>`)
                    : `<button onclick="shipViaShiprocket(${o.id})" class="px-3 py-1.5 text-xs bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-semibold flex items-center gap-1">🚀 Ship via Shiprocket</button>`
                }
            </div>
        </div>

        {{-- Customer & Shipping --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Customer</p>
                <p class="font-semibold text-gray-900">${esc(o.customer_name)}</p>
                <p class="text-sm text-gray-500">${esc(o.customer_email)}</p>
                <p class="text-sm text-gray-500">${esc(o.customer_phone||'—')}</p>
                <p class="text-xs text-gray-400 mt-1">Payment: <span class="font-semibold">${esc(o.payment_method||'—')}</span></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Shipping Address</p>
                <p class="font-semibold text-gray-900">${esc(o.shipping_name)}</p>
                <p class="text-sm text-gray-500">${esc(o.shipping_address)}</p>
                <p class="text-sm text-gray-500">${esc(o.shipping_city)}${o.shipping_state?', '+esc(o.shipping_state):''} ${esc(o.shipping_pincode||'')}</p>
                ${o.tracking_number?`<p class="text-xs text-blue-600 mt-1 font-mono">Tracking: ${esc(o.tracking_number)}</p>`:''}
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-50 bg-gray-50">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Order Items</p>
            </div>
            <div class="p-4">
                <table class="w-full text-sm">
                    <thead><tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                        <th class="pb-2 text-left">Product</th><th class="pb-2 text-left">Variant</th>
                        <th class="pb-2 text-center">Qty</th><th class="pb-2 text-right">Price</th><th class="pb-2 text-right">Total</th>
                    </tr></thead>
                    <tbody>${items}</tbody>
                </table>
            </div>
        </div>

        {{-- Totals --}}
        <div class="bg-gray-50 rounded-xl p-4 space-y-1.5">
            <div class="flex justify-between text-sm"><span class="text-gray-600">Subtotal</span><span>${fmt(o.subtotal)}</span></div>
            ${o.discount_amount>0?`<div class="flex justify-between text-sm"><span class="text-gray-600">Discount</span><span class="text-red-600">-${fmt(o.discount_amount)}</span></div>`:''}
            ${o.shipping_amount>0?`<div class="flex justify-between text-sm"><span class="text-gray-600">Shipping</span><span>${fmt(o.shipping_amount)}</span></div>`:''}
            ${o.tax_amount>0?`<div class="flex justify-between text-sm"><span class="text-gray-600">Tax</span><span>${fmt(o.tax_amount)}</span></div>`:''}
            <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2"><span>Total</span><span class="text-[#0082C3]">${fmt(o.total_amount)}</span></div>
        </div>

        ${o.admin_note?`<div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3"><p class="text-xs font-bold text-yellow-700 mb-1">Admin Note</p><p class="text-sm text-yellow-800">${esc(o.admin_note)}</p></div>`:''}

        ${renderShiprocketSection(o)}
    </div>`;
}

function renderShiprocketSection(o) {
    const note = o.admin_note || '';
    const srMatch  = note.match(/Shiprocket Order ID: (\d+)/);
    const shipMatch = note.match(/Shipment ID: (\d+)/);
    if (!srMatch) return '';

    const srOrderId   = srMatch[1];
    const srShipmentId = shipMatch ? shipMatch[1] : '—';
    const awb         = o.tracking_number || null;
    const carrier     = o.shipping_carrier || null;

    const statusMap = {
        'pending':    ['bg-yellow-100 text-yellow-700', 'Pending'],
        'processing': ['bg-indigo-100 text-indigo-700', 'Processing'],
        'shipped':    ['bg-purple-100 text-purple-700', 'Shipped'],
        'delivered':  ['bg-green-100 text-green-700',   'Delivered'],
        'cancelled':  ['bg-red-100 text-red-700',       'Cancelled'],
    };
    const [pillCls, pillLabel] = statusMap[o.status] || ['bg-gray-100 text-gray-600', o.status];

    return `
        <div class="border border-orange-200 bg-orange-50 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-orange-100">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🚀</span>
                    <p class="text-sm font-bold text-orange-800">Shiprocket Shipment</p>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${pillCls}">${pillLabel}</span>
                </div>
                <button onclick="syncShiprocket(${o.id})" id="syncBtn-${o.id}"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-500 text-white text-xs font-bold rounded-lg hover:bg-orange-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Sync Status
                </button>
            </div>
            <div class="px-4 py-3 grid grid-cols-2 gap-3 text-xs">
                <div>
                    <p class="text-orange-600 font-semibold uppercase tracking-wider mb-0.5">SR Order ID</p>
                    <p class="font-mono font-bold text-gray-900">${esc(srOrderId)}</p>
                </div>
                <div>
                    <p class="text-orange-600 font-semibold uppercase tracking-wider mb-0.5">Shipment ID</p>
                    <p class="font-mono font-bold text-gray-900">${esc(srShipmentId)}</p>
                </div>
                ${awb ? `
                <div>
                    <p class="text-orange-600 font-semibold uppercase tracking-wider mb-0.5">AWB / Tracking</p>
                    <p class="font-mono font-bold text-gray-900">${esc(awb)}</p>
                </div>` : ''}
                ${carrier ? `
                <div>
                    <p class="text-orange-600 font-semibold uppercase tracking-wider mb-0.5">Courier</p>
                    <p class="font-bold text-gray-900">${esc(carrier)}</p>
                </div>` : ''}
                ${o.shipped_at ? `
                <div>
                    <p class="text-orange-600 font-semibold uppercase tracking-wider mb-0.5">Shipped At</p>
                    <p class="text-gray-700">${new Date(o.shipped_at).toLocaleString('en-IN')}</p>
                </div>` : ''}
                ${o.delivered_at ? `
                <div>
                    <p class="text-orange-600 font-semibold uppercase tracking-wider mb-0.5">Delivered At</p>
                    <p class="text-gray-700">${new Date(o.delivered_at).toLocaleString('en-IN')}</p>
                </div>` : ''}
            </div>
            ${awb ? `
            <div class="px-4 pb-3">
                <a href="https://shiprocket.co/tracking/${esc(awb)}" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs text-orange-600 hover:text-orange-800 font-semibold underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Track on Shiprocket
                </a>
            </div>` : ''}
        </div>`;
}

function updateOrderStatus(id, status) {
    if (!status) return;
    fetch(`/admin/orders/${id}/status`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ status })
    }).then(r => r.json()).then(res => {
        if (res.success) { showToast('success', 'Status updated'); loadOrders(); viewOrder(id); }
        else showToast('error', res.message);
    });
}

// ── Shiprocket ────────────────────────────────────────────────────
async function shipViaShiprocket(orderId) {
    // Open courier selection modal instead of directly creating
    openCourierModal(orderId);
}

async function cancelShiprocket(orderId) {
    if (!confirm('Cancel this order on Shiprocket? This will also mark the order as cancelled locally.')) return;

    try {
        const r    = await fetch('/admin/shiprocket/orders/' + orderId + '/cancel', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await r.json();

        if (data.success) {
            showToast('success', '✓ Order cancelled on Shiprocket');
            loadOrders();
            viewOrder(orderId); // refresh detail panel
        } else {
            showToast('error', data.message || 'Cancel failed');
        }
    } catch (e) {
        showToast('error', e.message);
    }
}

async function syncShiprocket(orderId) {
    const btn = document.getElementById('syncBtn-' + orderId);
    if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Syncing...'; }

    try {
        const r    = await fetch('/admin/shiprocket/orders/' + orderId + '/sync', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await r.json();

        if (data.success) {
            const d = data.data;
            showToast('success', 'Synced! Status: ' + d.sr_status + (d.awb ? ' | AWB: ' + d.awb : ''));
            loadOrders();
            viewOrder(orderId); // refresh detail with new data
        } else {
            if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Sync Status'; }
            showToast('error', data.message || 'Sync failed');
        }
    } catch (e) {
        if (btn) { btn.disabled = false; btn.innerHTML = 'Sync Status'; }
        showToast('error', e.message);
    }
}

// ── Courier Selection Modal ───────────────────────────────────────
let srCurrentOrderId = null;

function openCourierModal(orderId) {
    srCurrentOrderId = orderId;
    document.getElementById('courierModal').style.display = 'block';
    loadCouriers(orderId);
}

function closeCourierModal() {
    document.getElementById('courierModal').style.display = 'none';
    srCurrentOrderId = null;
}

async function loadCouriers(orderId) {
    const body    = document.getElementById('courierModalBody');
    const title   = document.getElementById('courierModalTitle');
    body.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 gap-4">
            <svg class="w-10 h-10 text-orange-400 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <p class="text-sm text-gray-500 font-medium">Fetching available couriers...</p>
            <p class="text-xs text-gray-400">Checking serviceability & rates</p>
        </div>`;

    try {
        const r    = await fetch('/admin/shiprocket/orders/' + orderId + '/couriers', {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        });
        const data = await r.json();

        if (!data.success) throw new Error(data.message);

        const d        = data.data;
        const couriers = d.couriers || [];
        title.textContent = 'Ship Order — Select Courier';

        if (!couriers.length) {
            body.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 gap-3">
                    <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">No couriers available</p>
                    <p class="text-xs text-gray-400 text-center max-w-xs">No courier services found for this pincode route.<br>Check pickup & delivery pincodes in Settings.</p>
                </div>`;
            return;
        }

        // Route info bar
        const routeBar = `
            <div class="flex items-center gap-4 px-5 py-3 bg-orange-50 border-b border-orange-100 text-xs text-orange-700">
                <span class="font-semibold">📦 ${esc(d.order_number)}</span>
                <span class="text-orange-400">|</span>
                <span>📍 ${esc(d.pickup_pincode)} → ${esc(d.delivery_pincode)}</span>
                <span class="text-orange-400">|</span>
                <span>⚖️ ${d.weight} kg</span>
                ${d.is_cod ? '<span class="ml-auto bg-orange-200 text-orange-800 px-2 py-0.5 rounded-full font-bold">COD</span>' : '<span class="ml-auto bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold">Prepaid</span>'}
            </div>`;

        // Courier cards
        const cards = couriers.map((c, idx) => {
            const isBest    = idx === 0;
            const isFastest = couriers.reduce((min, x) => {
                const d1 = parseInt(x.estimated_days) || 999;
                const d2 = parseInt(min.estimated_days) || 999;
                return d1 < d2 ? x : min;
            }, couriers[0]).id === c.id;

            const stars = c.rating ? Math.round(parseFloat(c.rating)) : 0;
            const starHtml = stars > 0 ? Array.from({length:5}, (_,i) =>
                '<svg class="w-3 h-3 ' + (i < stars ? 'text-yellow-400' : 'text-gray-200') + '" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>'
            ).join('') : '';

            const badges = [];
            if (isBest)    badges.push('<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">💰 Best Price</span>');
            if (isFastest && !isBest) badges.push('<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">⚡ Fastest</span>');
            if (c.is_surface) badges.push('<span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Surface</span>');

            const logoHtml = c.logo
                ? '<img src="' + esc(c.logo) + '" alt="' + esc(c.name) + '" class="w-10 h-10 object-contain rounded-lg border border-gray-100 bg-white p-1" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\'">'
                  + '<div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center hidden"><span class="text-orange-600 font-bold text-sm">' + esc(c.name.charAt(0)) + '</span></div>'
                : '<div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center"><span class="text-orange-600 font-bold text-sm">' + esc(c.name.charAt(0)) + '</span></div>';

            const etd = c.etd || (c.estimated_days ? c.estimated_days + ' days' : '—');
            const codCharge = c.cod_charges > 0 ? '<span class="text-xs text-gray-400">+₹' + c.cod_charges.toFixed(0) + ' COD</span>' : '';

            return `
                <div class="group relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-2xl hover:border-orange-400 hover:shadow-md transition-all cursor-pointer bg-white"
                     onclick="selectCourier(${c.id}, '${esc(c.name)}', ${c.rate})">
                    <div class="flex-shrink-0">${logoHtml}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <p class="font-bold text-gray-900 text-sm">${esc(c.name)}</p>
                            ${badges.join('')}
                        </div>
                        <div class="flex items-center gap-3 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                ${esc(etd)}
                            </span>
                            ${c.delivery_performance ? '<span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' + esc(c.delivery_performance) + '% on-time</span>' : ''}
                        </div>
                        ${starHtml ? '<div class="flex items-center gap-0.5 mt-1">' + starHtml + '</div>' : ''}
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xl font-black text-gray-900">₹${c.rate.toFixed(0)}</p>
                        ${codCharge}
                        <button class="mt-2 px-4 py-1.5 bg-orange-500 text-white text-xs font-bold rounded-xl hover:bg-orange-600 transition-colors group-hover:bg-orange-600 shadow-sm">
                            Select & Ship
                        </button>
                    </div>
                </div>`;
        }).join('');

        body.innerHTML = routeBar + `
            <div class="p-5 space-y-3 max-h-[60vh] overflow-y-auto">
                <p class="text-xs text-gray-400 font-medium">${couriers.length} courier${couriers.length > 1 ? 's' : ''} available — sorted by price</p>
                ${cards}
            </div>`;

    } catch (e) {
        body.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 gap-3">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">Failed to load couriers</p>
                <p class="text-xs text-gray-400 text-center max-w-xs">${esc(e.message)}</p>
                <button onclick="loadCouriers(srCurrentOrderId)" class="px-4 py-2 bg-orange-500 text-white text-xs font-bold rounded-xl hover:bg-orange-600">Retry</button>
            </div>`;
    }
}

async function selectCourier(courierId, courierName, rate) {
    if (!srCurrentOrderId) return;
    if (!confirm('Ship with ' + courierName + ' for ₹' + rate.toFixed(0) + '?')) return;

    const capturedOrderId = srCurrentOrderId; // capture before async
    const body = document.getElementById('courierModalBody');
    body.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:64px 20px;gap:16px;">
            <svg style="width:40px;height:40px;color:#fb923c;animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                <circle style="opacity:0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path style="opacity:0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <p style="font-size:14px;color:#4b5563;font-weight:500;">Creating Shiprocket order with <strong>${esc(courierName)}</strong>...</p>
        </div>`;

    try {
        const r    = await fetch('/admin/shiprocket/orders/' + capturedOrderId + '/create', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ courier_id: courierId })
        });
        const data = await r.json();

        if (data.success) {
            const d = data.data;
            const srStatus = d.sr_status || d.status || 'NEW';
            const isNew    = srStatus === 'NEW' || srStatus === 'READY TO SHIP';

            body.innerHTML = `
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:48px 20px;gap:16px;">
                    <div style="width:72px;height:72px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:36px;height:36px;color:#22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p style="font-size:18px;font-weight:700;color:#111827;margin:0;">Pushed to Shiprocket!</p>
                    <p style="font-size:13px;color:#6b7280;margin:0;">Courier: <strong>${esc(courierName)}</strong></p>
                    <div style="background:#f9fafb;border-radius:12px;padding:16px;text-align:center;width:100%;max-width:280px;">
                        <p style="font-size:11px;color:#9ca3af;margin:0 0 2px;">Shiprocket Order ID</p>
                        <p style="font-family:monospace;font-weight:700;color:#111827;font-size:15px;margin:0 0 8px;">${esc(String(d.sr_order_id||'—'))}</p>
                        <p style="font-size:11px;color:#9ca3af;margin:0 0 2px;">Shipment ID</p>
                        <p style="font-family:monospace;font-weight:700;color:#111827;font-size:15px;margin:0 0 8px;">${esc(String(d.sr_shipment_id||'—'))}</p>
                        <p style="font-size:11px;color:#9ca3af;margin:0 0 2px;">Status</p>
                        <p style="font-weight:700;color:${isNew?'#16a34a':'#d97706'};font-size:13px;margin:0;">${esc(srStatus)}</p>
                    </div>
                    <p style="font-size:12px;color:#9ca3af;text-align:center;max-width:260px;margin:0;">
                        Go to <strong>app.shiprocket.in → Orders → New</strong> to generate AWB &amp; schedule pickup
                    </p>
                    <button onclick="closeCourierModal()" style="padding:10px 24px;background:#16a34a;color:#fff;font-weight:700;font-size:14px;border:none;border-radius:12px;cursor:pointer;">Done</button>
                </div>`;

            loadOrders();
            // Refresh detail panel to show Shiprocket section
            setTimeout(() => {
                if (!document.getElementById('detailModal').classList.contains('hidden')) {
                    viewOrder(capturedOrderId);
                }
            }, 500);
        } else {
            throw new Error(data.message || 'Failed to create order');
        }
    } catch (e) {
        showToast('error', e.message);
        loadCouriers(capturedOrderId);
    }
}

function generateInvoice(orderId) {
    fetch(`/admin/invoices/from-order/${orderId}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({})
    }).then(r => r.json()).then(res => {
        if (res.success) showToast('success', 'Invoice generated: ' + res.data.invoice_number);
        else showToast('error', res.message);
    });
}

function deleteOrder(id) {
    if (!confirm('Delete this order? This cannot be undone.')) return;
    fetch(`/admin/orders/${id}`, {
        method: 'DELETE',
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(r => r.json()).then(res => {
        if (res.success) { showToast('success', 'Order deleted'); loadOrders(); loadStats(); }
        else showToast('error', res.message);
    });
}

// ── Create order ──────────────────────────────────────────────────
let orderItems = [];
let productSearchTimer;

function openCreateModal() {
    orderItems = [];
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createOrderForm').reset();
    renderOrderItems();
}
function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }
function closeDetailModal() { document.getElementById('detailModal').classList.add('hidden'); }

function searchProducts(q) {
    clearTimeout(productSearchTimer);
    if (q.length < 2) { document.getElementById('productDropdown').classList.add('hidden'); return; }
    productSearchTimer = setTimeout(() => {
        fetch(`{{ route("admin.orders.search-products") }}?q=${encodeURIComponent(q)}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(res => {
                const dd = document.getElementById('productDropdown');
                if (!res.success || !res.data.length) { dd.classList.add('hidden'); return; }
                dd.innerHTML = res.data.map(p => `
                    <div onclick="addProduct(${JSON.stringify(p).replace(/"/g,'&quot;')})" class="px-3 py-2.5 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0">
                        <p class="text-sm font-semibold text-gray-900">${esc(p.name)}</p>
                        <p class="text-xs text-gray-400">${esc(p.sku_prefix||'—')} · ${fmt(p.sale_price||p.price||0)}</p>
                    </div>
                `).join('');
                dd.classList.remove('hidden');
            });
    }, 300);
}

function addProduct(p) {
    document.getElementById('productDropdown').classList.add('hidden');
    document.getElementById('productSearch').value = '';
    const existing = orderItems.find(i => i.product_id === p.id);
    if (existing) { existing.quantity++; }
    else {
        orderItems.push({
            product_id: p.id, product_name: p.name, product_sku: p.sku_prefix,
            variant_id: null, variant_name: null, product_image: p.thumbnail,
            quantity: 1, unit_price: parseFloat(p.sale_price||p.price||0), discount_amount: 0, tax_amount: 0,
        });
    }
    renderOrderItems();
}

function removeItem(idx) { orderItems.splice(idx, 1); renderOrderItems(); }

function updateItemQty(idx, val) {
    orderItems[idx].quantity = Math.max(1, parseInt(val)||1);
    renderOrderItems();
}

function renderOrderItems() {
    const container = document.getElementById('orderItemsList');
    const emptyMsg  = document.getElementById('emptyItemsMsg');
    if (!orderItems.length) {
        container.innerHTML = '<p class="text-xs text-gray-400 text-center py-4 border-2 border-dashed border-gray-200 rounded-lg" id="emptyItemsMsg">No items added yet. Search and add products above.</p>';
        recalcTotals();
        return;
    }
    container.innerHTML = orderItems.map((item, idx) => `
        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">${esc(item.product_name)}</p>
                <p class="text-xs text-gray-400">${esc(item.product_sku||'—')} · ${fmt(item.unit_price)} each</p>
            </div>
            <input type="number" value="${item.quantity}" min="1" onchange="updateItemQty(${idx}, this.value)"
                class="w-16 text-center px-2 py-1 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
            <span class="text-sm font-bold text-gray-900 w-20 text-right">${fmt(item.unit_price * item.quantity)}</span>
            <button type="button" onclick="removeItem(${idx})" class="text-red-400 hover:text-red-600 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    `).join('');
    recalcTotals();
}

function recalcTotals() {
    const subtotal  = orderItems.reduce((s, i) => s + (i.unit_price * i.quantity), 0);
    const shipping  = parseFloat(document.getElementById('shippingInput')?.value||0);
    const discount  = parseFloat(document.getElementById('discountInput')?.value||0);
    const tax       = parseFloat(document.getElementById('taxInput')?.value||0);
    const total     = subtotal + shipping + tax - discount;
    document.getElementById('calcSubtotal').textContent = fmt(subtotal);
    document.getElementById('calcTotal').textContent    = fmt(total);
}

function submitOrder(e) {
    e.preventDefault();
    if (!orderItems.length) { showToast('error', 'Add at least one product'); return; }
    const form = e.target;
    const fd   = new FormData(form);
    const data = Object.fromEntries(fd.entries());
    data.items = orderItems;

    const btn = document.getElementById('submitOrderBtn');
    btn.disabled = true; btn.textContent = 'Creating...';

    fetch('{{ route("admin.orders.store") }}', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
    }).then(r => r.json()).then(res => {
        btn.disabled = false; btn.textContent = 'Create Order';
        if (res.success) {
            showToast('success', 'Order ' + res.data.order_number + ' created!');
            closeCreateModal();
            loadOrders();
            loadStats();
        } else {
            showToast('error', res.message || 'Failed to create order');
        }
    }).catch(err => {
        btn.disabled = false; btn.textContent = 'Create Order';
        showToast('error', err.message);
    });
}

// Close dropdown on outside click
document.addEventListener('click', e => {
    if (!e.target.closest('#productSearch') && !e.target.closest('#productDropdown')) {
        document.getElementById('productDropdown')?.classList.add('hidden');
    }
});

document.addEventListener('DOMContentLoaded', () => { loadStats(); loadOrders(); });
</script>
@endpush
