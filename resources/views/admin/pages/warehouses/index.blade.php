@extends('admin.layouts.app')
@section('title', 'Warehouses')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Warehouses</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your storage locations and fulfillment centers</p>
        </div>
        <button onclick="openAdd()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Warehouse
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4">
        @foreach([
            ['id'=>'statTotal',   'label'=>'Total',    'color'=>'blue',   'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['id'=>'statActive',  'label'=>'Active',   'color'=>'green',  'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['id'=>'statDefault', 'label'=>'Default',  'color'=>'purple', 'icon'=>'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
            ['id'=>'statReturns', 'label'=>'Accepts Returns', 'color'=>'yellow', 'icon'=>'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6'],
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
            <input id="fSearch" type="text" placeholder="Search warehouses…"
                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                   oninput="debounceLoad()">
        </div>
        <select id="fType" onchange="loadWarehouses(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Types</option>
            <option value="main">Main</option>
            <option value="regional">Regional</option>
            <option value="dropship">Dropship</option>
            <option value="virtual">Virtual</option>
        </select>
        <select id="fStatus" onchange="loadWarehouses(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <select id="fPerPage" onchange="loadWarehouses(1)"
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
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Warehouse</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Processing</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="tBody" class="divide-y divide-gray-100">
                    <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>
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

{{-- ══════════ MODAL ══════════ --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBackdrop(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="modalBox"
         class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Warehouse</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="px-6 py-2 border-b border-gray-200 flex gap-1">
            @foreach(['basic'=>'Basic Info','address'=>'Address','settings'=>'Settings'] as $t=>$label)
            <button id="mtab-{{$t}}" onclick="mTab('{{$t}}')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors text-gray-500 hover:bg-gray-100">
                {{ $label }}
            </button>
            @endforeach
        </div>

        <form id="wForm" class="flex-1 overflow-y-auto">
            <input type="hidden" id="wId">

            {{-- Tab: Basic --}}
            <div id="mt-basic" class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse Name <span class="text-red-500">*</span></label>
                        <input id="wName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. Mumbai Central Warehouse" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                        <input id="wCode" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono uppercase" placeholder="MUM-01" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select id="wType" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="main">Main</option>
                            <option value="regional">Regional</option>
                            <option value="dropship">Dropship</option>
                            <option value="virtual">Virtual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                        <input id="wContactName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Manager name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                        <input id="wContactPhone" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="+91 98765 43210">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                        <input id="wContactEmail" type="email" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="warehouse@decathlon.com">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="wNotes" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Internal notes about this warehouse"></textarea>
                    </div>
                </div>
            </div>

            {{-- Tab: Address --}}
            <div id="mt-address" class="px-6 py-5 space-y-4" style="display:none">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                        <input id="wAddr1" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Building, Street">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                        <input id="wAddr2" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Area, Landmark">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input id="wCity" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Mumbai">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input id="wState" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Maharashtra">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pincode</label>
                        <input id="wPincode" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="400001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input id="wCountry" type="text" value="India" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input id="wLat" type="number" step="0.0000001" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="19.0760">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input id="wLng" type="number" step="0.0000001" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="72.8777">
                    </div>
                </div>
            </div>

            {{-- Tab: Settings --}}
            <div id="mt-settings" class="px-6 py-5 space-y-4" style="display:none">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Processing Days</label>
                        <input id="wProcessing" type="number" min="0" max="30" value="1" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <p class="text-xs text-gray-400 mt-1">Days to process an order from this warehouse</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="wStatus" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-span-2 space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <input id="wDefault" type="checkbox" class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Set as Default Warehouse</p>
                                <p class="text-xs text-gray-400">Orders will be fulfilled from this warehouse by default</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <input id="wReturns" type="checkbox" checked class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Accepts Returns</p>
                                <p class="text-xs text-gray-400">This warehouse can receive returned items</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </form>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
            <button id="saveBtn" onclick="save()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">Save Warehouse</button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium transition-all"></div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const BASE  = '/admin/warehouses';
let searchTimer, checkedIds = new Set(), allRows = [];

// ── API ──────────────────────────────────────────────────────────
async function api(url, method = 'GET', body = null) {
    const opts = {
        method,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    };
    if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
    const r = await fetch(url, opts);
    const ct = r.headers.get('content-type') || '';
    if (!ct.includes('json')) { window.location.reload(); return { success: false }; }
    return r.json();
}

// ── Toast ────────────────────────────────────────────────────────
function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-yellow-500'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}

// ── Dialogs ──────────────────────────────────────────────────────
async function showConfirmDialog(msg, cb) {
    const confirmed = await Dialog.confirm({
        title: 'Are you sure?',
        message: msg,
        type: 'danger',
        confirmText: 'Confirm',
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

// ── Load & Render ────────────────────────────────────────────────
async function loadWarehouses(page = 1) {
    const params = new URLSearchParams({
        page,
        per_page: document.getElementById('fPerPage').value || 15,
        search:   document.getElementById('fSearch').value  || '',
        type:     document.getElementById('fType').value    || '',
        status:   document.getElementById('fStatus').value  || '',
    });
    for (const [k, v] of [...params]) { if (!v) params.delete(k); }

    document.getElementById('tBody').innerHTML =
        `<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;

    const data = await api(`${BASE}/list?${params}`);
    if (!data.success) {
        document.getElementById('tBody').innerHTML =
            `<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Error: ${data.message || 'unknown'}</td></tr>`;
        return;
    }
    allRows = data.data;
    renderTable(data.data);
    renderPagination(data.pagination, page);
    renderStats(data.data, data.pagination.total);
}

const TYPE_COLORS = { main: 'bg-blue-100 text-blue-700', regional: 'bg-green-100 text-green-700', dropship: 'bg-purple-100 text-purple-700', virtual: 'bg-gray-100 text-gray-600' };

function renderTable(rows) {
    const tbody = document.getElementById('tBody');
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">No warehouses found</td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(w => `
        <tr class="hover:bg-gray-50 transition-colors" data-id="${w.id}">
            <td class="px-5 py-3.5">
                <input type="checkbox" class="row-chk w-4 h-4 text-[#0082C3] rounded border-gray-300"
                       value="${w.id}" ${checkedIds.has(w.id) ? 'checked' : ''}
                       onchange="onCheck(${w.id}, this.checked)">
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900">${w.name}</p>
                            ${w.is_default ? '<span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">Default</span>' : ''}
                        </div>
                        <p class="text-xs text-gray-400 font-mono">${w.code}</p>
                    </div>
                </div>
            </td>
            <td class="px-5 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${TYPE_COLORS[w.type] || 'bg-gray-100 text-gray-600'}">
                    ${w.type.charAt(0).toUpperCase() + w.type.slice(1)}
                </span>
            </td>
            <td class="px-5 py-3.5">
                <p class="text-sm text-gray-700">${[w.city, w.state].filter(Boolean).join(', ') || '—'}</p>
                <p class="text-xs text-gray-400">${w.country || ''}</p>
            </td>
            <td class="px-5 py-3.5">
                <p class="text-sm text-gray-700">${w.contact_name || '—'}</p>
                <p class="text-xs text-gray-400">${w.contact_phone || ''}</p>
            </td>
            <td class="px-5 py-3.5 text-sm text-gray-700">${w.processing_days} day${w.processing_days !== 1 ? 's' : ''}</td>
            <td class="px-5 py-3.5">
                <button onclick="toggleStatus(${w.id})"
                        class="px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors cursor-pointer ${w.is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200'}">
                    ${w.is_active ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    ${!w.is_default ? `<button onclick="setDefault(${w.id}, '${w.name.replace(/'/g,"&#39;")}')" class="p-2 rounded-lg text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 transition-colors" title="Set as Default">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </button>` : ''}
                    <button onclick="openEdit(${w.id})" class="p-2 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="del(${w.id}, '${w.name.replace(/'/g,"&#39;")}')" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderStats(rows, total) {
    document.getElementById('statTotal').textContent   = total;
    document.getElementById('statActive').textContent  = rows.filter(w => w.is_active).length;
    document.getElementById('statDefault').textContent = rows.filter(w => w.is_default).length;
    document.getElementById('statReturns').textContent = rows.filter(w => w.accepts_returns).length;
}

function renderPagination(p, current) {
    const el = document.getElementById('pagination');
    if (p.last_page <= 1) { el.innerHTML = `<p class="text-sm text-gray-500">Showing ${p.total} warehouses</p>`; return; }
    let pages = '';
    for (let i = 1; i <= p.last_page; i++) {
        if (i === 1 || i === p.last_page || Math.abs(i - current) <= 2) {
            pages += `<button onclick="loadWarehouses(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors ${i === current ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        } else if (Math.abs(i - current) === 3) { pages += `<span class="px-1 text-gray-400">…</span>`; }
    }
    el.innerHTML = `
        <p class="text-sm text-gray-500">Showing ${(current-1)*p.per_page+1}–${Math.min(current*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="loadWarehouses(${current-1})" ${current===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="loadWarehouses(${current+1})" ${current===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function debounceLoad() { clearTimeout(searchTimer); searchTimer = setTimeout(() => loadWarehouses(1), 400); }

// ── Modal ────────────────────────────────────────────────────────
function openAdd() {
    document.getElementById('modalTitle').textContent = 'Add Warehouse';
    document.getElementById('wId').value = '';
    document.getElementById('wForm').reset();
    document.getElementById('wProcessing').value = 1;
    document.getElementById('wCountry').value = 'India';
    document.getElementById('wReturns').checked = true;
    mTab('basic');
    showModal();
}

async function openEdit(id) {
    document.getElementById('modalTitle').textContent = 'Edit Warehouse';
    mTab('basic');
    showModal();
    const data = await api(`${BASE}/${id}`);
    if (!data.success) { toast('Failed to load', 'error'); closeModal(); return; }
    const w = data.data;
    document.getElementById('wId').value            = w.id;
    document.getElementById('wName').value          = w.name;
    document.getElementById('wCode').value          = w.code;
    document.getElementById('wType').value          = w.type;
    document.getElementById('wContactName').value   = w.contact_name || '';
    document.getElementById('wContactPhone').value  = w.contact_phone || '';
    document.getElementById('wContactEmail').value  = w.contact_email || '';
    document.getElementById('wNotes').value         = w.notes || '';
    document.getElementById('wAddr1').value         = w.address_line1 || '';
    document.getElementById('wAddr2').value         = w.address_line2 || '';
    document.getElementById('wCity').value          = w.city || '';
    document.getElementById('wState').value         = w.state || '';
    document.getElementById('wPincode').value       = w.pincode || '';
    document.getElementById('wCountry').value       = w.country || 'India';
    document.getElementById('wLat').value           = w.latitude || '';
    document.getElementById('wLng').value           = w.longitude || '';
    document.getElementById('wProcessing').value    = w.processing_days || 1;
    document.getElementById('wStatus').value        = w.is_active ? '1' : '0';
    document.getElementById('wDefault').checked     = !!w.is_default;
    document.getElementById('wReturns').checked     = !!w.accepts_returns;
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

function mTab(name) {
    ['basic', 'address', 'settings'].forEach(t => {
        document.getElementById(`mt-${t}`).style.display = t === name ? 'block' : 'none';
        document.getElementById(`mtab-${t}`).className =
            `px-4 py-2 text-sm font-medium rounded-lg transition-colors ${t === name ? 'bg-[#0082C3] text-white' : 'text-gray-500 hover:bg-gray-100'}`;
    });
}

// ── Save ─────────────────────────────────────────────────────────
async function save() {
    const name = document.getElementById('wName').value.trim();
    const code = document.getElementById('wCode').value.trim();
    if (!name) { toast('Name is required', 'error'); mTab('basic'); return; }
    if (!code) { toast('Code is required', 'error'); mTab('basic'); return; }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const id = document.getElementById('wId').value;
    const body = {
        name, code,
        type:            document.getElementById('wType').value,
        contact_name:    document.getElementById('wContactName').value,
        contact_phone:   document.getElementById('wContactPhone').value,
        contact_email:   document.getElementById('wContactEmail').value,
        notes:           document.getElementById('wNotes').value,
        address_line1:   document.getElementById('wAddr1').value,
        address_line2:   document.getElementById('wAddr2').value,
        city:            document.getElementById('wCity').value,
        state:           document.getElementById('wState').value,
        pincode:         document.getElementById('wPincode').value,
        country:         document.getElementById('wCountry').value,
        latitude:        document.getElementById('wLat').value || null,
        longitude:       document.getElementById('wLng').value || null,
        processing_days: parseInt(document.getElementById('wProcessing').value) || 1,
        is_active:       document.getElementById('wStatus').value === '1',
        is_default:      document.getElementById('wDefault').checked,
        accepts_returns: document.getElementById('wReturns').checked,
    };

    const data = await api(id ? `${BASE}/${id}` : BASE, id ? 'PUT' : 'POST', body);
    btn.disabled = false; btn.textContent = 'Save Warehouse';

    if (data.success) {
        closeModal();
        showSuccessDialog(data.message || 'Warehouse saved!');
        loadWarehouses(1);
    } else {
        const first = data.errors ? Object.values(data.errors)[0][0] : data.message;
        toast(first || 'Error', 'error');
    }
}

// ── Actions ──────────────────────────────────────────────────────
async function toggleStatus(id) {
    const data = await api(`${BASE}/${id}/toggle-status`, 'POST');
    if (data.success) { toast('Status updated'); loadWarehouses(1); }
    else toast(data.message || 'Error', 'error');
}

async function setDefault(id, name) {
    const data = await api(`${BASE}/${id}/set-default`, 'POST');
    if (data.success) { showSuccessDialog(data.message); loadWarehouses(1); }
    else toast(data.message || 'Error', 'error');
}

function del(id, name) {
    showConfirmDialog(`Delete "${name}"? This cannot be undone.`, async () => {
        const data = await api(`${BASE}/${id}`, 'DELETE');
        if (data.success) { showSuccessDialog('Warehouse deleted'); loadWarehouses(1); }
        else toast(data.message || 'Error', 'error');
    });
}

// ── Bulk ─────────────────────────────────────────────────────────
function onCheck(id, checked) {
    checked ? checkedIds.add(id) : checkedIds.delete(id);
    updateBulkBar();
}
function toggleAll(el) {
    document.querySelectorAll('.row-chk').forEach(c => { c.checked = el.checked; onCheck(parseInt(c.value), el.checked); });
}
function updateBulkBar() {
    const bar = document.getElementById('bulkBar');
    if (checkedIds.size > 0) {
        bar.classList.remove('hidden');
        document.getElementById('bulkCount').textContent = `${checkedIds.size} selected`;
    } else { bar.classList.add('hidden'); }
}
function clearSelection() {
    checkedIds.clear();
    document.querySelectorAll('.row-chk, #chkAll').forEach(c => c.checked = false);
    updateBulkBar();
}
async function applyBulk() {
    const action = document.getElementById('bulkAction').value;
    if (!action || !checkedIds.size) return;
    if (action === 'delete') {
        showConfirmDialog(`Delete ${checkedIds.size} warehouse(s)?`, async () => {
            const data = await api(`${BASE}/bulk-action`, 'POST', { action, ids: [...checkedIds] });
            if (data.success) { showSuccessDialog('Bulk action applied'); clearSelection(); loadWarehouses(1); }
            else toast(data.message || 'Error', 'error');
        });
    } else {
        const data = await api(`${BASE}/bulk-action`, 'POST', { action, ids: [...checkedIds] });
        if (data.success) { toast('Done'); clearSelection(); loadWarehouses(1); }
        else toast(data.message || 'Error', 'error');
    }
}

// ── Init ─────────────────────────────────────────────────────────
loadWarehouses(1);
</script>
@endpush
