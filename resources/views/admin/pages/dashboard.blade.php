@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner + Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Welcome Banner -->
        <div class="lg:col-span-2 bg-gradient-to-r from-[#0082C3] to-[#005a8c] rounded-2xl p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Welcome back, Admin! 👋</h1>
                <p class="text-white/90 mb-6">Here's what's happening with your store today.</p>
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <p class="text-xs text-white/80">Today's Revenue</p>
                        <p class="text-2xl font-bold">₹45,890</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <p class="text-xs text-white/80">Orders Today</p>
                        <p class="text-2xl font-bold">23</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button class="w-full flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="font-semibold">Add Product</span>
                </button>
                <button class="w-full flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <span class="font-semibold">Create Coupon</span>
                </button>
                <button class="w-full flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="font-semibold">View Orders</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards (6 cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+12%</span>
            </div>
            <p class="text-xs text-gray-600 font-medium mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">₹2.4M</p>
        </div>

        <!-- Orders Today -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-gradient-to-br from-green-500 to-green-600 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+23</span>
            </div>
            <p class="text-xs text-gray-600 font-medium mb-1">Orders Today</p>
            <p class="text-2xl font-bold text-gray-900">156</p>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+8%</span>
            </div>
            <p class="text-xs text-gray-600 font-medium mb-1">Total Customers</p>
            <p class="text-2xl font-bold text-gray-900">3,456</p>
        </div>

        <!-- Conversion Rate -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+2.1%</span>
            </div>
            <p class="text-xs text-gray-600 font-medium mb-1">Conversion Rate</p>
            <p class="text-2xl font-bold text-gray-900">3.2%</p>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">12</span>
            </div>
            <p class="text-xs text-gray-600 font-medium mb-1">Pending Orders</p>
            <p class="text-2xl font-bold text-gray-900">45</p>
        </div>

        <!-- Refund Requests -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-gradient-to-br from-red-500 to-red-600 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">-3</span>
            </div>
            <p class="text-xs text-gray-600 font-medium mb-1">Refund Requests</p>
            <p class="text-2xl font-bold text-gray-900">8</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Revenue Overview</h3>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:border-[#0082C3]">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 90 days</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                <div class="text-center">
                    <svg class="w-16 h-16 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">Revenue Chart</p>
                    <p class="text-sm text-gray-400 mt-1">Chart.js Integration</p>
                </div>
            </div>
        </div>

        <!-- Orders Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Orders Analytics</h3>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:border-[#0082C3]">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                <div class="text-center">
                    <svg class="w-16 h-16 text-green-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">Orders Chart</p>
                    <p class="text-sm text-gray-400 mt-1">Chart.js Integration</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Activity & Inventory Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Orders Activity (2 columns) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
                <button class="text-sm font-semibold text-[#0082C3] hover:text-[#006699]">View All</button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Order ID</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Customer</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Product</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Amount</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4"><span class="font-semibold text-gray-900">#1234</span></td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm">JD</div>
                                    <span class="text-sm font-medium text-gray-900">John Doe</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600">Running Shoes</td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-900">₹2,499</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Delivered</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4"><span class="font-semibold text-gray-900">#1233</span></td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-semibold text-sm">JS</div>
                                    <span class="text-sm font-medium text-gray-900">Jane Smith</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600">Yoga Mat</td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-900">₹899</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Processing</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4"><span class="font-semibold text-gray-900">#1232</span></td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-semibold text-sm">MJ</div>
                                    <span class="text-sm font-medium text-gray-900">Mike Johnson</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600">Cycling Helmet</td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-900">₹1,299</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Shipped</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inventory Alerts (1 column) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Inventory Alerts</h3>
                <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full">3</span>
            </div>
            
            <div class="space-y-4">
                <!-- Low Stock -->
                <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-semibold text-gray-900">Running Shoes Pro</span>
                        </div>
                        <span class="text-xs font-bold text-red-600">3 left</span>
                    </div>
                    <p class="text-xs text-gray-600 ml-4">SKU: RS-PRO-001</p>
                </div>

                <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-semibold text-gray-900">Football Size 5</span>
                        </div>
                        <span class="text-xs font-bold text-red-600">2 left</span>
                    </div>
                    <p class="text-xs text-gray-600 ml-4">SKU: FB-S5-002</p>
                </div>

                <!-- Out of Stock -->
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <span class="text-sm font-semibold text-gray-900">Cricket Bat</span>
                        </div>
                        <span class="text-xs font-bold text-gray-600">Out of stock</span>
                    </div>
                    <p class="text-xs text-gray-600 ml-4">SKU: CB-001</p>
                </div>

                <!-- Recently Added -->
                <div class="p-4 bg-green-50 border border-green-100 rounded-xl">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-semibold text-gray-900">Gym Bag Premium</span>
                        </div>
                        <span class="text-xs font-bold text-green-600">New</span>
                    </div>
                    <p class="text-xs text-gray-600 ml-4">Added 2 hours ago</p>
                </div>
            </div>

            <button class="w-full mt-4 py-2.5 text-sm font-semibold text-[#0082C3] hover:bg-gray-50 rounded-lg transition-colors">
                View All Inventory
            </button>
        </div>
    </div>

    <!-- Top Products & Customer Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Top Products</h3>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:border-[#0082C3]">
                    <option>Best Selling</option>
                    <option>Most Viewed</option>
                    <option>Highest Revenue</option>
                </select>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-blue-600">1</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">Running Shoes Pro</p>
                        <div class="flex items-center gap-4 mt-1">
                            <span class="text-xs text-gray-500">234 sold</span>
                            <span class="text-xs font-semibold text-green-600">₹5.8L revenue</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">₹2,499</p>
                        <p class="text-xs text-green-600">45 in stock</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-green-600">2</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">Yoga Mat Premium</p>
                        <div class="flex items-center gap-4 mt-1">
                            <span class="text-xs text-gray-500">189 sold</span>
                            <span class="text-xs font-semibold text-green-600">₹1.7L revenue</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">₹899</p>
                        <p class="text-xs text-green-600">67 in stock</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-purple-600">3</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">Cycling Helmet</p>
                        <div class="flex items-center gap-4 mt-1">
                            <span class="text-xs text-gray-500">156 sold</span>
                            <span class="text-xs font-semibold text-green-600">₹2.0L revenue</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">₹1,299</p>
                        <p class="text-xs text-red-600">3 in stock</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-orange-600">4</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">Gym Bag</p>
                        <div class="flex items-center gap-4 mt-1">
                            <span class="text-xs text-gray-500">142 sold</span>
                            <span class="text-xs font-semibold text-green-600">₹1.1L revenue</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">₹799</p>
                        <p class="text-xs text-green-600">89 in stock</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Insights -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Customer Insights</h3>
            
            <div class="space-y-4">
                <!-- New Customers Today -->
                <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-500 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">New Customers</p>
                                <p class="text-xs text-gray-600">Registered today</p>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">12</p>
                    </div>
                </div>

                <!-- Returning Customers -->
                <div class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-500 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Returning Customers</p>
                                <p class="text-xs text-gray-600">Repeat purchases</p>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-green-600">67%</p>
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="border border-gray-200 rounded-xl p-4">
                    <h4 class="text-sm font-bold text-gray-900 mb-3">Top Customers</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    JD
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">John Doe</p>
                                    <p class="text-xs text-gray-500">23 orders</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-900">₹45,890</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    JS
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Jane Smith</p>
                                    <p class="text-xs text-gray-500">18 orders</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-900">₹38,450</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    MJ
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Mike Johnson</p>
                                    <p class="text-xs text-gray-500">15 orders</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-900">₹32,100</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
