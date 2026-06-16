@extends('admin.layouts.app')
@section('title', 'Invoices')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
        <p class="text-sm text-gray-500 mt-0.5">Create and manage invoices</p>
    </div>
    <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3] transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        New Invoice
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    @foreach([
        ['id'=>'stat-total',  'label'=>'Total',       'color'=>'#0369a1'],
        ['id'=>'stat-draft',  'label'=>'Draft',        'color'=>'#6b7280'],
        ['id'=>'stat-sent',   'label'=>'Sent',         'color'=>'#2563eb'],
        ['id'=>'stat-paid',   'label'=>'Paid',         'color'=>'#047857'],
        ['id'=>'stat-overdue','label'=>'Overdue',      'color'=>'#dc2626'],
    ] as $s)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs font-semibold uppercase tracking-wider" style="color:{{ $s['color'] }}">{{ $s['label'] }}</p>
        <p id="{{ $s['id'] }}" class="text-2xl font-black text-gray-900 mt-1">—</p>
    </div>
    @endforeach
</div>

{{-- Revenue summary --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Invoice Value</p>
        <p id="stat-total-value" class="text-xl font-black text-gray-900 mt-1">—</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Paid Amount</p>
        <p id="stat-paid-value" class="text-xl font-black text-green-700 mt-1">—</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Outstanding Due</p>
        <p id="stat-due-value" class="text-xl font-black text-red-700 mt-1">—</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
    <div class="flex flex-wrap gap-3 items-center">
        <div class="flex-1 min-w-[200px] relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" id="searchInput" placeholder="Search invoice #, customer..." onkeyup="debounceLoad()" class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <select id="statusFilter" onchange="loadInvoices()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="sent">Sent</option>
            <option value="paid">Paid</option>
            <option value="overdue">Overdue</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <select id="typeFilter" onchange="loadInvoices()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Types</option>
            <option value="sale">Sale Invoice</option>
            <option value="credit_note">Credit Note</option>
            <option value="proforma">Proforma</option>
        </select>
        <input type="date" id="dateFrom" onchange="loadInvoices()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <input type="date" id="dateTo" onchange="loadInvoices()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Due</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="invoicesTableBody" class="divide-y divide-gray-50">
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

{{-- Create Invoice Modal --}}
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCreateModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900">Create Invoice</h2>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <form id="createInvoiceForm" onsubmit="submitInvoice(event)" class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Invoice Type</label>
                    <select name="type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="sale">Sale Invoice</option>
                        <option value="proforma">Proforma Invoice</option>
                        <option value="credit_note">Credit Note</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Invoice Date *</label>
                    <input type="date" name="invoice_date" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-gray-700 mb-3">Customer Details</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Name *</label>
                        <input type="text" name="customer_name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Email *</label>
                        <input type="email" name="customer_email" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                        <input type="text" name="customer_phone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">GSTIN</label>
                        <input type="text" name="customer_gstin" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="22AAAAA0000A1Z5">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Billing Address</label>
                        <textarea name="billing_address" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]"></textarea>
                    </div>
                </div>
            </div>

            {{-- Line Items --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-700">Line Items</h3>
                    <button type="button" onclick="addLineItem()" class="text-xs text-[#0082C3] hover:underline font-semibold">+ Add Item</button>
                </div>
                <div id="lineItemsList" class="space-y-2">
                    {{-- Items added dynamically --}}
                </div>
                <div class="bg-gray-50 rounded-xl p-4 mt-3 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-gray-600">Subtotal</span><span id="invSubtotal" class="font-semibold">₹0.00</span></div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-gray-600">Tax (GST %)</span>
                        <input type="number" name="tax_amount" id="invTax" value="0" min="0" step="0.01" onchange="recalcInvoice()" class="w-24 text-right px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-gray-600">Shipping</span>
                        <input type="number" name="shipping_amount" id="invShipping" value="0" min="0" step="0.01" onchange="recalcInvoice()" class="w-24 text-right px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-gray-600">Discount</span>
                        <input type="number" name="discount_amount" id="invDiscount" value="0" min="0" step="0.01" onchange="recalcInvoice()" class="w-24 text-right px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                    </div>
                    <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
                        <span>Total</span><span id="invTotal" class="text-[#0082C3]">₹0.00</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Due Date</label>
                    <input type="date" name="due_date" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Paid Amount (₹)</label>
                    <input type="number" name="paid_amount" value="0" min="0" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Thank you for your business..."></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50">Cancel</button>
                <button type="submit" id="submitInvoiceBtn" class="flex-1 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3]">Create Invoice</button>
            </div>
        </form>
    </div>
</div>

{{-- Detail / Print Modal --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDetailModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-lg font-bold text-gray-900" id="detailTitle">Invoice</h2>
            <div class="flex items-center gap-2">
                <button id="printBtn" onclick="printInvoice()" class="px-3 py-1.5 text-xs bg-gray-800 text-white rounded-lg hover:bg-gray-900 font-semibold">🖨 Print</button>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
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
    draft:'gray', sent:'blue', paid:'green', overdue:'red', cancelled:'gray'
};
const TYPE_LABELS = { sale:'Sale', credit_note:'Credit Note', proforma:'Proforma' };

function pill(text, color) {
    const map = {
        yellow:'bg-yellow-100 text-yellow-700', blue:'bg-blue-100 text-blue-700',
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

let currentPage = 1, lastPage = 1, totalInvoices = 0;
let debounceTimer, currentInvoiceId = null;
let isFirstLoad = true;

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => { currentPage = 1; loadInvoices(); }, 400);
}
function changePage(dir) {
    const newPage = currentPage + dir;
    if (newPage < 1 || newPage > lastPage) return;
    currentPage = newPage;
    loadInvoices();
}

function loadInvoices() {
    const params = new URLSearchParams({
        page:      currentPage,
        search:    document.getElementById('searchInput').value,
        status:    document.getElementById('statusFilter').value,
        type:      document.getElementById('typeFilter').value,
        date_from: document.getElementById('dateFrom').value,
        date_to:   document.getElementById('dateTo').value,
    });

    const tbody = document.getElementById('invoicesTableBody');
    tbody.innerHTML = '<tr><td colspan="9" class="px-4 py-12 text-center text-gray-400 text-sm">Loading...</td></tr>';

    fetch('{{ route("admin.invoices.list") }}?' + params, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            const invoices = res.data;
            lastPage      = res.meta.last_page;
            totalInvoices = res.meta.total;
            currentPage   = res.meta.current_page;

            if (!invoices.length) {
                tbody.innerHTML = '<tr><td colspan="9" class="px-4 py-12 text-center text-gray-400 text-sm">No invoices found</td></tr>';
                document.getElementById('pagination').classList.add('hidden');
                return;
            }

            tbody.innerHTML = invoices.map(inv => {
                const isOverdue = inv.status === 'sent' && inv.due_date && new Date(inv.due_date) < new Date();
                const statusColor = isOverdue ? 'red' : (STATUS_COLORS[inv.status]||'gray');
                const statusLabel = isOverdue ? 'Overdue' : inv.status;
                return `
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="viewInvoice(${inv.id})">
                    <td class="px-4 py-3 font-mono font-bold text-[#0082C3] text-xs">${esc(inv.invoice_number)}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-900 text-sm">${esc(inv.customer_name)}</p>
                        <p class="text-xs text-gray-400">${esc(inv.customer_email)}</p>
                    </td>
                    <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded font-medium">${esc(TYPE_LABELS[inv.type]||inv.type)}</span></td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900">${fmt(inv.total_amount)}</td>
                    <td class="px-4 py-3 text-right font-semibold ${inv.due_amount>0?'text-red-600':'text-green-600'}">${inv.due_amount>0?fmt(inv.due_amount):'Paid'}</td>
                    <td class="px-4 py-3 text-center">${pill(statusLabel, statusColor)}</td>
                    <td class="px-4 py-3 text-right text-xs text-gray-400">${inv.invoice_date ? new Date(inv.invoice_date).toLocaleDateString('en-IN') : '—'}</td>
                    <td class="px-4 py-3 text-right text-xs ${isOverdue?'text-red-600 font-semibold':'text-gray-400'}">${inv.due_date ? new Date(inv.due_date).toLocaleDateString('en-IN') : '—'}</td>
                    <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="viewInvoice(${inv.id})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="View">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            ${inv.status==='draft'?`<button onclick="markSent(${inv.id})" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg" title="Mark Sent"><i data-lucide="send" class="w-4 h-4"></i></button>`:''}
                            ${inv.status==='sent'?`<button onclick="markPaid(${inv.id})" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg" title="Mark Paid"><i data-lucide="circle-check" class="w-4 h-4"></i></button>`:''}
                            <button onclick="deleteInvoice(${inv.id})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            }).join('');

            const pg = document.getElementById('pagination');
            pg.classList.remove('hidden');
            document.getElementById('paginationInfo').textContent = `Showing ${invoices.length} of ${totalInvoices} invoices`;
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= lastPage;
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
        })
        .catch(err => {
            tbody.innerHTML = `<tr><td colspan="9" class="px-4 py-8 text-center text-red-500 text-sm">Error: ${esc(err.message)}</td></tr>`;
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
        });
}

function loadStats() {
    fetch('{{ route("admin.invoices.stats") }}', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const d = res.data;
            document.getElementById('stat-total').textContent       = d.total;
            document.getElementById('stat-draft').textContent       = d.draft;
            document.getElementById('stat-sent').textContent        = d.sent;
            document.getElementById('stat-paid').textContent        = d.paid;
            document.getElementById('stat-overdue').textContent     = d.overdue;
            document.getElementById('stat-total-value').textContent = fmt(d.total_value);
            document.getElementById('stat-paid-value').textContent  = fmt(d.paid_value);
            document.getElementById('stat-due-value').textContent   = fmt(d.due_value);
        });
}

function viewInvoice(id) {
    currentInvoiceId = id;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = '<p class="text-gray-400 text-sm text-center py-8">Loading...</p>';

    fetch(`/admin/invoices/${id}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(res => {
            if (!res.success) throw new Error(res.message);
            const inv = res.data;
            document.getElementById('detailTitle').textContent = inv.invoice_number;
            const items = (inv.items||[]).map(i => `
                <tr class="border-b border-gray-50">
                    <td class="py-2 pr-4 text-sm font-medium text-gray-900">${esc(i.name||i.product_name||'—')}</td>
                    <td class="py-2 pr-4 text-sm text-center">${i.quantity||1}</td>
                    <td class="py-2 pr-4 text-sm text-right">${fmt(i.unit_price||i.price||0)}</td>
                    <td class="py-2 text-sm text-right font-semibold">${fmt(i.total||((i.unit_price||0)*(i.quantity||1)))}</td>
                </tr>
            `).join('');

            document.getElementById('detailContent').innerHTML = `
            <div class="space-y-5">
                <div class="flex flex-wrap gap-2 items-center">
                    ${pill(inv.status, STATUS_COLORS[inv.status]||'gray')}
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">${esc(TYPE_LABELS[inv.type]||inv.type)}</span>
                    <span class="text-xs text-gray-400 ml-auto">Date: ${inv.invoice_date ? new Date(inv.invoice_date).toLocaleDateString('en-IN') : '—'}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bill To</p>
                        <p class="font-semibold text-gray-900">${esc(inv.customer_name)}</p>
                        <p class="text-sm text-gray-500">${esc(inv.customer_email)}</p>
                        ${inv.customer_phone?`<p class="text-sm text-gray-500">${esc(inv.customer_phone)}</p>`:''}
                        ${inv.customer_gstin?`<p class="text-xs text-gray-400 font-mono mt-1">GSTIN: ${esc(inv.customer_gstin)}</p>`:''}
                        ${inv.billing_address?`<p class="text-xs text-gray-500 mt-1">${esc(inv.billing_address)}</p>`:''}
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Payment</p>
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm"><span class="text-gray-500">Total</span><span class="font-bold">${fmt(inv.total_amount)}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500">Paid</span><span class="text-green-600 font-semibold">${fmt(inv.paid_amount)}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500">Due</span><span class="${inv.due_amount>0?'text-red-600':'text-green-600'} font-bold">${fmt(inv.due_amount)}</span></div>
                            ${inv.due_date?`<div class="flex justify-between text-sm"><span class="text-gray-500">Due Date</span><span>${new Date(inv.due_date).toLocaleDateString('en-IN')}</span></div>`:''}
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Items</p>
                    </div>
                    <div class="p-4">
                        <table class="w-full text-sm">
                            <thead><tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                <th class="pb-2 text-left">Description</th><th class="pb-2 text-center">Qty</th>
                                <th class="pb-2 text-right">Rate</th><th class="pb-2 text-right">Amount</th>
                            </tr></thead>
                            <tbody>${items||'<tr><td colspan="4" class="py-4 text-center text-gray-400 text-xs">No items</td></tr>'}</tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 space-y-1.5">
                    <div class="flex justify-between text-sm"><span class="text-gray-600">Subtotal</span><span>${fmt(inv.subtotal)}</span></div>
                    ${inv.discount_amount>0?`<div class="flex justify-between text-sm"><span class="text-gray-600">Discount</span><span class="text-red-600">-${fmt(inv.discount_amount)}</span></div>`:''}
                    ${inv.tax_amount>0?`<div class="flex justify-between text-sm"><span class="text-gray-600">Tax</span><span>${fmt(inv.tax_amount)}</span></div>`:''}
                    ${inv.shipping_amount>0?`<div class="flex justify-between text-sm"><span class="text-gray-600">Shipping</span><span>${fmt(inv.shipping_amount)}</span></div>`:''}
                    <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2"><span>Total</span><span class="text-[#0082C3]">${fmt(inv.total_amount)}</span></div>
                </div>
                ${inv.notes?`<div class="bg-blue-50 border border-blue-100 rounded-xl p-3"><p class="text-xs font-bold text-blue-700 mb-1">Notes</p><p class="text-sm text-blue-800">${esc(inv.notes)}</p></div>`:''}
                <div class="flex gap-2">
                    ${inv.status==='draft'?`<button onclick="markSent(${inv.id})" class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold">Mark as Sent</button>`:''}
                    ${inv.status==='sent'?`<button onclick="markPaid(${inv.id})" class="flex-1 px-3 py-2 text-sm bg-green-600 text-white rounded-xl hover:bg-green-700 font-semibold">Mark as Paid</button>`:''}
                    <button onclick="printInvoice()" class="flex-1 px-3 py-2 text-sm bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-semibold">🖨 Print</button>
                </div>
            </div>`;
        })
        .catch(err => {
            document.getElementById('detailContent').innerHTML = `<p class="text-red-500 text-sm text-center py-8">${esc(err.message)}</p>`;
        });
}

function markSent(id) {
    updateInvoiceStatus(id, 'sent');
}
function markPaid(id) {
    updateInvoiceStatus(id, 'paid');
}
function updateInvoiceStatus(id, status) {
    fetch(`/admin/invoices/${id}`, {
        method: 'PUT',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ status })
    }).then(r => r.json()).then(res => {
        if (res.success) { showToast('success', 'Invoice marked as ' + status); loadInvoices(); loadStats(); if (currentInvoiceId) viewInvoice(currentInvoiceId); }
        else showToast('error', res.message);
    });
}

function printInvoice() {
    if (!currentInvoiceId) return;
    window.open(`/admin/invoices/${currentInvoiceId}/print`, '_blank');
}

function deleteInvoice(id) {
    if (!confirm('Delete this invoice?')) return;
    fetch(`/admin/invoices/${id}`, {
        method: 'DELETE',
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(r => r.json()).then(res => {
        if (res.success) { showToast('success', 'Invoice deleted'); loadInvoices(); loadStats(); }
        else showToast('error', res.message);
    });
}

// ── Line items for create form ────────────────────────────────────
let lineItems = [];

function addLineItem() {
    lineItems.push({ name: '', quantity: 1, unit_price: 0, discount: 0 });
    renderLineItems();
}

function removeLineItem(idx) {
    lineItems.splice(idx, 1);
    renderLineItems();
}

function renderLineItems() {
    const container = document.getElementById('lineItemsList');
    if (!lineItems.length) {
        container.innerHTML = '<p class="text-xs text-gray-400 text-center py-3 border-2 border-dashed border-gray-200 rounded-lg">No items. Click "+ Add Item" to add.</p>';
        recalcInvoice();
        return;
    }
    container.innerHTML = lineItems.map((item, idx) => `
        <div class="grid grid-cols-12 gap-2 items-center bg-gray-50 rounded-xl p-3">
            <div class="col-span-5">
                <input type="text" placeholder="Item description" value="${esc(item.name)}" oninput="lineItems[${idx}].name=this.value"
                    class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
            </div>
            <div class="col-span-2">
                <input type="number" placeholder="Qty" value="${item.quantity}" min="1" oninput="lineItems[${idx}].quantity=parseInt(this.value)||1;recalcInvoice()"
                    class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0082C3] text-center">
            </div>
            <div class="col-span-3">
                <input type="number" placeholder="Price" value="${item.unit_price}" min="0" step="0.01" oninput="lineItems[${idx}].unit_price=parseFloat(this.value)||0;recalcInvoice()"
                    class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0082C3] text-right">
            </div>
            <div class="col-span-1 text-right text-xs font-semibold text-gray-700">
                ${fmt(item.unit_price * item.quantity)}
            </div>
            <div class="col-span-1 text-right">
                <button type="button" onclick="removeLineItem(${idx})" class="text-red-400 hover:text-red-600 p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    `).join('');
    recalcInvoice();
}

function recalcInvoice() {
    const subtotal  = lineItems.reduce((s, i) => s + (i.unit_price * i.quantity), 0);
    const tax       = parseFloat(document.getElementById('invTax')?.value||0);
    const shipping  = parseFloat(document.getElementById('invShipping')?.value||0);
    const discount  = parseFloat(document.getElementById('invDiscount')?.value||0);
    const total     = subtotal + tax + shipping - discount;
    document.getElementById('invSubtotal').textContent = fmt(subtotal);
    document.getElementById('invTotal').textContent    = fmt(total);
}

function openCreateModal() {
    lineItems = [];
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createInvoiceForm').reset();
    // Set today's date
    document.querySelector('[name="invoice_date"]').value = new Date().toISOString().split('T')[0];
    document.querySelector('[name="due_date"]').value = new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0];
    addLineItem(); // Start with one empty item
}
function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }
function closeDetailModal() { document.getElementById('detailModal').classList.add('hidden'); currentInvoiceId = null; }

function submitInvoice(e) {
    e.preventDefault();
    if (!lineItems.length || !lineItems.some(i => i.name)) { showToast('error', 'Add at least one item'); return; }

    const form = e.target;
    const fd   = new FormData(form);
    const data = Object.fromEntries(fd.entries());
    data.items = lineItems;

    const btn = document.getElementById('submitInvoiceBtn');
    btn.disabled = true; btn.textContent = 'Creating...';

    fetch('{{ route("admin.invoices.store") }}', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
    }).then(r => r.json()).then(res => {
        btn.disabled = false; btn.textContent = 'Create Invoice';
        if (res.success) {
            showToast('success', 'Invoice ' + res.data.invoice_number + ' created!');
            closeCreateModal();
            loadInvoices();
            loadStats();
        } else {
            showToast('error', res.message || 'Failed');
        }
    }).catch(err => {
        btn.disabled = false; btn.textContent = 'Create Invoice';
        showToast('error', err.message);
    });
}

document.addEventListener('DOMContentLoaded', () => { loadStats(); loadInvoices(); });
</script>
@endpush
