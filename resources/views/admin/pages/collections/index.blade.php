@extends('admin.layouts.app')
@section('title', 'Collections')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Collections</h1>
            <p class="text-sm text-gray-500 mt-0.5">Organize products into curated collections</p>
        </div>
        <button onclick="openAdd()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Collection
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-5 gap-4">
        @foreach([
            ['id'=>'statTotal',    'label'=>'Total',    'color'=>'blue',   'icon'=>'layers'],
            ['id'=>'statActive',   'label'=>'Active',   'color'=>'green',  'icon'=>'check-circle'],
            ['id'=>'statFeatured', 'label'=>'Featured', 'color'=>'purple', 'icon'=>'star'],
            ['id'=>'statManual',   'label'=>'Manual',   'color'=>'yellow', 'icon'=>'sliders'],
            ['id'=>'statAuto',     'label'=>'Auto',     'color'=>'pink',   'icon'=>'zap'],
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
        <div class="relative flex-1 min-w-[220px]">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input id="fSearch" type="text" placeholder="Search collections…"
                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                   oninput="debounceLoad()">
        </div>
        <select id="fType" onchange="loadCollections(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Types</option>
            <option value="manual">Manual</option>
            <option value="auto">Auto</option>
        </select>
        <select id="fStatus" onchange="loadCollections(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <select id="fVisibility" onchange="loadCollections(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <option value="">All Visibility</option>
            <option value="visible">Visible</option>
            <option value="hidden">Hidden</option>
        </select>
        <select id="fPerPage" onchange="loadCollections(1)"
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
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Collection</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Products</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Visibility</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Featured</th>
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
            <option value="feature">Mark Featured</option>
            <option value="unfeature">Remove Featured</option>
            <option value="delete">Delete</option>
        </select>
        <button onclick="applyBulk()"
                class="px-4 py-1.5 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3]">
            Apply
        </button>
        <button onclick="clearSelection()" class="text-gray-400 hover:text-white">✕</button>
    </div>

</div>

{{-- ══════════ MODAL ══════════ --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBackdrop(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="modalBox"
         class="fixed right-0 top-0 h-full w-full max-w-3xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Collection</h3>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="px-6 py-2 border-b border-gray-200 flex gap-1">
            @foreach(['basic'=>'Basic Info','seo'=>'SEO'] as $t=>$label)
            <button id="mtab-{{$t}}" onclick="mTab('{{$t}}')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors text-gray-500 hover:bg-gray-100">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Form --}}
        <form id="cForm" class="flex-1 overflow-y-auto">
            <input type="hidden" id="cId">

            {{-- Tab: Basic --}}
            <div id="mt-basic" class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Collection Image</label>
                        <div class="relative group mt-1">
                            <div id="imagePlaceholder" class="aspect-[3/1] w-full border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center bg-gray-50 hover:border-[#0082C3] hover:bg-blue-50/30 transition-all cursor-pointer overflow-hidden" onclick="document.getElementById('cImageInput').click()">
                                <div id="uploadUI" class="flex flex-col items-center">
                                    <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i data-lucide="upload" class="w-5 h-5 text-gray-400 group-hover:text-[#0082C3]"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Click to upload image</p>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP. Images are optimized automatically.</p>
                                </div>
                                <img id="imagePreview" src="" class="hidden absolute inset-0 w-full h-full object-cover">
                                
                                <div id="uploadLoader" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center">
                                    <div class="flex flex-col items-center">
                                    <i data-lucide="loader" class="animate-spin h-6 w-6 text-[#0082C3] mb-2"></i>
                                        <p class="text-xs font-medium text-gray-600">Uploading...</p>
                                    </div>
                                </div>
                            </div>
                            <input type="file" id="cImageInput" class="hidden" accept="image/*" onchange="handleImageUpload(event)">
                            <input type="hidden" id="cImageUrl">
                            
                            <button type="button" id="removeImageBtn" onclick="removeImage(event)" class="hidden absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-lg shadow-lg hover:bg-red-700 transition-colors z-10">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input id="cName" type="text"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               placeholder="e.g. Summer Sale" required
                               oninput="autoSlug()">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input id="cSlug" type="text"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono text-xs"
                               placeholder="summer-sale">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="cDesc" rows="3"
                                  class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                  placeholder="Collection description"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select id="cType"
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="manual">Manual</option>
                            <option value="auto">Auto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Visibility <span class="text-red-500">*</span></label>
                        <select id="cVisibility"
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="visible">Visible</option>
                            <option value="hidden">Hidden</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select id="cStatus"
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input id="cSort" type="number" min="0" value="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div class="col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input id="cFeatured" type="checkbox" class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-700">Mark as Featured</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Tab: SEO --}}
            <div id="mt-seo" class="px-6 py-5 space-y-4" style="display:none">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
                    <input id="cSeoTitle" type="text" maxlength="255"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                           placeholder="Title for search engines">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Description</label>
                    <textarea id="cSeoDesc" rows="4" maxlength="500"
                              class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                              placeholder="Description for search engines"></textarea>
                </div>
            </div>
        </form>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button onclick="closeModal()"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button id="saveBtn" onclick="save()"
                    class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                Save Collection
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium transition-all"></div>

@endsection

@push('scripts')
<script>
// ── Config ──────────────────────────────────────────────────────
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const BASE = '/admin/collections';
let searchTimer, checkedIds = new Set(), allRows = [];
let isFirstLoad = true;

// ── API helper ──────────────────────────────────────────────────
async function api(url, method = 'GET', body = null) {
    const opts = {
        method,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    };
    if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
    const r = await fetch(url, opts);
    // If response is HTML (redirect to login), reload page
    const ct = r.headers.get('content-type') || '';
    if (!ct.includes('json')) {
        window.location.reload();
        return { success: false };
    }
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

// ── Load & Render ────────────────────────────────────────────────
async function loadCollections(page = 1) {
    const params = new URLSearchParams({
        page,
        per_page:   document.getElementById('fPerPage').value    || 15,
        search:     document.getElementById('fSearch').value     || '',
        type:       document.getElementById('fType').value       || '',
        status:     document.getElementById('fStatus').value     || '',
        visibility: document.getElementById('fVisibility').value || '',
    });
    for (const [k, v] of [...params]) { if (!v) params.delete(k); }

    document.getElementById('tBody').innerHTML =
        `<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;

    try {
        const resp = await fetch(`${BASE}/list?${params}`, {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        console.log('Status:', resp.status, 'URL:', resp.url);
        const text = await resp.text();
        console.log('Raw response (first 300):', text.substring(0, 300));
        let data;
        try { data = JSON.parse(text); } catch(e) {
            document.getElementById('tBody').innerHTML =
                `<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Server returned non-JSON (status ${resp.status}). Are you logged in?</td></tr>`;
            return;
        }
        if (!data.success) {
            document.getElementById('tBody').innerHTML =
                `<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">API error: ${data.message || 'unknown'}</td></tr>`;
            return;
        }
        allRows = data.data;
        renderTable(data.data);
        renderPagination(data.pagination, page);
        renderStats();
    } catch(err) {
        console.error('loadCollections error:', err);
        document.getElementById('tBody').innerHTML =
            `<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Network error: ${err.message}</td></tr>`;
    }
    if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
}

function renderTable(rows) {
    console.log('renderTable called with', rows.length, 'rows');
    const tbody = document.getElementById('tBody');
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">No collections found</td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(c => `
        <tr class="hover:bg-gray-50 transition-colors" data-id="${c.id}">
            <td class="px-5 py-3.5">
                <input type="checkbox" class="row-chk w-4 h-4 text-[#0082C3] rounded border-gray-300"
                       value="${c.id}" ${checkedIds.has(c.id) ? 'checked' : ''}
                       onchange="onCheck(${c.id}, this.checked)">
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        ${(c.thumbnail_url || c.image_url)
                            ? `<img src="${c.thumbnail_url || c.image_url}" class="w-full h-full object-cover">`
                            : `<i data-lucide="layers" class="w-5 h-5 text-gray-400"></i>`}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">${c.name}</p>
                        <p class="text-xs text-gray-400 font-mono">${c.slug}</p>
                    </div>
                </div>
            </td>
            <td class="px-5 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${c.type === 'auto' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'}">
                    ${c.type === 'auto' ? 'Auto' : 'Manual'}
                </span>
            </td>
            <td class="px-5 py-3.5 text-sm text-gray-700">${c.products_count || 0}</td>
            <td class="px-5 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${c.visibility === 'visible' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">
                    ${c.visibility === 'visible' ? 'Visible' : 'Hidden'}
                </span>
            </td>
            <td class="px-5 py-3.5 text-center text-base">${c.is_featured ? '⭐' : '<span class="text-gray-300">☆</span>'}</td>
            <td class="px-5 py-3.5">
                <button onclick="toggleStatus(${c.id})"
                        class="px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors cursor-pointer ${c.status ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200'}">
                    ${c.status ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    <button onclick="openEdit(${c.id})"
                            class="p-2 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <button onclick="del(${c.id}, '${c.name.replace(/'/g,"&#39;")}')"
                            class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderStats() {
    api(`${BASE}/list?per_page=1000`).then(d => {
        if (!d.success) return;
        const all = d.data;
        document.getElementById('statTotal').textContent    = d.pagination.total;
        document.getElementById('statActive').textContent   = all.filter(c => c.status).length;
        document.getElementById('statFeatured').textContent = all.filter(c => c.is_featured).length;
        document.getElementById('statManual').textContent   = all.filter(c => c.type === 'manual').length;
        document.getElementById('statAuto').textContent     = all.filter(c => c.type === 'auto').length;
    }).catch(() => {});
}

function renderPagination(p, current) {
    const el = document.getElementById('pagination');
    if (p.last_page <= 1) {
        el.innerHTML = `<p class="text-sm text-gray-500">Showing ${p.total} collections</p>`;
        return;
    }
    let pages = '';
    for (let i = 1; i <= p.last_page; i++) {
        if (i === 1 || i === p.last_page || Math.abs(i - current) <= 2) {
            pages += `<button onclick="loadCollections(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors ${i === current ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        } else if (Math.abs(i - current) === 3) {
            pages += `<span class="px-1 text-gray-400">…</span>`;
        }
    }
    el.innerHTML = `
        <p class="text-sm text-gray-500">Showing ${(current-1)*p.per_page+1}–${Math.min(current*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="loadCollections(${current-1})" ${current===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed">←</button>
            ${pages}
            <button onclick="loadCollections(${current+1})" ${current===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed">→</button>
        </div>`;
}

// ── Debounce search ──────────────────────────────────────────────
function debounceLoad() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => loadCollections(1), 400);
}

// ── Modal open/close ─────────────────────────────────────────────
function openAdd() {
    document.getElementById('modalTitle').textContent = 'Add Collection';
    document.getElementById('cId').value = '';
    document.getElementById('cForm').reset();
    document.getElementById('cSort').value = 0;
    removeImage();
    mTab('basic');
    showModal();
}

async function openEdit(id) {
    document.getElementById('modalTitle').textContent = 'Edit Collection';
    mTab('basic');
    showModal();

    const data = await api(`${BASE}/${id}`);
    if (!data.success) { toast('Failed to load', 'error'); closeModal(); return; }
    const c = data.data;

    document.getElementById('cId').value          = c.id;
    document.getElementById('cName').value         = c.name;
    document.getElementById('cSlug').value         = c.slug;
    document.getElementById('cDesc').value         = c.description || '';
    document.getElementById('cType').value         = c.type;
    document.getElementById('cVisibility').value   = c.visibility;
    document.getElementById('cStatus').value       = c.status ? '1' : '0';
    document.getElementById('cSort').value         = c.sort_order || 0;
    document.getElementById('cFeatured').checked   = !!c.is_featured;
    document.getElementById('cSeoTitle').value     = c.seo_title || '';
    document.getElementById('cSeoDesc').value      = c.seo_description || '';
    
    if (c.image_url) {
        document.getElementById('cImageUrl').value = c.image_url;
        document.getElementById('imagePreview').src = c.image_url;
        document.getElementById('imagePreview').classList.remove('hidden');
        document.getElementById('uploadUI').classList.add('hidden');
        document.getElementById('removeImageBtn').classList.remove('hidden');
    } else {
        removeImage();
    }
}

function showModal() {
    document.getElementById('modal').classList.remove('hidden');
    requestAnimationFrame(() => {
        document.getElementById('modalBox').style.transform = 'translateX(0)';
    });
}

function closeModal() {
    document.getElementById('modalBox').style.transform = 'translateX(100%)';
    setTimeout(() => document.getElementById('modal').classList.add('hidden'), 420);
}

function onBackdrop(e) { if (e.target.id === 'modal') closeModal(); }

// ── Tab switch ───────────────────────────────────────────────────
function mTab(name) {
    ['basic', 'seo'].forEach(t => {
        document.getElementById(`mt-${t}`).style.display  = t === name ? 'block' : 'none';
        document.getElementById(`mtab-${t}`).className =
            `px-4 py-2 text-sm font-medium rounded-lg transition-colors ${t === name ? 'bg-[#0082C3] text-white' : 'text-gray-500 hover:bg-gray-100'}`;
    });
}

// ── Auto slug ────────────────────────────────────────────────────
function autoSlug() {
    if (!document.getElementById('cId').value) {
        document.getElementById('cSlug').value = document.getElementById('cName').value
            .toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }
}

// ── Image Upload ─────────────────────────────────────────────────
async function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        Dialog.alert({ title: 'Invalid File', message: 'Please select an image file (PNG, JPG, WEBP).', type: 'danger' });
        return;
    }

    const loader = document.getElementById('uploadLoader');
    const preview = document.getElementById('imagePreview');
    const uploadUI = document.getElementById('uploadUI');
    const removeBtn = document.getElementById('removeImageBtn');
    const submitBtn = document.getElementById('saveBtn');

    loader.classList.remove('hidden');
    submitBtn.disabled = true;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', 'collections');
    formData.append('folder', 'collections');
    
    const oldUrl = document.getElementById('cImageUrl').value;
    if (oldUrl) {
        formData.append('oldPath', oldUrl);
    }

    try {
        const response = await fetch('/api/upload', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            document.getElementById('cImageUrl').value = data.url;
            preview.src = data.url;
            preview.classList.remove('hidden');
            uploadUI.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        } else {
            Dialog.alert({ title: 'Upload Failed', message: data.message || 'Failed to upload image.', type: 'danger' });
        }
    } catch (error) {
        console.error('Upload error:', error);
        Dialog.alert({ title: 'Error', message: 'An error occurred during upload.', type: 'danger' });
    } finally {
        loader.classList.add('hidden');
        submitBtn.disabled = false;
    }
}

function removeImage(event) {
    if (event) event.stopPropagation();
    document.getElementById('cImageUrl').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imagePreview').src = '';
    document.getElementById('uploadUI').classList.remove('hidden');
    document.getElementById('removeImageBtn').classList.add('hidden');
    document.getElementById('cImageInput').value = '';
}

// ── Save (Create/Update) ─────────────────────────────────────────
async function save() {
    const name = document.getElementById('cName').value.trim();
    if (!name) { toast('Name is required', 'error'); mTab('basic'); document.getElementById('cName').focus(); return; }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const id = document.getElementById('cId').value;
    const body = {
        name,
        slug:            document.getElementById('cSlug').value.trim() || undefined,
        description:     document.getElementById('cDesc').value,
        image_url:       document.getElementById('cImageUrl').value || null,
        type:            document.getElementById('cType').value,
        visibility:      document.getElementById('cVisibility').value,
        status:          document.getElementById('cStatus').value === '1',
        is_featured:     document.getElementById('cFeatured').checked,
        sort_order:      parseInt(document.getElementById('cSort').value) || 0,
        seo_title:       document.getElementById('cSeoTitle').value,
        seo_description: document.getElementById('cSeoDesc').value,
    };

    const url    = id ? `${BASE}/${id}` : BASE;
    const method = id ? 'PUT' : 'POST';

    const data = await api(url, method, body);
    btn.disabled = false; btn.textContent = 'Save Collection';

    if (data.success) {
        toast(data.message || 'Saved!');
        closeModal();
        loadCollections(1);
    } else {
        const first = data.errors ? Object.values(data.errors)[0][0] : data.message;
        toast(first || 'Error', 'error');
    }
}

// ── Delete ───────────────────────────────────────────────────────
async function del(id, name) {
    const confirmed = await Dialog.confirm({
        title: 'Delete Collection',
        message: `Are you sure you want to delete "<strong>${name}</strong>"?<br><span class="text-red-500 text-xs">This action cannot be undone.</span>`,
        type: 'danger'
    });

    if (!confirmed) return;

    const data = await api(`${BASE}/${id}`, 'DELETE');
    if (data.success) { 
        Dialog.alert({ title: 'Deleted!', message: 'Collection deleted successfully', type: 'success' });
        loadCollections(1); 
    }
    else toast(data.message || 'Delete failed', 'error');
}

// ── Toggle Status ────────────────────────────────────────────────
async function toggleStatus(id) {
    const data = await api(`${BASE}/${id}/toggle-status`, 'POST');
    if (data.success) { toast('Status updated'); loadCollections(1); }
    else toast(data.message || 'Error', 'error');
}

// ── Bulk Actions ─────────────────────────────────────────────────
function onCheck(id, checked) {
    checked ? checkedIds.add(id) : checkedIds.delete(id);
    syncBulkBar();
}
function toggleAll(chk) {
    document.querySelectorAll('.row-chk').forEach(el => {
        el.checked = chk.checked;
        chk.checked ? checkedIds.add(parseInt(el.value)) : checkedIds.delete(parseInt(el.value));
    });
    syncBulkBar();
}
function clearSelection() {
    checkedIds.clear();
    document.querySelectorAll('.row-chk, #chkAll').forEach(el => el.checked = false);
    syncBulkBar();
}
function syncBulkBar() {
    const bar = document.getElementById('bulkBar');
    if (checkedIds.size > 0) {
        bar.classList.remove('hidden');
        document.getElementById('bulkCount').textContent = `${checkedIds.size} selected`;
    } else {
        bar.classList.add('hidden');
    }
}
async function applyBulk() {
    const action = document.getElementById('bulkAction').value;
    if (!action) { toast('Choose an action', 'warning'); return; }

    const doAction = async () => {
        const data = await api(`${BASE}/bulk-action`, 'POST', { action, ids: [...checkedIds] });
        if (data.success) {
            if (action === 'delete') {
                Dialog.alert({ title: 'Deleted!', message: `${checkedIds.size} collection(s) deleted`, type: 'success' });
            } else {
                toast(data.message);
            }
            clearSelection(); loadCollections(1);
        }
        else toast(data.message || 'Error', 'error');
    };

    if (action === 'delete') {
        const confirmed = await Dialog.confirm({
            title: 'Delete Collections',
            message: `Delete <strong>${checkedIds.size}</strong> selected collection(s)?<br><span class="text-red-500 text-xs">This action cannot be undone.</span>`,
            type: 'danger'
        });
        if (confirmed) doAction();
    } else {
        doAction();
    }
}

// ── Init ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    console.log('Collections page init');
    loadCollections(1).catch(e => {
        console.error('Init loadCollections failed:', e);
        document.getElementById('tBody').innerHTML =
            `<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Init error: ${e.message}</td></tr>`;
    });
});
</script>
@endpush
