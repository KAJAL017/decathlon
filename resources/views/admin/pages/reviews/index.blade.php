@extends('admin.layouts.app')
@section('title', 'Reviews')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reviews Management</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage customer and admin reviews</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-5 gap-4">
        @foreach([
            ['id'=>'statTotal',    'label'=>'Total',    'color'=>'blue',   'icon'=>'star'],
            ['id'=>'statPending',  'label'=>'Pending',  'color'=>'yellow', 'icon'=>'clock'],
            ['id'=>'statApproved', 'label'=>'Approved', 'color'=>'green',  'icon'=>'circle-check'],
            ['id'=>'statRejected', 'label'=>'Rejected', 'color'=>'red',    'icon'=>'x-circle'],
            ['id'=>'statAvg',      'label'=>'Avg Rating','color'=>'purple', 'icon'=>'star'],
        ] as $s)
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-{{ $s['color'] }}-50 flex items-center justify-center flex-shrink-0">
                <i data-lucide="{{ $s['icon'] }}" class="w-5 h-5 text-{{ $s['color'] }}-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $s['label'] }}</p>
                <p class="text-xl font-bold text-gray-900" id="{{ $s['id'] }}">—</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button id="tab-website-btn" onclick="switchTab('website')"
                    class="flex-1 px-6 py-3.5 text-sm font-semibold text-[#0082C3] border-b-2 border-[#0082C3] bg-blue-50/50 transition-colors">
                🌐 Customer Reviews
            </button>
            <button id="tab-admin-btn" onclick="switchTab('admin')"
                    class="flex-1 px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                🛡️ Admin Added
            </button>
        </div>

        {{-- Filters --}}
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-[200px]">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input id="fSearch" type="text" placeholder="Search reviewer, product…"
                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                       oninput="debounceLoad()">
            </div>
            <select id="fStatus" onchange="load(1)"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="spam">Spam</option>
            </select>
            <select id="fRating" onchange="load(1)"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <option value="">All Ratings</option>
                @for($i=5;$i>=1;$i--)
                <option value="{{ $i }}">{{ $i }} ★</option>
                @endfor
            </select>
            <select id="fPerPage" onchange="load(1)"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <option value="15">15 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3"><input type="checkbox" id="chkAll" onchange="toggleAll(this)" class="w-4 h-4 text-[#0082C3] rounded border-gray-300"></th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reviewer</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Review</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
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

    {{-- Admin Add Review (shown only in admin tab) --}}
    <div id="adminAddSection" class="hidden bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Add Review Manually</h3>
        </div>
        <form id="addForm" class="px-6 py-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product <span class="text-red-500">*</span></label>
                    <select id="aProduct" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="">Select product…</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reviewer Name <span class="text-red-500">*</span></label>
                    <input id="aName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Customer name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="aEmail" type="email" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="customer@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating <span class="text-red-500">*</span></label>
                    <div class="flex gap-1" id="starPicker">
                        @for($i=1;$i<=5;$i++)
                        <button type="button" onclick="setRating({{ $i }})" data-star="{{ $i }}"
                                class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors">★</button>
                        @endfor
                    </div>
                    <input type="hidden" id="aRating" value="5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input id="aTitle" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Review title">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="aStatus" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Review Body <span class="text-red-500">*</span></label>
                    <textarea id="aBody" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Write the review…"></textarea>
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button type="button" onclick="submitReview()"
                        class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">
                    Add Review
                </button>
                <button type="button" onclick="resetAddForm()"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                    Reset
                </button>
            </div>
        </form>
    </div>

    {{-- Bulk bar --}}
    <div id="bulkBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-4 z-50">
        <span id="bulkCount" class="text-sm font-medium"></span>
        <select id="bulkAction" class="px-3 py-1.5 bg-gray-800 border border-gray-600 rounded-lg text-sm text-white">
            <option value="">Choose action</option>
            <option value="approve">Approve</option>
            <option value="reject">Reject</option>
            <option value="spam">Mark Spam</option>
            <option value="feature">Feature</option>
            <option value="unfeature">Unfeature</option>
            <option value="delete">Delete</option>
        </select>
        <button onclick="applyBulk()" class="px-4 py-1.5 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3]">Apply</button>
        <button onclick="clearSelection()" class="text-gray-400 hover:text-white">✕</button>
    </div>

</div>

{{-- Edit Modal --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBackdrop(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="modalBox" class="fixed right-0 top-0 h-full w-full max-w-xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Edit Review</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
            <input type="hidden" id="eId">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reviewer Name</label>
                    <input id="eName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="eEmail" type="email" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select id="eRating" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} ★</option>@endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="eStatus" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="spam">Spam</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input id="eTitle" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Review Body</label>
                    <textarea id="eBody" rows="4" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"></textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Reply</label>
                    <textarea id="eReply" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Write a reply to this review…"></textarea>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">Cancel</button>
            <button id="saveBtn" onclick="saveEdit()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] disabled:opacity-60">Save Changes</button>
        </div>
    </div>
</div>

<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium"></div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const BASE  = '/admin/reviews';
let searchTimer, checkedIds = new Set();
let currentSource = 'website'; // ← global, always defined
let isFirstLoad = true;

// ── API ──────────────────────────────────────────────────────────
async function api(url, method = 'GET', body = null) {
    const opts = { method, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } };
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

// ── Tab switch ───────────────────────────────────────────────────
function switchTab(source) {
    currentSource = source;
    const isAdmin = source === 'admin';

    document.getElementById('tab-website-btn').className = !isAdmin
        ? 'flex-1 px-6 py-3.5 text-sm font-semibold text-[#0082C3] border-b-2 border-[#0082C3] bg-blue-50/50 transition-colors'
        : 'flex-1 px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors';
    document.getElementById('tab-admin-btn').className = isAdmin
        ? 'flex-1 px-6 py-3.5 text-sm font-semibold text-[#0082C3] border-b-2 border-[#0082C3] bg-blue-50/50 transition-colors'
        : 'flex-1 px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors';

    document.getElementById('adminAddSection').classList.toggle('hidden', !isAdmin);
    load(1);
}

// ── Load & Render ────────────────────────────────────────────────
async function load(page = 1) {
    const params = new URLSearchParams({
        page,
        per_page: document.getElementById('fPerPage').value || 15,
        search:   document.getElementById('fSearch').value  || '',
        status:   document.getElementById('fStatus').value  || '',
        rating:   document.getElementById('fRating').value  || '',
        source:   currentSource,
    });
    for (const [k, v] of [...params]) { if (!v) params.delete(k); }

    document.getElementById('tBody').innerHTML = `<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;

    const data = await api(`${BASE}/list?${params}`);
    if (!data.success) {
        document.getElementById('tBody').innerHTML = `<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Error: ${data.message || 'unknown'}</td></tr>`;
        return;
    }

    renderTable(data.data);
    renderPagination(data.pagination, page);
    if (data.stats) renderStats(data.stats);
    if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
}

const STATUS_COLORS = {
    approved: 'bg-green-100 text-green-700',
    pending:  'bg-yellow-100 text-yellow-700',
    rejected: 'bg-red-100 text-red-700',
    spam:     'bg-gray-100 text-gray-600',
};

function renderTable(rows) {
    const tbody = document.getElementById('tBody');
    if (!rows.length) { tbody.innerHTML = `<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">No reviews found</td></tr>`; return; }
    tbody.innerHTML = rows.map(r => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-3.5"><input type="checkbox" class="row-chk w-4 h-4 text-[#0082C3] rounded border-gray-300" value="${r.id}" ${checkedIds.has(r.id)?'checked':''} onchange="onCheck(${r.id},this.checked)"></td>
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        ${esc(r.reviewer_name).charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${esc(r.reviewer_name)}</p>
                        <p class="text-xs text-gray-400">${esc(r.reviewer_email || '')}</p>
                    </div>
                </div>
            </td>
            <td class="px-5 py-3.5 text-sm text-gray-700 max-w-[140px] truncate">${esc(r.product?.name || '—')}</td>
            <td class="px-5 py-3.5">
                <span class="text-yellow-500 text-sm">${'★'.repeat(r.rating)}${'☆'.repeat(5-r.rating)}</span>
            </td>
            <td class="px-5 py-3.5 max-w-[200px]">
                <p class="text-sm font-medium text-gray-900 truncate">${esc(r.title || '')}</p>
                <p class="text-xs text-gray-400 truncate">${esc(r.body || '')}</p>
            </td>
            <td class="px-5 py-3.5">
                <button onclick="toggleStatus(${r.id})"
                        class="px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors ${STATUS_COLORS[r.status] || 'bg-gray-100 text-gray-600'}">
                    ${r.status}
                </button>
            </td>
            <td class="px-5 py-3.5 text-xs text-gray-500">${r.created_at ? new Date(r.created_at).toLocaleDateString('en-IN') : '—'}</td>
            <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    <button onclick="toggleFeatured(${r.id})" class="p-2 rounded-lg transition-colors ${r.featured ? 'text-yellow-500 hover:bg-yellow-50' : 'text-gray-400 hover:text-yellow-500 hover:bg-yellow-50'}" title="${r.featured ? 'Unfeature' : 'Feature'}">
                        <i data-lucide="star" class="w-4 h-4 ${r.featured ? 'fill-yellow-500' : ''}"></i>
                    </button>
                    <button onclick="openEdit(${r.id})" class="p-2 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <button onclick="del(${r.id},'${esc(r.reviewer_name).replace(/'/g,"&#39;")}')" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderStats(s) {
    document.getElementById('statTotal').textContent    = s.total;
    document.getElementById('statPending').textContent  = s.pending;
    document.getElementById('statApproved').textContent = s.approved;
    document.getElementById('statRejected').textContent = s.rejected;
    document.getElementById('statAvg').textContent      = s.avg_rating + ' ★';
}

function renderPagination(p, current) {
    const el = document.getElementById('pagination');
    if (p.last_page <= 1) { el.innerHTML = `<p class="text-sm text-gray-500">Showing ${p.total} reviews</p>`; return; }
    let pages = '';
    for (let i = 1; i <= p.last_page; i++) {
        if (i === 1 || i === p.last_page || Math.abs(i - current) <= 2)
            pages += `<button onclick="load(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium ${i===current?'bg-[#0082C3] text-white':'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        else if (Math.abs(i - current) === 3) pages += `<span class="px-1 text-gray-400">…</span>`;
    }
    el.innerHTML = `<p class="text-sm text-gray-500">Showing ${(current-1)*p.per_page+1}–${Math.min(current*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="load(${current-1})" ${current===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="load(${current+1})" ${current===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function debounceLoad() { clearTimeout(searchTimer); searchTimer = setTimeout(() => load(1), 400); }
function esc(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

// ── Actions ──────────────────────────────────────────────────────
async function toggleStatus(id) {
    const data = await api(`${BASE}/${id}/toggle-status`, 'POST');
    if (data.success) { toast('Status updated'); load(1); }
    else toast(data.message || 'Error', 'error');
}

async function toggleFeatured(id) {
    const data = await api(`${BASE}/${id}/toggle-featured`, 'POST');
    if (data.success) { toast(data.data.featured ? 'Marked as featured' : 'Removed from featured'); load(1); }
    else toast(data.message || 'Error', 'error');
}

function del(id, name) {
    showConfirmDialog(`Delete review by "${name}"? This cannot be undone.`, async () => {
        const data = await api(`${BASE}/${id}`, 'DELETE');
        if (data.success) { showSuccessDialog('Review deleted'); load(1); }
        else toast(data.message || 'Error', 'error');
    });
}

// ── Edit Modal ───────────────────────────────────────────────────
async function openEdit(id) {
    document.getElementById('modalTitle').textContent = 'Edit Review';
    showModal();
    const data = await api(`${BASE}/${id}`);
    if (!data.success) { toast('Failed to load', 'error'); closeModal(); return; }
    const r = data.data;
    document.getElementById('eId').value     = r.id;
    document.getElementById('eName').value   = r.reviewer_name;
    document.getElementById('eEmail').value  = r.reviewer_email || '';
    document.getElementById('eRating').value = r.rating;
    document.getElementById('eStatus').value = r.status;
    document.getElementById('eTitle').value  = r.title || '';
    document.getElementById('eBody').value   = r.body || '';
    document.getElementById('eReply').value  = r.admin_reply || '';
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

async function saveEdit() {
    const id = document.getElementById('eId').value;
    const btn = document.getElementById('saveBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        product_id:     null, // not changing product in edit
        reviewer_name:  document.getElementById('eName').value,
        reviewer_email: document.getElementById('eEmail').value,
        rating:         parseInt(document.getElementById('eRating').value),
        status:         document.getElementById('eStatus').value,
        title:          document.getElementById('eTitle').value,
        body:           document.getElementById('eBody').value,
    };

    // Save review update
    const data = await api(`${BASE}/${id}`, 'PUT', body);

    // Save reply if filled
    const reply = document.getElementById('eReply').value.trim();
    if (reply) {
        await api(`${BASE}/${id}/reply`, 'POST', { reply });
    }

    btn.disabled = false; btn.textContent = 'Save Changes';
    if (data.success) { closeModal(); showSuccessDialog('Review updated'); load(1); }
    else { const first = data.errors ? Object.values(data.errors)[0][0] : data.message; toast(first || 'Error', 'error'); }
}

// ── Add Review (Admin tab) ───────────────────────────────────────
let selectedRating = 5;
function setRating(n) {
    selectedRating = n;
    document.getElementById('aRating').value = n;
    document.querySelectorAll('.star-btn').forEach(b => {
        b.classList.toggle('text-yellow-400', parseInt(b.dataset.star) <= n);
        b.classList.toggle('text-gray-300',   parseInt(b.dataset.star) > n);
    });
}

async function loadProducts() {
    const r = await fetch('/admin/products/list?per_page=200', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    const data = await r.json();
    if (!data.success) return;
    const sel = document.getElementById('aProduct');
    sel.innerHTML = '<option value="">Select product…</option>' +
        data.data.map(p => `<option value="${p.id}">${esc(p.name)}</option>`).join('');
}

async function submitReview() {
    const productId = document.getElementById('aProduct').value;
    const name      = document.getElementById('aName').value.trim();
    const body      = document.getElementById('aBody').value.trim();
    if (!productId) { toast('Select a product', 'error'); return; }
    if (!name)      { toast('Reviewer name required', 'error'); return; }
    if (!body)      { toast('Review body required', 'error'); return; }

    const payload = {
        product_id:    productId,
        reviewer_name: name,
        reviewer_email:document.getElementById('aEmail').value,
        rating:        parseInt(document.getElementById('aRating').value) || 5,
        title:         document.getElementById('aTitle').value,
        body:          body,
        status:        document.getElementById('aStatus').value,
        source:        'admin',
    };

    const data = await api(BASE, 'POST', payload);
    if (data.success) { showSuccessDialog('Review added'); resetAddForm(); load(1); }
    else { const first = data.errors ? Object.values(data.errors)[0][0] : data.message; toast(first || 'Error', 'error'); }
}

function resetAddForm() {
    document.getElementById('aProduct').value = '';
    document.getElementById('aName').value    = '';
    document.getElementById('aEmail').value   = '';
    document.getElementById('aTitle').value   = '';
    document.getElementById('aBody').value    = '';
    document.getElementById('aStatus').value  = 'approved';
    setRating(5);
}

// ── Bulk ─────────────────────────────────────────────────────────
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
        showConfirmDialog(`Delete ${checkedIds.size} review(s)?`, async () => {
            const data = await api(`${BASE}/bulk-action`, 'POST', { action, ids: [...checkedIds] });
            if (data.success) { showSuccessDialog(data.message); clearSelection(); load(1); }
            else toast(data.message || 'Error', 'error');
        });
    } else {
        const data = await api(`${BASE}/bulk-action`, 'POST', { action, ids: [...checkedIds] });
        if (data.success) { toast(data.message); clearSelection(); load(1); }
        else toast(data.message || 'Error', 'error');
    }
}

// ── Init ─────────────────────────────────────────────────────────
setRating(5);
loadProducts();
load(1);
</script>
@endpush
