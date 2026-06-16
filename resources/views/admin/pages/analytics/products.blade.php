@extends('admin.layouts.app')

@section('title', 'Product Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Analytics</h1>
            <p class="text-sm text-gray-600 mt-1">Track product performance and make data-driven decisions</p>
        </div>
        <div class="flex items-center gap-3">
            <select id="periodFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month" selected>This Month</option>
                <option value="year">This Year</option>
            </select>
            <button onclick="refreshAnalytics()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalViews">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="eye" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalOrders">0</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalRevenue">$0</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="circle-dollar-sign" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="conversionRate">0%</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Products by Revenue</h3>
            <div id="topRevenueProducts" class="space-y-3">
                <!-- Top products will be loaded here -->
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Products by Views</h3>
            <div id="topViewsProducts" class="space-y-3">
                <!-- Top products will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection