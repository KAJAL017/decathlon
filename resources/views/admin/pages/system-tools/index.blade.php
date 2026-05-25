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
                    'cache'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>',
                    'logs'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                    'sysinfo' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>',
                ];
                @endphp
                <button onclick="switchTab('{{$key}}')" id="nav-{{$key}}"
                        class="sys-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0
                               {{ $key === 'cache' ? 'bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $sysIcons[$key] !!}</svg>
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
                            ['type'=>'all','label'=>'Clear All Cache','desc'=>'Clears views, config, routes and app cache','color'=>'red','icon'=>'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                            ['type'=>'view','label'=>'Clear View Cache','desc'=>'Clears compiled Blade templates','color'=>'blue','icon'=>'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'],
                            ['type'=>'config','label'=>'Clear Config Cache','desc'=>'Clears cached configuration files','color'=>'yellow','icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                            ['type'=>'route','label'=>'Clear Route Cache','desc'=>'Clears cached route definitions','color'=>'green','icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
                        ] as $c)
                        <div class="p-4 border border-gray-200 rounded-xl hover:border-gray-300 transition-colors">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-{{ $c['color'] }}-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-{{ $c['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"/>
                                    </svg>
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
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Database Tables</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Table</th>
                        <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Rows</th>
                        <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Size (KB)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    ${d.tables.map(t => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-mono text-xs text-gray-700">${t.table_name}</td>
                            <td class="px-3 py-2 text-right text-gray-600">${t.table_rows || 0}</td>
                            <td class="px-3 py-2 text-right text-gray-600">${t.size_kb}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}
</script>
@endpush
