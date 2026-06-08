@extends('admin.layouts.app')
@section('title', 'Customers')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your customer accounts</p>
        </div>
        <button onclick="openAdd()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Customer
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['id'=>'statTotal',    'label'=>'Total Customers', 'color'=>'blue',  'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['id'=>'statActive',   'label'=>'Active',          'color'=>'green', 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['id'=>'statInactive', 'label'=>'Inactive',        'color'=>'red',   'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $s)
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-{{ $s['color'] }}-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-{{ $s['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $s['label'] }}</p>
                <p class="text-xl font-bold text-gray-900" id="{{ $s['id'] }}">—</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[220px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="fSearch" type="text" placeholder="Search by name or email…"
                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                   oninput="debounceLoad()">
        </div>
        <select id="fStatus" onchange="loadCustomers(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <select id="fPerPage" onchange="loadCustomers(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="15">15 / page</option>
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3">
                            <input type="checkbox" id="chkAll" onchange="toggleAll(this)"
                                   class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Login</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="tBody" class="divide-y divide-gray-100">
                    <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between"></div>
    </div>

    {{-- Bulk bar --}}
    <div id="bulkBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-4 z-50">
        <span id="bulkCount" class="text-sm font-medium"></span>
        <select id="bulkAction" class="px-3 py-1.5 bg-gray-800 border border-gray-600 rounded-lg text-sm text-white">
            <option value="">Choose action</option>
            <option value="activate">Activate</option>
            <option value="deactivate">Deactivate</option>
            <option value="delete">Delete</option>
        </select>
        <button onclick="applyBulk()" class="px-4 py-1.5 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3]">Apply</button>
        <button onclick="clearSelection()" class="text-gray-400 hover:text-white">✕</button>
    </div>

</div>

{{-- Modal --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBackdrop(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="modalBox"
         class="fixed right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Customer</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="cForm" class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
            <input type="hidden" id="cId">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input id="cName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="John Doe" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input id="cEmail" type="email" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="customer@example.com" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" id="passLabel">Password <span class="text-red-500">*</span></label>
                <input id="cPassword" type="password" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Min 8 characters">
                <p id="passHint" class="text-xs text-gray-400 mt-1 hidden">Leave blank to keep current password</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="cStatus" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </form>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
            <button id="saveBtn" onclick="save()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">Save Customer</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const BASE  = '/admin/customers';
let searchTimer, checkedIds = new Set();

async function api(url, method = 'GET', body = null) {
    const opts = { method, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } };
    if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
    const r = await fetch(url, opts);
    const ct = r.headers.get('content-type') || '';
    if (!ct.includes('json')) { window.location.reload(); return { success: false }; }
    return r.json();
}

function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    if (!el) {
        Toastify({ text: msg, style: { background: type === 'success' ? "#10b981" : "#ef4444" } }).showToast();
        return;
    }
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-yellow-500'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}

async function showConfirmDialog(msg, cb) {
    const confirmed = await Dialog.confirm({
        title: 'Are you sure?',
        message: msg,
        type: 'danger',
        confirmText: 'Delete',
        cancelText: 'Cancel'
    });
    if (confirmed) cb();
}

function showSuccessDialog(msg) {
    Dialog.alert({
        title: 'Success!',
        message: msg,
        type: 'success',
        confirmText: 'Great'
    });
}

async function loadCustomers(page = 1) {
    const params = new URLSearchParams({
        page,
        per_page: document.getElementById('fPerPage').value || 15,
        search:   document.getElementById('fSearch').value  || '',
        status:   document.getElementById('fStatus').value  || '',
    });
    for (const [k, v] of [...params]) { if (!v) params.delete(k); }

    document.getElementById('tBody').innerHTML = `<tr><td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;

    const data = await api(`${BASE}/list?${params}`);
    if (!data.success) {
        document.getElementById('tBody').innerHTML = `<tr><td colspan="7" class="px-5 py-12 text-center text-red-500 text-sm">Error: ${data.message || 'unknown'}</td></tr>`;
        return;
    }
    renderTable(data.data);
    renderPagination(data.pagination, page);
    renderStats(data.data, data.pagination.total);
}

function renderTable(rows) {
    const tbody = document.getElementById('tBody');
    if (!rows.length) { tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">No customers found</td></tr>`; return; }
    tbody.innerHTML = rows.map(c => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-3.5"><input type="checkbox" class="row-chk w-4 h-4 text-[#0082C3] rounded border-gray-300" value="${c.id}" ${checkedIds.has(c.id)?'checked':''} onchange="onCheck(${c.id},this.checked)"></td>
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        ${c.name.charAt(0).toUpperCase()}
                    </div>
                    <p class="text-sm font-semibold text-gray-900">${c.name}</p>
                </div>
            </td>
            <td class="px-5 py-3.5 text-sm text-gray-600">${c.email}</td>
            <td class="px-5 py-3.5 text-sm text-gray-500">${c.last_login || 'Never'}</td>
            <td class="px-5 py-3.5 text-sm text-gray-500">${c.created_at}</td>
            <td class="px-5 py-3.5">
                <button onclick="toggleStatus(${c.id})" class="px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors ${c.is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200'}">
                    ${c.is_active ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    <button onclick="openEdit(${c.id})" class="p-2 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="del(${c.id},'${c.name.replace(/'/g,"&#39;")}')" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderStats(rows, total) {
    document.getElementById('statTotal').textContent    = total;
    document.getElementById('statActive').textContent   = rows.filter(c => c.is_active).length;
    document.getElementById('statInactive').textContent = rows.filter(c => !c.is_active).length;
}

function renderPagination(p, current) {
    const el = document.getElementById('pagination');
    if (p.last_page <= 1) { el.innerHTML = `<p class="text-sm text-gray-500">Showing ${p.total} customers</p>`; return; }
    let pages = '';
    for (let i = 1; i <= p.last_page; i++) {
        if (i === 1 || i === p.last_page || Math.abs(i - current) <= 2)
            pages += `<button onclick="loadCustomers(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium ${i===current?'bg-[#0082C3] text-white':'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        else if (Math.abs(i - current) === 3) pages += `<span class="px-1 text-gray-400">…</span>`;
    }
    el.innerHTML = `<p class="text-sm text-gray-500">Showing ${(current-1)*p.per_page+1}–${Math.min(current*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="loadCustomers(${current-1})" ${current===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="loadCustomers(${current+1})" ${current===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function debounceLoad() { clearTimeout(searchTimer); searchTimer = setTimeout(() => loadCustomers(1), 400); }

function openAdd() {
    document.getElementById('modalTitle').textContent = 'Add Customer';
    document.getElementById('cId').value = '';
    document.getElementById('cForm').reset();
    document.getElementById('passLabel').innerHTML = 'Password <span class="text-red-500">*</span>';
    document.getElementById('passHint').classList.add('hidden');
    showModal();
}
async function openEdit(id) {
    document.getElementById('modalTitle').textContent = 'Edit Customer';
    document.getElementById('passLabel').innerHTML = 'Password';
    document.getElementById('passHint').classList.remove('hidden');
    showModal();
    const data = await api(`${BASE}/${id}`);
    if (!data.success) { toast('Failed to load', 'error'); closeModal(); return; }
    const c = data.data;
    document.getElementById('cId').value     = c.id;
    document.getElementById('cName').value   = c.name;
    document.getElementById('cEmail').value  = c.email;
    document.getElementById('cPassword').value = '';
    document.getElementById('cStatus').value = c.is_active ? '1' : '0';
}
function showModal() {
    document.getElementById('modal').classList.remove('hidden');
    requestAnimationFrame(() => { document.getElementById('modalBox').style.transform = 'translateX(0)'; });
}
function closeModal() {
    document.getElementById('modalBox').style.transform = 'translateX(100%)';
    setTimeout(() => document.getElementById('modal').classList.add('hidden'), 420);
}
function onBackdrop(e) { if (e.target.id === 'modal') closeModal(); }

async function save() {
    const name  = document.getElementById('cName').value.trim();
    const email = document.getElementById('cEmail').value.trim();
    const pass  = document.getElementById('cPassword').value;
    const id    = document.getElementById('cId').value;
    if (!name)  { toast('Name is required', 'error'); return; }
    if (!email) { toast('Email is required', 'error'); return; }
    if (!id && !pass) { toast('Password is required', 'error'); return; }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = { name, email, is_active: document.getElementById('cStatus').value === '1' };
    if (pass) body.password = pass;

    const data = await api(id ? `${BASE}/${id}` : BASE, id ? 'PUT' : 'POST', body);
    btn.disabled = false; btn.textContent = 'Save Customer';

    if (data.success) { closeModal(); showSuccessDialog(data.message || 'Saved!'); loadCustomers(1); }
    else { const first = data.errors ? Object.values(data.errors)[0][0] : data.message; toast(first || 'Error', 'error'); }
}

async function toggleStatus(id) {
    const data = await api(`${BASE}/${id}/toggle-status`, 'POST');
    if (data.success) { toast('Status updated'); loadCustomers(1); }
    else toast(data.message || 'Error', 'error');
}

function del(id, name) {
    showConfirmDialog(`Delete customer "${name}"? This cannot be undone.`, async () => {
        const data = await api(`${BASE}/${id}`, 'DELETE');
        if (data.success) { showSuccessDialog('Customer deleted'); loadCustomers(1); }
        else toast(data.message || 'Error', 'error');
    });
}

function onCheck(id, checked) { checked ? checkedIds.add(id) : checkedIds.delete(id); updateBulkBar(); }
function toggleAll(el) { document.querySelectorAll('.row-chk').forEach(c => { c.checked = el.checked; onCheck(parseInt(c.value), el.checked); }); }
function updateBulkBar() {
    const bar = document.getElementById('bulkBar');
    if (checkedIds.size > 0) { bar.classList.remove('hidden'); document.getElementById('bulkCount').textContent = `${checkedIds.size} selected`; }
    else bar.classList.add('hidden');
}
function clearSelection() { checkedIds.clear(); document.querySelectorAll('.row-chk, #chkAll').forEach(c => c.checked = false); updateBulkBar(); }
async function applyBulk() {
    const action = document.getElementById('bulkAction').value;
    if (!action || !checkedIds.size) return;
    if (action === 'delete') {
        showConfirmDialog(`Delete ${checkedIds.size} customer(s)?`, async () => {
            const data = await api(`${BASE}/bulk-action`, 'POST', { action, ids: [...checkedIds] });
            if (data.success) { showSuccessDialog('Done'); clearSelection(); loadCustomers(1); }
            else toast(data.message || 'Error', 'error');
        });
    } else {
        const data = await api(`${BASE}/bulk-action`, 'POST', { action, ids: [...checkedIds] });
        if (data.success) { toast('Done'); clearSelection(); loadCustomers(1); }
        else toast(data.message || 'Error', 'error');
    }
}

loadCustomers(1);
</script>
@endpush
