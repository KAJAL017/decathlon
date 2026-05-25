@extends('admin.layouts.app')
@section('title', 'Stock Management')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Stock Management</h1>
        <p class="text-sm text-gray-500 mt-0.5">Track inventory, record movements and manage stock levels</p>
    </div>
    <button onclick="openAdd()"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Record Movement
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div><p class="text-xs text-gray-500">Total Products</p><p class="text-xl font-bold text-gray-900" id="sTot">{{ $summary['total'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div><p class="text-xs text-gray-500">In Stock</p><p class="text-xl font-bold text-green-700" id="sIn">{{ $summary['in_stock'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div><p class="text-xs text-gray-500">Low Stock</p><p class="text-xl font-bold text-yellow-700" id="sLow">{{ $summary['low_stock'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <div><p class="text-xs text-gray-500">Out of Stock</p><p class="text-xl font-bold text-red-700" id="sOut">{{ $summary['out_of_stock'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div><p class="text-xs text-gray-500">Stock Value</p><p class="text-xl font-bold text-purple-700" id="sVal">₹{{ number_format($summary['total_value'],0) }}</p></div>
    </div>
</div>

{{-- Tabs --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="border-b border-gray-200 px-5 flex gap-1 pt-3">
        <button id="tab-overview" onclick="switchTab('overview')"
                class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition-colors border-b-2 border-[#0082C3] text-[#0082C3]">
            📦 Stock Overview
        </button>
        <button id="tab-movements" onclick="switchTab('movements')"
                class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700">
            📋 Movements
        </button>
        <button id="tab-low" onclick="switchTab('low')"
                class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700 flex items-center gap-1.5">
            ⚠️ Low Stock
            @if($summary['low_stock'] > 0)
            <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $summary['low_stock'] }}</span>
            @endif
        </button>
    </div>

    {{-- Overview Tab --}}
    <div id="content-overview" class="p-5">
        <div class="flex flex-wrap gap-3 mb-4">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input id="ovSearch" type="text" placeholder="Search products…"
                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                       oninput="debounceOv()">
            </div>
            <select id="ovStatus" onchange="loadOverview(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <option value="">All Status</option>
                <option value="in">In Stock</option>
                <option value="low">Low Stock</option>
                <option value="out">Out of Stock</option>
            </select>
            <select id="ovPer" onchange="loadOverview(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <option value="20">20/page</option>
                <option value="50">50/page</option>
                <option value="100">100/page</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock Qty</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Low Stock Alert</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="ovBody" class="divide-y divide-gray-100">
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>
                </tbody>
            </table>
        </div>
        <div id="ovPagination" class="mt-4 flex items-center justify-between"></div>
    </div>

    {{-- Movements Tab --}}
    <div id="content-movements" style="display:none" class="p-5">
        <div class="flex flex-wrap gap-3 mb-4">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input id="mvSearch" type="text" placeholder="Search by product…"
                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                       oninput="debounceMv()">
            </div>
            <select id="mvType" onchange="loadMovements(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <option value="">All Types</option>
                <option value="purchase">Purchase</option>
                <option value="sale">Sale</option>
                <option value="return">Return</option>
                <option value="adjustment">Adjustment</option>
                <option value="transfer">Transfer</option>
                <option value="damage">Damage</option>
                <option value="expired">Expired</option>
            </select>
            <input id="mvFrom" type="date" onchange="loadMovements(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <input id="mvTo" type="date" onchange="loadMovements(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty Change</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Before → After</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">By / Date</th>
                    </tr>
                </thead>
                <tbody id="mvBody" class="divide-y divide-gray-100">
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>
                </tbody>
            </table>
        </div>
        <div id="mvPagination" class="mt-4 flex items-center justify-between"></div>
    </div>

    {{-- Low Stock Tab --}}
    <div id="content-low" style="display:none" class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Current Stock</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Alert Threshold</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Urgency</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="lowBody" class="divide-y divide-gray-100">
                    <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

{{-- ══ MODAL — 2-Step ══ --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBd(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="mBox" class="fixed right-0 top-0 h-full w-full max-w-xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Record Stock Movement</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="mStep">Step 1 of 2 — Select Product</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Step Progress --}}
        <div class="px-6 py-3 border-b border-gray-100 bg-white flex-shrink-0">
            <div class="flex items-center gap-2">
                <div id="sdot-1" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-[#0082C3] text-white transition-all">1</div>
                <span id="slbl-1" class="text-xs font-medium text-[#0082C3]">Select Product</span>
                <div id="sline-1" class="flex-1 h-0.5 bg-[#0082C3] transition-all"></div>
                <div id="sdot-2" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-gray-200 text-gray-500 transition-all">2</div>
                <span id="slbl-2" class="text-xs font-medium text-gray-400">Movement Details</span>
            </div>
        </div>

        <form id="sForm" class="flex-1 overflow-y-auto">
            {{-- Step 1: Product Selection --}}
            <div id="s1" class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Product <span class="text-red-500">*</span></label>
                    <input id="productSearch" type="text" placeholder="Type product name or SKU…"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                           oninput="searchProducts()">
                    <div id="productResults" class="mt-2 border border-gray-200 rounded-lg overflow-hidden hidden max-h-64 overflow-y-auto"></div>
                </div>
                {{-- Selected product card --}}
                <div id="selectedProductCard" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-blue-900" id="selProdName">—</p>
                            <p class="text-xs text-blue-600 mt-0.5">SKU: <span id="selProdSku">—</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-blue-600">Current Stock</p>
                            <p class="text-2xl font-bold text-blue-900" id="selProdStock">—</p>
                        </div>
                    </div>
                    <input type="hidden" id="selProdId">
                </div>
            </div>

            {{-- Step 2: Movement Details --}}
            <div id="s2" class="px-6 py-5 space-y-4" style="display:none">
                {{-- Movement Type Cards --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Movement Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['purchase',   '📥', 'Purchase',   'Stock received from supplier', 'add'],
                            ['sale',       '📤', 'Sale',       'Stock sold to customer',       'remove'],
                            ['return',     '↩️', 'Return',     'Customer returned item',       'add'],
                            ['adjustment', '⚖️', 'Adjustment', 'Manual stock correction',      'add'],
                            ['transfer',   '🔄', 'Transfer',   'Move between locations',       'add'],
                            ['damage',     '💔', 'Damage',     'Damaged/broken stock',         'remove'],
                        ] as [$val,$icon,$label,$sub,$dir])
                        <label class="mv-type-card cursor-pointer border-2 border-gray-200 rounded-xl p-3 hover:border-[#0082C3] transition-all flex items-start gap-2"
                               data-val="{{$val}}" data-dir="{{$dir}}">
                            <input type="radio" name="mvType" value="{{$val}}" class="hidden">
                            <span class="text-lg leading-none mt-0.5">{{$icon}}</span>
                            <div>
                                <div class="text-xs font-semibold text-gray-800">{{$label}}</div>
                                <div class="text-xs text-gray-400">{{$sub}}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Direction + Quantity --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Direction</label>
                        <div class="flex gap-2">
                            <label class="flex-1 flex items-center gap-2 cursor-pointer p-2.5 border-2 border-gray-200 rounded-lg hover:border-green-400 transition-all" id="dir-add">
                                <input type="radio" name="mvDir" value="add" class="w-4 h-4 text-green-500" checked>
                                <span class="text-sm font-medium text-green-700">➕ Add</span>
                            </label>
                            <label class="flex-1 flex items-center gap-2 cursor-pointer p-2.5 border-2 border-gray-200 rounded-lg hover:border-red-400 transition-all" id="dir-remove">
                                <input type="radio" name="mvDir" value="remove" class="w-4 h-4 text-red-500">
                                <span class="text-sm font-medium text-red-700">➖ Remove</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <input id="mvQty" type="number" min="1" value="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               oninput="updatePreview()">
                    </div>
                </div>

                {{-- Stock Preview --}}
                <div id="stockPreview" class="p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Current Stock:</span>
                        <span class="font-semibold" id="prevCurrent">—</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-1">
                        <span class="text-gray-600">After Movement:</span>
                        <span class="font-bold text-lg" id="prevAfter">—</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost Per Unit (₹)</label>
                        <input id="mvCost" type="number" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input id="mvLocation" type="text" value="Main Warehouse"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="mvNotes" rows="2" placeholder="Reason for this movement…"
                              class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"></textarea>
                </div>
            </div>
        </form>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between flex-shrink-0">
            <button id="btnPrev" onclick="prevStep()" class="hidden px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">← Previous</button>
            <div class="flex gap-3 ml-auto">
                <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="btnNext" onclick="nextStep()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Next →</button>
                <button id="btnSave" onclick="save()" style="display:none" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">Record Movement</button>
            </div>
        </div>
    </div>
</div>

{{-- Adjust Stock Modal --}}
<div id="adjustModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50" onclick="closeAdjust()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6" style="animation:dlg-pop .35s cubic-bezier(0.34,1.56,0.64,1) both">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Adjust Stock</h3>
        <input type="hidden" id="adjProdId">
        <p class="text-sm text-gray-600 mb-1">Product: <strong id="adjProdName">—</strong></p>
        <p class="text-sm text-gray-600 mb-4">Current: <strong id="adjCurrent">—</strong></p>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">New Stock Quantity</label>
            <input id="adjQty" type="number" min="0"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
            <input id="adjNotes" type="text" placeholder="e.g. Physical count correction"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div class="flex gap-3">
            <button onclick="closeAdjust()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">Cancel</button>
            <button onclick="saveAdjust()" class="flex-1 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3] transition-colors">Save</button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium"></div>

@endsection

@push('scripts')
<script>
const CSRF=document.querySelector('meta[name="csrf-token"]').content;
const BASE='/admin/stock';
let step=1, selProduct=null, currentType='', ovTimer, mvTimer, searchTimer;

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

// ── Tab Switch ───────────────────────────────────────────────────
function switchTab(name){
    ['overview','movements','low'].forEach(t=>{
        document.getElementById(`content-${t}`).style.display=t===name?'block':'none';
        const btn=document.getElementById(`tab-${t}`);
        if(btn){
            btn.className=t===name
                ?'px-4 py-2.5 text-sm font-medium rounded-t-lg transition-colors border-b-2 border-[#0082C3] text-[#0082C3]'
                :'px-4 py-2.5 text-sm font-medium rounded-t-lg transition-colors border-b-2 border-transparent text-gray-500 hover:text-gray-700 flex items-center gap-1.5';
        }
    });
    if(name==='overview')loadOverview(1);
    if(name==='movements')loadMovements(1);
    if(name==='low')loadLowStock();
}

// ── Overview ─────────────────────────────────────────────────────
async function loadOverview(page=1){
    const params=new URLSearchParams({page,per_page:document.getElementById('ovPer').value||20,
        search:document.getElementById('ovSearch').value||'',
        stock_status:document.getElementById('ovStatus').value||''});
    for(const[k,v]of[...params]){if(!v)params.delete(k);}
    document.getElementById('ovBody').innerHTML=`<tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;
    const data=await api(`${BASE}/list?tab=overview&${params}`);
    if(!data.success){document.getElementById('ovBody').innerHTML=`<tr><td colspan="6" class="px-4 py-12 text-center text-red-500 text-sm">Failed to load</td></tr>`;return;}
    renderOverview(data.data);
    renderPagination('ovPagination',data.pagination,page,'loadOverview');
    if(data.summary)updateStats(data.summary);
}

const STATUS_MAP={in:{cls:'bg-green-100 text-green-700',label:'In Stock'},low:{cls:'bg-yellow-100 text-yellow-700',label:'Low Stock'},out:{cls:'bg-red-100 text-red-700',label:'Out of Stock'}};

function renderOverview(rows){
    const tbody=document.getElementById('ovBody');
    if(!rows.length){tbody.innerHTML=`<tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">No products found</td></tr>`;return;}
    tbody.innerHTML=rows.map(p=>{
        const st=STATUS_MAP[p.stock_status]||STATUS_MAP.in;
        const pct=p.low_stock_threshold>0?Math.min(100,Math.round((p.stock_quantity/p.low_stock_threshold)*100)):100;
        const barColor=p.stock_status==='out'?'bg-red-500':p.stock_status==='low'?'bg-yellow-500':'bg-green-500';
        return `<tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3.5">
                <p class="text-sm font-semibold text-gray-900">${p.name}</p>
            </td>
            <td class="px-4 py-3.5 text-xs text-gray-500 font-mono">${p.sku||'—'}</td>
            <td class="px-4 py-3.5 text-center">
                <span class="text-lg font-bold ${p.stock_quantity<=0?'text-red-600':p.stock_status==='low'?'text-yellow-600':'text-gray-900'}">${p.stock_quantity||0}</span>
                <div class="w-20 h-1.5 bg-gray-200 rounded-full mx-auto mt-1 overflow-hidden">
                    <div class="h-full ${barColor} rounded-full" style="width:${pct}%"></div>
                </div>
            </td>
            <td class="px-4 py-3.5 text-center text-sm text-gray-600">${p.low_stock_threshold||5}</td>
            <td class="px-4 py-3.5 text-center"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${st.cls}">${st.label}</span></td>
            <td class="px-4 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    <button onclick="openAddForProduct(${p.id},'${p.name.replace(/'/g,"&#39;")}',${p.stock_quantity||0})"
                            class="px-3 py-1.5 bg-[#0082C3] text-white text-xs font-medium rounded-lg hover:bg-[#006ba3] transition-colors">
                        + Movement
                    </button>
                    <button onclick="openAdjust(${p.id},'${p.name.replace(/'/g,"&#39;")}',${p.stock_quantity||0})"
                            class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Adjust
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function debounceOv(){clearTimeout(ovTimer);ovTimer=setTimeout(()=>loadOverview(1),400);}

// ── Movements ────────────────────────────────────────────────────
async function loadMovements(page=1){
    const params=new URLSearchParams({page,per_page:20,
        search:document.getElementById('mvSearch').value||'',
        type:document.getElementById('mvType').value||'',
        date_from:document.getElementById('mvFrom').value||'',
        date_to:document.getElementById('mvTo').value||''});
    for(const[k,v]of[...params]){if(!v)params.delete(k);}
    document.getElementById('mvBody').innerHTML=`<tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;
    const data=await api(`${BASE}/list?tab=movements&${params}`);
    if(!data.success){document.getElementById('mvBody').innerHTML=`<tr><td colspan="6" class="px-4 py-12 text-center text-red-500 text-sm">Failed to load</td></tr>`;return;}
    renderMovements(data.data);
    renderPagination('mvPagination',data.pagination,page,'loadMovements');
}

const MV_COLORS={purchase:'bg-green-100 text-green-700',sale:'bg-blue-100 text-blue-700',return:'bg-purple-100 text-purple-700',adjustment:'bg-yellow-100 text-yellow-700',transfer:'bg-teal-100 text-teal-700',damage:'bg-red-100 text-red-700',expired:'bg-gray-100 text-gray-600'};

function renderMovements(rows){
    const tbody=document.getElementById('mvBody');
    if(!rows.length){tbody.innerHTML=`<tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">No movements found</td></tr>`;return;}
    tbody.innerHTML=rows.map(m=>{
        const isAdd=m.quantity>=0;
        const d=new Date(m.created_at);
        return `<tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3.5">
                <p class="text-sm font-semibold text-gray-900">${m.product?.name||'—'}</p>
                <p class="text-xs text-gray-400 font-mono">${m.product?.sku||''}</p>
            </td>
            <td class="px-4 py-3.5"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${MV_COLORS[m.type]||'bg-gray-100 text-gray-600'}">${m.type}</span></td>
            <td class="px-4 py-3.5 text-center">
                <span class="text-base font-bold ${isAdd?'text-green-600':'text-red-600'}">${isAdd?'+':''}${m.quantity}</span>
            </td>
            <td class="px-4 py-3.5 text-center text-sm text-gray-600">${m.quantity_before} → <strong>${m.quantity_after}</strong></td>
            <td class="px-4 py-3.5 text-xs text-gray-500 max-w-xs truncate">${m.notes||'—'}</td>
            <td class="px-4 py-3.5 text-xs text-gray-500">${m.creator?.name||'System'}<br>${d.toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</td>
        </tr>`;
    }).join('');
}

function debounceMv(){clearTimeout(mvTimer);mvTimer=setTimeout(()=>loadMovements(1),400);}

// ── Low Stock ────────────────────────────────────────────────────
async function loadLowStock(){
    document.getElementById('lowBody').innerHTML=`<tr><td colspan="5" class="px-4 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;
    const data=await api(`${BASE}/low-stock`);
    if(!data.success){document.getElementById('lowBody').innerHTML=`<tr><td colspan="5" class="px-4 py-12 text-center text-red-500 text-sm">Failed to load</td></tr>`;return;}
    const rows=data.data;
    if(!rows.length){document.getElementById('lowBody').innerHTML=`<tr><td colspan="5" class="px-4 py-12 text-center text-green-600 text-sm font-medium">✅ All products are well stocked!</td></tr>`;return;}
    document.getElementById('lowBody').innerHTML=rows.map(p=>{
        const pct=p.low_stock_threshold>0?Math.round((p.stock_quantity/p.low_stock_threshold)*100):0;
        const urgency=p.stock_quantity<=0?'🔴 Critical':pct<=30?'🟠 High':'🟡 Medium';
        return `<tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3.5"><p class="text-sm font-semibold text-gray-900">${p.name}</p><p class="text-xs text-gray-400 font-mono">${p.sku||''}</p></td>
            <td class="px-4 py-3.5 text-center"><span class="text-xl font-bold ${p.stock_quantity<=0?'text-red-600':'text-yellow-600'}">${p.stock_quantity}</span></td>
            <td class="px-4 py-3.5 text-center text-sm text-gray-600">${p.low_stock_threshold}</td>
            <td class="px-4 py-3.5 text-center text-sm">${urgency}</td>
            <td class="px-4 py-3.5 text-right">
                <button onclick="openAddForProduct(${p.id},'${p.name.replace(/'/g,"&#39;")}',${p.stock_quantity})"
                        class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Restock
                </button>
            </td>
        </tr>`;
    }).join('');
}

// ── Pagination ───────────────────────────────────────────────────
function renderPagination(elId,p,cur,fn){
    const el=document.getElementById(elId);
    if(!p||p.last_page<=1){el.innerHTML=`<p class="text-sm text-gray-500">Showing ${p?.total||0} items</p>`;return;}
    let pages='';
    for(let i=1;i<=p.last_page;i++){
        if(i===1||i===p.last_page||Math.abs(i-cur)<=2)pages+=`<button onclick="${fn}(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium ${i===cur?'bg-[#0082C3] text-white':'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        else if(Math.abs(i-cur)===3)pages+=`<span class="px-1 text-gray-400">…</span>`;
    }
    el.innerHTML=`<p class="text-sm text-gray-500">Showing ${(cur-1)*p.per_page+1}–${Math.min(cur*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="${fn}(${cur-1})" ${cur===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="${fn}(${cur+1})" ${cur===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function updateStats(s){
    document.getElementById('sTot').textContent=s.total||0;
    document.getElementById('sIn').textContent=s.in_stock||0;
    document.getElementById('sLow').textContent=s.low_stock||0;
    document.getElementById('sOut').textContent=s.out_of_stock||0;
    document.getElementById('sVal').textContent='₹'+new Intl.NumberFormat('en-IN').format(s.total_value||0);
}

// ── Product Search ───────────────────────────────────────────────
let searchDebounce;
async function searchProducts(){
    clearTimeout(searchDebounce);
    const q=document.getElementById('productSearch').value.trim();
    if(q.length<2){document.getElementById('productResults').classList.add('hidden');return;}
    searchDebounce=setTimeout(async()=>{
        const data=await api(`/admin/products/list?search=${encodeURIComponent(q)}&per_page=10`);
        const res=document.getElementById('productResults');
        if(!data.success||!data.data.length){res.innerHTML=`<div class="px-4 py-3 text-sm text-gray-400">No products found</div>`;res.classList.remove('hidden');return;}
        res.innerHTML=data.data.map(p=>`
            <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors"
                 onclick="selectProduct(${p.id},'${p.name.replace(/'/g,"&#39;")}','${p.sku_prefix||''}',${p.stock_quantity||0})">
                <p class="text-sm font-medium text-gray-900">${p.name}</p>
                <p class="text-xs text-gray-400">SKU: ${p.sku_prefix||'—'} | Stock: ${p.stock_quantity||0}</p>
            </div>`).join('');
        res.classList.remove('hidden');
    },300);
}

function selectProduct(id,name,sku,stock){
    selProduct={id,name,sku,stock};
    document.getElementById('productSearch').value=name;
    document.getElementById('productResults').classList.add('hidden');
    document.getElementById('selProdId').value=id;
    document.getElementById('selProdName').textContent=name;
    document.getElementById('selProdSku').textContent=sku||'—';
    document.getElementById('selProdStock').textContent=stock;
    document.getElementById('selectedProductCard').classList.remove('hidden');
    updatePreview();
}

function updatePreview(){
    if(!selProduct)return;
    const qty=parseInt(document.getElementById('mvQty').value)||0;
    const dir=document.querySelector('input[name="mvDir"]:checked')?.value||'add';
    const after=dir==='add'?selProduct.stock+qty:Math.max(0,selProduct.stock-qty);
    document.getElementById('prevCurrent').textContent=selProduct.stock;
    const el=document.getElementById('prevAfter');
    el.textContent=after;
    el.className=`font-bold text-lg ${after<=0?'text-red-600':after<5?'text-yellow-600':'text-green-600'}`;
}

// ── Step Navigation ──────────────────────────────────────────────
function goStep(n){
    step=n;
    document.getElementById('s1').style.display=n===1?'block':'none';
    document.getElementById('s2').style.display=n===2?'block':'none';
    const steps=['Step 1 of 2 — Select Product','Step 2 of 2 — Movement Details'];
    document.getElementById('mStep').textContent=steps[n-1];
    document.getElementById('btnPrev').classList.toggle('hidden',n===1);
    document.getElementById('btnNext').style.display=n<2?'':'none';
    document.getElementById('btnSave').style.display=n===2?'':'none';
    // Update step dots
    const dot1=document.getElementById('sdot-1'),dot2=document.getElementById('sdot-2');
    const lbl1=document.getElementById('slbl-1'),lbl2=document.getElementById('slbl-2');
    const line=document.getElementById('sline-1');
    if(n===2){
        dot1.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-green-500 text-white transition-all';dot1.innerHTML='✓';
        lbl1.className='text-xs font-medium text-green-600';
        line.className='flex-1 h-0.5 bg-green-500 transition-all';
        dot2.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-[#0082C3] text-white transition-all';
        lbl2.className='text-xs font-medium text-[#0082C3]';
    }else{
        dot1.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-[#0082C3] text-white transition-all';dot1.innerHTML='1';
        lbl1.className='text-xs font-medium text-[#0082C3]';
        line.className='flex-1 h-0.5 bg-[#0082C3] transition-all';
        dot2.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-gray-200 text-gray-500 transition-all';
        lbl2.className='text-xs font-medium text-gray-400';
    }
}

function nextStep(){
    if(step===1&&!selProduct){toast('Please select a product','error');return;}
    if(step<2)goStep(step+1);
}
function prevStep(){if(step>1)goStep(step-1);}

// ── Type Cards ───────────────────────────────────────────────────
document.querySelectorAll('.mv-type-card').forEach(card=>{
    card.addEventListener('click',()=>{
        document.querySelectorAll('.mv-type-card').forEach(c=>{c.classList.remove('border-[#0082C3]','bg-blue-50');c.classList.add('border-gray-200');});
        card.classList.add('border-[#0082C3]','bg-blue-50');card.classList.remove('border-gray-200');
        card.querySelector('input').checked=true;
        currentType=card.dataset.val;
        // Auto-set direction
        const dir=card.dataset.dir;
        document.querySelector(`input[name="mvDir"][value="${dir}"]`).checked=true;
        updatePreview();
    });
});

document.querySelectorAll('input[name="mvDir"]').forEach(r=>r.addEventListener('change',updatePreview));

// ── Modal ────────────────────────────────────────────────────────
function openAdd(){
    selProduct=null;currentType='';
    document.getElementById('productSearch').value='';
    document.getElementById('selectedProductCard').classList.add('hidden');
    document.getElementById('productResults').classList.add('hidden');
    document.getElementById('sForm').reset();
    document.getElementById('mvLocation').value='Main Warehouse';
    document.querySelectorAll('.mv-type-card').forEach(c=>{c.classList.remove('border-[#0082C3]','bg-blue-50');c.classList.add('border-gray-200');});
    goStep(1);showModal();
}

function openAddForProduct(id,name,stock){
    openAdd();
    setTimeout(()=>selectProduct(id,name,'',stock),50);
}

function showModal(){document.getElementById('modal').classList.remove('hidden');requestAnimationFrame(()=>{document.getElementById('mBox').style.transform='translateX(0)';});}
function closeModal(){document.getElementById('mBox').style.transform='translateX(100%)';setTimeout(()=>document.getElementById('modal').classList.add('hidden'),420);}
function onBd(e){if(e.target.id==='modal')closeModal();}

// ── Save Movement ────────────────────────────────────────────────
async function save(){
    if(!selProduct){toast('No product selected','error');return;}
    if(!currentType){toast('Select movement type','error');return;}
    const qty=parseInt(document.getElementById('mvQty').value)||0;
    if(qty<=0){toast('Quantity must be > 0','error');return;}
    const dir=document.querySelector('input[name="mvDir"]:checked')?.value||'add';

    const btn=document.getElementById('btnSave');
    btn.disabled=true;btn.textContent='Saving…';
    const data=await api(BASE,'POST',{
        product_id:selProduct.id,
        type:currentType,
        quantity:qty,
        direction:dir,
        cost_per_unit:parseFloat(document.getElementById('mvCost').value)||null,
        location:document.getElementById('mvLocation').value||'Main Warehouse',
        notes:document.getElementById('mvNotes').value,
    });
    btn.disabled=false;btn.textContent='Record Movement';

    if(data.success){
        showSuccessDialog(`Stock updated to ${data.new_stock}`);
        closeModal();
        loadOverview(1);
    }else{
        const e=data.errors?Object.values(data.errors)[0][0]:data.message;
        toast(e||'Error','error');
    }
}

// ── Adjust Stock ─────────────────────────────────────────────────
function openAdjust(id,name,current){
    document.getElementById('adjProdId').value=id;
    document.getElementById('adjProdName').textContent=name;
    document.getElementById('adjCurrent').textContent=current;
    document.getElementById('adjQty').value=current;
    document.getElementById('adjNotes').value='';
    document.getElementById('adjustModal').classList.remove('hidden');
}
function closeAdjust(){document.getElementById('adjustModal').classList.add('hidden');}
async function saveAdjust(){
    const id=document.getElementById('adjProdId').value;
    const qty=parseInt(document.getElementById('adjQty').value);
    if(isNaN(qty)||qty<0){toast('Enter valid quantity','error');return;}
    const data=await api(`${BASE}/adjust`,'POST',{
        product_id:id,
        new_quantity:qty,
        notes:document.getElementById('adjNotes').value||'Manual adjustment',
    });
    if(data.success){showSuccessDialog(`Stock adjusted to ${data.new_stock}`);closeAdjust();loadOverview(1);}
    else toast(data.message||'Error','error');
}

// ── Dialogs ──────────────────────────────────────────────────────
function showSuccessDialog(message='Done'){
    const d=document.createElement('div');d.className='fixed inset-0 z-[9999] flex items-center justify-center p-4';
    d.innerHTML=`<style>@keyframes succ-backdrop{from{opacity:0}to{opacity:1}}@keyframes succ-pop{0%{opacity:0;transform:scale(0.5)}60%{opacity:1;transform:scale(1.1)}80%{transform:scale(0.95)}100%{opacity:1;transform:scale(1)}}@keyframes succ-ring{0%{stroke-dashoffset:166;opacity:0}20%{opacity:1}100%{stroke-dashoffset:0}}@keyframes succ-check{0%{stroke-dashoffset:48;opacity:0}40%{opacity:0}100%{stroke-dashoffset:0;opacity:1}}@keyframes succ-msg{0%{opacity:0;transform:translateY(8px)}100%{opacity:1;transform:translateY(0)}}.succ-bg{animation:succ-backdrop .2s ease forwards}.succ-box{animation:succ-pop .4s cubic-bezier(0.34,1.56,0.64,1) forwards}.succ-ring{stroke-dasharray:166;stroke-dashoffset:166;animation:succ-ring .6s .1s cubic-bezier(0.65,0,0.45,1) forwards}.succ-check{stroke-dasharray:48;stroke-dashoffset:48;animation:succ-check .4s .5s cubic-bezier(0.65,0,0.45,1) forwards}.succ-msg{animation:succ-msg .3s .7s ease forwards;opacity:0}</style>
        <div class="succ-bg fixed inset-0 bg-black/30 backdrop-blur-[2px]"></div>
        <div class="succ-box relative bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 w-64">
            <svg class="w-20 h-20" viewBox="0 0 52 52"><circle class="succ-ring" cx="26" cy="26" r="25" fill="none" stroke="#22c55e" stroke-width="2"/><path class="succ-check" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 27 l8 8 l16-16"/></svg>
            <p class="succ-msg text-sm font-semibold text-gray-700 text-center">${message}</p>
        </div>`;
    document.body.appendChild(d);
    setTimeout(()=>{d.style.transition='opacity .25s ease';d.style.opacity='0';setTimeout(()=>d.remove(),260);},1600);
}

// ── Init ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded',()=>{
    // Check URL tab param
    const urlTab=new URLSearchParams(window.location.search).get('tab');
    if(urlTab==='movements')switchTab('movements');
    else if(urlTab==='low')switchTab('low');
    else loadOverview(1);
});
</script>
@endpush
