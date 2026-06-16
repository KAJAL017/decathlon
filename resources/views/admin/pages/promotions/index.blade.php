@extends('admin.layouts.app')
@section('title', 'Promotions')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Promotions</h1>
        <p class="text-sm text-gray-500 mt-0.5">Flash sales, discounts, buy X get Y and more</p>
    </div>
    <button onclick="openAdd()"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Add Promotion
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    @foreach([
        ['id'=>'sTotal',     'label'=>'Total',     'color'=>'blue',   'icon'=>'shopping-bag'],
        ['id'=>'sActive',    'label'=>'Active',    'color'=>'green',  'icon'=>'circle-check'],
        ['id'=>'sScheduled', 'label'=>'Scheduled', 'color'=>'yellow', 'icon'=>'calendar'],
        ['id'=>'sExpired',   'label'=>'Expired',   'color'=>'red',    'icon'=>'clock'],
        ['id'=>'sFlash',     'label'=>'Flash Sales','color'=>'purple','icon'=>'zap'],
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
        <input id="fSearch" type="text" placeholder="Search promotions…"
               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
               oninput="debounceLoad()">
    </div>
    <select id="fType" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="">All Types</option>
        <option value="percentage">Percentage</option>
        <option value="fixed_amount">Fixed Amount</option>
        <option value="free_shipping">Free Shipping</option>
        <option value="buy_x_get_y">Buy X Get Y</option>
        <option value="flash_sale">Flash Sale</option>
        <option value="bundle">Bundle</option>
    </select>
    <select id="fStatus" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="scheduled">Scheduled</option>
        <option value="expired">Expired</option>
        <option value="inactive">Inactive</option>
    </select>
    <select id="fPer" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="15">15/page</option>
        <option value="25">25/page</option>
        <option value="50">50/page</option>
    </select>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3"><input type="checkbox" id="chkAll" onchange="toggleAll(this)" class="w-4 h-4 text-[#0082C3] rounded border-gray-300"></th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Promotion</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Discount</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Schedule</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usage</th>
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
    <button onclick="clearSel()" class="text-gray-400 hover:text-white">✕</button>
</div>

</div>{{-- end space-y-6 --}}

{{-- ══ MODAL ══ --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBd(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="mBox" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="mTitle" class="text-lg font-semibold text-gray-900">Add Promotion</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="mStep">Step 1 of 3 — Basic Info</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Step Progress --}}
        <div class="px-6 py-3 border-b border-gray-100 bg-white flex-shrink-0">
            <div class="flex items-center gap-2">
                @foreach([1=>'Basic Info', 2=>'Discount Rules', 3=>'Schedule & Display'] as $n=>$label)
                <div class="flex items-center gap-2 {{ $n < 3 ? 'flex-1' : '' }}">
                    <div id="step-dot-{{$n}}"
                         class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all
                                {{ $n === 1 ? 'bg-[#0082C3] text-white' : 'bg-gray-200 text-gray-500' }}">
                        {{$n}}
                    </div>
                    <span id="step-lbl-{{$n}}" class="text-xs font-medium hidden sm:block
                          {{ $n === 1 ? 'text-[#0082C3]' : 'text-gray-400' }}">{{$label}}</span>
                    @if($n < 3)
                    <div id="step-line-{{$n}}" class="flex-1 h-0.5 {{ $n === 1 ? 'bg-[#0082C3]' : 'bg-gray-200' }} transition-all"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Form --}}
        <form id="pForm" class="flex-1 overflow-y-auto">
            <input type="hidden" id="pId">

            {{-- Step 1: Basic Info --}}
            <div id="s1" class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Promotion Name <span class="text-red-500">*</span></label>
                    <input id="pName" type="text" placeholder="e.g. Summer Flash Sale 50% OFF"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                           oninput="autoSlug()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input id="pSlug" type="text" placeholder="summer-flash-sale"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Promotion Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2" id="typeGrid">
                        @foreach([
                            ['percentage',   '🏷️', 'Percentage',    'e.g. 20% OFF'],
                            ['fixed_amount', '💰', 'Fixed Amount',  'e.g. ₹200 OFF'],
                            ['free_shipping','🚚', 'Free Shipping', 'No delivery charge'],
                            ['buy_x_get_y',  '🎁', 'Buy X Get Y',  'Buy 2 Get 1 Free'],
                            ['flash_sale',   '⚡', 'Flash Sale',   'Time-limited deal'],
                            ['bundle',       '📦', 'Bundle Deal',  'Multi-product offer'],
                        ] as [$val,$icon,$label,$sub])
                        <label class="type-card cursor-pointer border-2 border-gray-200 rounded-xl p-3 hover:border-[#0082C3] transition-all"
                               data-val="{{$val}}">
                            <input type="radio" name="pType" value="{{$val}}" class="hidden">
                            <div class="text-xl mb-1">{{$icon}}</div>
                            <div class="text-xs font-semibold text-gray-800">{{$label}}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{$sub}}</div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="pDesc" rows="2" placeholder="Internal notes about this promotion"
                              class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"></textarea>
                </div>
            </div>

            {{-- Step 2: Discount Rules --}}
            <div id="s2" class="px-6 py-5 space-y-4" style="display:none">
                {{-- Percentage / Fixed / Flash --}}
                <div id="r-discount" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span id="discSymbol" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">%</span>
                                <input id="pDiscVal" type="number" min="0" step="0.01" placeholder="0"
                                       class="w-full pl-8 pr-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                        </div>
                        <div id="r-maxDisc">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Discount (₹)</label>
                            <input id="pMaxDisc" type="number" min="0" placeholder="No cap"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                </div>
                {{-- Buy X Get Y --}}
                <div id="r-bxgy" class="space-y-4" style="display:none">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buy Qty</label>
                            <input id="pBuyQty" type="number" min="1" placeholder="2"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Get Qty</label>
                            <input id="pGetQty" type="number" min="1" placeholder="1"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Get Discount %</label>
                            <input id="pGetDisc" type="number" min="0" max="100" placeholder="100"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                </div>
                {{-- Conditions --}}
                <div class="border-t pt-4 space-y-4">
                    <h4 class="text-sm font-semibold text-gray-700">Conditions</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount (₹)</label>
                            <input id="pMinOrder" type="number" min="0" placeholder="0 = no minimum"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Quantity</label>
                            <input id="pMinQty" type="number" min="0" placeholder="0 = no minimum"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Usage Limit</label>
                            <input id="pUsageLimit" type="number" min="1" placeholder="Unlimited"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Per User Limit</label>
                            <input id="pPerUser" type="number" min="1" value="1"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Applies To</label>
                        <select id="pAppliesTo" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="all">All Products</option>
                            <option value="specific_products">Specific Products</option>
                            <option value="specific_categories">Specific Categories</option>
                            <option value="specific_brands">Specific Brands</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <input id="pPriority" type="number" min="0" value="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <p class="text-xs text-gray-400 mt-1">Higher number = applied first when multiple promotions match</p>
                    </div>
                </div>
            </div>

            {{-- Step 3: Schedule & Display --}}
            <div id="s3" class="px-6 py-5 space-y-4" style="display:none">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date & Time</label>
                        <input id="pStart" type="datetime-local"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date & Time</label>
                        <input id="pEnd" type="datetime-local"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                </div>
                <div class="border-t pt-4 space-y-4">
                    <h4 class="text-sm font-semibold text-gray-700">Badge & Display</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Badge Text</label>
                            <input id="pBadge" type="text" maxlength="50" placeholder="e.g. 50% OFF"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Badge Color</label>
                            <div class="flex gap-2 items-center">
                                <input id="pBadgeColor" type="color" value="#ef4444"
                                       class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5">
                                <input id="pBadgeColorHex" type="text" value="#ef4444" maxlength="7"
                                       class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                       oninput="document.getElementById('pBadgeColor').value=this.value">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50">
                            <input id="pCountdown" type="checkbox" class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">⏱ Show Countdown Timer</p>
                                <p class="text-xs text-gray-400">Display live countdown on product pages</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50">
                            <input id="pHomepage" type="checkbox" class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">🏠 Show on Homepage</p>
                                <p class="text-xs text-gray-400">Feature this promotion on the homepage banner</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50">
                            <input id="pActive" type="checkbox" checked class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">✅ Active</p>
                                <p class="text-xs text-gray-400">Enable this promotion immediately</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </form>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between flex-shrink-0">
            <button id="btnPrev" onclick="prevStep()" class="hidden px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                ← Previous
            </button>
            <div class="flex gap-3 ml-auto">
                <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button id="btnNext" onclick="nextStep()"
                        class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">
                    Next →
                </button>
                <button id="btnSave" onclick="save()" style="display:none"
                        class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                    Save Promotion
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium"></div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const BASE = '/admin/promotions';
let step = 1, checkedIds = new Set(), searchTimer, currentType = 'percentage';
let isFirstLoad = true;

// ── API ──────────────────────────────────────────────────────────
async function api(url, method='GET', body=null) {
    const opts = { method, credentials:'same-origin',
        headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':CSRF} };
    if (body) { opts.headers['Content-Type']='application/json'; opts.body=JSON.stringify(body); }
    const r = await fetch(url, opts);
    const ct = r.headers.get('content-type')||'';
    if (!ct.includes('json')) { window.location.reload(); return {success:false}; }
    return r.json();
}

// ── Toast ────────────────────────────────────────────────────────
function toast(msg, type='success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium
        ${type==='success'?'bg-green-600':type==='error'?'bg-red-600':'bg-yellow-500'}`;
    el.classList.remove('hidden');
    setTimeout(()=>el.classList.add('hidden'), 3000);
}

// ── Load & Render ────────────────────────────────────────────────
async function load(page=1) {
    const params = new URLSearchParams({
        page, per_page: document.getElementById('fPer').value||15,
        search: document.getElementById('fSearch').value||'',
        type:   document.getElementById('fType').value||'',
        status: document.getElementById('fStatus').value||'',
    });
    for (const [k,v] of [...params]) { if(!v) params.delete(k); }
    document.getElementById('tBody').innerHTML =
        `<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;
    try {
        const data = await api(`${BASE}/list?${params}`);
        if (!data.success) { document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Failed to load</td></tr>`; if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); } return; }
        renderTable(data.data);
        renderPagination(data.pagination, page);
        renderStats(data.data, data.pagination.total);
    } catch(e) {
        document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Error: ${e.message}</td></tr>`;
    }
    if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
}

const TYPE_COLORS = {
    percentage:'bg-blue-100 text-blue-700', fixed_amount:'bg-green-100 text-green-700',
    free_shipping:'bg-teal-100 text-teal-700', buy_x_get_y:'bg-purple-100 text-purple-700',
    flash_sale:'bg-orange-100 text-orange-700', bundle:'bg-pink-100 text-pink-700'
};
const TYPE_LABELS = {
    percentage:'% Off', fixed_amount:'₹ Off', free_shipping:'Free Ship',
    buy_x_get_y:'Buy X Get Y', flash_sale:'Flash Sale', bundle:'Bundle'
};
const STATUS_COLORS = {
    Active:'bg-green-100 text-green-700', Scheduled:'bg-yellow-100 text-yellow-700',
    Expired:'bg-red-100 text-red-700', Inactive:'bg-gray-100 text-gray-600'
};

function discountLabel(p) {
    if (p.type==='percentage'||p.type==='flash_sale') return p.discount_value+'% OFF';
    if (p.type==='fixed_amount') return '₹'+p.discount_value+' OFF';
    if (p.type==='free_shipping') return 'Free Shipping';
    if (p.type==='buy_x_get_y') return `Buy ${p.buy_quantity} Get ${p.get_quantity}`;
    if (p.type==='bundle') return 'Bundle Deal';
    return '-';
}
function statusLabel(p) {
    if (!p.is_active) return 'Inactive';
    const now = Date.now();
    if (p.starts_at && new Date(p.starts_at)>now) return 'Scheduled';
    if (p.ends_at   && new Date(p.ends_at)<now)   return 'Expired';
    return 'Active';
}
function fmtDate(d) { return d ? new Date(d).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}) : '—'; }

function renderTable(rows) {
    const tbody = document.getElementById('tBody');
    if (!rows.length) { tbody.innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">No promotions found</td></tr>`; return; }
    tbody.innerHTML = rows.map(p => {
        const sl = statusLabel(p);
        return `<tr class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-3.5"><input type="checkbox" class="row-chk w-4 h-4 text-[#0082C3] rounded border-gray-300" value="${p.id}" ${checkedIds.has(p.id)?'checked':''} onchange="onChk(${p.id},this.checked)"></td>
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-2">
                    ${p.badge_text?`<span class="px-2 py-0.5 rounded-full text-xs font-bold text-white" style="background:${p.badge_color||'#ef4444'}">${p.badge_text}</span>`:''}
                    <div>
                        <p class="text-sm font-semibold text-gray-900">${p.name}</p>
                        <p class="text-xs text-gray-400 font-mono">${p.slug}</p>
                    </div>
                </div>
            </td>
            <td class="px-5 py-3.5"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${TYPE_COLORS[p.type]||'bg-gray-100 text-gray-600'}">${TYPE_LABELS[p.type]||p.type}</span></td>
            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">${discountLabel(p)}</td>
            <td class="px-5 py-3.5 text-xs text-gray-500">${fmtDate(p.starts_at)}<br>${fmtDate(p.ends_at)}</td>
            <td class="px-5 py-3.5 text-sm text-gray-700">${p.used_count||0}${p.usage_limit?'/'+p.usage_limit:''}</td>
            <td class="px-5 py-3.5">
                <button onclick="toggleStatus(${p.id})" class="px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors ${STATUS_COLORS[sl]||'bg-gray-100 text-gray-600'}">${sl}</button>
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    <button onclick="openEdit(${p.id})" class="p-2 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <button onclick="del(${p.id},'${p.name.replace(/'/g,"&#39;")}')" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderStats(rows, total) {
    document.getElementById('sTotal').textContent = total;
    document.getElementById('sActive').textContent    = rows.filter(p=>statusLabel(p)==='Active').length;
    document.getElementById('sScheduled').textContent = rows.filter(p=>statusLabel(p)==='Scheduled').length;
    document.getElementById('sExpired').textContent   = rows.filter(p=>statusLabel(p)==='Expired').length;
    document.getElementById('sFlash').textContent     = rows.filter(p=>p.type==='flash_sale').length;
}

function renderPagination(p, cur) {
    const el = document.getElementById('pagination');
    if (p.last_page<=1) { el.innerHTML=`<p class="text-sm text-gray-500">Showing ${p.total} promotions</p>`; return; }
    let pages='';
    for(let i=1;i<=p.last_page;i++) {
        if(i===1||i===p.last_page||Math.abs(i-cur)<=2)
            pages+=`<button onclick="load(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium ${i===cur?'bg-[#0082C3] text-white':'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        else if(Math.abs(i-cur)===3) pages+=`<span class="px-1 text-gray-400">…</span>`;
    }
    el.innerHTML=`<p class="text-sm text-gray-500">Showing ${(cur-1)*p.per_page+1}–${Math.min(cur*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="load(${cur-1})" ${cur===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="load(${cur+1})" ${cur===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function debounceLoad() { clearTimeout(searchTimer); searchTimer=setTimeout(()=>load(1),400); }
</script>
@endpush

@push('scripts')
<script>
// ── Step Navigation ──────────────────────────────────────────────
function goStep(n) {
    step = n;
    [1,2,3].forEach(i => {
        document.getElementById(`s${i}`).style.display = i===n?'block':'none';
        const dot = document.getElementById(`step-dot-${i}`);
        const lbl = document.getElementById(`step-lbl-${i}`);
        const line = document.getElementById(`step-line-${i}`);
        if (i < n) {
            dot.className = 'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-green-500 text-white';
            dot.innerHTML = '✓';
            if (lbl) { lbl.className='text-xs font-medium hidden sm:block text-green-600'; }
            if (line) line.className = 'flex-1 h-0.5 bg-green-500 transition-all';
        } else if (i === n) {
            dot.className = 'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-[#0082C3] text-white';
            dot.innerHTML = i;
            if (lbl) lbl.className='text-xs font-medium hidden sm:block text-[#0082C3]';
            if (line) line.className = 'flex-1 h-0.5 bg-[#0082C3] transition-all';
        } else {
            dot.className = 'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-gray-200 text-gray-500';
            dot.innerHTML = i;
            if (lbl) lbl.className='text-xs font-medium hidden sm:block text-gray-400';
            if (line) line.className = 'flex-1 h-0.5 bg-gray-200 transition-all';
        }
    });
    const steps = ['Step 1 of 3 — Basic Info','Step 2 of 3 — Discount Rules','Step 3 of 3 — Schedule & Display'];
    document.getElementById('mStep').textContent = steps[n-1];
    document.getElementById('btnPrev').classList.toggle('hidden', n===1);
    document.getElementById('btnNext').style.display = n<3?'':'none';
    document.getElementById('btnSave').style.display = n===3?'':'none';
}

function nextStep() {
    if (step===1) {
        if (!document.getElementById('pName').value.trim()) { toast('Please enter a promotion name','error'); return; }
        if (!currentType) { toast('Please select a promotion type','error'); return; }
        updateDiscountUI();
    }
    if (step < 3) goStep(step+1);
}
function prevStep() { if (step>1) goStep(step-1); }

// ── Type Selection ───────────────────────────────────────────────
document.querySelectorAll('.type-card').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelectorAll('.type-card').forEach(c => {
            c.classList.remove('border-[#0082C3]','bg-blue-50');
            c.classList.add('border-gray-200');
        });
        card.classList.add('border-[#0082C3]','bg-blue-50');
        card.classList.remove('border-gray-200');
        card.querySelector('input').checked = true;
        currentType = card.dataset.val;
    });
});

function updateDiscountUI() {
    const isBxGy = currentType === 'buy_x_get_y';
    const isFreeShip = currentType === 'free_shipping';
    document.getElementById('r-discount').style.display = isBxGy||isFreeShip ? 'none' : 'block';
    document.getElementById('r-bxgy').style.display = isBxGy ? 'block' : 'none';
    document.getElementById('r-maxDisc').style.display = currentType==='percentage'||currentType==='flash_sale' ? 'block' : 'none';
    const sym = document.getElementById('discSymbol');
    if (sym) sym.textContent = currentType==='fixed_amount' ? '₹' : '%';
}

// ── Auto slug ────────────────────────────────────────────────────
function autoSlug() {
    if (!document.getElementById('pId').value) {
        document.getElementById('pSlug').value = document.getElementById('pName').value
            .toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'');
    }
}

// ── Badge color sync ─────────────────────────────────────────────
document.getElementById('pBadgeColor').addEventListener('input', function() {
    document.getElementById('pBadgeColorHex').value = this.value;
});

// ── Modal ────────────────────────────────────────────────────────
function openAdd() {
    document.getElementById('mTitle').textContent = 'Add Promotion';
    document.getElementById('pId').value = '';
    document.getElementById('pForm').reset();
    document.getElementById('pBadgeColor').value = '#ef4444';
    document.getElementById('pBadgeColorHex').value = '#ef4444';
    document.getElementById('pActive').checked = true;
    currentType = '';
    document.querySelectorAll('.type-card').forEach(c => { c.classList.remove('border-[#0082C3]','bg-blue-50'); c.classList.add('border-gray-200'); });
    goStep(1);
    showModal();
}

async function openEdit(id) {
    document.getElementById('mTitle').textContent = 'Edit Promotion';
    goStep(1);
    showModal();
    const data = await api(`${BASE}/${id}`);
    if (!data.success) { toast('Failed to load','error'); closeModal(); return; }
    const p = data.data;
    document.getElementById('pId').value = p.id;
    document.getElementById('pName').value = p.name;
    document.getElementById('pSlug').value = p.slug;
    document.getElementById('pDesc').value = p.description||'';
    currentType = p.type;
    document.querySelectorAll('.type-card').forEach(c => {
        const isActive = c.dataset.val === p.type;
        c.classList.toggle('border-[#0082C3]', isActive);
        c.classList.toggle('bg-blue-50', isActive);
        c.classList.toggle('border-gray-200', !isActive);
        if (isActive) c.querySelector('input').checked = true;
    });
    document.getElementById('pDiscVal').value = p.discount_value||'';
    document.getElementById('pMaxDisc').value = p.max_discount_amount||'';
    document.getElementById('pBuyQty').value = p.buy_quantity||'';
    document.getElementById('pGetQty').value = p.get_quantity||'';
    document.getElementById('pGetDisc').value = p.get_discount_percent||100;
    document.getElementById('pMinOrder').value = p.min_order_amount||'';
    document.getElementById('pMinQty').value = p.min_quantity||'';
    document.getElementById('pUsageLimit').value = p.usage_limit||'';
    document.getElementById('pPerUser').value = p.usage_per_user||1;
    document.getElementById('pAppliesTo').value = p.applies_to||'all';
    document.getElementById('pPriority').value = p.priority||0;
    document.getElementById('pStart').value = p.starts_at ? p.starts_at.replace(' ','T').substring(0,16) : '';
    document.getElementById('pEnd').value   = p.ends_at   ? p.ends_at.replace(' ','T').substring(0,16)   : '';
    document.getElementById('pBadge').value = p.badge_text||'';
    document.getElementById('pBadgeColor').value = p.badge_color||'#ef4444';
    document.getElementById('pBadgeColorHex').value = p.badge_color||'#ef4444';
    document.getElementById('pCountdown').checked = !!p.show_countdown;
    document.getElementById('pHomepage').checked  = !!p.show_on_homepage;
    document.getElementById('pActive').checked    = !!p.is_active;
}

function showModal() {
    document.getElementById('modal').classList.remove('hidden');
    requestAnimationFrame(() => { document.getElementById('mBox').style.transform='translateX(0)'; });
}
function closeModal() {
    document.getElementById('mBox').style.transform='translateX(100%)';
    setTimeout(()=>document.getElementById('modal').classList.add('hidden'),420);
}
function onBd(e) { if(e.target.id==='modal') closeModal(); }

// ── Save ─────────────────────────────────────────────────────────
async function save() {
    const id = document.getElementById('pId').value;
    const body = {
        name:               document.getElementById('pName').value.trim(),
        slug:               document.getElementById('pSlug').value.trim()||undefined,
        description:        document.getElementById('pDesc').value,
        type:               currentType,
        discount_value:     parseFloat(document.getElementById('pDiscVal').value)||0,
        max_discount_amount:parseFloat(document.getElementById('pMaxDisc').value)||null,
        buy_quantity:       parseInt(document.getElementById('pBuyQty').value)||null,
        get_quantity:       parseInt(document.getElementById('pGetQty').value)||null,
        get_discount_percent:parseFloat(document.getElementById('pGetDisc').value)||100,
        min_order_amount:   parseFloat(document.getElementById('pMinOrder').value)||null,
        min_quantity:       parseInt(document.getElementById('pMinQty').value)||null,
        usage_limit:        parseInt(document.getElementById('pUsageLimit').value)||null,
        usage_per_user:     parseInt(document.getElementById('pPerUser').value)||1,
        applies_to:         document.getElementById('pAppliesTo').value,
        priority:           parseInt(document.getElementById('pPriority').value)||0,
        starts_at:          document.getElementById('pStart').value||null,
        ends_at:            document.getElementById('pEnd').value||null,
        badge_text:         document.getElementById('pBadge').value,
        badge_color:        document.getElementById('pBadgeColorHex').value,
        show_countdown:     document.getElementById('pCountdown').checked,
        show_on_homepage:   document.getElementById('pHomepage').checked,
        is_active:          document.getElementById('pActive').checked,
    };
    if (!body.name) { toast('Name required','error'); goStep(1); return; }
    if (!body.type) { toast('Select a type','error'); goStep(1); return; }

    const btn = document.getElementById('btnSave');
    btn.disabled=true; btn.textContent='Saving…';
    const data = await api(id?`${BASE}/${id}`:BASE, id?'PUT':'POST', body);
    btn.disabled=false; btn.textContent='Save Promotion';

    if (data.success) { showSuccessDialog(id?'Promotion updated':'Promotion created'); closeModal(); load(1); }
    else { const e=data.errors?Object.values(data.errors)[0][0]:data.message; toast(e||'Error','error'); }
}

// ── Delete ───────────────────────────────────────────────────────
async function del(id, name) {
    showConfirmDialog('Delete Promotion',
        `Delete "<strong>${name}</strong>"?<br><span class="text-xs text-red-500">This cannot be undone.</span>`,
        async () => {
            const data = await api(`${BASE}/${id}`,'DELETE');
            if (data.success) { showSuccessDialog('Promotion deleted'); load(1); }
            else toast(data.message||'Error','error');
        });
}

// ── Toggle Status ────────────────────────────────────────────────
async function toggleStatus(id) {
    const data = await api(`${BASE}/${id}/toggle-status`,'POST');
    if (data.success) { toast('Status updated'); load(1); }
    else toast(data.message||'Error','error');
}

// ── Bulk ─────────────────────────────────────────────────────────
function onChk(id,checked) { checked?checkedIds.add(id):checkedIds.delete(id); syncBulk(); }
function toggleAll(chk) {
    document.querySelectorAll('.row-chk').forEach(el => {
        el.checked=chk.checked; chk.checked?checkedIds.add(parseInt(el.value)):checkedIds.delete(parseInt(el.value));
    }); syncBulk();
}
function clearSel() { checkedIds.clear(); document.querySelectorAll('.row-chk,#chkAll').forEach(el=>el.checked=false); syncBulk(); }
function syncBulk() {
    const bar=document.getElementById('bulkBar');
    checkedIds.size>0 ? bar.classList.remove('hidden') : bar.classList.add('hidden');
    document.getElementById('bulkCount').textContent=`${checkedIds.size} selected`;
}
async function applyBulk() {
    const action=document.getElementById('bulkAction').value;
    if (!action) { toast('Choose an action','warning'); return; }
    const doIt = async () => {
        const data = await api(`${BASE}/bulk-action`,'POST',{action,ids:[...checkedIds]});
        if (data.success) { if(action==='delete') showSuccessDialog(`${checkedIds.size} deleted`); else toast(data.message); clearSel(); load(1); }
        else toast(data.message||'Error','error');
    };
    if (action==='delete') showConfirmDialog('Delete Promotions',`Delete ${checkedIds.size} promotion(s)?`,doIt);
    else doIt();
}

// ── Confirm Dialog ───────────────────────────────────────────────
function showConfirmDialog(title, message, onConfirm) {
    const d = document.createElement('div');
    d.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4';
    d.innerHTML = `
        <style>
            @keyframes dlg-backdrop{from{opacity:0}to{opacity:1}}
            @keyframes dlg-pop{0%{opacity:0;transform:scale(0.85) translateY(16px)}60%{opacity:1;transform:scale(1.03) translateY(-3px)}80%{transform:scale(0.98) translateY(1px)}100%{opacity:1;transform:scale(1) translateY(0)}}
            .dlg-bg{animation:dlg-backdrop .2s ease forwards}
            .dlg-box{animation:dlg-pop .35s cubic-bezier(0.34,1.56,0.64,1) forwards}
        </style>
        <div class="dlg-bg fixed inset-0 bg-black/40 backdrop-blur-[2px]" onclick="this.parentElement.remove()"></div>
        <div class="dlg-box relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6">
            <div class="flex flex-col items-center text-center gap-3 mb-5">
                <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center">
                    <i data-lucide="trash-2" class="w-7 h-7 text-red-500"></i>
                </div>
                <div><h3 class="text-base font-semibold text-gray-900">${title}</h3><p class="text-sm text-gray-500 mt-1 leading-relaxed">${message}</p></div>
            </div>
            <div class="flex gap-2.5">
                <button onclick="this.closest('.fixed').remove()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">Cancel</button>
                <button id="_cok" class="flex-1 px-4 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition-colors">Delete</button>
            </div>
        </div>`;
    document.body.appendChild(d);
    d.querySelector('#_cok').addEventListener('click',()=>{ d.remove(); onConfirm(); });
}

// ── Success Dialog ───────────────────────────────────────────────
function showSuccessDialog(message='Done') {
    const d = document.createElement('div');
    d.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4';
    d.innerHTML = `
        <style>
            @keyframes succ-backdrop{from{opacity:0}to{opacity:1}}
            @keyframes succ-pop{0%{opacity:0;transform:scale(0.5)}60%{opacity:1;transform:scale(1.1)}80%{transform:scale(0.95)}100%{opacity:1;transform:scale(1)}}
            @keyframes succ-ring{0%{stroke-dashoffset:166;opacity:0}20%{opacity:1}100%{stroke-dashoffset:0}}
            @keyframes succ-check{0%{stroke-dashoffset:48;opacity:0}40%{opacity:0}100%{stroke-dashoffset:0;opacity:1}}
            @keyframes succ-msg{0%{opacity:0;transform:translateY(8px)}100%{opacity:1;transform:translateY(0)}}
            .succ-bg{animation:succ-backdrop .2s ease forwards}
            .succ-box{animation:succ-pop .4s cubic-bezier(0.34,1.56,0.64,1) forwards}
            .succ-ring{stroke-dasharray:166;stroke-dashoffset:166;animation:succ-ring .6s .1s cubic-bezier(0.65,0,0.45,1) forwards}
            .succ-check{stroke-dasharray:48;stroke-dashoffset:48;animation:succ-check .4s .5s cubic-bezier(0.65,0,0.45,1) forwards}
            .succ-msg{animation:succ-msg .3s .7s ease forwards;opacity:0}
        </style>
        <div class="succ-bg fixed inset-0 bg-black/30 backdrop-blur-[2px]"></div>
        <div class="succ-box relative bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 w-64">
            <svg class="w-20 h-20" viewBox="0 0 52 52">
                <circle class="succ-ring" cx="26" cy="26" r="25" fill="none" stroke="#22c55e" stroke-width="2"/>
                <path class="succ-check" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 27 l8 8 l16-16"/>
            </svg>
            <p class="succ-msg text-sm font-semibold text-gray-700 text-center">${message}</p>
        </div>`;
    document.body.appendChild(d);
    setTimeout(()=>{ d.style.transition='opacity .25s ease'; d.style.opacity='0'; setTimeout(()=>d.remove(),260); },1600);
}

// ── Init ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => load(1));
</script>
@endpush
