@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

{{-- ── HEADER ─────────────────────────────────────────────────── --}}
<div class="flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Good {{ date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') }},
            {{ session('admin_name', 'Admin') }} 👋
        </h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }} · Here's what's happening with your store</p>
    </div>
    <div class="flex items-center gap-3">
        <span id="lastUpdated" class="text-xs text-gray-400 hidden md:block"></span>
        <button onclick="loadDashboard()" id="refreshBtn"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <svg id="refreshIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Refresh
        </button>
        <a href="{{ route('admin.reports.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Analytics
        </a>
    </div>
</div>

{{-- ── ROW 1: 4 PRIMARY KPI CARDS ─────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Products --}}
    <a href="{{ route('admin.products.index') }}"
       class="relative overflow-hidden rounded-2xl p-5 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 group bg-[#0369a1]">
        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-6 -left-4 w-24 h-24 rounded-full bg-white/5"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <svg class="w-4 h-4 text-white/50 group-hover:text-white/90 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p id="kpiProducts" class="text-3xl font-black text-white">—</p>
            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider mt-1">Total Products</p>
            <p id="kpiProductsSub" class="text-xs text-blue-300 mt-0.5"></p>
        </div>
    </a>

    {{-- Reviews --}}
    <a href="{{ route('admin.reviews.index') }}"
       class="relative overflow-hidden rounded-2xl p-5 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 group bg-[#b45309]">
        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-6 -left-4 w-24 h-24 rounded-full bg-white/5"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <svg class="w-4 h-4 text-white/50 group-hover:text-white/90 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p id="kpiReviews" class="text-3xl font-black text-white">—</p>
            <p class="text-xs font-semibold text-yellow-200 uppercase tracking-wider mt-1">Total Reviews</p>
            <p id="kpiReviewsSub" class="text-xs text-yellow-300 mt-0.5"></p>
        </div>
    </a>

    {{-- Promotions --}}
    <a href="{{ route('admin.promotions.index') }}"
       class="relative overflow-hidden rounded-2xl p-5 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 group bg-[#047857]">
        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-6 -left-4 w-24 h-24 rounded-full bg-white/5"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                </div>
                <svg class="w-4 h-4 text-white/50 group-hover:text-white/90 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p id="kpiPromotions" class="text-3xl font-black text-white">—</p>
            <p class="text-xs font-semibold text-green-200 uppercase tracking-wider mt-1">Active Promotions</p>
            <p id="kpiPromotionsSub" class="text-xs text-green-300 mt-0.5"></p>
        </div>
    </a>

    {{-- Coupons --}}
    <a href="{{ route('admin.coupons.index') }}"
       class="relative overflow-hidden rounded-2xl p-5 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 group bg-[#6d28d9]">
        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-6 -left-4 w-24 h-24 rounded-full bg-white/5"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <svg class="w-4 h-4 text-white/50 group-hover:text-white/90 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p id="kpiCoupons" class="text-3xl font-black text-white">—</p>
            <p class="text-xs font-semibold text-purple-200 uppercase tracking-wider mt-1">Active Coupons</p>
            <p id="kpiCouponsSub" class="text-xs text-purple-300 mt-0.5"></p>
        </div>
    </a>

</div>

{{-- ── ROW 2: 6 SECONDARY METRIC PILLS ────────────────────────── --}}
<div class="grid grid-cols-3 lg:grid-cols-6 gap-3">
    @php
    $pills = [
        ['id'=>'statCategories', 'label'=>'Categories',     'href'=>route('admin.categories.index'), 'bg'=>'#eff6ff','border'=>'#93c5fd','num'=>'#1d4ed8','txt'=>'#3b82f6'],
        ['id'=>'statBrands',     'label'=>'Brands',          'href'=>route('admin.brands.index'),     'bg'=>'#f5f3ff','border'=>'#c4b5fd','num'=>'#5b21b6','txt'=>'#7c3aed'],
        ['id'=>'statCollections','label'=>'Collections',     'href'=>route('admin.collections.index'),'bg'=>'#ecfdf5','border'=>'#6ee7b7','num'=>'#065f46','txt'=>'#059669'],
        ['id'=>'statLowStock',   'label'=>'Low Stock ⚠️',   'href'=>route('admin.stock.index'),      'bg'=>'#fff7ed','border'=>'#fdba74','num'=>'#9a3412','txt'=>'#ea580c'],
        ['id'=>'statOutStock',   'label'=>'Out of Stock',    'href'=>route('admin.stock.index'),      'bg'=>'#fef2f2','border'=>'#fca5a5','num'=>'#991b1b','txt'=>'#dc2626'],
        ['id'=>'statCustomers',  'label'=>'Customers',       'href'=>route('admin.customers.index'),  'bg'=>'#eff6ff','border'=>'#bfdbfe','num'=>'#1e40af','txt'=>'#2563eb'],
    ];
    @endphp
    @foreach($pills as $p)
    <a href="{{ $p['href'] }}"
       class="rounded-xl p-3.5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
       style="background:{{ $p['bg'] }};border:1.5px solid {{ $p['border'] }}">
        <p id="{{ $p['id'] }}" class="text-2xl font-black" style="color:{{ $p['num'] }}">
            <span class="inline-block w-8 h-6 rounded animate-pulse" style="background:{{ $p['border'] }}40"></span>
        </p>
        <p class="text-xs font-semibold mt-1" style="color:{{ $p['txt'] }}">{{ $p['label'] }}</p>
    </a>
    @endforeach
