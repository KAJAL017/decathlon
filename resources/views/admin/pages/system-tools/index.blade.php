@extends('admin.layouts.app')
@section('title', 'System Tools')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Tools</h1>
            <p class="text-sm text-gray-500 mt-0.5">Cache management, logs viewer and system information</p>
        </div>
    </div>

    <div class="flex gap-6">
        <div class="w-52 flex-shrink-0">
            <nav class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-4">
                @foreach(['cache'=>'Cache','logs'=>'Logs','sysinfo'=>'System Info'] as $key=>$label)
                @php
                $sysIcons = [
                    'cache'   => 'trash-2',
                    'logs'    => 'file-text',
                    'sysinfo' => 'monitor',
                ];
                @endphp
                <button onclick="switchTab('{{$key}}')" id="nav-{{$key}}"
                        class="sys-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0
                               {{ $key === 'cache' ? 'bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="{{ $sysIcons[$key] }}" class="w-4 h-4 flex-shrink-0"></i>
                    <span>{{$label}}</span>
                </button>
                @endforeach
            </nav>
        </div>

        <div class="flex-1 min-w-0">

            {{-- Cache --}}
            <div id="tab-cache" class="sys-tab space-y-4">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Cache Management</h2>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['type'=>'all','label'=>'Clear All Cache','desc'=>'Clears views, config, routes and app cache','color'=>'red','icon'=>'trash-2'],
                            ['type'=>'view','label'=>'Clear View Cache','desc'=>'Clears compiled Blade templates','color'=>'blue','icon'=>'layout'],
                            ['type'=>'config','label'=>'Clear Config Cache','desc'=>'Clears cached configuration files','color'=>'yellow','icon'=>'settings'],
                            ['type'=>'route','label'=>'Clear Route Cache','desc'=>'Clears cached route definitions','color'=>'green','icon'=>'map'],
                        ] as $c)
                        <div class="p-4 border border-gray-200 rounded-xl hover:border-gray-300 transition-colors">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-{{ $c['color'] }}-50 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="{{ $c['icon'] }}" class="w-5 h-5 text-{{ $c['color'] }}-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $c['label'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $c['desc'] }}</p>
                                </div>
                            </div>
                            <button onclick="clearCache('{{ $c['type'] }}')"
                                    class="w-full px-3 py-2 text-sm font-medium text-{{ $c['color'] }}-700 bg-{{ $c['color'] }}-50 border border-{{ $c['color'] }}-200 rounded-lg hover:bg-{{ $c['color'] }}-100 transition-colors">
                                Clear Now
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Logs --}}
            <div id="tab-logs" class="sys-tab space-y-4" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-gray-900">Application Logs</h2>
                        <div class="flex items-center gap-2">
                            <span id="logSize" class="text-xs text-gray-400"></span>
                            <button onclick="loadLogs()" class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Refresh</button>
                            <button onclick="clearLogs()" class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50">Clear Logs</button>
                        </div>
                    </div>
                    <div id="logContainer" class="bg-gray-900 rounded-lg p-4 h-96 overflow-y-auto font-mono text-xs">
                        <p class="text-gray-400">Loading logs…</p>
                    </div>
                </div>
            </div>

            {{-- System Info --}}
            <div id="tab-sysinfo" class="sys-tab space-y-4" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-gray-900">System Information</h2>
                        <button onclick="loadSysInfo()" class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Refresh</button>
                    </div>
                    <div id="sysInfoContent">
                        <p class="text-sm text-gray-400">Loading…</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium transition-all"></div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let isFirstLoad = true;

function switchTab(name) {
    document.querySelectorAll('.sys-tab').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.sys-nav').forEach(b => {
        b.className = 'sys-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 text-gray-700 hover:bg-gray-50';
    });
    document.getElementById('tab-' + name).style.display = 'block';
    document.getElementById('nav-' + name).className = 'sys-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]';
    if (name === 'logs') loadLogs();
    if (name === 'sysinfo') loadSysInfo();
}

function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}

async function clearCache(type) {
    const r = await fetch('/admin/system-tools/clear-cache', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ type }),
    });
    const data = await r.json();
    if (data.success) toast(data.message);
    else toast(data.message || 'Error', 'error');
}

async function loadLogs() {
    document.getElementById('logContainer').innerHTML = '<p class="text-gray-400">Loading…</p>';
    const r = await fetch('/admin/system-tools/logs', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    const data = await r.json();
    if (!data.success) { document.getElementById('logContainer').innerHTML = '<p class="text-red-400">Failed to load logs</p>'; return; }

    document.getElementById('logSize').textContent = `${data.data.size} KB`;
    const lines = data.data.lines;
    if (!lines.length) { document.getElementById('logContainer').innerHTML = '<p class="text-gray-400">No log entries found</p>'; return; }

    const levelColors = { error: 'text-red-400', warning: 'text-yellow-400', info: 'text-blue-400', debug: 'text-gray-400', critical: 'text-red-500' };
    document.getElementById('logContainer').innerHTML = lines.map(l => `
        <div class="mb-1.5">
            <span class="text-gray-500">[${l.time}]</span>
            <span class="${levelColors[l.level] || 'text-gray-300'} font-semibold uppercase text-[10px]"> ${l.level}</span>
            <span class="text-gray-300"> ${l.message}</span>
        </div>
    `).join('');
    if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
}

async function clearLogs() {
    const r = await fetch('/admin/system-tools/clear-logs', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });
    const data = await r.json();
    if (data.success) { toast('Logs cleared'); loadLogs(); }
    else toast(data.message || 'Error', 'error');
}

