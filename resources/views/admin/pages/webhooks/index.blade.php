@extends('admin.layouts.app')
@section('title', 'Webhooks')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Webhooks</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manage webhook endpoints for external integrations</p>
    </div>
    <button onclick="openAdd()"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Create Webhook
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach([
        ['id'=>'sTotal',   'label'=>'Total',   'color'=>'blue',   'icon'=>'webhook'],
        ['id'=>'sActive',  'label'=>'Active',  'color'=>'green',  'icon'=>'circle-check'],
        ['id'=>'sInactive','label'=>'Inactive','color'=>'red',    'icon'=>'circle-x'],
        ['id'=>'sEvents',  'label'=>'Events',  'color'=>'purple', 'icon'=>'zap'],
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
        <input id="fSearch" type="text" placeholder="Search by name or URL…"
               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
               oninput="debounceLoad()">
    </div>
    <button onclick="load(1)" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
        <i data-lucide="refresh-cw" class="w-4 h-4 inline"></i> Refresh
    </button>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Name</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">URL</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Events</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-100">
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Loading…</td></tr>
            </tbody>
        </table>
    </div>
    <div id="pagination" class="border-t border-gray-200 px-4 py-3"></div>
</div>

</div>

{{-- Add/Edit Modal --}}
<div id="modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 id="modalTitle" class="text-lg font-bold text-gray-900">Create Webhook</h2>
            <button onclick="closeModal()" class="p-1 hover:bg-gray-100 rounded-lg"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="webhookForm" onsubmit="save(event)" class="px-6 py-4 space-y-4">
            @csrf
            <input type="hidden" id="formId">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" id="formName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="url" id="formUrl" required placeholder="https://example.com/webhook" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Secret (optional)</label>
                <input type="text" id="formSecret" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Events</label>
                <div id="eventsList" class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                    <p class="text-gray-400 text-xs col-span-2">Loading events…</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-[#0082C3] rounded-lg hover:bg-[#006ba3]">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPage = 1, debounceTimer;

function debounceLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(() => load(1), 300); }

async function load(page = 1) {
    currentPage = page;
    const search = document.getElementById('fSearch').value;
    const res = await fetch(`{{ route('admin.webhooks.list') }}?page=${page}&search=${encodeURIComponent(search)}`);
    const json = await res.json();
    const data = json.data;
    document.getElementById('sTotal').textContent = data.total;
    document.getElementById('sActive').textContent = data.active;
    document.getElementById('sInactive').textContent = data.inactive;
    document.getElementById('sEvents').textContent = {{ count(App\Http\Controllers\Admin\WebhookController::EVENTS) }};

    const tbody = document.getElementById('tableBody');
    if (data.data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No webhooks found</td></tr>';
    } else {
        tbody.innerHTML = data.data.map(w => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">${w.name}</td>
                <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate">${w.url}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 px-2 py-1 rounded-full">${Array.isArray(w.events) ? w.events.length : 0} events</span></td>
                <td class="px-4 py-3 text-center">
                    <button onclick="toggleStatus(${w.id})" class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium ${w.is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'}">
                        ${w.is_active ? 'Active' : 'Inactive'}
                    </button>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button onclick="testWebhook(${w.id})" class="p-1.5 hover:bg-blue-50 rounded-lg text-blue-600" title="Test"><i data-lucide="play" class="w-4 h-4"></i></button>
                        <button onclick="edit(${w.id})" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-600" title="Edit"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                        <button onclick="remove(${w.id})" class="p-1.5 hover:bg-red-50 rounded-lg text-red-600" title="Delete"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
    lucide.createIcons();
    renderPagination(data.last_page, data.current_page);
}

function renderPagination(lastPage, current) {
    if (lastPage <= 1) { document.getElementById('pagination').innerHTML = ''; return; }
    let html = '<div class="flex items-center justify-between"><p class="text-sm text-gray-500">Page ' + current + ' of ' + lastPage + '</p><div class="flex gap-1">';
    for (let i = 1; i <= lastPage; i++) {
        html += `<button onclick="load(${i})" class="px-3 py-1 text-sm rounded-lg ${i===current?'bg-[#0082C3] text-white':'bg-gray-100 text-gray-600 hover:bg-gray-200'}">${i}</button>`;
    }
    html += '</div></div>';
    document.getElementById('pagination').innerHTML = html;
}

async function loadEvents() {
    const res = await fetch('{{ route("admin.webhooks.events") }}');
    const json = await res.json();
    const container = document.getElementById('eventsList');
    container.innerHTML = Object.entries(json.data).map(([key, label]) =>
        `<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="events[]" value="${key}" class="webhook-event rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]"><span>${label}</span></label>`
    ).join('');
}

function openAdd() {
    document.getElementById('modalTitle').textContent = 'Create Webhook';
    document.getElementById('formId').value = '';
    document.getElementById('formName').value = '';
    document.getElementById('formUrl').value = '';
    document.getElementById('formSecret').value = '';
    document.querySelectorAll('.webhook-event').forEach(cb => cb.checked = false);
    document.getElementById('modal').classList.remove('hidden');
    loadEvents();
}

async function edit(id) {
    const res = await fetch(`{{ route('admin.webhooks.show', ':id') }}`.replace(':id', id));
    const json = await res.json();
    const w = json.data;
    document.getElementById('modalTitle').textContent = 'Edit Webhook';
    document.getElementById('formId').value = w.id;
    document.getElementById('formName').value = w.name;
    document.getElementById('formUrl').value = w.url;
    document.getElementById('formSecret').value = w.secret || '';
    document.getElementById('modal').classList.remove('hidden');
    await loadEvents();
    const events = Array.isArray(w.events) ? w.events : [];
    document.querySelectorAll('.webhook-event').forEach(cb => { cb.checked = events.includes(cb.value); });
}

function closeModal() { document.getElementById('modal').classList.add('hidden'); }

async function save(e) {
    e.preventDefault();
    const id = document.getElementById('formId').value;
    const events = [...document.querySelectorAll('.webhook-event:checked')].map(cb => cb.value);
    const body = {
        name: document.getElementById('formName').value,
        url: document.getElementById('formUrl').value,
        secret: document.getElementById('formSecret').value,
        events: events,
        _token: '{{ csrf_token() }}'
    };
    const url = id ? `{{ route('admin.webhooks.update', ':id') }}`.replace(':id', id) : '{{ route("admin.webhooks.store") }}';
    const method = id ? 'PUT' : 'POST';
    await fetch(url, { method, headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify(body) });
    closeModal(); load(currentPage);
}

async function toggleStatus(id) {
    await fetch(`{{ route('admin.webhooks.toggle', ':id') }}`.replace(':id', id), { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'} });
    load(currentPage);
}

async function testWebhook(id) {
    if (!confirm('Send a test payload to this webhook?')) return;
    const res = await fetch(`{{ route('admin.webhooks.test', ':id') }}`.replace(':id', id), { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'} });
    const json = await res.json();
    alert(json.message || 'Test sent');
}

async function remove(id) {
    if (!confirm('Delete this webhook permanently?')) return;
    await fetch(`{{ route('admin.webhooks.destroy', ':id') }}`.replace(':id', id), { method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'} });
    load(currentPage);
}

load();
</script>
@endpush
