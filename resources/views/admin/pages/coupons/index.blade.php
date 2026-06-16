@extends('admin.layouts.app')
@section('title', 'Coupons')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Coupons</h1>
        <p class="text-sm text-gray-500 mt-0.5">Create and manage discount coupon codes</p>
    </div>
    <button onclick="openAdd()"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Create Coupon
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    @foreach([
        ['id'=>'sTotal',     'label'=>'Total',     'color'=>'blue',   'icon'=>'ticket'],
        ['id'=>'sActive',    'label'=>'Active',    'color'=>'green',  'icon'=>'circle-check'],
        ['id'=>'sScheduled', 'label'=>'Scheduled', 'color'=>'yellow', 'icon'=>'calendar'],
        ['id'=>'sExpired',   'label'=>'Expired',   'color'=>'red',    'icon'=>'clock'],
        ['id'=>'sUsed',      'label'=>'Total Used', 'color'=>'purple','icon'=>'users'],
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
        <input id="fSearch" type="text" placeholder="Search by code or name…"
               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
               oninput="debounceLoad()">
    </div>
    <select id="fType" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="">All Types</option>
        <option value="percentage">Percentage</option>
        <option value="fixed_amount">Fixed Amount</option>
        <option value="free_shipping">Free Shipping</option>
        <option value="buy_x_get_y">Buy X Get Y</option>
    </select>
    <select id="fStatus" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="scheduled">Scheduled</option>
        <option value="expired">Expired</option>
        <option value="exhausted">Exhausted</option>
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
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Discount</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Conditions</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usage</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Expires</th>
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

</div>

{{-- ══ MODAL ══ --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBd(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="mBox" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="mTitle" class="text-lg font-semibold text-gray-900">Create Coupon</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="mStep">Step 1 of 3 — Coupon Code & Type</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Step Progress --}}
        <div class="px-6 py-3 border-b border-gray-100 bg-white flex-shrink-0">
            <div class="flex items-center gap-2">
                @foreach([1=>'Code & Type', 2=>'Discount Value', 3=>'Conditions & Schedule'] as $n=>$label)
                <div class="flex items-center gap-2 {{ $n < 3 ? 'flex-1' : '' }}">
                    <div id="sdot-{{$n}}" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $n===1?'bg-[#0082C3] text-white':'bg-gray-200 text-gray-500' }}">{{$n}}</div>
                    <span id="slbl-{{$n}}" class="text-xs font-medium hidden sm:block {{ $n===1?'text-[#0082C3]':'text-gray-400' }}">{{$label}}</span>
                    @if($n < 3)<div id="sline-{{$n}}" class="flex-1 h-0.5 {{ $n===1?'bg-[#0082C3]':'bg-gray-200' }} transition-all"></div>@endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Form --}}
        <form id="cForm" class="flex-1 overflow-y-auto">
            <input type="hidden" id="cId">

            {{-- Step 1: Code & Type --}}
            <div id="s1" class="px-6 py-5 space-y-5">
                {{-- Code --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <input id="cCode" type="text" placeholder="e.g. SUMMER20"
                               class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               oninput="this.value=this.value.toUpperCase()">
                        <button type="button" onclick="genCode()"
                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors flex items-center gap-1.5">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            Generate
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Customers will enter this code at checkout</p>
                </div>
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internal Name <span class="text-red-500">*</span></label>
                    <input id="cName" type="text" placeholder="e.g. Summer Sale 2026"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <p class="text-xs text-gray-400 mt-1">For internal reference only — not shown to customers</p>
                </div>
                {{-- Discount Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            ['percentage',   '🏷️', 'Percentage Off',  'e.g. 20% off total'],
                            ['fixed_amount', '💰', 'Fixed Amount Off', 'e.g. ₹200 off total'],
                            ['free_shipping','🚚', 'Free Shipping',    'Remove delivery charge'],
                            ['buy_x_get_y',  '🎁', 'Buy X Get Y',     'Buy 2 get 1 free'],
                        ] as [$val,$icon,$label,$sub])
                        <label class="type-card cursor-pointer border-2 border-gray-200 rounded-xl p-3.5 hover:border-[#0082C3] transition-all flex items-start gap-3"
                               data-val="{{$val}}">
                            <input type="radio" name="cType" value="{{$val}}" class="hidden">
                            <span class="text-2xl leading-none mt-0.5">{{$icon}}</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">{{$label}}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{$sub}}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Step 2: Discount Value --}}
            <div id="s2" class="px-6 py-5 space-y-5" style="display:none">
                {{-- Percentage / Fixed --}}
                <div id="r-val">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span class="text-red-500">*</span></label>
                    <div class="flex gap-3 items-center">
                        <div class="relative flex-1">
                            <span id="discSym" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-sm">%</span>
                            <input id="cDiscVal" type="number" min="0" step="0.01" placeholder="0"
                                   class="w-full pl-8 pr-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div id="r-maxDisc" class="flex-1">
                            <input id="cMaxDisc" type="number" min="0" placeholder="Max discount (₹) — optional"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1" id="discHint">Enter percentage value (0–100)</p>
                </div>
                {{-- Buy X Get Y --}}
                <div id="r-bxgy" class="space-y-3" style="display:none">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Customer Buys (Qty)</label>
                            <input id="cBuyQty" type="number" min="1" placeholder="2"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Customer Gets (Qty)</label>
                            <input id="cGetQty" type="number" min="1" placeholder="1"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">At Discount %</label>
                            <input id="cGetDisc" type="number" min="0" max="100" placeholder="100"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">100% = free. 50% = half price.</p>
                </div>
                {{-- Free Shipping info --}}
                <div id="r-freeship" class="hidden p-4 bg-teal-50 border border-teal-200 rounded-xl">
                    <p class="text-sm text-teal-800 font-medium">🚚 Free Shipping Coupon</p>
                    <p class="text-xs text-teal-600 mt-1">This coupon will remove the delivery charge at checkout. You can set a minimum order amount in the next step.</p>
                </div>
                {{-- Applies To --}}
                <div class="border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Applies To</label>
                    <select id="cAppliesTo" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="all">All Products</option>
                        <option value="specific_products">Specific Products</option>
                        <option value="specific_categories">Specific Categories</option>
                        <option value="specific_brands">Specific Brands</option>
                    </select>
                </div>
                {{-- Customer Eligibility --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Eligibility</label>
                    <select id="cEligibility" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="all">All Customers</option>
                        <option value="new_customers">New Customers Only</option>
                        <option value="specific_customers">Specific Customers</option>
                    </select>
                </div>
            </div>

            {{-- Step 3: Conditions & Schedule --}}
            <div id="s3" class="px-6 py-5 space-y-5" style="display:none">
                {{-- Usage Limits --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Usage Limits</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Total Usage Limit</label>
                            <input id="cUsageLimit" type="number" min="1" placeholder="Unlimited"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Per Customer Limit</label>
                            <input id="cPerUser" type="number" min="1" value="1"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                </div>
                {{-- Minimum Requirements --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Minimum Requirements</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Min Order Amount (₹)</label>
                            <input id="cMinOrder" type="number" min="0" placeholder="No minimum"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Min Quantity</label>
                            <input id="cMinQty" type="number" min="0" placeholder="No minimum"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                </div>
                {{-- Active Dates --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Active Dates</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Start Date</label>
                            <input id="cStart" type="datetime-local"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Expiry Date</label>
                            <input id="cExpiry" type="datetime-local"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                </div>
                {{-- Combinations --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Combinations</h4>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50">
                            <input id="cCombineOther" type="checkbox" class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Combine with other coupons</p>
                                <p class="text-xs text-gray-400">Allow stacking multiple coupon codes</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50">
                            <input id="cCombinePromo" type="checkbox" checked class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Combine with promotions</p>
                                <p class="text-xs text-gray-400">Allow using with active promotions</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50">
                            <input id="cActive" type="checkbox" checked class="w-4 h-4 text-[#0082C3] rounded border-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-700">✅ Active</p>
                                <p class="text-xs text-gray-400">Enable this coupon immediately</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </form>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between flex-shrink-0">
            <button id="btnPrev" onclick="prevStep()" class="hidden px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">← Previous</button>
            <div class="flex gap-3 ml-auto">
                <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="btnNext" onclick="nextStep()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Next →</button>
                <button id="btnSave" onclick="save()" style="display:none" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">Save Coupon</button>
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
const BASE = '/admin/coupons';
let step=1, checkedIds=new Set(), searchTimer, currentType='percentage';
let isFirstLoad = true;

async function api(url,method='GET',body=null){
    const opts={method,credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':CSRF}};
    if(body){opts.headers['Content-Type']='application/json';opts.body=JSON.stringify(body);}
    const r=await fetch(url,opts);
    const ct=r.headers.get('content-type')||'';
    if(!ct.includes('json')){window.location.reload();return{success:false};}
    return r.json();
}

function toast(msg,type='success'){
    const el=document.getElementById('toast');
    el.textContent=msg;
    el.className=`fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type==='success'?'bg-green-600':type==='error'?'bg-red-600':'bg-yellow-500'}`;
    el.classList.remove('hidden');
    setTimeout(()=>el.classList.add('hidden'),3000);
}

// ── Load ─────────────────────────────────────────────────────────
async function load(page=1){
    const params=new URLSearchParams({page,per_page:document.getElementById('fPer').value||15,
        search:document.getElementById('fSearch').value||'',
        type:document.getElementById('fType').value||'',
        status:document.getElementById('fStatus').value||''});
    for(const[k,v]of[...params]){if(!v)params.delete(k);}
    document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;
    try{
        const data=await api(`${BASE}/list?${params}`);
        if(!data.success){document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Failed to load</td></tr>`;if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }return;}
        renderTable(data.data);
        renderPagination(data.pagination,page);
        renderStats(data.data,data.pagination.total);
    }catch(e){document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Error: ${e.message}</td></tr>`;}
    if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
}

const TYPE_COLORS={percentage:'bg-blue-100 text-blue-700',fixed_amount:'bg-green-100 text-green-700',free_shipping:'bg-teal-100 text-teal-700',buy_x_get_y:'bg-purple-100 text-purple-700'};
const TYPE_LABELS={percentage:'% Off',fixed_amount:'₹ Off',free_shipping:'Free Ship',buy_x_get_y:'Buy X Get Y'};
const STATUS_COLORS={active:'bg-green-100 text-green-700',scheduled:'bg-yellow-100 text-yellow-700',expired:'bg-red-100 text-red-700',exhausted:'bg-orange-100 text-orange-700',inactive:'bg-gray-100 text-gray-600'};
function fmtDate(d){return d?new Date(d).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}):'—';}

function renderTable(rows){
    const tbody=document.getElementById('tBody');
    if(!rows.length){tbody.innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">No coupons found</td></tr>`;return;}
    tbody.innerHTML=rows.map(c=>`
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-3.5"><input type="checkbox" class="row-chk w-4 h-4 text-[#0082C3] rounded border-gray-300" value="${c.id}" ${checkedIds.has(c.id)?'checked':''} onchange="onChk(${c.id},this.checked)"></td>
            <td class="px-5 py-3.5">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-900 text-white text-sm font-mono font-bold rounded-lg tracking-wider">${c.code}</span>
                    <p class="text-xs text-gray-500 mt-1">${c.name}</p>
                </div>
            </td>
            <td class="px-5 py-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${TYPE_COLORS[c.discount_type]||'bg-gray-100 text-gray-600'}">${TYPE_LABELS[c.discount_type]||c.discount_type}</span>
                <p class="text-sm font-semibold text-gray-800 mt-1">${c.discount_label||''}</p>
            </td>
            <td class="px-5 py-3.5 text-xs text-gray-500">
                ${c.min_order_amount?`Min ₹${c.min_order_amount}`:'No min order'}<br>
                ${c.applies_to==='all'?'All products':c.applies_to.replace('_',' ')}
            </td>
            <td class="px-5 py-3.5 text-sm text-gray-700">
                ${c.used_count||0}${c.usage_limit?'<span class="text-gray-400">/'+c.usage_limit+'</span>':''}
                <p class="text-xs text-gray-400">${c.usage_per_user?c.usage_per_user+'/customer':''}</p>
            </td>
            <td class="px-5 py-3.5 text-xs text-gray-500">${fmtDate(c.expires_at)}</td>
            <td class="px-5 py-3.5">
                <button onclick="toggleStatus(${c.id})" class="px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors ${STATUS_COLORS[c.status]||'bg-gray-100 text-gray-600'}">${c.status||'—'}</button>
            </td>
            <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    <button onclick="copyCode('${c.code}')" class="p-2 rounded-lg text-gray-500 hover:text-green-600 hover:bg-green-50 transition-colors" title="Copy Code">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                    <button onclick="openEdit(${c.id})" class="p-2 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <button onclick="del(${c.id},'${c.code}')" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </td>
        </tr>`).join('');
}

function renderStats(rows,total){
    document.getElementById('sTotal').textContent=total;
    document.getElementById('sActive').textContent=rows.filter(c=>c.status==='active').length;
    document.getElementById('sScheduled').textContent=rows.filter(c=>c.status==='scheduled').length;
    document.getElementById('sExpired').textContent=rows.filter(c=>c.status==='expired'||c.status==='exhausted').length;
    document.getElementById('sUsed').textContent=rows.reduce((a,c)=>a+(c.used_count||0),0);
}

function copyCode(code){
    navigator.clipboard.writeText(code).then(()=>{
        toast(code + ' copied to clipboard');
    }).catch(()=>{
        // Fallback for older browsers
        const el=document.createElement('textarea');
        el.value=code;
        el.style.position='fixed';
        el.style.opacity='0';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        toast(code + ' copied to clipboard');
    });
}

function renderPagination(p,cur){
    const el=document.getElementById('pagination');
    if(p.last_page<=1){el.innerHTML=`<p class="text-sm text-gray-500">Showing ${p.total} coupons</p>`;return;}
    let pages='';
    for(let i=1;i<=p.last_page;i++){
        if(i===1||i===p.last_page||Math.abs(i-cur)<=2)pages+=`<button onclick="load(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium ${i===cur?'bg-[#0082C3] text-white':'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        else if(Math.abs(i-cur)===3)pages+=`<span class="px-1 text-gray-400">…</span>`;
    }
    el.innerHTML=`<p class="text-sm text-gray-500">Showing ${(cur-1)*p.per_page+1}–${Math.min(cur*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="load(${cur-1})" ${cur===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="load(${cur+1})" ${cur===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function debounceLoad(){clearTimeout(searchTimer);searchTimer=setTimeout(()=>load(1),400);}

// ── Step Navigation ──────────────────────────────────────────────
function goStep(n){
    step=n;
    [1,2,3].forEach(i=>{
        document.getElementById(`s${i}`).style.display=i===n?'block':'none';
        const dot=document.getElementById(`sdot-${i}`);
        const lbl=document.getElementById(`slbl-${i}`);
        const line=document.getElementById(`sline-${i}`);
        if(i<n){
            dot.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-green-500 text-white';
            dot.innerHTML='✓';
            if(lbl)lbl.className='text-xs font-medium hidden sm:block text-green-600';
            if(line)line.className='flex-1 h-0.5 bg-green-500 transition-all';
        }else if(i===n){
            dot.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-[#0082C3] text-white';
            dot.innerHTML=i;
            if(lbl)lbl.className='text-xs font-medium hidden sm:block text-[#0082C3]';
            if(line)line.className='flex-1 h-0.5 bg-[#0082C3] transition-all';
        }else{
            dot.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-gray-200 text-gray-500';
            dot.innerHTML=i;
            if(lbl)lbl.className='text-xs font-medium hidden sm:block text-gray-400';
            if(line)line.className='flex-1 h-0.5 bg-gray-200 transition-all';
        }
    });
    const steps=['Step 1 of 3 — Coupon Code & Type','Step 2 of 3 — Discount Value','Step 3 of 3 — Conditions & Schedule'];
    document.getElementById('mStep').textContent=steps[n-1];
    document.getElementById('btnPrev').classList.toggle('hidden',n===1);
    document.getElementById('btnNext').style.display=n<3?'':'none';
    document.getElementById('btnSave').style.display=n===3?'':'none';
}

function nextStep(){
    if(step===1){
        if(!document.getElementById('cCode').value.trim()){toast('Please enter a coupon code','error');return;}
        if(!document.getElementById('cName').value.trim()){toast('Please enter a name','error');return;}
        if(!currentType){toast('Please select a discount type','error');return;}
        updateDiscountUI();
    }
    if(step<3)goStep(step+1);
}
function prevStep(){if(step>1)goStep(step-1);}

// ── Type Selection ───────────────────────────────────────────────
document.querySelectorAll('.type-card').forEach(card=>{
    card.addEventListener('click',()=>{
        document.querySelectorAll('.type-card').forEach(c=>{c.classList.remove('border-[#0082C3]','bg-blue-50');c.classList.add('border-gray-200');});
        card.classList.add('border-[#0082C3]','bg-blue-50');card.classList.remove('border-gray-200');
        card.querySelector('input').checked=true;
        currentType=card.dataset.val;
    });
});

function updateDiscountUI(){
    const isBxGy=currentType==='buy_x_get_y';
    const isFree=currentType==='free_shipping';
    document.getElementById('r-val').style.display=isBxGy||isFree?'none':'block';
    document.getElementById('r-bxgy').style.display=isBxGy?'block':'none';
    document.getElementById('r-freeship').classList.toggle('hidden',!isFree);
    document.getElementById('r-maxDisc').style.display=currentType==='percentage'?'block':'none';
    const sym=document.getElementById('discSym');
    if(sym)sym.textContent=currentType==='fixed_amount'?'₹':'%';
    const hint=document.getElementById('discHint');
    if(hint)hint.textContent=currentType==='fixed_amount'?'Enter flat amount in ₹':'Enter percentage value (0–100)';
}

// ── Generate Code ────────────────────────────────────────────────
async function genCode(){
    const data=await api(`${BASE}/generate-code`);
    if(data.success)document.getElementById('cCode').value=data.code;
}

// ── Modal ────────────────────────────────────────────────────────
function openAdd(){
    document.getElementById('mTitle').textContent='Create Coupon';
    document.getElementById('cId').value='';
    document.getElementById('cForm').reset();
    document.getElementById('cActive').checked=true;
    document.getElementById('cCombinePromo').checked=true;
    currentType='';
    document.querySelectorAll('.type-card').forEach(c=>{c.classList.remove('border-[#0082C3]','bg-blue-50');c.classList.add('border-gray-200');});
    goStep(1);showModal();
}

async function openEdit(id){
    document.getElementById('mTitle').textContent='Edit Coupon';
    goStep(1);showModal();
    const data=await api(`${BASE}/${id}`);
    if(!data.success){toast('Failed to load','error');closeModal();return;}
    const c=data.data;
    document.getElementById('cId').value=c.id;
    document.getElementById('cCode').value=c.code;
    document.getElementById('cName').value=c.name;
    currentType=c.discount_type;
    document.querySelectorAll('.type-card').forEach(card=>{
        const active=card.dataset.val===c.discount_type;
        card.classList.toggle('border-[#0082C3]',active);card.classList.toggle('bg-blue-50',active);card.classList.toggle('border-gray-200',!active);
        if(active)card.querySelector('input').checked=true;
    });
    document.getElementById('cDiscVal').value=c.discount_value||'';
    document.getElementById('cMaxDisc').value=c.max_discount_amount||'';
    document.getElementById('cBuyQty').value=c.buy_quantity||'';
    document.getElementById('cGetQty').value=c.get_quantity||'';
    document.getElementById('cGetDisc').value=c.get_discount_percent||100;
    document.getElementById('cAppliesTo').value=c.applies_to||'all';
    document.getElementById('cEligibility').value=c.customer_eligibility||'all';
    document.getElementById('cUsageLimit').value=c.usage_limit||'';
    document.getElementById('cPerUser').value=c.usage_per_user||1;
    document.getElementById('cMinOrder').value=c.min_order_amount||'';
    document.getElementById('cMinQty').value=c.min_quantity||'';
    document.getElementById('cStart').value=c.starts_at?c.starts_at.replace(' ','T').substring(0,16):'';
    document.getElementById('cExpiry').value=c.expires_at?c.expires_at.replace(' ','T').substring(0,16):'';
    document.getElementById('cCombineOther').checked=!!c.combine_with_other_coupons;
    document.getElementById('cCombinePromo').checked=!!c.combine_with_promotions;
    document.getElementById('cActive').checked=!!c.is_active;
}

function showModal(){document.getElementById('modal').classList.remove('hidden');requestAnimationFrame(()=>{document.getElementById('mBox').style.transform='translateX(0)';});}
function closeModal(){document.getElementById('mBox').style.transform='translateX(100%)';setTimeout(()=>document.getElementById('modal').classList.add('hidden'),420);}
function onBd(e){if(e.target.id==='modal')closeModal();}

// ── Save ─────────────────────────────────────────────────────────
async function save(){
    const id=document.getElementById('cId').value;
    const body={
        code:document.getElementById('cCode').value.trim().toUpperCase(),
        name:document.getElementById('cName').value.trim(),
        discount_type:currentType,
        discount_value:parseFloat(document.getElementById('cDiscVal').value)||0,
        max_discount_amount:parseFloat(document.getElementById('cMaxDisc').value)||null,
        buy_quantity:parseInt(document.getElementById('cBuyQty').value)||null,
        get_quantity:parseInt(document.getElementById('cGetQty').value)||null,
        get_discount_percent:parseFloat(document.getElementById('cGetDisc').value)||100,
        applies_to:document.getElementById('cAppliesTo').value,
        customer_eligibility:document.getElementById('cEligibility').value,
        usage_limit:parseInt(document.getElementById('cUsageLimit').value)||null,
        usage_per_user:parseInt(document.getElementById('cPerUser').value)||1,
        min_order_amount:parseFloat(document.getElementById('cMinOrder').value)||null,
        min_quantity:parseInt(document.getElementById('cMinQty').value)||null,
        starts_at:document.getElementById('cStart').value||null,
        expires_at:document.getElementById('cExpiry').value||null,
        combine_with_other_coupons:document.getElementById('cCombineOther').checked,
        combine_with_promotions:document.getElementById('cCombinePromo').checked,
        is_active:document.getElementById('cActive').checked,
    };
    if(!body.code){toast('Code required','error');goStep(1);return;}
    if(!body.name){toast('Name required','error');goStep(1);return;}
    if(!body.discount_type){toast('Select a type','error');goStep(1);return;}

    const btn=document.getElementById('btnSave');
    btn.disabled=true;btn.textContent='Saving…';
    const data=await api(id?`${BASE}/${id}`:BASE,id?'PUT':'POST',body);
    btn.disabled=false;btn.textContent='Save Coupon';

    if(data.success){
        Dialog.alert({ title: 'Success!', message: id?'Coupon updated':'Coupon created', type: 'success' });
        closeModal();load(1);
    }
    else{const e=data.errors?Object.values(data.errors)[0][0]:data.message;toast(e||'Error','error');}
}

// ── Delete / Toggle / Bulk ───────────────────────────────────────
async function del(id,code){
    const confirmed = await Dialog.confirm({
        title: 'Delete Coupon',
        message: `Delete coupon <strong class="font-mono">${code}</strong>?<br><span class="text-xs text-red-500">This cannot be undone.</span>`,
        type: 'danger'
    });
    if (!confirmed) return;
    
    const data=await api(`${BASE}/${id}`,'DELETE');
    if(data.success){
        Dialog.alert({ title: 'Deleted!', message: 'Coupon deleted', type: 'success' });
        load(1);
    } else toast(data.message||'Error','error');
}
async function toggleStatus(id){const data=await api(`${BASE}/${id}/toggle-status`,'POST');if(data.success){toast('Status updated');load(1);}else toast(data.message||'Error','error');}
function onChk(id,checked){checked?checkedIds.add(id):checkedIds.delete(id);syncBulk();}
function toggleAll(chk){document.querySelectorAll('.row-chk').forEach(el=>{el.checked=chk.checked;chk.checked?checkedIds.add(parseInt(el.value)):checkedIds.delete(parseInt(el.value));});syncBulk();}
function clearSel(){checkedIds.clear();document.querySelectorAll('.row-chk,#chkAll').forEach(el=>el.checked=false);syncBulk();}
function syncBulk(){const bar=document.getElementById('bulkBar');checkedIds.size>0?bar.classList.remove('hidden'):bar.classList.add('hidden');document.getElementById('bulkCount').textContent=`${checkedIds.size} selected`;}
async function applyBulk(){
    const action=document.getElementById('bulkAction').value;
    if(!action){toast('Choose an action','warning');return;}
    
    if(action==='delete') {
        const confirmed = await Dialog.confirm({
            title: 'Delete Coupons',
            message: `Delete ${checkedIds.size} coupon(s)?`,
            type: 'danger'
        });
        if (!confirmed) return;
    }
    
    const data=await api(`${BASE}/bulk-action`,'POST',{action,ids:[...checkedIds]});
    if(data.success){
        if(action==='delete') Dialog.alert({ title: 'Deleted!', message: `${checkedIds.size} deleted`, type: 'success' });
        else toast(data.message);
        clearSel();load(1);
    }else toast(data.message||'Error','error');
}

document.addEventListener('DOMContentLoaded',()=>load(1));
</script>
@endpush
