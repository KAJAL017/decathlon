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
        <i data-lucide="plus" class="w-4 h-4"></i>
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
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
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
                <i data-lucide="x" class="w-6 h-6"></i>
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
                <i data-lucide="x" class="w-6 h-6"></i>
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
let isFirstLoad = true;

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
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteOrder(${o.id})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
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
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
        })
        .catch(err => {
            tbody.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-red-500 text-sm">Error: ${esc(err.message)}</td></tr>`;
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
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
                    : `<button id="shiprocketBtn-${o.id}" onclick="shipViaShiprocket(${o.id})" class="px-3 py-1.5 text-xs bg-orange-500 text-white rounded-lg hover:bg-orange-600 disabled:opacity-60 disabled:cursor-not-allowed font-semibold flex items-center gap-1">🚀 Ship via Shiprocket</button>`
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
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
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
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
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
    if (!confirm('Push this order to Shiprocket?')) return;

    const btn = document.getElementById('shiprocketBtn-' + orderId);
    const originalHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" class="w-3.5 h-3.5 animate-spin"></i> Pushing...';
    }

    try {
        const r = await fetch('/admin/shiprocket/orders/' + orderId + '/create', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        });
        const data = await r.json();

        if (!data.success) {
            throw new Error(data.message || 'Shiprocket push failed');
        }

        const d = data.data || {};
        showToast('success', 'Order pushed to Shiprocket' + (d.sr_order_id ? ': ' + d.sr_order_id : ''));
        loadOrders();
        if (!document.getElementById('detailModal').classList.contains('hidden')) {
            viewOrder(orderId);
        }
    } catch (e) {
        showToast('error', e.message);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
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
    if (btn) { btn.disabled = true; btn.innerHTML = '<i data-lucide="loader" class="w-3.5 h-3.5 animate-spin"></i> Syncing...'; }

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
            if (btn) { btn.disabled = false; btn.innerHTML = '<i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Sync Status'; }
            showToast('error', data.message || 'Sync failed');
        }
    } catch (e) {
        if (btn) { btn.disabled = false; btn.innerHTML = 'Sync Status'; }
        showToast('error', e.message);
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
                <i data-lucide="x" class="w-4 h-4"></i>
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
