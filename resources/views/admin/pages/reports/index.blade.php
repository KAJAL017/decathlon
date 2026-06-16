@extends('admin.layouts.app')
@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
        <p class="text-sm text-gray-500 mt-0.5">Deep insights across your entire store</p>
    </div>
    <div class="flex items-center gap-3 flex-wrap">
        {{-- Date Range --}}
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3 py-2 shadow-sm">
            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
            <select id="dateRange" onchange="onDateRangeChange()" class="text-sm text-gray-700 bg-transparent border-none outline-none font-medium">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="365">Last 1 year</option>
                <option value="custom">Custom range</option>
            </select>
        </div>
        <div id="customDateRange" class="hidden flex items-center gap-2">
            <input type="date" id="dateFrom" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <span class="text-gray-400 text-sm">to</span>
            <input type="date" id="dateTo" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <button onclick="applyCustomDate()" class="px-3 py-2 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3]">Apply</button>
        </div>
        <button onclick="exportCurrentTab()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
            <i data-lucide="download" class="w-4 h-4"></i>
            Export CSV
        </button>
        <button onclick="refreshCurrentTab()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
            <i data-lucide="refresh-cw" id="refreshIcon" class="w-4 h-4"></i>
            Refresh
        </button>
    </div>
</div>

<div class="flex gap-6">

{{-- Left Sidebar Nav --}}
<div class="w-52 flex-shrink-0">
    <nav class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-4">
        @foreach([
            ['overview',  'Overview',   'bar-chart-3'],
            ['products',  'Products',   'package'],
            ['inventory', 'Inventory',  'clipboard-list'],
            ['reviews',   'Reviews',    'star'],
            ['marketing', 'Marketing',  'megaphone'],
            ['customers', 'Customers',  'users'],
            ['catalog',   'Catalog',    'layout-grid'],
            ['activity',  'Activity',   'file-text'],
        ] as [$key, $label, $icon])
        <button onclick="switchSection('{{$key}}')" id="nav-{{$key}}"
                class="report-nav w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-left transition-all border-b border-gray-50 last:border-0
                       {{ $key === 'overview' ? 'bg-[#0082C3] text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i data-lucide="{{ $icon }}" class="w-4 h-4 flex-shrink-0"></i>
            {{ $label }}
        </button>
        @endforeach
    </nav>

    {{-- Period badge --}}
    <div id="periodBadge" class="mt-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 text-center hidden">
        <p class="text-xs text-blue-600 font-semibold">Period</p>
        <p id="periodText" class="text-xs text-blue-500 mt-0.5"></p>
    </div>
</div>

{{-- Right Content --}}
<div class="flex-1 min-w-0">

{{-- ══ OVERVIEW ══ --}}
<div id="section-overview" class="report-section space-y-5">
    <div id="overview-skeleton" class="space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">@for($i=0;$i<5;$i++)<div class="bg-white rounded-xl border border-gray-100 p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-3"></div><div class="h-7 bg-gray-200 rounded w-1/2"></div></div>@endfor</div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">@for($i=0;$i<4;$i++)<div class="bg-white rounded-xl border border-gray-100 p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-3"></div><div class="h-7 bg-gray-200 rounded w-1/2"></div></div>@endfor</div>
    </div>
    <div id="overview-content" class="hidden space-y-5">
        {{-- KPI row --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach([
                ['id'=>'ov-total-products',   'label'=>'Total Products',   'color'=>'#0369a1'],
                ['id'=>'ov-total-collections','label'=>'Collections',      'color'=>'#0e7490'],
                ['id'=>'ov-total-reviews',    'label'=>'Total Reviews',    'color'=>'#b45309'],
                ['id'=>'ov-avg-rating',       'label'=>'Avg Rating',       'color'=>'#d97706'],
                ['id'=>'ov-stock-value',      'label'=>'Stock Value',      'color'=>'#047857'],
            ] as $k)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs font-semibold uppercase tracking-wider" style="color:{{ $k['color'] }}">{{ $k['label'] }}</p>
                <p id="{{ $k['id'] }}" class="text-2xl font-black text-gray-900 mt-1">—</p>
            </div>
            @endforeach
        </div>
        {{-- Alert row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-xl p-4 border" style="background:#fff7ed;border-color:#fdba74"><p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Low Stock</p><p id="ov-low-stock" class="text-2xl font-black text-orange-700 mt-1">—</p></div>
            <div class="rounded-xl p-4 border" style="background:#fef2f2;border-color:#fca5a5"><p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Out of Stock</p><p id="ov-out-stock" class="text-2xl font-black text-red-700 mt-1">—</p></div>
            <div class="rounded-xl p-4 border" style="background:#eff6ff;border-color:#93c5fd"><p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Active Promotions</p><p id="ov-promotions" class="text-2xl font-black text-blue-700 mt-1">—</p></div>
            <div class="rounded-xl p-4 border" style="background:#f5f3ff;border-color:#c4b5fd"><p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Active Coupons</p><p id="ov-coupons" class="text-2xl font-black text-purple-700 mt-1">—</p></div>
        </div>
        {{-- Review trend chart --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-900">Review Activity (Period)</h3>
                <span id="ov-new-reviews" class="text-xs bg-blue-50 text-blue-700 font-semibold px-2.5 py-1 rounded-full"></span>
            </div>
            <div class="p-5" style="height:200px"><canvas id="chartReviewTrend"></canvas></div>
        </div>
        {{-- Tables --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Top 5 Rated Products</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-[#0082C3] hover:underline">View all →</a>
                </div>
                <div id="ov-top-rated" class="divide-y divide-gray-50"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Recent Reviews</h3>
                    <a href="{{ route('admin.reviews.index') }}" class="text-xs text-[#0082C3] hover:underline">View all →</a>
                </div>
                <div id="ov-recent-reviews" class="divide-y divide-gray-50"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ PRODUCTS ══ --}}
<div id="section-products" class="report-section hidden space-y-5">
    <div id="products-skeleton" class="grid grid-cols-1 md:grid-cols-2 gap-5">@for($i=0;$i<4;$i++)<div class="bg-white rounded-xl border border-gray-100 p-5 animate-pulse space-y-3"><div class="h-4 bg-gray-200 rounded w-1/3"></div><div class="h-48 bg-gray-100 rounded"></div></div>@endfor</div>
    <div id="products-content" class="hidden space-y-5">
        {{-- Flags row --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-black text-[#0082C3]" id="prod-featured">—</p>
                <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Featured</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-black text-green-600" id="prod-new">—</p>
                <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">New Arrivals</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-black text-orange-600" id="prod-bestseller">—</p>
                <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Best Sellers</p>
            </div>
        </div>
        {{-- Charts row 1 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Products by Category</h3></div>
                <div class="p-5" style="height:280px"><canvas id="chartByCategory"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Products by Brand (Top 10)</h3></div>
                <div class="p-5" style="height:280px"><canvas id="chartByBrand"></canvas></div>
            </div>
        </div>
        {{-- Charts row 2 --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">By Type</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartByType"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Stock Status</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartStockDist"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">By Status</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartByStatus"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ INVENTORY ══ --}}
<div id="section-inventory" class="report-section hidden space-y-5">
    <div id="inventory-skeleton" class="space-y-5">
        <div class="bg-white rounded-xl border border-gray-100 p-5 animate-pulse space-y-3"><div class="h-4 bg-gray-200 rounded w-1/4"></div><div class="h-48 bg-gray-100 rounded"></div></div>
    </div>
    <div id="inventory-content" class="hidden space-y-5">
        {{-- Movements chart --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Stock Movements by Type</h3></div>
            <div class="p-5" style="height:220px"><canvas id="chartMovements"></canvas></div>
        </div>
        {{-- Stock tables --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">⚠️ Low Stock Products</h3>
                    <span id="low-stock-badge" class="text-xs bg-orange-100 text-orange-700 font-semibold px-2 py-0.5 rounded-full">0</span>
                </div>
                <div id="table-low-stock" class="overflow-x-auto max-h-64"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">🚫 Out of Stock</h3>
                    <span id="out-stock-badge" class="text-xs bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded-full">0</span>
                </div>
                <div id="table-out-stock" class="overflow-x-auto max-h-64"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
            </div>
        </div>
        {{-- Recent movements --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Recent Stock Movements</h3>
                <a href="{{ route('admin.stock.index') }}" class="text-xs text-[#0082C3] hover:underline">View all →</a>
            </div>
            <div id="table-recent-movements" class="overflow-x-auto"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
        </div>
        {{-- Warehouses --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Active Warehouses</h3>
                <a href="{{ route('admin.warehouses.index') }}" class="text-xs text-[#0082C3] hover:underline">Manage →</a>
            </div>
            <div id="table-warehouses" class="p-5"><p class="text-sm text-gray-400">Loading…</p></div>
        </div>
    </div>
</div>

{{-- ══ REVIEWS ══ --}}
<div id="section-reviews" class="report-section hidden space-y-5">
    <div id="reviews-skeleton" class="space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">@for($i=0;$i<4;$i++)<div class="bg-white rounded-xl border border-gray-100 p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-3"></div><div class="h-7 bg-gray-200 rounded w-1/2"></div></div>@endfor</div>
    </div>
    <div id="reviews-content" class="hidden space-y-5">
        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-gray-900" id="rv-total">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Total</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-yellow-500" id="rv-avg">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Avg Rating</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-green-600" id="rv-approved">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Approved</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-orange-500" id="rv-pending">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Pending</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-blue-600" id="rv-verified">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Verified</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-red-500" id="rv-rejected">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Rejected</p></div>
        </div>
        {{-- Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Rating Distribution</h3></div>
                <div class="p-5" style="height:220px"><canvas id="chartRatingDist"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Status Breakdown</h3></div>
                <div class="p-5" style="height:220px"><canvas id="chartReviewStatus"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Source (Website vs Admin)</h3></div>
                <div class="p-5" style="height:220px"><canvas id="chartReviewSource"></canvas></div>
            </div>
        </div>
        {{-- Review trend --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Review Trend (Period)</h3></div>
            <div class="p-5" style="height:180px"><canvas id="chartReviewTrendTab"></canvas></div>
        </div>
        {{-- Recent reviews table --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Recent Reviews</h3>
                <div class="flex items-center gap-2">
                    <select id="reviewFilter" onchange="filterReviews()" class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                        <option value="">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <a href="{{ route('admin.reviews.index') }}" class="text-xs text-[#0082C3] hover:underline">View all →</a>
                </div>
            </div>
            <div id="table-recent-reviews" class="overflow-x-auto"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
        </div>
    </div>
</div>

{{-- ══ MARKETING ══ --}}
<div id="section-marketing" class="report-section hidden space-y-5">
    <div id="marketing-skeleton" class="grid grid-cols-1 md:grid-cols-2 gap-5">@for($i=0;$i<4;$i++)<div class="bg-white rounded-xl border border-gray-100 p-5 animate-pulse space-y-3"><div class="h-4 bg-gray-200 rounded w-1/3"></div><div class="h-48 bg-gray-100 rounded"></div></div>@endfor</div>
    <div id="marketing-content" class="hidden space-y-5">
        {{-- Email stats --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-gray-900" id="mkt-campaigns">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Campaigns</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-blue-600" id="mkt-sent">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Emails Sent</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-green-600" id="mkt-opened">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Opened</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-purple-600" id="mkt-open-rate">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Open Rate</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-orange-600" id="mkt-click-rate">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Click Rate</p></div>
        </div>
        {{-- Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Promotions by Type</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartPromoType"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Promotions by Status</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartPromoStatus"></canvas></div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Coupon Usage (Top 10)</h3></div>
                <div class="p-5" style="height:280px"><canvas id="chartCoupons"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Email Campaigns by Status</h3></div>
                <div class="p-5" style="height:280px"><canvas id="chartCampaigns"></canvas></div>
            </div>
        </div>
        {{-- Top campaigns table --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Top Campaigns by Open Rate</h3>
                <a href="{{ route('admin.email-campaigns.index') }}" class="text-xs text-[#0082C3] hover:underline">View all →</a>
            </div>
            <div id="table-top-campaigns" class="overflow-x-auto"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
        </div>
        {{-- Coupons detail table --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Coupon Details</h3>
                <a href="{{ route('admin.coupons.index') }}" class="text-xs text-[#0082C3] hover:underline">Manage →</a>
            </div>
            <div id="table-coupons-detail" class="overflow-x-auto"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
        </div>
    </div>
</div>

{{-- ══ CUSTOMERS ══ --}}
<div id="section-customers" class="report-section hidden space-y-5">
    <div id="customers-skeleton" class="space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">@for($i=0;$i<4;$i++)<div class="bg-white rounded-xl border border-gray-100 p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-3"></div><div class="h-7 bg-gray-200 rounded w-1/2"></div></div>@endfor</div>
    </div>
    <div id="customers-content" class="hidden space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-[#0369a1]" id="cust-total">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Total Customers</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-green-600" id="cust-active">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Active</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-gray-600" id="cust-admins">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Admin Users</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-purple-600" id="cust-roles">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Roles</p></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Users by Role</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartUsersByRole"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Active vs Inactive</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartUsersStatus"></canvas></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Admin Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-[#0082C3] hover:underline">Manage →</a>
            </div>
            <div id="table-users" class="overflow-x-auto"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
        </div>
    </div>
</div>

{{-- ══ CATALOG ══ --}}
<div id="section-catalog" class="report-section hidden space-y-5">
    <div id="catalog-skeleton" class="space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">@for($i=0;$i<4;$i++)<div class="bg-white rounded-xl border border-gray-100 p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-3"></div><div class="h-7 bg-gray-200 rounded w-1/2"></div></div>@endfor</div>
    </div>
    <div id="catalog-content" class="hidden space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            @foreach([
                ['id'=>'cat-products',   'label'=>'Products',   'color'=>'#0369a1', 'href'=>route('admin.products.index')],
                ['id'=>'cat-categories', 'label'=>'Categories', 'color'=>'#047857', 'href'=>route('admin.categories.index')],
                ['id'=>'cat-brands',     'label'=>'Brands',     'color'=>'#6d28d9', 'href'=>route('admin.brands.index')],
                ['id'=>'cat-collections','label'=>'Collections','color'=>'#0e7490', 'href'=>route('admin.collections.index')],
                ['id'=>'cat-tags',       'label'=>'Tags',       'color'=>'#b45309', 'href'=>route('admin.tags.index')],
                ['id'=>'cat-attributes', 'label'=>'Attributes', 'color'=>'#0f766e', 'href'=>route('admin.attributes.index')],
            ] as $c)
            <a href="{{ $c['href'] }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center hover:shadow-md hover:-translate-y-0.5 transition-all">
                <p class="text-2xl font-black" id="{{ $c['id'] }}" style="color:{{ $c['color'] }}">—</p>
                <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">{{ $c['label'] }}</p>
            </a>
            @endforeach
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Top Categories by Products</h3></div>
                <div class="p-5" style="height:280px"><canvas id="chartCatalogCategories"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Top Brands by Products</h3></div>
                <div class="p-5" style="height:280px"><canvas id="chartCatalogBrands"></canvas></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Collections Overview</h3>
                <a href="{{ route('admin.collections.index') }}" class="text-xs text-[#0082C3] hover:underline">Manage →</a>
            </div>
            <div id="table-collections" class="overflow-x-auto"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
        </div>
    </div>
</div>

{{-- ══ ACTIVITY ══ --}}
<div id="section-activity" class="report-section hidden space-y-5">
    <div id="activity-skeleton" class="space-y-5">
        <div class="bg-white rounded-xl border border-gray-100 p-5 animate-pulse space-y-3"><div class="h-4 bg-gray-200 rounded w-1/4"></div><div class="h-48 bg-gray-100 rounded"></div></div>
    </div>
    <div id="activity-content" class="hidden space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-gray-900" id="act-total">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Total Logs</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-blue-600" id="act-today">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Today</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-green-600" id="act-created">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Created</p></div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center"><p class="text-2xl font-black text-red-600" id="act-deleted">—</p><p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-wider">Deleted</p></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Activity by Action</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartActivityByAction"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Activity by Module</h3></div>
                <div class="p-5" style="height:250px"><canvas id="chartActivityByModule"></canvas></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50"><h3 class="text-sm font-bold text-gray-900">Activity Trend (Period)</h3></div>
            <div class="p-5" style="height:180px"><canvas id="chartActivityTrend"></canvas></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Recent Activity</h3>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-[#0082C3] hover:underline">View all →</a>
            </div>
            <div id="table-activity" class="overflow-x-auto"><p class="text-sm text-gray-400 p-5">Loading…</p></div>
        </div>
    </div>
</div>

</div>{{-- end right --}}
</div>{{-- end flex --}}
</div>{{-- end space-y-6 --}}

<div id="toast" class="fixed bottom-5 right-5 z-50 hidden">
    <div id="toast-inner" class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white min-w-[220px]">
        <span id="toast-icon"></span><span id="toast-msg"></span>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.plugins.legend.position = 'bottom';
Chart.defaults.plugins.tooltip.backgroundColor = '#1f2937';
Chart.defaults.plugins.tooltip.padding = 10;
Chart.defaults.plugins.tooltip.cornerRadius = 8;

const COLORS = ['#0082C3','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1','#84cc16'];
const charts = {};

function createChart(id, type, labels, datasets, options = {}) {
    if (charts[id]) { charts[id].destroy(); delete charts[id]; }
    const ctx = document.getElementById(id);
    if (!ctx) return;
    charts[id] = new Chart(ctx, {
        type, data: { labels, datasets },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: type==='doughnut'||type==='pie' } }, ...options }
    });
}

let currentSection = 'overview';
const loaded = {};
let currentData = {};
let isFirstLoad = true;

// ── Date range ────────────────────────────────────────────────────
function onDateRangeChange() {
    const val = document.getElementById('dateRange').value;
    document.getElementById('customDateRange').classList.toggle('hidden', val !== 'custom');
    if (val !== 'custom') { delete loaded[currentSection]; loadSection(currentSection); }
}
function applyCustomDate() { delete loaded[currentSection]; loadSection(currentSection); }
function getDateParams() {
    const val = document.getElementById('dateRange').value;
    if (val === 'custom') {
        const from = document.getElementById('dateFrom').value;
        const to   = document.getElementById('dateTo').value;
        return from && to ? `&date_from=${from}&date_to=${to}` : '';
    }
    return `&days=${val}`;
}

// ── Tab switch ────────────────────────────────────────────────────
function switchSection(key) {
    currentSection = key;
    document.querySelectorAll('.report-section').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.report-nav').forEach(btn => {
        btn.classList.remove('bg-[#0082C3]','text-white');
        btn.classList.add('text-gray-600','hover:bg-gray-50','hover:text-gray-900');
    });
    document.getElementById('section-' + key)?.classList.remove('hidden');
    const nav = document.getElementById('nav-' + key);
    if (nav) { nav.classList.add('bg-[#0082C3]','text-white'); nav.classList.remove('text-gray-600','hover:bg-gray-50','hover:text-gray-900'); }
    if (!loaded[key]) loadSection(key);
}

function refreshCurrentTab() {
    delete loaded[currentSection];
    Object.keys(charts).forEach(id => { charts[id]?.destroy(); delete charts[id]; });
    loadSection(currentSection);
}

function loadSection(key) {
    const urls = {
        overview:  '{{ route("admin.reports.overview") }}',
        products:  '{{ route("admin.reports.products") }}',
        inventory: '{{ route("admin.reports.inventory") }}',
        reviews:   '{{ route("admin.reports.reviews") }}',
        marketing: '{{ route("admin.reports.marketing") }}',
        customers: '{{ route("admin.reports.customers") }}',
        catalog:   '{{ route("admin.reports.catalog") }}',
        activity:  '{{ route("admin.reports.activity") }}',
    };
    showSkeleton(key, true);
    fetch(urls[key] + '?' + getDateParams().replace('&',''), { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(res => {
            if (!res.success) throw new Error(res.message || 'Failed');
            currentData[key] = res.data;
            renderSection(key, res.data);
            loaded[key] = true;
            showSkeleton(key, false);
            if (res.data.period) {
                document.getElementById('periodBadge')?.classList.remove('hidden');
                document.getElementById('periodText').textContent = res.data.period.from + ' to ' + res.data.period.to;
            }
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
        })
        .catch(err => { showSkeleton(key, false); showToast('error', 'Failed: ' + err.message); if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); } });
}

function showSkeleton(key, show) {
    const sk = document.getElementById(key + '-skeleton');
    const ct = document.getElementById(key + '-content');
    if (sk) sk.classList.toggle('hidden', !show);
    if (ct) { if (show) ct.style.display = 'none'; else { ct.style.display = ''; ct.classList.remove('hidden'); } }
}

// ── Utilities ─────────────────────────────────────────────────────
function esc(s) { return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function setText(id, val) { const el=document.getElementById(id); if(el) el.textContent=val??'0'; }
function stars(n) { const r=Math.round(n||0); return '★'.repeat(r)+'☆'.repeat(5-r); }
function statusPill(s) {
    const m={approved:'bg-green-100 text-green-700',pending:'bg-yellow-100 text-yellow-700',rejected:'bg-red-100 text-red-700',spam:'bg-gray-100 text-gray-600'};
    return `<span class="px-2 py-0.5 rounded-full text-xs font-medium ${m[s]||'bg-gray-100 text-gray-600'}">${esc(s)}</span>`;
}
function showToast(type, msg) {
    const t=document.getElementById('toast');
    document.getElementById('toast-inner').className='flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white min-w-[220px] '+(type==='success'?'bg-green-600':'bg-red-600');
    document.getElementById('toast-icon').textContent=type==='success'?'✓':'✕';
    document.getElementById('toast-msg').textContent=msg;
    t.classList.remove('hidden');
    setTimeout(()=>t.classList.add('hidden'),3500);
}
function renderSection(key, data) {
    if(key==='overview')  renderOverview(data);
    if(key==='products')  renderProducts(data);
    if(key==='inventory') renderInventory(data);
    if(key==='reviews')   renderReviews(data);
    if(key==='marketing') renderMarketing(data);
    if(key==='customers') renderCustomers(data);
    if(key==='catalog')   renderCatalog(data);
    if(key==='activity')  renderActivity(data);
}
</script>

<script>
// ── OVERVIEW ──────────────────────────────────────────────────────
function renderOverview(d) {
    setText('ov-total-products',    d.total_products);
    setText('ov-total-collections', d.total_collections);
    setText('ov-total-reviews',     d.total_reviews);
    setText('ov-avg-rating',        (d.avg_rating||0) + ' ★');
    setText('ov-stock-value',       '₹' + Number(d.stock_value||0).toLocaleString('en-IN',{maximumFractionDigits:0}));
    setText('ov-low-stock',         d.low_stock_count);
    setText('ov-out-stock',         d.out_of_stock_count);
    setText('ov-promotions',        d.active_promotions);
    setText('ov-coupons',           d.active_coupons);
    const nr = document.getElementById('ov-new-reviews');
    if (nr) nr.textContent = (d.new_reviews||0) + ' new in period';

    // Review trend chart
    const trend = d.review_trend || [];
    createChart('chartReviewTrend','line', trend.map(t=>t.date),
        [{label:'Reviews',data:trend.map(t=>t.count),borderColor:'#0082C3',backgroundColor:'#0082C320',fill:true,tension:0.4,pointRadius:3}],
        {plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}},x:{ticks:{font:{size:10}}}}}
    );

    const tr = document.getElementById('ov-top-rated');
    tr.innerHTML = (!d.top_rated||!d.top_rated.length) ? '<p class="text-sm text-gray-400 p-5">No rated products yet</p>'
        : d.top_rated.map(p=>`<div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50">
            <div class="min-w-0"><p class="text-sm font-semibold text-gray-900 truncate">${esc(p.name)}</p><p class="text-xs text-gray-400">${esc(p.brand||'—')}</p></div>
            <div class="text-right ml-3 flex-shrink-0"><p class="text-sm font-bold text-yellow-500">${stars(p.avg_rating)} ${p.avg_rating}</p><p class="text-xs text-gray-400">${p.reviews_count} reviews</p></div>
        </div>`).join('');

    const rr = document.getElementById('ov-recent-reviews');
    rr.innerHTML = (!d.recent_reviews||!d.recent_reviews.length) ? '<p class="text-sm text-gray-400 p-5">No reviews yet</p>'
        : d.recent_reviews.map(r=>`<div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50">
            <div class="min-w-0"><p class="text-sm font-semibold text-gray-900">${esc(r.reviewer_name)}</p><p class="text-xs text-gray-400 truncate">${esc(r.product)}</p></div>
            <div class="text-right ml-3 flex-shrink-0"><p class="text-xs text-yellow-500">${stars(r.rating)}</p><div class="flex items-center gap-1 justify-end mt-0.5">${statusPill(r.status)}<span class="text-xs text-gray-400">${esc(r.created_at)}</span></div></div>
        </div>`).join('');
}

// ── PRODUCTS ──────────────────────────────────────────────────────
function renderProducts(d) {
    setText('prod-featured',   d.flags?.featured || 0);
    setText('prod-new',        d.flags?.new || 0);
    setText('prod-bestseller', d.flags?.best_seller || 0);

    const cat = d.by_category||[];
    createChart('chartByCategory','bar', cat.map(i=>i.name),
        [{label:'Products',data:cat.map(i=>i.count),backgroundColor:cat.map((_,i)=>COLORS[i%COLORS.length]),borderRadius:4}],
        {indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{precision:0}},y:{ticks:{font:{size:11}}}}}
    );
    const br = d.by_brand||[];
    createChart('chartByBrand','bar', br.map(i=>i.name),
        [{label:'Products',data:br.map(i=>i.count),backgroundColor:br.map((_,i)=>COLORS[i%COLORS.length]),borderRadius:4}],
        {indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{precision:0}},y:{ticks:{font:{size:11}}}}}
    );
    const tp = d.by_type||[];
    createChart('chartByType','doughnut', tp.map(i=>i.type),
        [{data:tp.map(i=>i.count),backgroundColor:tp.map((_,i)=>COLORS[i%COLORS.length]),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );
    const sd = d.stock_distribution||{};
    createChart('chartStockDist','doughnut',['In Stock','Low Stock','Out of Stock'],
        [{data:[sd.in_stock||0,sd.low_stock||0,sd.out_of_stock||0],backgroundColor:['#10b981','#f59e0b','#ef4444'],borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );
    const bs = d.by_status||{};
    const bsLabels = Object.keys(bs);
    createChart('chartByStatus','doughnut', bsLabels,
        [{data:bsLabels.map(k=>bs[k]),backgroundColor:bsLabels.map((_,i)=>COLORS[i%COLORS.length]),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );
}

// ── INVENTORY ─────────────────────────────────────────────────────
function renderInventory(d) {
    const mvColorMap = {purchase:'#10b981',sale:'#ef4444',return:'#3b82f6',adjustment:'#f59e0b',damage:'#6b7280',transfer:'#8b5cf6',expired:'#ec4899'};
    const mv = d.movements_by_type||[];
    createChart('chartMovements','bar', mv.map(m=>m.label||m.type),
        [{label:'Movements',data:mv.map(m=>m.count),backgroundColor:mv.map(m=>mvColorMap[m.type]||'#6b7280'),borderRadius:4}],
        {plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}}}}
    );

    const lsEl = document.getElementById('table-low-stock');
    document.getElementById('low-stock-badge').textContent = (d.low_stock_products||[]).length;
    lsEl.innerHTML = (!d.low_stock_products||!d.low_stock_products.length) ? '<p class="text-sm text-gray-400 p-5">No low stock products 🎉</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Product</th><th class="px-4 py-2 text-right">Stock</th><th class="px-4 py-2 text-right">Threshold</th><th class="px-4 py-2 text-center">Urgency</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${d.low_stock_products.map(p=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-medium text-gray-900 truncate max-w-[140px]">${esc(p.name)}</td><td class="px-4 py-2.5 text-right font-bold text-orange-600">${p.stock_quantity}</td><td class="px-4 py-2.5 text-right text-gray-500">${p.threshold}</td><td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium ${p.urgency==='critical'?'bg-red-100 text-red-700':'bg-orange-100 text-orange-700'}">${esc(p.urgency)}</span></td></tr>`).join('')}
        </tbody></table>`;

    const osEl = document.getElementById('table-out-stock');
    document.getElementById('out-stock-badge').textContent = (d.out_of_stock||[]).length;
    osEl.innerHTML = (!d.out_of_stock||!d.out_of_stock.length) ? '<p class="text-sm text-gray-400 p-5">No out-of-stock products 🎉</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Product</th><th class="px-4 py-2 text-left">Brand</th><th class="px-4 py-2 text-left">SKU</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${d.out_of_stock.map(p=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-medium text-gray-900 truncate max-w-[140px]">${esc(p.name)}</td><td class="px-4 py-2.5 text-gray-500 text-xs">${esc(p.brand||'—')}</td><td class="px-4 py-2.5 text-gray-400 text-xs font-mono">${esc(p.sku_prefix||'—')}</td></tr>`).join('')}
        </tbody></table>`;

    // Recent movements
    const rmEl = document.getElementById('table-recent-movements');
    const rm = d.recent_movements||[];
    rmEl.innerHTML = !rm.length ? '<p class="text-sm text-gray-400 p-5">No movements yet</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Product</th><th class="px-4 py-2 text-center">Type</th><th class="px-4 py-2 text-right">Qty</th><th class="px-4 py-2 text-left">Note</th><th class="px-4 py-2 text-right">Time</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${rm.map(m=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-medium text-gray-900 truncate max-w-[140px]">${esc(m.product)}</td><td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium" style="background:${mvColorMap[m.type]||'#6b7280'}20;color:${mvColorMap[m.type]||'#6b7280'}">${esc(m.type)}</span></td><td class="px-4 py-2.5 text-right font-bold ${m.quantity>0?'text-green-600':'text-red-600'}">${m.quantity>0?'+':''}${m.quantity}</td><td class="px-4 py-2.5 text-gray-500 text-xs truncate max-w-[120px]">${esc(m.note||'—')}</td><td class="px-4 py-2.5 text-right text-xs text-gray-400">${esc(m.created_at)}</td></tr>`).join('')}
        </tbody></table>`;

    // Warehouses
    const whEl = document.getElementById('table-warehouses');
    const wh = d.warehouse_summary||[];
    whEl.innerHTML = !wh.length ? '<p class="text-sm text-gray-400">No warehouses configured</p>'
        : `<div class="grid grid-cols-2 md:grid-cols-4 gap-3">${wh.map(w=>`<div class="p-3 bg-gray-50 rounded-xl border border-gray-100"><p class="text-sm font-bold text-gray-900">${esc(w.name)}</p><p class="text-xs text-gray-400 font-mono">${esc(w.code)}</p><p class="text-xs text-gray-500 mt-0.5">${esc(w.city||'—')}</p></div>`).join('')}</div>`;
}
</script>

<script>
// ── REVIEWS ───────────────────────────────────────────────────────
let allReviews = [];
function renderReviews(d) {
    allReviews = d.recent_reviews || [];
    setText('rv-total',    d.total);
    setText('rv-avg',      (d.avg_rating||0) + ' ★');
    setText('rv-approved', d.by_status?.approved||0);
    setText('rv-pending',  d.by_status?.pending||0);
    setText('rv-verified', d.verified||0);
    setText('rv-rejected', d.by_status?.rejected||0);

    const rd = [...(d.rating_dist||[])].sort((a,b)=>b.rating-a.rating);
    createChart('chartRatingDist','bar', rd.map(i=>i.rating+' ★'),
        [{label:'Reviews',data:rd.map(i=>i.count),backgroundColor:'#f59e0b',borderRadius:4}],
        {indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{precision:0}}}}
    );

    const bs = d.by_status||{};
    const bsLabels = Object.keys(bs);
    createChart('chartReviewStatus','doughnut', bsLabels,
        [{data:bsLabels.map(k=>bs[k]),backgroundColor:bsLabels.map(k=>({approved:'#10b981',pending:'#f59e0b',rejected:'#ef4444',spam:'#6b7280'}[k]||'#6b7280')),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );

    const src = d.by_source||{};
    const srcLabels = Object.keys(src);
    createChart('chartReviewSource','doughnut', srcLabels,
        [{data:srcLabels.map(k=>src[k]),backgroundColor:srcLabels.map((_,i)=>COLORS[i%COLORS.length]),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );

    const trend = d.review_trend||[];
    createChart('chartReviewTrendTab','line', trend.map(t=>t.date),
        [{label:'Reviews',data:trend.map(t=>t.count),borderColor:'#f59e0b',backgroundColor:'#f59e0b20',fill:true,tension:0.4,pointRadius:3}],
        {plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}},x:{ticks:{font:{size:10}}}}}
    );

    renderReviewsTable(allReviews);
}

function filterReviews() {
    const filter = document.getElementById('reviewFilter').value;
    const filtered = filter ? allReviews.filter(r => r.status === filter) : allReviews;
    renderReviewsTable(filtered);
}

function renderReviewsTable(reviews) {
    const el = document.getElementById('table-recent-reviews');
    el.innerHTML = !reviews.length ? '<p class="text-sm text-gray-400 p-5">No reviews found</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase sticky top-0"><th class="px-4 py-2 text-left">Reviewer</th><th class="px-4 py-2 text-left">Product</th><th class="px-4 py-2 text-center">Rating</th><th class="px-4 py-2 text-left">Title</th><th class="px-4 py-2 text-center">Status</th><th class="px-4 py-2 text-center">Verified</th><th class="px-4 py-2 text-center">Source</th><th class="px-4 py-2 text-right">Time</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${reviews.map(r=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-medium text-gray-900">${esc(r.reviewer_name)}</td><td class="px-4 py-2.5 text-gray-500 truncate max-w-[120px]">${esc(r.product)}</td><td class="px-4 py-2.5 text-center text-yellow-500 text-xs">${stars(r.rating)}</td><td class="px-4 py-2.5 text-gray-600 truncate max-w-[120px]">${esc(r.title||'—')}</td><td class="px-4 py-2.5 text-center">${statusPill(r.status)}</td><td class="px-4 py-2.5 text-center text-xs">${r.verified_purchase?'<span class="text-green-600 font-semibold">✓</span>':'<span class="text-gray-300">—</span>'}</td><td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">${esc(r.source||'—')}</span></td><td class="px-4 py-2.5 text-right text-xs text-gray-400">${esc(r.created_at)}</td></tr>`).join('')}
        </tbody></table>`;
}

// ── MARKETING ─────────────────────────────────────────────────────
function renderMarketing(d) {
    setText('mkt-campaigns',  d.total_campaigns);
    setText('mkt-sent',       Number(d.total_sent||0).toLocaleString('en-IN'));
    setText('mkt-opened',     Number(d.total_opened||0).toLocaleString('en-IN'));
    setText('mkt-open-rate',  (d.open_rate||0) + '%');
    setText('mkt-click-rate', (d.click_rate||0) + '%');

    const pt = d.promotions_by_type||[];
    createChart('chartPromoType','bar', pt.map(t=>t.type.replace(/_/g,' ')),
        [{label:'Promotions',data:pt.map(t=>t.count),backgroundColor:pt.map((_,i)=>COLORS[i%COLORS.length]),borderRadius:4}],
        {plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}}}}
    );

    const ps = d.promotions_by_status||{};
    const psLabels = Object.keys(ps);
    createChart('chartPromoStatus','doughnut', psLabels,
        [{data:psLabels.map(k=>ps[k]),backgroundColor:psLabels.map(k=>({active:'#10b981',scheduled:'#3b82f6',expired:'#ef4444',inactive:'#6b7280'}[k]||'#6b7280')),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );

    const cu = d.coupons_usage||[];
    createChart('chartCoupons','bar', cu.map(c=>c.code),
        [{label:'Used',data:cu.map(c=>c.used_count),backgroundColor:cu.map(c=>(c.percent||0)>=90?'#ef4444':(c.percent||0)>=60?'#f59e0b':'#10b981'),borderRadius:4}],
        {indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{precision:0}},y:{ticks:{font:{size:11}}}}}
    );

    const cs = d.campaigns_by_status||{};
    const csLabels = Object.keys(cs);
    createChart('chartCampaigns','doughnut', csLabels,
        [{data:csLabels.map(k=>cs[k]),backgroundColor:csLabels.map(k=>({draft:'#6b7280',scheduled:'#f59e0b',sending:'#3b82f6',sent:'#10b981',paused:'#f97316',cancelled:'#ef4444'}[k]||'#6b7280')),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );

    // Top campaigns table
    const tcEl = document.getElementById('table-top-campaigns');
    const tc = d.top_campaigns||[];
    tcEl.innerHTML = !tc.length ? '<p class="text-sm text-gray-400 p-5">No sent campaigns yet</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Campaign</th><th class="px-4 py-2 text-right">Sent</th><th class="px-4 py-2 text-right">Opened</th><th class="px-4 py-2 text-right">Clicked</th><th class="px-4 py-2 text-right">Open Rate</th><th class="px-4 py-2 text-right">Click Rate</th><th class="px-4 py-2 text-right">Date</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${tc.map(c=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-medium text-gray-900 truncate max-w-[160px]">${esc(c.name)}</td><td class="px-4 py-2.5 text-right text-gray-600">${Number(c.sent).toLocaleString()}</td><td class="px-4 py-2.5 text-right text-gray-600">${Number(c.opened).toLocaleString()}</td><td class="px-4 py-2.5 text-right text-gray-600">${Number(c.clicked).toLocaleString()}</td><td class="px-4 py-2.5 text-right font-bold text-green-600">${c.open_rate}%</td><td class="px-4 py-2.5 text-right font-bold text-blue-600">${c.click_rate}%</td><td class="px-4 py-2.5 text-right text-xs text-gray-400">${esc(c.sent_at||'—')}</td></tr>`).join('')}
        </tbody></table>`;

    // Coupons detail
    const cdEl = document.getElementById('table-coupons-detail');
    cdEl.innerHTML = !cu.length ? '<p class="text-sm text-gray-400 p-5">No coupons yet</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Code</th><th class="px-4 py-2 text-left">Name</th><th class="px-4 py-2 text-left">Type</th><th class="px-4 py-2 text-right">Value</th><th class="px-4 py-2 text-right">Used</th><th class="px-4 py-2 text-right">Limit</th><th class="px-4 py-2 text-right">Usage %</th><th class="px-4 py-2 text-center">Status</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${cu.map(c=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-mono font-bold text-gray-900 text-xs">${esc(c.code)}</td><td class="px-4 py-2.5 text-gray-600 truncate max-w-[120px]">${esc(c.name)}</td><td class="px-4 py-2.5 text-gray-500 text-xs capitalize">${esc(c.discount_type||'—')}</td><td class="px-4 py-2.5 text-right font-semibold text-gray-900">${c.discount_type==='percentage'?(c.discount_value+'%'):('₹'+c.discount_value)}</td><td class="px-4 py-2.5 text-right text-gray-600">${c.used_count}</td><td class="px-4 py-2.5 text-right text-gray-400">${c.usage_limit||'∞'}</td><td class="px-4 py-2.5 text-right"><div class="flex items-center justify-end gap-2"><div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full rounded-full" style="width:${Math.min(c.percent,100)}%;background:${c.percent>=90?'#ef4444':c.percent>=60?'#f59e0b':'#10b981'}"></div></div><span class="text-xs font-semibold text-gray-700">${c.percent}%</span></div></td><td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium ${c.is_active?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500'}">${c.is_active?'Active':'Inactive'}</span></td></tr>`).join('')}
        </tbody></table>`;
}

// ── Export CSV ────────────────────────────────────────────────────
function exportCurrentTab() {
    const d = currentData[currentSection];
    if (!d) { showToast('error', 'Load the tab first'); return; }
    let rows = [], headers = [];

    if (currentSection === 'overview') {
        headers = ['Metric','Value'];
        rows = [['Total Products',d.total_products],['Total Collections',d.total_collections],['Total Reviews',d.total_reviews],['Avg Rating',d.avg_rating],['Stock Value',d.stock_value],['Low Stock',d.low_stock_count],['Out of Stock',d.out_of_stock_count],['Active Promotions',d.active_promotions],['Active Coupons',d.active_coupons]];
    } else if (currentSection === 'products') {
        headers = ['Category/Brand','Count'];
        rows = (d.by_category||[]).map(c=>[c.name,c.count]);
    } else if (currentSection === 'inventory') {
        headers = ['Product','Brand','Stock','Threshold','Urgency'];
        rows = (d.low_stock_products||[]).map(p=>[p.name,p.brand,p.stock_quantity,p.threshold,p.urgency]);
    } else if (currentSection === 'reviews') {
        headers = ['Reviewer','Product','Rating','Title','Status','Verified','Source','Time'];
        rows = (d.recent_reviews||[]).map(r=>[r.reviewer_name,r.product,r.rating,r.title||'',r.status,r.verified_purchase?'Yes':'No',r.source||'',r.created_at]);
    } else if (currentSection === 'marketing') {
        headers = ['Code','Name','Type','Used','Limit','Usage%','Active'];
        rows = (d.coupons_usage||[]).map(c=>[c.code,c.name,c.discount_type,c.used_count,c.usage_limit||'∞',c.percent+'%',c.is_active?'Yes':'No']);
    }

    const csv = [headers, ...rows].map(r => r.map(v => '"'+String(v||'').replace(/"/g,'""')+'"').join(',')).join('\n');
    const blob = new Blob([csv], {type:'text/csv'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = `report-${currentSection}-${new Date().toISOString().split('T')[0]}.csv`;
    a.click(); URL.revokeObjectURL(url);
    showToast('success', 'CSV exported!');
}

// ── CUSTOMERS ─────────────────────────────────────────────────────
function renderCustomers(d) {
    setText('cust-total',  d.total_users);
    setText('cust-active', d.active_users);
    setText('cust-admins', d.total_users);
    setText('cust-roles',  d.total_roles);

    const ur = d.users_by_role||[];
    createChart('chartUsersByRole','doughnut', ur.map(r=>r.role),
        [{data:ur.map(r=>r.count),backgroundColor:ur.map((_,i)=>COLORS[i%COLORS.length]),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );
    createChart('chartUsersStatus','doughnut',['Active','Inactive'],
        [{data:[d.active_users,d.total_users-d.active_users],backgroundColor:['#10b981','#ef4444'],borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );

    const usEl = document.getElementById('table-users');
    const users = d.users||[];
    usEl.innerHTML = !users.length ? '<p class="text-sm text-gray-400 p-5">No users</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Name</th><th class="px-4 py-2 text-left">Email</th><th class="px-4 py-2 text-left">Role</th><th class="px-4 py-2 text-center">Status</th><th class="px-4 py-2 text-right">Last Login</th><th class="px-4 py-2 text-right">Joined</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${users.map(u=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-medium text-gray-900">${esc(u.name)}</td><td class="px-4 py-2.5 text-gray-500 text-xs">${esc(u.email)}</td><td class="px-4 py-2.5"><span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded font-medium">${esc(u.role)}</span></td><td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium ${u.is_active?'bg-green-100 text-green-700':'bg-red-100 text-red-700'}">${u.is_active?'Active':'Inactive'}</span></td><td class="px-4 py-2.5 text-right text-xs text-gray-400">${esc(u.last_login)}</td><td class="px-4 py-2.5 text-right text-xs text-gray-400">${esc(u.created_at)}</td></tr>`).join('')}
        </tbody></table>`;
}

// ── CATALOG ───────────────────────────────────────────────────────
function renderCatalog(d) {
    setText('cat-products',    d.total_products);
    setText('cat-categories',  d.total_categories);
    setText('cat-brands',      d.total_brands);
    setText('cat-collections', d.total_collections);
    setText('cat-tags',        d.total_tags);
    setText('cat-attributes',  d.total_attributes);

    const cat = d.by_category||[];
    createChart('chartCatalogCategories','bar', cat.map(c=>c.name),
        [{label:'Products',data:cat.map(c=>c.count),backgroundColor:cat.map((_,i)=>COLORS[i%COLORS.length]),borderRadius:4}],
        {indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{precision:0}},y:{ticks:{font:{size:11}}}}}
    );
    const br = d.by_brand||[];
    createChart('chartCatalogBrands','bar', br.map(b=>b.name),
        [{label:'Products',data:br.map(b=>b.count),backgroundColor:br.map((_,i)=>COLORS[i%COLORS.length]),borderRadius:4}],
        {indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{precision:0}},y:{ticks:{font:{size:11}}}}}
    );

    const colEl = document.getElementById('table-collections');
    const cols = d.collections||[];
    colEl.innerHTML = !cols.length ? '<p class="text-sm text-gray-400 p-5">No collections</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Name</th><th class="px-4 py-2 text-center">Type</th><th class="px-4 py-2 text-right">Products</th><th class="px-4 py-2 text-center">Status</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${cols.map(c=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5 font-medium text-gray-900">${esc(c.name)}</td><td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">${esc(c.type)}</span></td><td class="px-4 py-2.5 text-right font-bold text-gray-900">${c.products_count}</td><td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium ${c.is_active?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500'}">${c.is_active?'Active':'Inactive'}</span></td></tr>`).join('')}
        </tbody></table>`;
}

// ── ACTIVITY ──────────────────────────────────────────────────────
function renderActivity(d) {
    setText('act-total',   d.total);
    setText('act-today',   d.today);
    setText('act-created', d.created);
    setText('act-deleted', d.deleted);

    const ba = d.by_action||[];
    createChart('chartActivityByAction','doughnut', ba.map(a=>a.action),
        [{data:ba.map(a=>a.count),backgroundColor:ba.map((_,i)=>COLORS[i%COLORS.length]),borderWidth:2,borderColor:'#fff'}],
        {plugins:{legend:{display:true,position:'bottom'}}}
    );
    const bm = d.by_module||[];
    createChart('chartActivityByModule','bar', bm.map(m=>m.module),
        [{label:'Actions',data:bm.map(m=>m.count),backgroundColor:bm.map((_,i)=>COLORS[i%COLORS.length]),borderRadius:4}],
        {indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{precision:0}},y:{ticks:{font:{size:11}}}}}
    );
    const trend = d.trend||[];
    createChart('chartActivityTrend','line', trend.map(t=>t.date),
        [{label:'Actions',data:trend.map(t=>t.count),borderColor:'#6366f1',backgroundColor:'#6366f120',fill:true,tension:0.4,pointRadius:3}],
        {plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}},x:{ticks:{font:{size:10}}}}}
    );

    const actEl = document.getElementById('table-activity');
    const recent = d.recent||[];
    const actionColors = {created:'#047857',updated:'#0369a1',deleted:'#b91c1c',status_changed:'#b45309',login:'#3730a3',logout:'#374151'};
    actEl.innerHTML = !recent.length ? '<p class="text-sm text-gray-400 p-5">No activity</p>'
        : `<table class="w-full text-sm"><thead><tr class="bg-gray-50 text-xs text-gray-500 uppercase"><th class="px-4 py-2 text-left">Action</th><th class="px-4 py-2 text-left">Module</th><th class="px-4 py-2 text-left">Description</th><th class="px-4 py-2 text-left">User</th><th class="px-4 py-2 text-right">Time</th></tr></thead><tbody class="divide-y divide-gray-50">
        ${recent.map(a=>`<tr class="hover:bg-gray-50"><td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full text-xs font-semibold text-white" style="background:${actionColors[a.action]||'#374151'}">${esc(a.action)}</span></td><td class="px-4 py-2.5 text-xs font-mono text-gray-500">${esc(a.module||'—')}</td><td class="px-4 py-2.5 text-gray-600 truncate max-w-[200px]">${esc(a.description||'—')}</td><td class="px-4 py-2.5 text-gray-500 text-xs">${esc(a.user)}</td><td class="px-4 py-2.5 text-right text-xs text-gray-400">${esc(a.created_at)}</td></tr>`).join('')}
        </tbody></table>`;
}

// ── Init ──────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => { loadSection('overview'); });</script>
@endpush