async function loadSysInfo() {
    document.getElementById('sysInfoContent').innerHTML = '<p class="text-sm text-gray-400">Loading…</p>';
    const r = await fetch('/admin/system-tools/system-info', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    const data = await r.json();
    if (!data.success) { document.getElementById('sysInfoContent').innerHTML = '<p class="text-sm text-red-500">Failed to load</p>'; return; }

    const d = data.data;
    const infoRows = [
        ['PHP Version', d.php_version],
        ['Laravel Version', d.laravel_version],
        ['Database', `MySQL ${d.db_version}`],
        ['DB Size', `${d.db_size_mb} MB`],
        ['Environment', d.env],
        ['Debug Mode', d.debug_mode],
        ['Timezone', d.timezone],
        ['Memory Limit', d.memory_limit],
        ['Max Upload', d.max_upload],
    ];

    document.getElementById('sysInfoContent').innerHTML = `
        <div class="grid grid-cols-2 gap-3 mb-6">
            ${infoRows.map(([k, v]) => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-500">${k}</span>
                    <span class="text-sm font-semibold text-gray-900">${v}</span>
                </div>
            `).join('')}
        </div>

        {{-- Database Tables --}}
        <div class="mt-5">
            <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i data-lucide="database" class="w-4 h-4 text-[#0082C3]"></i>
                    Database Tables
                    <span class="text-xs bg-[#0082C3] text-white font-bold px-2 py-0.5 rounded-full" id="tableCountBadge">${d.table_count || d.tables.length}</span>
                </h3>
                <div class="flex items-center gap-2">
                    <input type="text" id="tableSearch" placeholder="Search tables..." oninput="filterTables(this.value)"
                        class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#0082C3] w-44">
                    <select id="tableSortBy" onchange="sortTables(this.value)"
                        class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="name">Sort: Name</option>
                        <option value="rows">Sort: Rows</option>
                        <option value="size">Sort: Size</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-sm" id="dbTablesTable">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Table Name</th>
                            <th class="px-4 py-2.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Engine</th>
                            <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Rows</th>
                            <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Data (KB)</th>
                            <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Index (KB)</th>
                            <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total (KB)</th>
                        </tr>
                    </thead>
                    <tbody id="dbTablesTbody" class="divide-y divide-gray-50">
                        ${renderTableRows(d.tables)}
                    </tbody>
                </table>
            </div>
            <p id="tableFilterInfo" class="text-xs text-gray-400 mt-2 text-right">${d.tables.length} tables total</p>
        </div>
    `;

    // Store tables globally for filter/sort
    window._allDbTables = d.tables;
}

function renderTableRows(tables) {
    if (!tables.length) return '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">No tables found</td></tr>';
    return tables.map((t, i) => {
        const sizeKb = parseFloat(t.size_kb) || 0;
        const sizeBar = Math.min(100, (sizeKb / 100) * 100);
        const rowColor = sizeKb > 500 ? 'text-orange-600' : sizeKb > 100 ? 'text-blue-600' : 'text-gray-700';
        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-2.5 text-xs text-gray-400">${i + 1}</td>
            <td class="px-4 py-2.5">
                <span class="font-mono text-xs font-semibold text-gray-800">${esc(t.table_name)}</span>
            </td>
            <td class="px-4 py-2.5 text-center">
                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-semibold rounded">${esc(t.engine || 'InnoDB')}</span>
            </td>
            <td class="px-4 py-2.5 text-right font-semibold text-gray-700 text-xs">${Number(t.table_rows || 0).toLocaleString()}</td>
            <td class="px-4 py-2.5 text-right text-xs text-gray-500">${t.data_kb || 0}</td>
            <td class="px-4 py-2.5 text-right text-xs text-gray-500">${t.index_kb || 0}</td>
            <td class="px-4 py-2.5 text-right text-xs font-bold ${rowColor}">${t.size_kb || 0}</td>
        </tr>`;
    }).join('');
}

function filterTables(q) {
    const all = window._allDbTables || [];
    const filtered = q ? all.filter(t => t.table_name.toLowerCase().includes(q.toLowerCase())) : all;
    document.getElementById('dbTablesTbody').innerHTML = renderTableRows(filtered);
    document.getElementById('tableFilterInfo').textContent = filtered.length + ' of ' + all.length + ' tables';
}

function sortTables(by) {
    const all = window._allDbTables || [];
    const sorted = [...all].sort((a, b) => {
        if (by === 'rows') return (b.table_rows || 0) - (a.table_rows || 0);
        if (by === 'size') return (parseFloat(b.size_kb) || 0) - (parseFloat(a.size_kb) || 0);
        return a.table_name.localeCompare(b.table_name);
    });
    document.getElementById('dbTablesTbody').innerHTML = renderTableRows(sorted);
}

function esc(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
</script>
@endpush
