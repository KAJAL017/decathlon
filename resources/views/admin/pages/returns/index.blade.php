@extends('admin.layouts.app')
@section('title', 'Returns & Refunds')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Returns & Refunds</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manage return requests and refunds</p>
    </div>
    <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3] transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Return
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    @foreach([
        ['id'=>'stat-total',    'label'=>'Total Returns',   'color'=>'#0369a1'],
        ['id'=>'stat-requested','label'=>'Requested',       'color'=>'#d97706'],
        ['id'=>'stat-approved', 'label'=>'Approved',        'color'=>'#2563eb'],
        ['id'=>'stat-refunded', 'label'=>'Refunded',        'color'=>'#047857'],
        ['id'=>'stat-rejected', 'label'=>'Rejected',        'color'=>'#dc2626'],
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
            <input type="text" id="searchInput" placeholder="Search return #, order #, customer..." onkeyup="debounceLoad()" class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <select id="statusFilter" onchange="loadReturns()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Status</option>
            <option value="requested">Requested</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="received">Received</option>
            <option value="refunded">Refunded</option>
            <option value="closed">Closed</option>
        </select>
        <select id="typeFilter" onchange="loadReturns()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Types</option>
            <option value="return">Return</option>
            <option value="exchange">Exchange</option>
            <option value="refund_only">Refund Only</option>
        </select>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Return #</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reason</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Refund</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="returnsTableBody" class="divide-y divide-gray-50">
                <tr><td colspan="9" class="px-4 py-12 text-center text-gray-400 text-sm">Loading...</td></tr>
            </tbody>
        </table>
    </div>
    <div id="pagination" class="flex items-center justify-between px-4 py-3 border-t border-gray-100 hidden">
        <p id="paginationInfo" class="text-xs text-gray-500"></p>
        <div class="flex gap-2">
            <button id="prevBtn" onclick="changePage(-1)" class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40">← Prev</button>
            <button id="nextBtn" onclick="changePage(1)" class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40">Next →</button>
        </div>
    </div>
</div>

</div>

{{-- Create Return Modal --}}
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900">New Return Request</h2>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="createReturnForm" onsubmit="submitReturn(event)" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Order Number *</label>
                <input type="text" id="orderSearch" placeholder="Enter order number (e.g. ORD-2026-00001)" oninput="searchOrder(this.value)"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <div id="orderResult" class="mt-2 hidden bg-blue-50 border border-blue-100 rounded-lg p-3">
                    <p id="orderResultText" class="text-sm text-blue-800 font-semibold"></p>
                    <input type="hidden" id="selectedOrderId" name="order_id">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Return Type *</label>
                    <select name="type" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="return">Return</option>
                        <option value="exchange">Exchange</option>
                        <option value="refund_only">Refund Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Reason *</label>
                    <select name="reason" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="damaged">Damaged / Defective</option>
                        <option value="wrong_item">Wrong Item</option>
                        <option value="not_as_described">Not as Described</option>
                        <option value="changed_mind">Changed Mind</option>
                        <option value="size_issue">Size / Fit Issue</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Describe the issue..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Refund Amount (₹)</label>
                    <input type="number" name="refund_amount" min="0" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Refund Method</label>
                    <select name="refund_method" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="original_payment">Original Payment</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="store_credit">Store Credit</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Admin Note</label>
                <textarea name="admin_note" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Internal note..."></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50">Cancel</button>
                <button type="submit" id="submitReturnBtn" class="flex-1 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3]">Create Return</button>
            </div>
        </form>
    </div>
</div>

{{-- Detail Modal --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDetailModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900" id="detailTitle">Return Details</h2>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="detailContent" class="p-6"></div>
    </div>
</div>

<div id="toast" class="fixed bottom-5 right-5 z-50 hidden">
    <div id="toastInner" class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white min-w-[220px]">
        <span id="toastIcon"></span><span id="toastMsg"></span>
    </div>
</div>
@endsection

@push('scripts')
<script>
const STATUS_COLORS = {
    requested:'yellow', approved:'blue', rejected:'red',
    received:'indigo', refunded:'green', closed:'gray'
};
const TYPE_COLORS = { return:'blue', exchange:'purple', refund_only:'orange' };

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

let currentPage = 1, lastPage = 1, totalReturns = 0;
let debounceTimer;

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => { currentPage = 1; loadReturns(); }, 400);
}
function changePage(dir) {
    const newPage = currentPage + dir;
    if (newPage < 1 || newPage > lastPage) return;
    currentPage = newPage;
    loadReturns();
}

function loadReturns() {
    const params = new URLSearchParams({
        page:   currentPage,
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        type:   document.getElementById('typeFilter').value,
    });

    const tbody = document.getElementById('returnsTableBody');
    tbody.innerHTML = '<tr><td colspan="9" class="px-4 py-12 text-center text-gray-400 text-sm">Loading...</td></tr>';

    fetch('{{ route("admin.returns.list") }}?' + params, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            const returns = res.data;
            lastPage    = res.meta.last_page;
            totalReturns= res.meta.total;
            currentPage = res.meta.current_page;

            if (!returns.length) {
                tbody.innerHTML = '<tr><td colspan="9" class="px-4 py-12 text-center text-gray-400 text-sm">No returns found</td></tr>';
                document.getElementById('pagination').classList.add('hidden');
                return;
            }

            tbody.innerHTML = returns.map(r => `
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="viewReturn(${r.id})">
                    <td class="px-4 py-3 font-mono font-bold text-[#0082C3] text-xs">${esc(r.return_number)}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">${esc(r.order?.order_number||'—')}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${esc(r.order?.customer_name||'—')}</td>
                    <td class="px-4 py-3 text-xs text-gray-500 capitalize">${esc(r.reason?.replace(/_/g,' ')||'—')}</td>
                    <td class="px-4 py-3 text-center">${pill(r.type?.replace(/_/g,' '), TYPE_COLORS[r.type]||'gray')}</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-900">${r.refund_amount>0?fmt(r.refund_amount):'—'}</td>
                    <td class="px-4 py-3 text-center">${pill(r.status, STATUS_COLORS[r.status]||'gray')}</td>
                    <td class="px-4 py-3 text-right text-xs text-gray-400">${new Date(r.created_at).toLocaleDateString('en-IN')}</td>
                    <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="viewReturn(${r.id})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button onclick="deleteReturn(${r.id})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            const pg = document.getElementById('pagination');
            pg.classList.remove('hidden');
            document.getElementById('paginationInfo').textContent = `Showing ${returns.length} of ${totalReturns} returns`;
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= lastPage;
        })
        .catch(err => {
            tbody.innerHTML = `<tr><td colspan="9" class="px-4 py-8 text-center text-red-500 text-sm">Error: ${esc(err.message)}</td></tr>`;
        });
}

function loadStats() {
    fetch('{{ route("admin.returns.stats") }}', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const d = res.data;
            document.getElementById('stat-total').textContent     = d.total;
            document.getElementById('stat-requested').textContent = d.requested;
            document.getElementById('stat-approved').textContent  = d.approved;
            document.getElementById('stat-refunded').textContent  = d.refunded;
            document.getElementById('stat-rejected').textContent  = d.rejected;
        });
}

function viewReturn(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = '<p class="text-gray-400 text-sm text-center py-8">Loading...</p>';

    fetch(`/admin/returns/${id}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            const r = res.data;
            document.getElementById('detailTitle').textContent = 'Return ' + r.return_number;
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2 items-center">
                        ${pill(r.status, STATUS_COLORS[r.status]||'gray')}
                        ${pill(r.type?.replace(/_/g,' '), TYPE_COLORS[r.type]||'gray')}
                        <span class="text-xs text-gray-400">${new Date(r.created_at).toLocaleString('en-IN')}</span>
                        <div class="ml-auto">
                            <select onchange="updateReturnStatus(${r.id}, this.value)" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                                <option value="">Change Status</option>
                                ${['approved','rejected','received','refunded','closed'].map(s=>`<option value="${s}">${s.charAt(0).toUpperCase()+s.slice(1)}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Order</span><span class="font-mono font-bold text-[#0082C3]">${esc(r.order?.order_number||'—')}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Customer</span><span class="font-semibold">${esc(r.order?.customer_name||'—')}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Reason</span><span class="capitalize">${esc(r.reason?.replace(/_/g,' ')||'—')}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Refund Amount</span><span class="font-bold text-green-600">${r.refund_amount>0?fmt(r.refund_amount):'—'}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Refund Method</span><span class="capitalize">${esc(r.refund_method?.replace(/_/g,' ')||'—')}</span></div>
                        ${r.refunded_at?`<div class="flex justify-between text-sm"><span class="text-gray-500">Refunded At</span><span>${new Date(r.refunded_at).toLocaleDateString('en-IN')}</span></div>`:''}
                    </div>
                    ${r.description?`<div class="bg-blue-50 border border-blue-100 rounded-xl p-3"><p class="text-xs font-bold text-blue-700 mb-1">Description</p><p class="text-sm text-blue-800">${esc(r.description)}</p></div>`:''}
                    ${r.admin_note?`<div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3"><p class="text-xs font-bold text-yellow-700 mb-1">Admin Note</p><p class="text-sm text-yellow-800">${esc(r.admin_note)}</p></div>`:''}
                </div>
            `;
        })
        .catch(err => {
            document.getElementById('detailContent').innerHTML = `<p class="text-red-500 text-sm text-center py-8">${esc(err.message)}</p>`;
        });
}

function updateReturnStatus(id, status) {
    if (!status) return;
    fetch(`/admin/returns/${id}`, {
        method: 'PUT',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ status })
    }).then(r => r.json()).then(res => {
        if (res.success) { showToast('success', 'Status updated'); loadReturns(); viewReturn(id); }
        else showToast('error', res.message);
    });
}

function deleteReturn(id) {
    if (!confirm('Delete this return request?')) return;
    fetch(`/admin/returns/${id}`, {
        method: 'DELETE',
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(r => r.json()).then(res => {
        if (res.success) { showToast('success', 'Return deleted'); loadReturns(); loadStats(); }
        else showToast('error', res.message);
    });
}

// Order search for create form
let orderSearchTimer;
function searchOrder(q) {
    clearTimeout(orderSearchTimer);
    if (q.length < 3) { document.getElementById('orderResult').classList.add('hidden'); return; }
    orderSearchTimer = setTimeout(() => {
        fetch(`/admin/orders/list?search=${encodeURIComponent(q)}&page=1`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(res => {
                const resultDiv = document.getElementById('orderResult');
                if (res.success && res.data.length) {
                    const o = res.data[0];
                    document.getElementById('orderResultText').textContent = `✓ ${o.order_number} — ${o.customer_name} — ${o.total_amount ? '₹'+Number(o.total_amount).toLocaleString('en-IN') : ''}`;
                    document.getElementById('selectedOrderId').value = o.id;
                    resultDiv.classList.remove('hidden');
                } else {
                    resultDiv.classList.add('hidden');
                }
            });
    }, 400);
}

function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createReturnForm').reset();
    document.getElementById('orderResult').classList.add('hidden');
}
function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }
function closeDetailModal() { document.getElementById('detailModal').classList.add('hidden'); }

function submitReturn(e) {
    e.preventDefault();
    const orderId = document.getElementById('selectedOrderId').value;
    if (!orderId) { showToast('error', 'Please search and select a valid order'); return; }

    const form = e.target;
    const fd   = new FormData(form);
    const data = Object.fromEntries(fd.entries());
    data.order_id = orderId;

    const btn = document.getElementById('submitReturnBtn');
    btn.disabled = true; btn.textContent = 'Creating...';

    fetch('{{ route("admin.returns.store") }}', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
    }).then(r => r.json()).then(res => {
        btn.disabled = false; btn.textContent = 'Create Return';
        if (res.success) {
            showToast('success', 'Return ' + res.data.return_number + ' created!');
            closeCreateModal();
            loadReturns();
            loadStats();
        } else {
            showToast('error', res.message || 'Failed');
        }
    }).catch(err => {
        btn.disabled = false; btn.textContent = 'Create Return';
        showToast('error', err.message);
    });
}

document.addEventListener('DOMContentLoaded', () => { loadStats(); loadReturns(); });
</script>
@endpush