</div>

{{-- ── ROW 3: CATALOG OVERVIEW + STORE HEALTH ─────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

    {{-- Catalog Overview --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 flex-shrink-0">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Catalog Overview</h3>
                <p class="text-xs text-gray-400 mt-0.5">Your store's content at a glance</p>
            </div>
            <a href="{{ route('admin.products.index') }}"
               class="text-xs font-semibold text-[#0082C3] hover:text-[#006ba3] flex items-center gap-1">
                Manage
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="p-4 grid grid-cols-2 gap-2.5 flex-1" id="catalogGrid" style="grid-auto-rows:1fr">
            @php
            $catalogItems = [
                ['id'=>'catProducts',    'label'=>'Products',    'href'=>route('admin.products.index'),        'color'=>'#0369a1', 'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['id'=>'catCategories',  'label'=>'Categories',  'href'=>route('admin.categories.index'),      'color'=>'#047857', 'icon'=>'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                ['id'=>'catBrands',      'label'=>'Brands',      'href'=>route('admin.brands.index'),          'color'=>'#6d28d9', 'icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['id'=>'catCollections', 'label'=>'Collections', 'href'=>route('admin.collections.index'),     'color'=>'#0e7490', 'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['id'=>'catTags',        'label'=>'Tags',        'href'=>route('admin.tags.index'),            'color'=>'#b45309', 'icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['id'=>'catAttributes',  'label'=>'Attributes',  'href'=>route('admin.attributes.index'),      'color'=>'#0f766e', 'icon'=>'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                ['id'=>'catCustomers',   'label'=>'Customers',   'href'=>route('admin.customers.index'),       'color'=>'#1d4ed8', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['id'=>'catStock',       'label'=>'Stock Alerts', 'href'=>route('admin.stock.index'),           'color'=>'#9a3412', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ];
            @endphp
            @foreach($catalogItems as $item)
            <a href="{{ $item['href'] }}"
               class="flex items-center gap-3 px-3.5 py-3 rounded-xl hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group"
               style="background-color:{{ $item['color'] }}">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p id="{{ $item['id'] }}" class="text-lg font-black text-white leading-none">—</p>
                    <p class="text-xs font-semibold text-white/80">{{ $item['label'] }}</p>
                </div>
                <svg class="w-3.5 h-3.5 text-white/30 group-hover:text-white/70 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Store Health --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-50 flex-shrink-0">
            <h3 class="text-sm font-bold text-gray-900">Store Health</h3>
            <p class="text-xs text-gray-400 mt-0.5">Key metrics at a glance</p>
        </div>
        <div class="p-4 flex flex-col gap-2 flex-1 justify-between">
            @php
            $health = [
                ['id'=>'ovStockValue', 'label'=>'Stock Value',       'color'=>'#047857', 'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['id'=>'ovAvgRating',  'label'=>'Avg Rating',        'color'=>'#b45309', 'icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ['id'=>'ovCampaigns',  'label'=>'Email Campaigns',   'color'=>'#0369a1', 'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['id'=>'ovWarehouses', 'label'=>'Active Warehouses', 'color'=>'#6d28d9', 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['id'=>'ovAdmins',     'label'=>'Admin Users',       'color'=>'#374151', 'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['id'=>'ovTodayLogs', 'label'=>'Activity Today',    'color'=>'#c2410c', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ];
            @endphp
            @foreach($health as $h)
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color:{{ $h['color'] }}">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $h['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600">{{ $h['label'] }}</span>
                </div>
                <span id="{{ $h['id'] }}" class="text-sm font-bold text-gray-900">—</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── ROW 4: RECENT REVIEWS + ACTIVITY ───────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Recent Reviews --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Recent Reviews</h3>
                <p class="text-xs text-gray-400 mt-0.5">Latest customer feedback</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}"
               class="text-xs font-semibold text-[#0082C3] hover:text-[#006ba3] flex items-center gap-1">
                View all
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div id="recentReviews">
            @for($i=0;$i<4;$i++)
            <div class="flex items-center gap-3 px-6 py-3.5 border-b border-gray-50 animate-pulse">
                <div class="w-9 h-9 rounded-full bg-gray-200 flex-shrink-0"></div>
                <div class="flex-1"><div class="h-3 bg-gray-200 rounded w-3/4 mb-2"></div><div class="h-2.5 bg-gray-100 rounded w-1/2"></div></div>
                <div class="w-16 h-5 bg-gray-100 rounded-full"></div>
            </div>
            @endfor
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Recent Activity</h3>
                <p class="text-xs text-gray-400 mt-0.5">Admin actions log</p>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}"
               class="text-xs font-semibold text-[#0082C3] hover:text-[#006ba3] flex items-center gap-1">
                View all
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div id="recentActivity">
            @for($i=0;$i<5;$i++)
            <div class="flex items-center gap-3 px-6 py-3 border-b border-gray-50 animate-pulse">
                <div class="w-8 h-8 rounded-lg bg-gray-200 flex-shrink-0"></div>
                <div class="flex-1"><div class="h-3 bg-gray-200 rounded w-3/4 mb-2"></div><div class="h-2.5 bg-gray-100 rounded w-1/3"></div></div>
                <div class="w-12 h-3 bg-gray-100 rounded"></div>
            </div>
            @endfor
        </div>
    </div>
</div>

{{-- ── ROW 5: QUICK ACTIONS ────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900">Quick Actions</h3>
            <p class="text-xs text-gray-400 mt-0.5">Jump to common tasks</p>
        </div>
    </div>
    <div class="grid grid-cols-3 lg:grid-cols-6 gap-3">
        @php
        $actions = [
            ['label'=>'Add Product',    'sub'=>'Catalog',    'href'=>route('admin.products.index'),        'color'=>'#0369a1', 'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label'=>'Collections',    'sub'=>'Catalog',    'href'=>route('admin.collections.index'),     'color'=>'#0e7490', 'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['label'=>'Add Coupon',     'sub'=>'Marketing',  'href'=>route('admin.coupons.index'),         'color'=>'#6d28d9', 'icon'=>'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
            ['label'=>'Stock Check',    'sub'=>'Inventory',  'href'=>route('admin.stock.index'),           'color'=>'#b45309', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label'=>'Email Campaign', 'sub'=>'Marketing',  'href'=>route('admin.email-campaigns.index'),'color'=>'#b91c1c', 'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['label'=>'Analytics',      'sub'=>'Reports',    'href'=>route('admin.reports.index'),         'color'=>'#047857', 'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ];
        @endphp
        @foreach($actions as $a)
        <a href="{{ $a['href'] }}"
           class="flex flex-col items-center gap-2.5 p-4 rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group"
           style="background-color:{{ $a['color'] }}">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $a['icon'] }}"/>
                </svg>
            </div>
            <div class="text-center">
                <p class="text-xs font-bold text-white leading-tight">{{ $a['label'] }}</p>
                <p class="text-xs text-white/60 mt-0.5">{{ $a['sub'] }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>

</div>{{-- end space-y-6 --}}
@endsection

@push('scripts')
<script>
const COLORS = ['#0082C3','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1','#84cc16'];

function esc(s) { return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function setText(id, val) { const el=document.getElementById(id); if(el) el.textContent=val??'—'; }
function stars(n) { const r=Math.round(n||0); return '★'.repeat(r)+'☆'.repeat(5-r); }

async function loadDashboard() {
    const btn = document.getElementById('refreshBtn');
    const icon = document.getElementById('refreshIcon');
    if(btn) btn.disabled = true;
    if(icon) icon.classList.add('animate-spin');

    try {
        const r = await fetch('/admin/dashboard/stats', {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        });
        if (!r.ok) throw new Error('HTTP ' + r.status);
        const res = await r.json();
        if (!res.success) throw new Error(res.message);
        renderDashboard(res.data);
        const el = document.getElementById('lastUpdated');
        if(el) el.textContent = 'Updated ' + new Date().toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit'});
    } catch(e) {
        console.error('Dashboard error:', e.message);
    }

    if(btn) btn.disabled = false;
    if(icon) icon.classList.remove('animate-spin');
}

function renderDashboard(d) {
    // KPI cards
    setText('kpiProducts',    d.total_products);
    setText('kpiProductsSub', d.active_products + ' active · ' + d.total_categories + ' categories');
    setText('kpiReviews',     d.total_reviews);
    setText('kpiReviewsSub',  d.avg_rating + ' ★ avg · ' + d.pending_reviews + ' pending');
    setText('kpiPromotions',  d.active_promotions);
    setText('kpiPromotionsSub', d.total_campaigns + ' campaigns total');
    setText('kpiCoupons',     d.active_coupons);
    setText('kpiCouponsSub',  d.sent_campaigns + ' campaigns sent');

    // Secondary pills
    setText('statCategories',  d.total_categories);
    setText('statBrands',      d.total_brands);
    setText('statCollections', d.total_collections);
    setText('statLowStock',    d.low_stock_count);
    setText('statOutStock',    d.out_of_stock_count);
    setText('statCustomers',   d.total_customers || '—');

    // Store health
    setText('ovStockValue',  '₹' + Number(d.stock_value||0).toLocaleString('en-IN',{maximumFractionDigits:0}));
    setText('ovAvgRating',   (d.avg_rating||0) + ' ★');
    setText('ovCampaigns',   d.total_campaigns + ' total');
    setText('ovWarehouses',  d.active_warehouses + ' / ' + d.total_warehouses);
    setText('ovAdmins',      d.active_admins + ' / ' + d.total_admins);
    setText('ovTodayLogs',   d.today_logs + ' actions');

    // Catalog Overview
    setText('catProducts',    d.total_products);
    setText('catCategories',  d.total_categories);
    setText('catBrands',      d.total_brands);
    setText('catCollections', d.total_collections);
    setText('catTags',        d.total_tags || '—');
    setText('catAttributes',  d.total_attributes || '—');
    setText('catCustomers',   d.total_customers || '—');
    setText('catStock',       d.low_stock_count + d.out_of_stock_count);
    setText('catPromotions',  d.active_promotions);
    setText('catWarehouses',  d.total_warehouses);

    // Recent Reviews
    const rrEl = document.getElementById('recentReviews');
    const reviews = d.recent_reviews || [];
    const statusStyle = {
        approved: 'background:#dcfce7;color:#15803d',
        pending:  'background:#fef9c3;color:#a16207',
        rejected: 'background:#fee2e2;color:#dc2626',
        spam:     'background:#f3f4f6;color:#6b7280'
    };
    rrEl.innerHTML = !reviews.length
        ? '<p class="text-sm text-gray-400 p-6 text-center">No reviews yet</p>'
        : reviews.map(r => `
            <div class="flex items-center gap-3 px-6 py-3.5 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                     style="background:linear-gradient(135deg,#0082C3,#8b5cf6)">
                    ${esc(r.reviewer_name).charAt(0).toUpperCase()}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-900 truncate">${esc(r.reviewer_name)}</p>
                        <span class="text-yellow-500 text-xs flex-shrink-0">${stars(r.rating)}</span>
                    </div>
                    <p class="text-xs text-gray-400 truncate">${esc(r.product)}</p>
                </div>
                <div class="flex-shrink-0 text-right">
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold" style="${statusStyle[r.status]||statusStyle.spam}">${esc(r.status)}</span>
                    <p class="text-xs text-gray-400 mt-0.5">${esc(r.time)}</p>
                </div>
            </div>`).join('');

    // Recent Activity
    const raEl = document.getElementById('recentActivity');
    const activity = d.recent_activity || [];
    const actionColor = {
        created:        '#047857',
        updated:        '#0369a1',
        deleted:        '#b91c1c',
        status_changed: '#b45309',
        bulk_action:    '#6d28d9',
        login:          '#3730a3',
        logout:         '#374151',
    };
    raEl.innerHTML = !activity.length
        ? '<p class="text-sm text-gray-400 p-6 text-center">No activity yet</p>'
        : activity.map(a => `
            <div class="flex items-center gap-3 px-6 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background-color:${actionColor[a.action]||actionColor.updated}">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-800 truncate">${esc(a.description||a.action)}</p>
                    <p class="text-xs text-gray-400">${esc(a.user)} · <span class="font-mono bg-gray-100 px-1 rounded text-gray-500">${esc(a.module)}</span></p>
                </div>
                <span class="text-xs text-gray-400 flex-shrink-0 whitespace-nowrap">${esc(a.time)}</span>
            </div>`).join('');
}

// Load on init + auto-refresh every 5 min
loadDashboard();
setInterval(loadDashboard, 5 * 60 * 1000);
</script>
@endpush
