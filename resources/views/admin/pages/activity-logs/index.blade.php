@extends('admin.layouts.app')
@section('title', 'Activity Logs')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
            <p class="text-sm text-gray-500 mt-0.5">Track all admin actions across the system</p>
        </div>
        <button onclick="exportLogs()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export CSV
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4">
        @foreach([
            ['id'=>'statTotal',  'label'=>'Total Logs',    'color'=>'blue',   'icon'=>'file-text'],
            ['id'=>'statToday',  'label'=>'Today',         'color'=>'green',  'icon'=>'calendar'],
            ['id'=>'statUsers',  'label'=>'Active Admins', 'color'=>'purple', 'icon'=>'users'],
            ['id'=>'statModules','label'=>'Modules',       'color'=>'orange', 'icon'=>'layout-grid'],
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

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px]">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input id="fSearch" type="text" placeholder="Search action, module, description…"
                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                   oninput="debounceLoad()">
        </div>
        <select id="fModule" onchange="load(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Modules</option>
        </select>
        <select id="fAction" onchange="load(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Actions</option>
            @foreach(['created','updated','deleted','status_changed','bulk_action','replied','login','logout'] as $a)
            <option value="{{ $a }}">{{ ucfirst($a) }}</option>
            @endforeach
        </select>
        <input id="fDateFrom" type="date" onchange="load(1)"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <input id="fDateTo" type="date" onchange="load(1)"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <select id="fPerPage" onchange="load(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
            <option value="100">100 / page</option>
        </select>
        <button onclick="clearFilters()" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
            Clear
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">Time</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Admin</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Module</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody id="tBody" class="divide-y divide-gray-100">
                    <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between"></div>
    </div>

</div>

{{-- Detail Modal --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="if(event.target.id==='modal') closeModal()">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="modalBox" class="fixed right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-900">Activity Detail</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div id="modalContent" class="flex-1 overflow-y-auto px-6 py-5">
            <p class="text-gray-400 text-sm">Loading…</p>
        </div>
    </div>
</div>

<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium"></div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let searchTimer;
let isFirstLoad = true;

// ── Action colors ────────────────────────────────────────────────
const ACTION_COLORS = {
    created:        'bg-green-100 text-green-700',
    updated:        'bg-blue-100 text-blue-700',
    deleted:        'bg-red-100 text-red-700',
    status_changed: 'bg-yellow-100 text-yellow-700',
    bulk_action:    'bg-purple-100 text-purple-700',
    replied:        'bg-teal-100 text-teal-700',
    login:          'bg-indigo-100 text-indigo-700',
    logout:         'bg-gray-100 text-gray-600',
};

const ACTION_ICONS = {
    created:        'plus',
    updated:        'pencil',
    deleted:        'trash-2',
    status_changed: 'arrow-right-left',
    bulk_action:    'list',
    default:        'info',
};

// ── Load & Render ────────────────────────────────────────────────
async function load(page = 1) {
    const params = new URLSearchParams({
        page,
        per_page:  document.getElementById('fPerPage').value  || 25,
        search:    document.getElementById('fSearch').value   || '',
        module:    document.getElementById('fModule').value   || '',
        action:    document.getElementById('fAction').value   || '',
        date_from: document.getElementById('fDateFrom').value || '',
        date_to:   document.getElementById('fDateTo').value   || '',
    });
    for (const [k, v] of [...params]) { if (!v) params.delete(k); }

    document.getElementById('tBody').innerHTML =
        `<tr><td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;

    const r = await fetch(`/admin/activity-logs/list?${params}`, {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await r.json();
    if (!data.success) {
        document.getElementById('tBody').innerHTML =
            `<tr><td colspan="7" class="px-5 py-12 text-center text-red-500 text-sm">Error: ${data.message || 'unknown'}</td></tr>`;
        return;
    }

    renderTable(data.data);
    renderPagination(data.pagination, page);
    loadStats(data.data, data.pagination.total);
    populateModuleFilter(data.data);
    if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
}

function renderTable(rows) {
    const tbody = document.getElementById('tBody');
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">No activity logs found</td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(log => {
        const actionColor = ACTION_COLORS[log.action] || 'bg-gray-100 text-gray-600';
        const actionIcon  = ACTION_ICONS[log.action]  || ACTION_ICONS.default;
        const time = new Date(log.created_at);
        const timeStr = time.toLocaleString('en-IN', { day:'numeric', month:'short', hour:'2-digit', minute:'2-digit' });
        const userName = log.user?.name || 'System';
        const userInitial = userName.charAt(0).toUpperCase();

        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-3.5">
                <p class="text-xs font-medium text-gray-700">${timeStr}</p>
                <p class="text-xs text-gray-400">${time.toLocaleDateString('en-IN', {year:'numeric'})}</p>
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        ${userInitial}
                    </div>
                    <span class="text-sm text-gray-700">${esc(userName)}</span>
                </div>
            </td>
            <td class="px-5 py-3.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ${actionColor}">
                    <i data-lucide="${actionIcon}" class="w-3 h-3"></i>
                    ${esc(log.action)}
                </span>
            </td>
            <td class="px-5 py-3.5">
                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded font-mono">
                    ${esc(log.module || '—')}
                </span>
            </td>
            <td class="px-5 py-3.5 max-w-xs">
                <p class="text-sm text-gray-700 truncate" title="${esc(log.description || '')}">
                    ${esc(log.description || '—')}
                </p>
            </td>
            <td class="px-5 py-3.5 text-xs text-gray-400 font-mono">${esc(log.ip_address || '—')}</td>
            <td class="px-5 py-3.5 text-right">
                <button onclick="showDetail(${log.id})"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="View Details">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </td>
        </tr>`;
    }).join('');
}

function loadStats(rows, total) {
    document.getElementById('statTotal').textContent = total.toLocaleString();

    // Today count — fetch separately
    const today = new Date().toISOString().split('T')[0];
    fetch(`/admin/activity-logs/list?date_from=${today}&date_to=${today}&per_page=1`, {
        credentials: 'same-origin', headers: { 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => {
        if (d.success) document.getElementById('statToday').textContent = d.pagination.total.toLocaleString();
    });

    // Unique modules
    const modules = [...new Set(rows.map(r => r.module).filter(Boolean))];
    document.getElementById('statModules').textContent = modules.length;

    // Unique users
    const users = [...new Set(rows.map(r => r.user?.name).filter(Boolean))];
    document.getElementById('statUsers').textContent = users.length;
}

let modulesPopulated = false;
function populateModuleFilter(rows) {
    if (modulesPopulated) return;
    const sel = document.getElementById('fModule');
    const existing = [...sel.options].map(o => o.value);
    const modules = [...new Set(rows.map(r => r.module).filter(Boolean))].sort();
    modules.forEach(m => {
        if (!existing.includes(m)) {
            const opt = document.createElement('option');
            opt.value = m; opt.textContent = m;
            sel.appendChild(opt);
        }
    });
    modulesPopulated = true;
}

function renderPagination(p, current) {
    const el = document.getElementById('pagination');
    if (p.last_page <= 1) {
        el.innerHTML = `<p class="text-sm text-gray-500">Showing ${p.total.toLocaleString()} logs</p>`;
        return;
    }
    let pages = '';
    for (let i = 1; i <= p.last_page; i++) {
        if (i === 1 || i === p.last_page || Math.abs(i - current) <= 2)
            pages += `<button onclick="load(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium ${i===current?'bg-[#0082C3] text-white':'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        else if (Math.abs(i - current) === 3) pages += `<span class="px-1 text-gray-400">…</span>`;
    }
    el.innerHTML = `
        <p class="text-sm text-gray-500">Showing ${((current-1)*p.per_page+1).toLocaleString()}–${Math.min(current*p.per_page,p.total).toLocaleString()} of ${p.total.toLocaleString()}</p>
        <div class="flex items-center gap-1">
            <button onclick="load(${current-1})" ${current===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="load(${current+1})" ${current===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function debounceLoad() { clearTimeout(searchTimer); searchTimer = setTimeout(() => load(1), 400); }
function clearFilters() {
    ['fSearch','fModule','fAction','fDateFrom','fDateTo'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    modulesPopulated = false;
    load(1);
}
function esc(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

// ── Detail Modal ─────────────────────────────────────────────────
async function showDetail(id) {
    document.getElementById('modal').classList.remove('hidden');
    requestAnimationFrame(() => { document.getElementById('modalBox').style.transform = 'translateX(0)'; });
    document.getElementById('modalContent').innerHTML = '<p class="text-gray-400 text-sm">Loading…</p>';

    const r = await fetch(`/admin/activity-logs/${id}`, {
        credentials: 'same-origin', headers: { 'Accept': 'application/json' }
    });
    const data = await r.json();
    if (!data.success) { document.getElementById('modalContent').innerHTML = '<p class="text-red-500 text-sm">Failed to load</p>'; return; }

    const log = data.data;
    const actionColor = ACTION_COLORS[log.action] || 'bg-gray-100 text-gray-600';
    const time = new Date(log.created_at).toLocaleString('en-IN', { dateStyle:'long', timeStyle:'medium' });

    let propsHtml = '';
    if (log.properties && Object.keys(log.properties).length > 0) {
        propsHtml = `
            <div class="mt-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Properties</h4>
                <div class="bg-gray-900 rounded-xl p-4 overflow-x-auto">
                    <pre class="text-xs text-green-400 font-mono whitespace-pre-wrap">${esc(JSON.stringify(log.properties, null, 2))}</pre>
                </div>
            </div>`;
    }

    document.getElementById('modalContent').innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold ${actionColor}">
                    ${esc(log.action)}
                </span>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded font-mono">${esc(log.module || '—')}</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-400 mb-0.5">Admin User</p>
                    <p class="text-sm font-semibold text-gray-800">${esc(log.user?.name || 'System')}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-400 mb-0.5">Time</p>
                    <p class="text-sm font-semibold text-gray-800">${time}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-400 mb-0.5">IP Address</p>
                    <p class="text-sm font-mono text-gray-800">${esc(log.ip_address || '—')}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-400 mb-0.5">Log ID</p>
                    <p class="text-sm font-mono text-gray-800">#${log.id}</p>
                </div>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-400 mb-0.5">Description</p>
                <p class="text-sm text-gray-800">${esc(log.description || '—')}</p>
            </div>

            ${log.user_agent ? `
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-400 mb-0.5">User Agent</p>
                <p class="text-xs text-gray-600 font-mono break-all">${esc(log.user_agent)}</p>
            </div>` : ''}

            ${propsHtml}
        </div>`;
}

function closeModal() {
    document.getElementById('modalBox').style.transform = 'translateX(100%)';
    setTimeout(() => document.getElementById('modal').classList.add('hidden'), 420);
}

// ── Export CSV ───────────────────────────────────────────────────
async function exportLogs() {
    const params = new URLSearchParams({
        per_page: 1000,
        search:    document.getElementById('fSearch').value   || '',
        module:    document.getElementById('fModule').value   || '',
        action:    document.getElementById('fAction').value   || '',
        date_from: document.getElementById('fDateFrom').value || '',
        date_to:   document.getElementById('fDateTo').value   || '',
    });
    for (const [k, v] of [...params]) { if (!v) params.delete(k); }

    const r = await fetch(`/admin/activity-logs/list?${params}`, {
        credentials: 'same-origin', headers: { 'Accept': 'application/json' }
    });
    const data = await r.json();
    if (!data.success || !data.data.length) { toast('No data to export', 'error'); return; }

    const headers = ['ID','Time','Admin','Action','Module','Description','IP'];
    const rows = data.data.map(l => [
        l.id,
        new Date(l.created_at).toLocaleString('en-IN'),
        l.user?.name || 'System',
        l.action,
        l.module || '',
        (l.description || '').replace(/,/g, ';'),
        l.ip_address || '',
    ]);

    const csv = [headers, ...rows].map(r => r.join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url; a.download = `activity-logs-${new Date().toISOString().split('T')[0]}.csv`;
    a.click(); URL.revokeObjectURL(url);
    toast('CSV exported!');
}

function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}

// ── Init ─────────────────────────────────────────────────────────
load(1);
</script>
@endpush
