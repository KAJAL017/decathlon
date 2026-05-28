@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products</h1>
            <p class="text-sm text-gray-600 mt-1">Manage your product catalog with variants and attributes</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Import/Export Dropdown -->
            <div class="relative" id="importExportDropdown">
                <button onclick="toggleImportExportDropdown()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    Import/Export
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="importExportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="py-1">
                        <button onclick="openImportModal()" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Import Products
                        </button>
                        <button onclick="openExportModal()" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Export Products
                        </button>
                        <hr class="my-1">
                        <a href="/admin/products/import/template" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Template
                        </a>
                        <button onclick="openImportExportJobsModal()" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View Jobs
                        </button>
                    </div>
                </div>
            </div>
            
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalProducts">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeProducts">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Draft</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="draftProducts">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Featured</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="featuredProducts">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">New</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="newProducts">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Best Seller</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="bestSellerProducts">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Bulk Actions -->
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex items-center gap-4 flex-wrap">
            <div id="bulkActionsContainer" class="hidden">
                <select id="bulkActionSelect" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="feature">Mark as Featured</option>
                    <option value="unfeature">Remove Featured</option>
                    <option value="delete">Delete</option>
                </select>
                <button onclick="applyBulkAction()" class="px-4 py-2.5 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Apply
                </button>
                <span id="selectedCount" class="text-sm text-gray-600 ml-2"></span>
            </div>
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search by name, SKU or slug..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="brandFilter" data-searchable data-placeholder="All Brands" onchange="loadProducts()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Brands</option>
            </select>
            <select id="categoryFilter" data-searchable data-placeholder="All Categories" onchange="loadProducts()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Categories</option>
            </select>
            <select id="typeFilter" data-searchable data-placeholder="All Types" onchange="loadProducts()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Types</option>
                <option value="simple">Simple</option>
                <option value="variable">Variable</option>
                <option value="digital">Digital</option>
                <option value="service">Service</option>
            </select>
            <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadProducts()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="draft">Draft</option>
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadProducts()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Brand</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Variants</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Price Range</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Skeleton Loader -->
                    <tr class="skeleton-row">
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-4 rounded"></div></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-16 h-16 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-32 mb-2"></div>
                                    <div class="skeleton-text h-3 w-24"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-20"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-24"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-8"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-24"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="skeleton-row">
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-4 rounded"></div></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-16 h-16 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-28 mb-2"></div>
                                    <div class="skeleton-text h-3 w-20"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-20"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-24"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-8"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-24"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 bg-gray-50"></div>
    </div>
</div>


<!-- Multi-Step Modal (Slide from Right) -->
<div id="productModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <!-- Modal Content -->
    <div id="productModalContent" class="fixed right-0 top-0 h-full w-full max-w-5xl bg-white shadow-2xl flex flex-col overflow-hidden" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div class="flex items-center gap-4">
                <div>
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Product</h3>
                    <p class="text-sm text-gray-600 mt-0.5">Create a new product with variants and attributes</p>
                </div>
                <button onclick="fillDemoData()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-600 text-white text-xs font-semibold rounded-lg hover:bg-purple-700 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Fill Demo Data
                </button>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Tabs Navigation -->
        <div class="px-4 py-2.5 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <div class="flex items-center gap-1 overflow-x-auto">

                {{-- 1. Details --}}
                <button onclick="switchTab('basic')" id="tab-basic" class="tab-btn active flex-shrink-0 px-3.5 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Details
                    </span>
                </button>

                {{-- 2. Pricing --}}
                <button onclick="switchTab('pricing')" id="tab-pricing" class="tab-btn flex-shrink-0 px-3.5 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pricing
                    </span>
                </button>

                {{-- 3. Media --}}
                <button onclick="switchTab('media')" id="tab-media" class="tab-btn flex-shrink-0 px-3.5 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Media
                    </span>
                </button>

                {{-- 4. Variants --}}
                <button onclick="switchTab('variants')" id="tab-variants" class="tab-btn flex-shrink-0 px-3.5 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                        Variants
                    </span>
                </button>

                {{-- 5. Organization --}}
                <button onclick="switchTab('organization')" id="tab-organization" class="tab-btn flex-shrink-0 px-3.5 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Organization
                    </span>
                </button>

                {{-- 6. SEO --}}
                <button onclick="switchTab('seo')" id="tab-seo" class="tab-btn flex-shrink-0 px-3.5 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        SEO
                    </span>
                </button>

                {{-- 7. Advanced --}}
                <button onclick="switchTab('advanced')" id="tab-advanced" class="tab-btn flex-shrink-0 px-3.5 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Advanced
                    </span>
                </button>
            </div>
        </div>

        <!-- Modal Body with Tabs -->
        <form id="productForm" class="flex-1 overflow-y-auto" style="visibility:hidden">
            <input type="hidden" id="productId">
            
            <!-- ══════════════════════════════════════════════════════
                 TAB 1: DETAILS
                 Name · Slug · Type · Brand · Description
            ══════════════════════════════════════════════════════ -->
            <div id="content-basic" class="tab-content active px-6 py-5" style="display:none">
                <div class="space-y-4">

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="productName" maxlength="200"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               placeholder="e.g., Kipsta Football Size 5" required>
                        <div class="flex items-center justify-between mt-1">
                            <p id="productNameError" class="hidden text-xs text-red-600"></p>
                            <p class="text-xs text-gray-400 ml-auto"><span id="productNameCount">0</span>/200</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Slug <span class="text-gray-400 text-xs">(auto-generated)</span>
                        </label>
                        <input type="text" id="productSlug"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               placeholder="kipsta-football-size-5">
                        <p id="productSlugError" class="hidden text-xs text-red-600 mt-1"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Product Type <span class="text-red-500">*</span>
                            </label>
                            <select id="productType" data-searchable data-placeholder="Select Type"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#0082C3]" required>
                                <option value="simple">Simple Product</option>
                                <option value="variable">Variable Product (with variants)</option>
                                <option value="digital">Digital Product</option>
                                <option value="service">Service</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Brand</label>
                            <select id="productBrand" data-searchable data-placeholder="Select Brand"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <option value="">Select Brand</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Short Description</label>
                        <textarea id="productShortDescription" rows="2" maxlength="500"
                                  class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                         focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                  placeholder="Brief product description (max 500 chars)"></textarea>
                        <p class="text-xs text-gray-400 mt-1 text-right"><span id="shortDescCount">0</span>/500</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea id="productDescription" rows="5"
                                  class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                         focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                  placeholder="Full product description with features, benefits, usage…"></textarea>
                    </div>

                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════
                 TAB 2: PRICING
                 Status · Availability · Pricing · SKU · Shipping · Digital
            ══════════════════════════════════════════════════════ -->
            <div id="content-pricing" class="tab-content px-6 py-5 space-y-6" style="display:none">

                {{-- Status & Availability --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status & Visibility
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select id="productStatus" data-searchable data-placeholder="Select Status"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#0082C3]" required>
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Availability <span class="text-red-500">*</span></label>
                            <select id="productAvailability" data-searchable data-placeholder="Select Availability"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                    onchange="toggleAvailableDate()" required>
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="pre_order">Pre-Order</option>
                                <option value="backorder">Backorder</option>
                            </select>
                        </div>
                        <div id="availableDateContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Available Date</label>
                            <input type="date" id="productAvailableDate"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Visibility</label>
                            <select id="productVisibility" data-searchable data-placeholder="Select Visibility"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <option value="visible">Visible (Everywhere)</option>
                                <option value="hidden">Hidden</option>
                                <option value="catalog_only">Catalog Only</option>
                                <option value="search_only">Search Only</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Pricing --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pricing
                    </h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Regular Price <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                <input type="number" step="0.01" min="0" id="productRegularPrice"
                                       class="w-full pl-8 pr-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                              focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                       placeholder="0.00" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sale Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                <input type="number" step="0.01" min="0" id="productSalePrice"
                                       class="w-full pl-8 pr-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                              focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                       placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Cost Per Item</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                <input type="number" step="0.01" min="0" id="productCostPrice"
                                       class="w-full pl-8 pr-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                              focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div id="profitMarginDisplay" class="hidden mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Profit Margin:</span>
                            <span class="font-semibold text-blue-700" id="profitMarginValue">0%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm mt-1">
                            <span class="text-gray-600">Profit Amount:</span>
                            <span class="font-semibold text-green-700" id="profitAmountValue">₹0.00</span>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Stock / Inventory --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Inventory & Stock
                    </h4>

                    <div class="flex items-center gap-3 mb-4 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <input type="checkbox" id="productManageStock"
                               class="w-4 h-4 text-[#0082C3] border-gray-300 rounded"
                               onchange="toggleStockFields()">
                        <label for="productManageStock" class="text-sm font-medium text-gray-700 cursor-pointer">
                            Track stock quantity for this product
                        </label>
                    </div>

                    <div id="stockFieldsContainer" class="grid grid-cols-2 gap-4" style="display:none">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Stock Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" min="0" id="productStockQuantity"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                   placeholder="0" oninput="updateStockBadge()">
                            <p class="text-xs text-gray-400 mt-1">Units currently in stock</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Low Stock Alert</label>
                            <input type="number" min="0" id="productLowStockThreshold"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                   placeholder="5" value="5">
                            <p class="text-xs text-gray-400 mt-1">Alert when stock ≤ this number</p>
                        </div>
                        <div class="col-span-2 flex items-center gap-3">
                            <input type="checkbox" id="productAllowBackorder"
                                   class="w-4 h-4 text-[#0082C3] border-gray-300 rounded">
                            <label for="productAllowBackorder" class="text-sm text-gray-700 cursor-pointer">
                                Allow backorders (sell even when out of stock)
                            </label>
                        </div>
                        <div id="stockStatusBadge" class="col-span-2 hidden">
                            <div id="stockStatusInner" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium"></div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h10"/>
                        </svg>
                        Identifiers
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SKU</label>
                            <div class="flex gap-2">
                                <input type="text" id="productSku"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                              focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                       placeholder="Auto-generated or enter custom">
                                <button type="button" onclick="generateSKU()"
                                        class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm
                                               font-medium rounded-lg transition-colors flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Generate
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Barcode (UPC / EAN)</label>
                            <input type="text" id="productBarcode"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                   placeholder="Enter barcode">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Shipping --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/>
                        </svg>
                        Shipping & Dimensions
                    </h4>
                    <div class="flex items-center gap-6 mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="productRequiresShipping" checked
                                   class="w-4 h-4 text-[#0082C3] border-gray-300 rounded"
                                   onchange="toggleShippingFields()">
                            <span class="text-sm text-gray-700">Requires shipping</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="productShipsSeparately"
                                   class="w-4 h-4 text-[#0082C3] border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Ships separately</span>
                        </label>
                    </div>
                    <div id="shippingDetailsFields" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Weight</label>
                                <div class="flex gap-2">
                                    <input type="number" step="0.01" min="0" id="productWeight"
                                           class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                                  focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                           placeholder="0.00">
                                    <select id="productWeightUnit"
                                            class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                                   focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                        <option value="lb">lb</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Dimension Unit</label>
                                <select id="productDimensionUnit"
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                               focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    <option value="cm">cm</option>
                                    <option value="m">m</option>
                                    <option value="in">in</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Length</label>
                                <input type="number" step="0.01" min="0" id="productLength"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                              focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Width</label>
                                <input type="number" step="0.01" min="0" id="productWidth"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                              focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Height</label>
                                <input type="number" step="0.01" min="0" id="productHeight"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                              focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Digital Product Settings --}}
                <div id="digitalProductSettings" class="hidden space-y-4 border-t pt-6">
                    <h4 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Digital Product Settings
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Download URL</label>
                            <input type="url" id="productDownloadUrl"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                   placeholder="https://example.com/download/file.zip">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Download Limit</label>
                            <input type="number" min="0" id="productDownloadLimit"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                   placeholder="0 = unlimited">
                        </div>
                    </div>
                </div>

            </div>

            <!-- ══════════════════════════════════════════════════════
                 TAB 3: MEDIA  (unchanged)
            ══════════════════════════════════════════════════════ -->
            <div id="content-media" class="tab-content px-6 py-4" style="display:none">
                <div class="space-y-6">
                    <!-- Images Section -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold text-gray-900">Product Images (ImageKit)</h4>
                        <p class="text-xs text-gray-500">Upload product images. First image will be featured image.</p>
                        
                        <!-- Image Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#0082C3] transition-colors">
                            <button type="button" onclick="openImageKitUpload()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Upload Images
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Recommended: 800x800px, PNG or JPG</p>
                        </div>

                        <!-- Uploaded Images Grid -->
                        <div id="productImagesGrid" class="grid grid-cols-4 gap-4 mt-4">
                            <!-- Images will be added here dynamically -->
                        </div>
                    </div>

                    <!-- Product Videos (Collapsible - Default Closed) -->
                    <div class="collapsible-section border-t pt-6" data-section="product-videos">
                        <div class="collapsible-header" onclick="toggleCollapsible('product-videos')">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span>Product Videos</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="section-badge optional">Optional</span>
                                <svg class="collapsible-icon w-5 h-5 text-gray-400 transition-transform duration-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="collapsible-content">
                            <div class="collapsible-body">
                                <p class="helper-text">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Add YouTube or Vimeo videos to showcase your product in action
                                </p>

                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Video Gallery</p>
                                        <p class="text-xs text-gray-500">Embed videos from YouTube or Vimeo</p>
                                    </div>
                                    <button type="button" onclick="openAddVideoModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Video
                                    </button>
                                </div>

                                <!-- Videos List -->
                                <div id="productVideosList" class="space-y-3">
                                    <div class="text-center py-8 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="font-medium">No videos added yet</p>
                                        <p class="text-xs mt-1">Click "Add Video" to embed your first video</p>
                                    </div>
                                </div>

                                <!-- Video Tips -->
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex gap-2">
                                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-xs text-blue-800">
                                            <p class="font-semibold mb-1">Video Best Practices:</p>
                                            <ul class="list-disc list-inside space-y-0.5 text-blue-700">
                                                <li>Use high-quality product demonstration videos</li>
                                                <li>Keep videos under 2 minutes for better engagement</li>
                                                <li>Add captions for accessibility</li>
                                                <li>Show product features and benefits clearly</li>
                                                <li>Supported: YouTube, Vimeo URLs</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Variants -->
            <div id="content-variants" class="tab-content px-6 py-4" style="display:none">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">Product Variants</h4>
                            <p class="text-xs text-gray-500 mt-1">Generate variants based on attributes (Color, Size, etc.)</p>
                        </div>
                        <button type="button" onclick="openVariantGenerator()" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Generate Variants
                        </button>
                    </div>

                    <!-- Variant Generator Panel -->
                    <div id="variantGeneratorPanel" class="hidden border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <h5 class="text-sm font-semibold text-gray-900 mb-3">Select Attributes for Variants</h5>
                        <div id="variantAttributesContainer" class="space-y-3">
                            <!-- Attributes will be loaded here -->
                        </div>
                        <div class="flex items-center gap-2 mt-4">
                            <button type="button" onclick="generateVariants()" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                Generate
                            </button>
                            <button type="button" onclick="closeVariantGenerator()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Generated Variants List -->
                    <div id="variantsListContainer" class="space-y-2">
                        <p class="text-sm text-gray-500 text-center py-8">No variants yet. Click "Generate Variants" to create them.</p>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <!-- Product Attributes Section -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Product Attributes</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Non-variant specs — Material, Warranty, Country of Origin, etc.</p>
                                </div>
                            </div>
                            <button type="button" onclick="openAddAttributeModal()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Attribute
                            </button>
                        </div>

                        <div id="productAttributesList" class="space-y-2">
                            <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="font-medium text-gray-400">No attributes added yet</p>
                                <p class="text-xs mt-1 text-gray-400">Click "Add Attribute" above to add product specs</p>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex gap-2 text-xs text-green-800">
                                <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span><strong>Tip:</strong> Product Attributes are for specs like Material, Warranty, Origin. For Size/Color variants, use "Generate Variants" above.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════
                 TAB 5: ORGANIZATION
                 Categories · Tags · Collections · Product Flags
            ══════════════════════════════════════════════════════ -->
            <div id="content-organization" class="tab-content px-6 py-5 space-y-6" style="display:none">

                {{-- Categories --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Categories
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Primary Category</label>
                            <select id="productPrimaryCategory" data-searchable data-placeholder="Select Primary Category"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <option value="">Select Primary Category</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Additional Categories</label>
                            <select id="productAdditionalCategories" multiple data-searchable data-placeholder="Select Categories"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Tags --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tags
                    </h4>
                    <select id="productTags" multiple data-searchable data-placeholder="Select Tags"
                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Tags improve searchability and help customers find this product</p>
                </div>

                <hr class="border-gray-100">

                {{-- Collections --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Collections
                    </h4>
                    <select id="productCollections" multiple data-searchable data-placeholder="Select Collections"
                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Add to curated collections — Best Sellers, Summer Sale, etc.</p>
                </div>

                <hr class="border-gray-100">

                {{-- Product Flags --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        Product Flags
                    </h4>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" id="productIsFeatured"
                                   class="w-4 h-4 text-[#0082C3] border-gray-300 rounded">
                            <div>
                                <p class="text-sm font-medium text-gray-700">⭐ Featured</p>
                                <p class="text-xs text-gray-400">Show in featured sections</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" id="productIsNew"
                                   class="w-4 h-4 text-[#0082C3] border-gray-300 rounded">
                            <div>
                                <p class="text-sm font-medium text-gray-700">🆕 New Arrival</p>
                                <p class="text-xs text-gray-400">Badge: New</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" id="productIsBestSeller"
                                   class="w-4 h-4 text-[#0082C3] border-gray-300 rounded">
                            <div>
                                <p class="text-sm font-medium text-gray-700">🔥 Best Seller</p>
                                <p class="text-xs text-gray-400">Badge: Best Seller</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" id="productIsDigital"
                                   class="w-4 h-4 text-[#0082C3] border-gray-300 rounded">
                            <div>
                                <p class="text-sm font-medium text-gray-700">💾 Digital Product</p>
                                <p class="text-xs text-gray-400">No physical shipping</p>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Tab: SEO -->
            <div id="content-seo" class="tab-content px-6 py-5" style="display:none">
                <div class="space-y-5">

                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">SEO Settings</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Optimize your product for search engines</p>
                        </div>
                        <button type="button" onclick="autoGenerateSEO()"
                                class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#0082C3] text-white
                                       text-xs font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Auto Generate
                        </button>
                    </div>

                    <!-- SEO Title -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-gray-700">SEO Title</label>
                            <span id="seoTitleCount" class="text-xs text-gray-400">0/60</span>
                        </div>
                        <input type="text" id="productSeoTitle" maxlength="60"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               placeholder="e.g., Kipsta Football Size 5 — Buy Online | Decathlon"
                               oninput="updateSeoCounter('productSeoTitle','seoTitleCount',60); updateSeoPreview()">
                        <div class="mt-1.5 h-1 rounded-full bg-gray-200 overflow-hidden">
                            <div id="seoTitleProgress" class="h-full bg-green-500 transition-all duration-300" style="width:0%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Ideal: 50–60 characters</p>
                    </div>

                    <!-- SEO Description -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-gray-700">SEO Description</label>
                            <span id="seoDescCount" class="text-xs text-gray-400">0/160</span>
                        </div>
                        <textarea id="productSeoDescription" maxlength="160" rows="3"
                                  class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                         focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                  placeholder="Brief product description for Google search results…"
                                  oninput="updateSeoCounter('productSeoDescription','seoDescCount',160); updateSeoPreview()"></textarea>
                        <div class="mt-1.5 h-1 rounded-full bg-gray-200 overflow-hidden">
                            <div id="seoDescProgress" class="h-full bg-green-500 transition-all duration-300" style="width:0%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Ideal: 120–160 characters</p>
                    </div>

                    <!-- SEO Keywords -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">SEO Keywords</label>
                        <input type="text" id="productSeoKeywords"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               placeholder="football, size 5, kipsta, decathlon, outdoor sport"
                               oninput="updateSeoPreview()">
                        <p class="text-xs text-gray-400 mt-1">Separate with commas</p>
                    </div>

                    <!-- Google Preview -->
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">🔍 Google Search Preview</p>
                        <div class="bg-white border border-gray-100 rounded-lg p-4 space-y-1 shadow-sm">
                            <p id="seoPreviewUrl" class="text-xs text-green-700 truncate font-mono">
                                yoursite.com › products › product-slug
                            </p>
                            <p id="seoPreviewTitle" class="text-base font-medium text-blue-700 leading-snug truncate">
                                Product Title — Decathlon
                            </p>
                            <p id="seoPreviewDesc" class="text-sm text-gray-600 leading-snug" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                Product description will appear here in Google search results…
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Tab: Advanced -->
            <div id="content-advanced" class="tab-content px-6 py-4" style="display:none">
                <div class="space-y-6">
                    
                    <!-- Related Products Section (Collapsible - Default Closed) -->
                    <div class="collapsible-section" data-section="related-products">
                        <div class="collapsible-header" onclick="toggleCollapsible('related-products')">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <span>Related Products</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="section-badge optional">Optional</span>
                                <svg class="collapsible-icon w-5 h-5 text-gray-400 transition-transform duration-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="collapsible-content">
                            <div class="collapsible-body">
                                <p class="helper-text">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Suggest similar or complementary products to increase cross-selling
                                </p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Related Products</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Products that are similar or complementary</p>
                                            </div>
                                            <button type="button" onclick="openProductSelector('related')" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                + Add Related
                                            </button>
                                        </div>
                                        <div id="relatedProductsList" class="space-y-2 min-h-[60px] border border-gray-200 rounded-lg p-3 bg-gray-50">
                                            <p class="text-xs text-gray-400 text-center py-2">No related products added</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Upsell Products</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Higher-value alternatives to increase order value</p>
                                            </div>
                                            <button type="button" onclick="openProductSelector('upsell')" class="px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                                + Add Upsell
                                            </button>
                                        </div>
                                        <div id="upsellProductsList" class="space-y-2 min-h-[60px] border border-gray-200 rounded-lg p-3 bg-gray-50">
                                            <p class="text-xs text-gray-400 text-center py-2">No upsell products added</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Cross-sell Products</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Complementary products often bought together</p>
                                            </div>
                                            <button type="button" onclick="openProductSelector('cross_sell')" class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                                + Add Cross-sell
                                            </button>
                                        </div>
                                        <div id="crossSellProductsList" class="space-y-2 min-h-[60px] border border-gray-200 rounded-lg p-3 bg-gray-50">
                                            <p class="text-xs text-gray-400 text-center py-2">No cross-sell products added</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQs Section (Collapsible - Default Closed) -->
                    <div class="collapsible-section border-t pt-6" data-section="product-faqs">
                        <div class="collapsible-header" onclick="toggleCollapsible('product-faqs')">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Frequently Asked Questions</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="section-badge optional">Optional</span>
                                <svg class="collapsible-icon w-5 h-5 text-gray-400 transition-transform duration-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="collapsible-content">
                            <div class="collapsible-body">
                                <p class="helper-text">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Add common questions and answers to help customers make informed decisions
                                </p>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">FAQ List</p>
                                            <p class="text-xs text-gray-500">Questions and answers about this product</p>
                                        </div>
                                        <button type="button" onclick="addFaqItem()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add FAQ
                                        </button>
                                    </div>
                                    <div id="productFaqsList" class="space-y-3">
                                        <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="font-medium">No FAQs added yet</p>
                                            <p class="text-xs mt-1">Click "Add FAQ" to add questions and answers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Bundles Section (Collapsible - Default Closed) -->
                    <div class="collapsible-section border-t pt-6" data-section="product-bundles">
                        <div class="collapsible-header" onclick="toggleCollapsible('product-bundles')">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Product Bundles</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="section-badge optional">Optional</span>
                                <svg class="collapsible-icon w-5 h-5 text-gray-400 transition-transform duration-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="collapsible-content">
                            <div class="collapsible-body">
                                <p class="helper-text">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Create product bundles by combining multiple products at a special price
                                </p>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <input type="checkbox" id="productIsBundle" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" onchange="toggleBundleFields()">
                                        <label for="productIsBundle" class="text-sm font-medium text-gray-700 cursor-pointer">
                                            This is a bundle product
                                        </label>
                                    </div>

                                    <div id="bundleFieldsContainer" class="hidden space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Bundle Items</p>
                                                <p class="text-xs text-gray-500">Products included in this bundle</p>
                                            </div>
                                            <button type="button" onclick="openBundleItemSelector()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-600 text-white text-xs font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Add Item
                                            </button>
                                        </div>

                                        <div id="bundleItemsList" class="space-y-2">
                                            <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                                <p class="font-medium">No bundle items added</p>
                                                <p class="text-xs mt-1">Click "Add Item" to add products to this bundle</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bundle Discount (%)</label>
                                                <input type="number" id="bundleDiscount" min="0" max="100" step="1" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="10">
                                                <p class="text-xs text-gray-500 mt-1">Discount percentage for bundle</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bundle Price</label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                                    <input type="number" id="bundlePrice" min="0" step="0.01" class="w-full pl-7 pr-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent bg-gray-50" placeholder="0.00" readonly>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Auto-calculated bundle price</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bundle Tips -->
                                    <div class="p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                        <div class="flex gap-2">
                                            <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="text-xs text-orange-800">
                                                <p class="font-semibold mb-1">Bundle Tips:</p>
                                                <ul class="list-disc list-inside space-y-0.5 text-orange-700">
                                                    <li>Bundles combine multiple products at a discounted price</li>
                                                    <li>Set quantity for each product in the bundle</li>
                                                    <li>Bundle price is auto-calculated based on discount</li>
                                                    <li>Great for "Starter Kits" or "Complete Sets"</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between sticky bottom-0">
            <div class="flex items-center gap-2">
                <button type="button" onclick="previousTab()" id="prevTabBtn" class="hidden px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    ← Previous
                </button>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="nextTab()" id="nextTabBtn" class="px-4 py-2.5 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Next →
                </button>
                <button type="submit" form="productForm" id="submitBtn" class="hidden px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="submitBtnText">Create Product</span>
                    <span id="submitBtnLoading" class="hidden flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Product Selector Modal -->
<div id="productSelectorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900" id="selectorModalTitle">Select Products</h3>
            <button type="button" onclick="closeProductSelector()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Search -->
        <div class="px-6 py-3 border-b border-gray-200">
            <input type="text" id="productSelectorSearch" placeholder="Search products..." class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" onkeyup="searchProductsForSelector()">
        </div>

        <!-- Products List -->
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div id="productSelectorList" class="space-y-2">
                <!-- Products will be loaded here -->
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" onclick="closeProductSelector()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                Cancel
            </button>
            <button type="button" onclick="addSelectedProducts()" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3]">
                Add Selected
            </button>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<style>
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}
.skeleton-row { animation: fadeIn 0.3s ease-in; }
.skeleton-text, .skeleton-avatar {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.tab-btn {
    color: #6b7280;
    background: transparent;
}
.tab-btn.active {
    color: #0082C3;
    background: #e0f2fe;
}
/* tab show/hide is handled via inline style by switchTab() */

/* Nuclear option — hide all tab contents by ID until JS shows them */
#content-basic,
#content-pricing,
#content-media,
#content-variants,
#content-organization,
#content-seo,
#content-advanced {
    display: none !important;
}
/* Active tab — overrides the ID rule above using same specificity + !important */
#content-basic.tab-active,
#content-pricing.tab-active,
#content-media.tab-active,
#content-variants.tab-active,
#content-organization.tab-active,
#content-seo.tab-active,
#content-advanced.tab-active {
    display: block !important;
}

/* Sortable.js Drag & Drop Styles */
.sortable-ghost {
    opacity: 0.4;
    background: #e0f2fe;
    border: 2px dashed #0082C3 !important;
}

.sortable-chosen {
    cursor: grabbing !important;
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    z-index: 1000;
}

.sortable-drag {
    opacity: 1 !important;
    cursor: grabbing !important;
}

.sortable-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.sortable-item:hover {
    transform: translateY(-2px);
}

/* Collapsible Section Styles */
.collapsible-section {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #ffffff;
    margin-bottom: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.collapsible-section:hover {
    border-color: #d1d5db;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.collapsible-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    cursor: pointer;
    user-select: none;
    background: #f9fafb;
    border-bottom: 1px solid transparent;
    transition: all 0.2s ease;
}

.collapsible-header:hover {
    background: #f3f4f6;
}

.collapsible-header.active {
    border-bottom-color: #e5e7eb;
}

.collapsible-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    font-size: 14px;
    color: #111827;
}

.collapsible-icon {
    width: 20px;
    height: 20px;
    color: #6b7280;
    transition: transform 0.3s ease;
}

.collapsible-header.active .collapsible-icon {
    transform: rotate(180deg);
}

.collapsible-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0;
}

.collapsible-content.active {
    max-height: 5000px; /* Large enough for content */
    opacity: 1;
}

.collapsible-body {
    padding: 20px;
}

.section-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.section-badge.required {
    background: #fee2e2;
    color: #991b1b;
}

.section-badge.optional {
    background: #e0e7ff;
    color: #3730a3;
}

.section-badge.new {
    background: #d1fae5;
    color: #065f46;
}

/* Helper text styles */
.helper-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.helper-text svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

/* Character counter */
.char-counter {
    font-size: 11px;
    color: #9ca3af;
    text-align: right;
    margin-top: 4px;
}

.char-counter.warning {
    color: #f59e0b;
}

.char-counter.error {
    color: #ef4444;
}
</style>

<script>
let currentPage = 1;
let searchTimeout;
let selectedProducts = new Set();
let currentTab = 'basic';
let productImages = [];
let productVideos = [];
let productFaqs = [];
let productVariants = [];
let variantAttributes = [];
let relatedProducts = { related: [], upsell: [], cross_sell: [] };
let currentRelationType = '';
let selectedProductsForRelation = new Set();

// Collapsible sections state (stored in localStorage)
let collapsibleState = {};

// Load collapsible state from localStorage
function loadCollapsibleState() {
    const saved = localStorage.getItem('productFormCollapsibleState');
    if (saved) {
        try {
            collapsibleState = JSON.parse(saved);
        } catch (e) {
            collapsibleState = {};
        }
    }
}

// Save collapsible state to localStorage
function saveCollapsibleState() {
    localStorage.setItem('productFormCollapsibleState', JSON.stringify(collapsibleState));
}

// Toggle collapsible section
function toggleCollapsible(sectionId) {
    console.log('Toggling section:', sectionId); // Debug log
    
    // Find section by data-section attribute (new pattern)
    const section = document.querySelector(`[data-section="${sectionId}"]`);
    
    if (section) {
        // New pattern: find header and content within section
        const header = section.querySelector('.collapsible-header');
        const content = section.querySelector('.collapsible-content');
        const icon = section.querySelector('.collapsible-icon');
        
        if (!content || !header) {
            console.error('Content or header not found for section:', sectionId);
            return;
        }
        
        // Toggle active class on header
        const isActive = header.classList.contains('active');
        
        if (isActive) {
            // Close section
            header.classList.remove('active');
            content.classList.remove('active');
            if (icon) icon.style.transform = 'rotate(-90deg)';
            collapsibleState[sectionId] = false;
        } else {
            // Open section
            header.classList.add('active');
            content.classList.add('active');
            if (icon) icon.style.transform = 'rotate(0deg)';
            collapsibleState[sectionId] = true;
        }
        
        saveCollapsibleState();
        return;
    }
    
    // Fallback to old pattern with data-collapsible
    const header = document.querySelector(`[data-collapsible="${sectionId}"]`);
    const content = document.getElementById(sectionId);
    
    if (!header || !content) {
        console.error('Header or content not found for section:', sectionId);
        return;
    }
    
    const isActive = content.classList.contains('active');
    
    if (isActive) {
        header.classList.remove('active');
        content.classList.remove('active');
        collapsibleState[sectionId] = false;
    } else {
        header.classList.add('active');
        content.classList.add('active');
        collapsibleState[sectionId] = true;
    }
    
    saveCollapsibleState();
}

// Initialize collapsible sections
function initCollapsibleSections() {
    loadCollapsibleState();
    
    // Define default open sections
    const defaultOpenSections = [
        'section-pricing',      // Old pattern - Pricing & Inventory
        'product-status',       // New pattern - Product Status
        'product-categories',   // New pattern - Categories
        'product-tags'          // New pattern - Tags
    ];
    
    // Initialize new pattern sections (data-section)
    document.querySelectorAll('[data-section]').forEach(section => {
        const sectionId = section.getAttribute('data-section');
        const header = section.querySelector('.collapsible-header');
        const content = section.querySelector('.collapsible-content');
        const icon = section.querySelector('.collapsible-icon');
        
        if (!content || !header) {
            console.warn('Missing content or header for section:', sectionId);
            return;
        }
        
        // Check saved state or use default
        const shouldBeOpen = collapsibleState.hasOwnProperty(sectionId) 
            ? collapsibleState[sectionId] 
            : defaultOpenSections.includes(sectionId);
        if (shouldBeOpen) {
            header.classList.add('active');
            content.classList.add('active');
            if (icon) icon.style.transform = 'rotate(0deg)';
            collapsibleState[sectionId] = true;
        } else {
            header.classList.remove('active');
            content.classList.remove('active');
            if (icon) icon.style.transform = 'rotate(-90deg)';
            collapsibleState[sectionId] = false;
        }
    });
    
    // Initialize old pattern sections (data-collapsible)
    document.querySelectorAll('.collapsible-content').forEach(content => {
        const sectionId = content.id;
        if (!sectionId) return;
        
        const header = document.querySelector(`[data-collapsible="${sectionId}"]`);
        if (!header) return;
        
        // Check saved state or use default
        const shouldBeOpen = collapsibleState.hasOwnProperty(sectionId) 
            ? collapsibleState[sectionId] 
            : defaultOpenSections.includes(sectionId);
        if (shouldBeOpen) {
            header.classList.add('active');
            content.classList.add('active');
            collapsibleState[sectionId] = true;
        } else {
            header.classList.remove('active');
            content.classList.remove('active');
            collapsibleState[sectionId] = false;
        }
    });
    
    // Save initial state
    saveCollapsibleState();
}

// Character counter function
function updateCharCounter(inputId, counterId, maxLength) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    
    if (!input || !counter) return;
    
    input.addEventListener('input', function() {
        const length = this.value.length;
        counter.textContent = `${length}/${maxLength}`;
        
        // Add warning/error classes
        counter.classList.remove('warning', 'error');
        if (length > maxLength * 0.9) {
            counter.classList.add('warning');
        }
        if (length > maxLength) {
            counter.classList.add('error');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('searchInput') && document.getElementById('productsTableBody')) {
        loadProducts();
        loadBrands();
        loadCategories();
        loadTags();
        loadCollections();
    }
    
    // Initialize collapsible sections
    initCollapsibleSections();
    
    // Initialize pricing listeners
    initPricingListeners();
    
    // Auto-slug generation
    document.getElementById('productName')?.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('productSlug').value = slug;
    });
    
    // Character counters
    document.getElementById('productName')?.addEventListener('input', function() {
        document.getElementById('productNameCount').textContent = this.value.length;
    });
    
    document.getElementById('productShortDescription')?.addEventListener('input', function() {
        document.getElementById('shortDescCount').textContent = this.value.length;
    });
    
    // SEO character counters handled by updateSeoCounter() in SEO tab

    // Digital product toggle
    document.getElementById('productIsDigital')?.addEventListener('change', function() {
        const digitalSettings = document.getElementById('digitalProductSettings');
        if (this.checked) {
            digitalSettings.classList.remove('hidden');
        } else {
            digitalSettings.classList.add('hidden');
            document.getElementById('productDownloadUrl').value = '';
            document.getElementById('productDownloadLimit').value = '';
        }
    });

    // Availability status change
    document.getElementById('productAvailability')?.addEventListener('change', toggleAvailableDate);

    // Product tags are now handled by searchable multi-select
    // No need for manual tag input handling

    
    // Form submission
    document.getElementById('productForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveProduct();
    });
});

// Product Tags Management - Now using multi-select
// Tags are stored as array of tag IDs
let productTagIds = [];

// Availability Date Toggle
function toggleAvailableDate() {
    const availability = document.getElementById('productAvailability').value;
    const dateContainer = document.getElementById('availableDateContainer');
    
    if (availability === 'pre_order' || availability === 'backorder') {
        dateContainer.classList.remove('hidden');
    } else {
        dateContainer.classList.add('hidden');
        document.getElementById('productAvailableDate').value = '';
    }
}


// Toggle Shipping Fields
function toggleShippingFields() {
    const requiresShipping = document.getElementById('productRequiresShipping').checked;
    const shippingFields = document.getElementById('shippingDetailsFields');
    
    if (requiresShipping) {
        shippingFields.classList.remove('hidden');
    } else {
        shippingFields.classList.add('hidden');
        // Clear shipping fields
        document.getElementById('productWeight').value = '';
        document.getElementById('productLength').value = '';
        document.getElementById('productWidth').value = '';
        document.getElementById('productHeight').value = '';
    }
}

// Toggle Bundle Fields
function toggleBundleFields() {
    const isBundle = document.getElementById('productIsBundle').checked;
    const bundleFields = document.getElementById('bundleFieldsContainer');
    
    if (isBundle) {
        bundleFields.classList.remove('hidden');
    } else {
        bundleFields.classList.add('hidden');
        // Clear bundle fields
        document.getElementById('bundleDiscount').value = '';
        document.getElementById('bundlePrice').value = '';
    }
}

// Open Bundle Item Selector (Placeholder)
function openBundleItemSelector() {
    alert('Bundle item selector will be implemented. This allows selecting products to include in the bundle.');
}

// Add Custom Field
let customFieldsArray = [];
function addCustomField() {
    const fieldId = Date.now();
    const fieldHtml = `
        <div class="custom-field-item p-3 border border-gray-200 rounded-lg bg-gray-50" data-field-id="${fieldId}">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <input type="text" placeholder="Field Name (e.g., Manufacturer)" class="custom-field-name w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div class="flex gap-2">
                    <input type="text" placeholder="Field Value" class="custom-field-value flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <button type="button" onclick="removeCustomField(${fieldId})" class="px-3 py-2 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    const container = document.getElementById('customFieldsList');
    // Remove empty state if exists
    const emptyState = container.querySelector('.text-center');
    if (emptyState) {
        container.innerHTML = '';
    }
    container.insertAdjacentHTML('beforeend', fieldHtml);
}

// Remove Custom Field
function removeCustomField(fieldId) {
    const field = document.querySelector(`[data-field-id="${fieldId}"]`);
    if (field) {
        field.remove();
    }
    
    // Show empty state if no fields left
    const container = document.getElementById('customFieldsList');
    if (container.children.length === 0) {
        container.innerHTML = `
            <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                <p class="font-medium">No custom fields added</p>
                <p class="text-xs mt-1">Click "Add Field" to add custom metadata</p>
            </div>
        `;
    }
}

// Calculate Profit Margin
function calculateProfitMargin() {
    const regularPrice = parseFloat(document.getElementById('productRegularPrice').value) || 0;
    const salePrice = parseFloat(document.getElementById('productSalePrice').value) || 0;
    const costPrice = parseFloat(document.getElementById('productCostPrice').value) || 0;
    
    const profitDisplay = document.getElementById('profitMarginDisplay');
    const profitMarginValue = document.getElementById('profitMarginValue');
    const profitAmountValue = document.getElementById('profitAmountValue');
    
    // Use sale price if available, otherwise regular price
    const sellingPrice = salePrice > 0 ? salePrice : regularPrice;
    
    if (sellingPrice > 0 && costPrice > 0) {
        const profitAmount = sellingPrice - costPrice;
        const profitMargin = ((profitAmount / sellingPrice) * 100).toFixed(2);
        
        profitMarginValue.textContent = profitMargin + '%';
        profitAmountValue.textContent = '₹' + profitAmount.toFixed(2);
        
        // Color code based on margin
        if (profitMargin < 10) {
            profitMarginValue.className = 'font-semibold text-red-700';
        } else if (profitMargin < 30) {
            profitMarginValue.className = 'font-semibold text-yellow-700';
        } else {
            profitMarginValue.className = 'font-semibold text-green-700';
        }
        
        profitDisplay.classList.remove('hidden');
    } else {
        profitDisplay.classList.add('hidden');
    }
}

// Initialize profit calculation listeners
function initPricingListeners() {
    const regularPriceInput = document.getElementById('productRegularPrice');
    const salePriceInput = document.getElementById('productSalePrice');
    const costPriceInput = document.getElementById('productCostPrice');
    
    if (regularPriceInput) {
        regularPriceInput.addEventListener('input', calculateProfitMargin);
    }
    if (salePriceInput) {
        salePriceInput.addEventListener('input', calculateProfitMargin);
    }
    if (costPriceInput) {
        costPriceInput.addEventListener('input', calculateProfitMargin);
    }
}

// Generate Professional SKU
function generateSKU() {
    const productName = document.getElementById('productName').value.trim();
    const brandSelect = document.getElementById('productBrand');
    const categorySelect = document.getElementById('productPrimaryCategory');
    const productType = document.getElementById('productType').value;
    
    if (!productName) {
        showToast('Please enter product name first', 'error');
        return;
    }
    
    let skuParts = [];
    
    // 1. Category prefix (first 4 letters, uppercase)
    if (categorySelect && categorySelect.selectedOptions[0] && categorySelect.value) {
        const categoryName = categorySelect.selectedOptions[0].text;
        const categoryPrefix = categoryName.substring(0, 4).toUpperCase().replace(/[^A-Z]/g, '');
        if (categoryPrefix) skuParts.push(categoryPrefix);
    }
    
    // 2. Brand prefix (first 4 letters, uppercase)
    if (brandSelect && brandSelect.selectedOptions[0] && brandSelect.value) {
        const brandName = brandSelect.selectedOptions[0].text;
        const brandPrefix = brandName.substring(0, 4).toUpperCase().replace(/[^A-Z]/g, '');
        if (brandPrefix) skuParts.push(brandPrefix);
    }
    
    // 3. Product name keywords (first 2-3 significant words, max 4 chars each)
    const nameWords = productName
        .toUpperCase()
        .replace(/[^A-Z0-9\s]/g, '')
        .split(/\s+/)
        .filter(word => word.length > 2) // Skip small words like "A", "OF", "THE"
        .slice(0, 3) // Take first 3 significant words
        .map(word => word.substring(0, 4)); // Max 4 chars per word
    
    if (nameWords.length > 0) {
        skuParts.push(...nameWords);
    }
    
    // 4. Product type indicator (first 3 letters)
    if (productType) {
        const typePrefix = productType.substring(0, 3).toUpperCase();
        skuParts.push(typePrefix);
    }
    
    // 5. Random unique identifier (4 digits)
    const randomId = Math.floor(1000 + Math.random() * 9000);
    skuParts.push(randomId.toString());
    
    // Combine all parts with hyphen
    const generatedSKU = skuParts.join('-');
    
    // Set the SKU
    document.getElementById('productSku').value = generatedSKU;
    
    // Show success message
    showToast('SKU generated successfully: ' + generatedSKU, 'success');
}

// Old tag functions removed - now using searchable multi-select
// Tags are managed through the select element directly



function loadProducts(page = 1) {
    currentPage = page;
    
    const searchInput = document.getElementById('searchInput');
    const brandFilter = document.getElementById('brandFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const perPageSelect = document.getElementById('perPageSelect');
    
    if (!searchInput) return;
    
    const search = searchInput.value;
    const brand = brandFilter?.value || '';
    const category = categoryFilter?.value || '';
    const type = typeFilter?.value || '';
    const status = statusFilter?.value || '';
    const perPage = perPageSelect?.value || 10;

    const url = `/admin/products/list?page=${page}&search=${search}&brand_id=${brand}&category_id=${category}&product_type=${type}&status=${status}&per_page=${perPage}`;

    const tbody = document.getElementById('productsTableBody');
    const skeletonRows = tbody.querySelectorAll('.skeleton-row');
    skeletonRows.forEach(row => row.style.display = '');

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderProducts(data.data);
            renderPagination(data.pagination);
            updateStats(data.data, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error loading products:', error);
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-red-600">
                        Error loading products. Please refresh the page.
                    </td>
                </tr>
            `;
        }
    });
}

function loadBrands() {
    fetch('/admin/brands/list?per_page=1000&status=1', {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(data => {
        if (data.success && data.data) {
            const brandSelect = document.getElementById('productBrand');
            const brandFilter = document.getElementById('brandFilter');

            if (brandSelect) {
                brandSelect.innerHTML = '<option value="">Select Brand</option>';
                data.data.forEach(brand => {
                    brandSelect.innerHTML += `<option value="${brand.id}">${brand.name}</option>`;
                });
                // Refresh SearchableSelect instance
                const instance = searchableSelectInstances.find(inst => inst.select === brandSelect);
                if (instance) instance.refresh();
            }

            if (brandFilter) {
                brandFilter.innerHTML = '<option value="">All Brands</option>';
                data.data.forEach(brand => {
                    brandFilter.innerHTML += `<option value="${brand.id}">${brand.name}</option>`;
                });
                const filterInstance = searchableSelectInstances.find(inst => inst.select === brandFilter);
                if (filterInstance) filterInstance.refresh();
            }
        } else {
            console.error('loadBrands failed:', data);
        }
    })
    .catch(err => console.error('loadBrands error:', err));
}

function loadCategories() {
    fetch('/admin/categories/list?per_page=1000&status=1', {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const categorySelect = document.getElementById('productPrimaryCategory');
            const additionalCategoriesSelect = document.getElementById('productAdditionalCategories');
            const categoryFilter = document.getElementById('categoryFilter');
            
            // Update Primary Category dropdown
            if (categorySelect) {
                // Update native select options
                categorySelect.innerHTML = '<option value="">Select Category</option>';
                data.data.forEach(category => {
                    categorySelect.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });
                // Refresh SearchableSelect instance
                const instance = searchableSelectInstances.find(inst => inst.select === categorySelect);
                if (instance) {
                    instance.refresh();
                }
            }
            
            // Update Additional Categories multi-select dropdown
            if (additionalCategoriesSelect) {
                // Update native select options
                additionalCategoriesSelect.innerHTML = '';
                data.data.forEach(category => {
                    additionalCategoriesSelect.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });
                // Refresh SearchableSelect instance
                const instance = searchableSelectInstances.find(inst => inst.select === additionalCategoriesSelect);
                if (instance) {
                    instance.refresh();
                }
            }
            
            // Update Category Filter dropdown
            if (categoryFilter) {
                categoryFilter.innerHTML = '<option value="">All Categories</option>';
                data.data.forEach(category => {
                    categoryFilter.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });
                // Refresh SearchableSelect instance
                const instance = searchableSelectInstances.find(inst => inst.select === categoryFilter);
                if (instance) {
                    instance.refresh();
                }
            }
        } else {
            console.error('Failed to load categories:', data);
        }
    })
    .catch(error => {
        console.error('Error loading categories:', error);
    });
}

// Load Tags from Database
function loadTags() {
    fetch('/admin/tags/list?per_page=1000&status=1', {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.tags && data.tags.data) {
            const tagsSelect = document.getElementById('productTags');
            
            if (tagsSelect) {
                // Update native select options
                tagsSelect.innerHTML = '';
                data.tags.data.forEach(tag => {
                    tagsSelect.innerHTML += `<option value="${tag.id}">${tag.name}</option>`;
                });
                // Find and refresh the SearchableSelect instance
                if (typeof searchableSelectInstances !== 'undefined') {
                    const instance = searchableSelectInstances.find(inst => inst.select === tagsSelect);
                    if (instance) {
                        instance.refresh();
                    }
                }
            }
        } else {
            console.error('Failed to load tags:', data);
        }
    })
    .catch(error => {
        console.error('Error loading tags:', error);
    });
}

// Load Collections from Database
function loadCollections() {
    fetch('/admin/collections/list?per_page=1000&status=1', {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(data => {
        if (data.success && data.data) {
            const collectionsSelect = document.getElementById('productCollections');
            if (collectionsSelect) {
                collectionsSelect.innerHTML = '';
                data.data.forEach(col => {
                    const opt = document.createElement('option');
                    opt.value = col.id;
                    opt.textContent = col.name;
                    collectionsSelect.appendChild(opt);
                });
                // Refresh SearchableSelect instance
                const instance = searchableSelectInstances.find(inst => inst.select === collectionsSelect);
                if (instance) instance.refresh();
            }
        } else {
            console.error('loadCollections failed:', data);
        }
    })
    .catch(err => console.error('loadCollections error:', err));
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        requestAnimationFrame(() => {
            loadProducts(1);
        });
    }, 300);
}

function updateStats(products, pagination) {
    document.getElementById('totalProducts').innerHTML = pagination.total;
    
    fetch('/admin/products/list?per_page=1000', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const allProducts = data.data;
            document.getElementById('activeProducts').innerHTML = allProducts.filter(p => p.status === 'active').length;
            document.getElementById('draftProducts').innerHTML = allProducts.filter(p => p.status === 'draft').length;
            document.getElementById('featuredProducts').innerHTML = allProducts.filter(p => p.is_featured).length;
            document.getElementById('newProducts').innerHTML = allProducts.filter(p => p.is_new).length;
            document.getElementById('bestSellerProducts').innerHTML = allProducts.filter(p => p.is_best_seller).length;
        }
    });
}


function renderProducts(products) {
    const tbody = document.getElementById('productsTableBody');
    
    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No products found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = products.map(product => {
        const statusColors = {
            'active': 'bg-green-100 text-green-700',
            'inactive': 'bg-red-100 text-red-700',
            'draft': 'bg-yellow-100 text-yellow-700'
        };
        
        const typeColors = {
            'simple': 'bg-blue-100 text-blue-700',
            'variable': 'bg-purple-100 text-purple-700',
            'digital': 'bg-cyan-100 text-cyan-700',
            'service': 'bg-orange-100 text-orange-700'
        };
        
        const imageUrl = product.featured_image?.image_url || '';
        
        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <input type="checkbox" class="product-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" value="${product.id}" onchange="updateBulkActions()">
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        ${imageUrl ? `
                            <img src="${imageUrl}" alt="${product.name}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                        ` : `
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        `}
                        <div>
                            <p class="text-sm font-semibold text-gray-900">${product.name}</p>
                            <p class="text-xs text-gray-500">${product.slug}</p>
                            ${product.sku_prefix ? `<p class="text-xs text-gray-400 mt-0.5">SKU: ${product.sku_prefix}</p>` : ''}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-900">${product.brand?.name || '-'}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-900">${product.category?.name || '-'}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium ${typeColors[product.product_type] || 'bg-gray-100 text-gray-700'}">
                        ${product.product_type}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-900">${product.variants_count || 0}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-900">${product.price_range || '-'}</span>
                </td>
                <td class="px-6 py-4">
                    <button onclick="toggleStatus(${product.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium transition-colors ${statusColors[product.status] || 'bg-gray-100 text-gray-700'}">
                        <span class="w-1.5 h-1.5 rounded-full ${product.status === 'active' ? 'bg-green-600' : product.status === 'draft' ? 'bg-yellow-600' : 'bg-red-600'}"></span>
                        ${product.status}
                    </button>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="duplicateProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}'))" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Duplicate">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button onclick="editProduct(${product.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.variants_count || 0})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (!container) return;
    
    if (pagination.last_page <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let pages = [];
    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)) {
            pages.push(i);
        } else if (pages[pages.length - 1] !== '...') {
            pages.push('...');
        }
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Showing <span class="font-semibold">${pagination.from || 0}</span> to <span class="font-semibold">${pagination.to || 0}</span> of <span class="font-semibold">${pagination.total}</span> results
            </p>
            <div class="flex items-center gap-2">
                <button onclick="loadProducts(${pagination.current_page - 1})" ${pagination.current_page === 1 ? 'disabled' : ''} class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Previous
                </button>
                ${pages.map(page => {
                    if (page === '...') {
                        return '<span class="px-3 py-1.5 text-gray-500">...</span>';
                    }
                    return `
                        <button onclick="loadProducts(${page})" class="px-3 py-1.5 border rounded-lg text-sm font-medium transition-colors ${page === pagination.current_page ? 'bg-[#0082C3] text-white border-[#0082C3]' : 'border-gray-300 text-gray-700 hover:bg-gray-50'}">
                            ${page}
                        </button>
                    `;
                }).join('')}
                <button onclick="loadProducts(${pagination.current_page + 1})" ${pagination.current_page === pagination.last_page ? 'disabled' : ''} class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Next
                </button>
            </div>
        </div>
    `;
}

// Tab Navigation
const TAB_ORDER = ['basic', 'pricing', 'media', 'variants', 'organization', 'seo', 'advanced'];

function switchTab(tabName) {
    currentTab = tabName;

    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    const tabBtn = document.getElementById(`tab-${tabName}`);
    if (tabBtn) tabBtn.classList.add('active');

    // Toggle tab-active class (CSS uses ID+class specificity to override display:none !important)
    TAB_ORDER.forEach(t => {
        const el = document.getElementById(`content-${t}`);
        if (!el) return;
        if (t === tabName) {
            el.classList.add('tab-active');
        } else {
            el.classList.remove('tab-active');
        }
    });

    updateTabNavigation();
}

// On page load — ensure no tab has tab-active (all hidden by CSS)
document.addEventListener('DOMContentLoaded', () => {
    TAB_ORDER.forEach(t => {
        const el = document.getElementById(`content-${t}`);
        if (el) el.classList.remove('tab-active');
    });
});

function updateTabNavigation() {
    const idx = TAB_ORDER.indexOf(currentTab);
    const prevBtn   = document.getElementById('prevTabBtn');
    const nextBtn   = document.getElementById('nextTabBtn');
    const submitBtn = document.getElementById('submitBtn');

    idx > 0 ? prevBtn.classList.remove('hidden') : prevBtn.classList.add('hidden');

    if (idx < TAB_ORDER.length - 1) {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    } else {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    }
}

function nextTab() {
    const idx = TAB_ORDER.indexOf(currentTab);
    if (idx < TAB_ORDER.length - 1) switchTab(TAB_ORDER[idx + 1]);
}

function previousTab() {
    const idx = TAB_ORDER.indexOf(currentTab);
    if (idx > 0) switchTab(TAB_ORDER[idx - 1]);
}


// Bulk Actions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const bulkContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkContainer.classList.remove('hidden');
        bulkContainer.classList.add('flex', 'items-center', 'gap-2');
        selectedCount.textContent = `${checkboxes.length} selected`;
    } else {
        bulkContainer.classList.add('hidden');
    }
}

function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (!action || ids.length === 0) {
        showToast('Please select action and products', 'error');
        return;
    }
    
    if (action === 'delete') {
        showConfirmDialog('Delete Products', `Delete ${ids.length} product(s)?`, () => {
            bulkActionRequest(action, ids);
        });
    } else {
        bulkActionRequest(action, ids);
    }
}

function bulkActionRequest(action, ids) {
    fetch('/admin/products/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action, ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadProducts(currentPage);
            document.getElementById('selectAll').checked = false;
        } else {
            showToast(data.message || 'Operation failed', 'error');
        }
    });
}

// Modal Operations
function openAddModal() {
    resetForm();
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('submitBtnText').textContent = 'Create Product';
    switchTab('basic');
    
    // Reload tags and categories to get latest from database
    loadTags();
    loadCategories();
    
    openModal();
}

function editProduct(id) {
    // Reload tags and categories first to get latest from database
    loadTags();
    loadCategories();
    
    fetch(`/admin/products/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            populateForm(data.data);
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('submitBtnText').textContent = 'Update Product';
            switchTab('basic');
            openModal();
        }
    });
}

function resetForm() {
    // Helper — safe set, skip if element missing
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };
    const chk = (id, val) => { const el = document.getElementById(id); if (el) el.checked = val; };
    const cls = (id, add, cls2) => { const el = document.getElementById(id); if (el) el.classList[add ? 'add' : 'remove'](cls2); };

    set('productId',                '');
    set('productName',              '');
    set('productSlug',              '');
    set('productType',              'simple');
    set('productBrand',             '');
    set('productPrimaryCategory',   '');
    set('productShortDescription',  '');
    set('productDescription',       '');
    set('productStatus',            'draft');
    set('productAvailability',      'in_stock');
    set('productAvailableDate',     '');
    cls('availableDateContainer', true, 'hidden');
    set('productVisibility',        'visible');

    // Stock toggle
    cls('stockFieldsContainer', true, 'hidden');

    // Flags (new IDs)
    chk('productIsFeatured',   false);
    chk('productIsNew',        false);
    chk('productIsBestSeller', false);
    chk('productIsDigital',    false);

    // Digital product fields
    set('productDownloadUrl',   '');
    set('productDownloadLimit', '');
    cls('digitalProductSettings', true, 'hidden');

    // Shipping
    set('productWeight', '');
    set('productLength', '');
    set('productWidth',  '');
    set('productHeight', '');

    // SEO
    set('productSeoTitle',       '');
    set('productSeoDescription', '');
    set('productSeoKeywords',    '');

    // Pricing
    set('productRegularPrice', '');
    set('productSalePrice',    '');
    set('productCostPrice',    '');
    set('productSku',          '');
    set('productBarcode',      '');
    cls('profitMarginDisplay', true, 'hidden');
    chk('productManageStock',    false);
    set('productStockQuantity',  '');
    set('productLowStockThreshold', '5');
    chk('productAllowBackorder', false);
    const stockCont = document.getElementById('stockFieldsContainer');
    if (stockCont) stockCont.style.display = 'none';
    const stockBadge = document.getElementById('stockStatusBadge');
    if (stockBadge) stockBadge.classList.add('hidden');

    // Arrays
    productImages   = [];
    productVideos   = [];
    productFaqs     = [];
    productVariants = [];
    productTagIds   = [];
    _productAttrs   = [];
    relatedProducts = { related: [], upsell: [], cross_sell: [] };

    // Reset tags multi-select
    const tagsSelect = document.getElementById('productTags');
    if (tagsSelect) {
        Array.from(tagsSelect.options).forEach(o => o.selected = false);
        const inst = searchableSelectInstances.find(i => i.select === tagsSelect);
        if (inst) inst.refresh();
    }

    // Reset additional categories
    const addCatSelect = document.getElementById('productAdditionalCategories');
    if (addCatSelect) {
        Array.from(addCatSelect.options).forEach(o => o.selected = false);
        const inst = searchableSelectInstances.find(i => i.select === addCatSelect);
        if (inst) inst.refresh();
    }

    // Reset collections
    const colSelect = document.getElementById('productCollections');
    if (colSelect) {
        Array.from(colSelect.options).forEach(o => o.selected = false);
        const inst = searchableSelectInstances.find(i => i.select === colSelect);
        if (inst) inst.refresh();
    }

    // Reset product attributes list
    _productAttrs = [];
    _renderProductAttrs();

    renderVideosList();
    renderFaqsList();
    renderRelatedProducts();
    clearErrors();
}

function populateForm(product) {
    document.getElementById('productId').value = product.id;
    document.getElementById('productName').value = product.name;
    document.getElementById('productSlug').value = product.slug;
    document.getElementById('productType').value = product.product_type;
    document.getElementById('productBrand').value = product.brand_id || '';
    document.getElementById('productPrimaryCategory').value = product.category_id || '';
    setSummernoteContent('productShortDescription', product.short_description || '');
    setSummernoteContent('productDescription', product.description || '');
    document.getElementById('productStatus').value = product.status;
    document.getElementById('productVisibility').value = product.visibility || 'visible';
    
    // Format datetime for input fields (fields removed, skip gracefully)
    // published_at / unpublished_at handled server-side only
    
    document.getElementById('productIsFeatured').checked = product.is_featured;
    document.getElementById('productIsNew').checked = product.is_new;
    document.getElementById('productIsBestSeller').checked = product.is_best_seller;
    document.getElementById('productIsDigital').checked = product.is_digital;

    // Stock / Inventory
    const manageStock = !!product.manage_stock;
    document.getElementById('productManageStock').checked = manageStock;
    document.getElementById('productStockQuantity').value = product.stock_quantity || 0;
    document.getElementById('productLowStockThreshold').value = product.low_stock_threshold || 5;
    document.getElementById('productAllowBackorder').checked = !!product.allow_backorder;
    const stockCont = document.getElementById('stockFieldsContainer');
    if (stockCont) stockCont.style.display = manageStock ? 'grid' : 'none';
    if (manageStock) updateStockBadge();
    
    // Availability status
    document.getElementById('productAvailability').value = product.availability_status || 'in_stock';
    if (product.available_date) {
        document.getElementById('productAvailableDate').value = product.available_date;
        document.getElementById('availableDateContainer').classList.remove('hidden');
    } else {
        document.getElementById('availableDateContainer').classList.add('hidden');
    }
    
    // Show/hide digital settings
    if (product.is_digital) {
        document.getElementById('digitalProductSettings').classList.remove('hidden');
        document.getElementById('productDownloadUrl').value = product.download_url || '';
        document.getElementById('productDownloadLimit').value = product.download_limit || '';
    }
    
    document.getElementById('productWeight').value = product.weight || '';
    document.getElementById('productLength').value = product.length || '';
    document.getElementById('productWidth').value = product.width || '';
    document.getElementById('productHeight').value = product.height || '';
    document.getElementById('productSeoTitle').value = product.seo_title || '';
    document.getElementById('productSeoDescription').value = product.seo_description || '';
    document.getElementById('productSeoKeywords').value = product.seo_keywords || '';
    
    // Load tags - select options in multi-select
    if (product.tags && product.tags.length > 0) {
        productTagIds = product.tags.map(t => t.id);
        const tagsSelect = document.getElementById('productTags');
        if (tagsSelect) {
            Array.from(tagsSelect.options).forEach(opt => {
                opt.selected = productTagIds.includes(parseInt(opt.value));
            });
            const inst = searchableSelectInstances.find(i => i.select === tagsSelect);
            if (inst) inst.refresh();
        }
    }

    // Load additional categories
    if (product.categories && product.categories.length > 0) {
        const addCatSelect = document.getElementById('productAdditionalCategories');
        if (addCatSelect) {
            const categoryIds = product.categories.map(c => c.id);
            Array.from(addCatSelect.options).forEach(opt => {
                opt.selected = categoryIds.includes(parseInt(opt.value));
            });
            const inst = searchableSelectInstances.find(i => i.select === addCatSelect);
            if (inst) inst.refresh();
        }
    }
    
    // Load videos
    if (product.videos && product.videos.length > 0) {
        productVideos = product.videos.map(v => ({
            id: v.id,
            title: v.title || '',
            provider: v.provider,
            video_url: v.video_url,
            video_id: v.video_id,
            thumbnail_url: v.thumbnail_url,
            is_featured: v.is_featured,
            sort_order: v.sort_order
        }));
        renderVideosList();
    }
    
    // Load FAQs
    if (product.faqs && product.faqs.length > 0) {
        productFaqs = product.faqs.map(f => ({
            id: f.id,
            question: f.question,
            answer: f.answer,
            sort_order: f.sort_order,
            status: f.status
        }));
        renderFaqsList();
    }
    
    // Load related products
    relatedProducts = { related: [], upsell: [], cross_sell: [] };
    if (product.id) {
        ['related', 'upsell', 'cross_sell'].forEach(type => {
            fetch(`/admin/products/${product.id}/related/${type}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        relatedProducts[type] = data.data.map(p => p.id);
                        renderRelatedProducts();
                    }
                });
        });
    }
    renderRelatedProducts();
}

function saveProduct() {
    const id = document.getElementById('productId').value;
    const url = id ? `/admin/products/${id}` : '/admin/products';
    const method = id ? 'PUT' : 'POST';
    
    const data = {
        name: document.getElementById('productName').value,
        slug: document.getElementById('productSlug').value,
        product_type: document.getElementById('productType').value,
        brand_id: document.getElementById('productBrand').value || null,
        category_id: document.getElementById('productPrimaryCategory').value || null,
        short_description: getSummernoteContent('productShortDescription'),
        description: getSummernoteContent('productDescription'),
        status: document.getElementById('productStatus').value,
        availability_status: document.getElementById('productAvailability').value,
        available_date: document.getElementById('productAvailableDate').value || null,
        visibility: document.getElementById('productVisibility').value,
        published_at: null,
        unpublished_at: null,
        is_digital: document.getElementById('productIsDigital').checked ? 1 : 0,
        download_url: document.getElementById('productDownloadUrl').value || null,
        download_limit: document.getElementById('productDownloadLimit').value || null,
        is_featured: document.getElementById('productIsFeatured').checked ? 1 : 0,
        is_new: document.getElementById('productIsNew').checked ? 1 : 0,
        is_best_seller: document.getElementById('productIsBestSeller').checked ? 1 : 0,
        manage_stock: document.getElementById('productManageStock').checked ? 1 : 0,
        stock_quantity: document.getElementById('productManageStock').checked ? (parseInt(document.getElementById('productStockQuantity').value) || 0) : null,
        low_stock_threshold: parseInt(document.getElementById('productLowStockThreshold').value) || 5,
        allow_backorder: document.getElementById('productAllowBackorder').checked ? 1 : 0,
        weight: document.getElementById('productWeight').value || null,
        length: document.getElementById('productLength').value || null,
        width: document.getElementById('productWidth').value || null,
        height: document.getElementById('productHeight').value || null,
        seo_title: document.getElementById('productSeoTitle').value,
        seo_description: document.getElementById('productSeoDescription').value,
        seo_keywords: document.getElementById('productSeoKeywords').value,
        tags: Array.from(document.getElementById('productTags').selectedOptions).map(opt => parseInt(opt.value)).filter(Boolean),
        categories: Array.from(document.getElementById('productAdditionalCategories').selectedOptions).map(opt => parseInt(opt.value)).filter(Boolean),
        collections: Array.from(document.getElementById('productCollections').selectedOptions).map(opt => parseInt(opt.value)).filter(Boolean),
        videos: productVideos,
        faqs: productFaqs,
        variants: productVariants.map(v => ({
            sku: v.sku,
            price: v.price,
            compare_price: v.compare_price || null,
            cost_price: v.cost_price || null,
            stock_quantity: v.stock_quantity || 0,
            attributes: v.attributes,
        })),
    };
    
    clearErrors();
    setLoading(true);
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const productId = data.data.id;
            
            // Save related products
            const relatedPromises = [];
            ['related', 'upsell', 'cross_sell'].forEach(type => {
                if (relatedProducts[type].length > 0) {
                    relatedPromises.push(
                        fetch(`/admin/products/${productId}/related`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                type: type,
                                product_ids: relatedProducts[type]
                            })
                        })
                    );
                }
            });
            
            Promise.all(relatedPromises).then(() => {
                setLoading(false);
                showToast(data.message, 'success');
                closeModal();
                loadProducts(currentPage);
            });
        } else {
            setLoading(false);
            if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const fieldName = key.split('_').map((word, index) => 
                        index === 0 ? word : word.charAt(0).toUpperCase() + word.slice(1)
                    ).join('');
                    const errorEl = document.getElementById(`product${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)}Error`);
                    if (errorEl) {
                        errorEl.textContent = data.errors[key][0];
                        errorEl.classList.remove('hidden');
                    }
                });
            } else {
                showToast(data.message || 'Operation failed', 'error');
            }
        }
    })
    .catch(error => {
        setLoading(false);
        showToast('An error occurred', 'error');
    });
}

function toggleStatus(id) {
    fetch(`/admin/products/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadProducts(currentPage);
        } else {
            showToast(data.message || 'Operation failed', 'error');
        }
    });
}

function deleteProduct(id, name, variantsCount) {
    showConfirmDialog(
        'Delete Product',
        `Delete "<strong>${name}</strong>"?${variantsCount > 0 ? `<br><span class="text-xs text-orange-500">Has ${variantsCount} variant(s) — will also be deleted.</span>` : ''}`,
        () => {
            fetch(`/admin/products/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showSuccessDialog('Product deleted successfully');
                    loadProducts(currentPage);
                } else {
                    showToast(data.message || 'Operation failed', 'error');
                }
            });
        }
    );
}

// ImageKit Integration - Multiple Images Upload with Responsive Sizes
function openImageKitUpload() {
    // Create file input element
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.multiple = true; // Allow multiple file selection
    
    fileInput.onchange = function(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) {
            return;
        }
        
        // Validate files
        const validFiles = [];
        for (const file of files) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showToast(`${file.name} is not an image file`, 'error');
                continue;
            }
            
            // Validate file size (max 10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                showToast(`${file.name} is too large (max 10MB)`, 'error');
                continue;
            }
            
            validFiles.push(file);
        }
        
        if (validFiles.length > 0) {
            uploadMultipleToImageKit(validFiles);
        }
    };
    
    // Trigger file selection
    fileInput.click();
}

function uploadMultipleToImageKit(files) {
    if (typeof ImageKit === 'undefined') {
        showToast('ImageKit SDK not loaded', 'error');
        return;
    }
    
    showToast(`Uploading ${files.length} image(s)...`, 'info');
    
    let uploadedCount = 0;
    let failedCount = 0;
    
    // Upload each file with fresh authentication
    files.forEach((file, index) => {
        // Get fresh authentication parameters for each file
        fetch("{{ parse_url(route('imagekit.auth'), PHP_URL_PATH) }}")
            .then(response => response.json())
            .then(authParams => {
                const imageKitConfig = {
                    publicKey: "{{ \App\Models\Setting::get('imagekit_public_key') ?: config('imagekit.public_key', '') }}",
                    urlEndpoint: "{{ \App\Models\Setting::get('imagekit_url_endpoint') ?: config('imagekit.url_endpoint', '') }}",
                    authenticationEndpoint: "{{ parse_url(route('imagekit.auth'), PHP_URL_PATH) }}"
                };

                if (!imageKitConfig.publicKey || !imageKitConfig.urlEndpoint) {
                    failedCount++;
                    console.error('ImageKit not configured. Go to Integrations → ImageKit.');
                    if (uploadedCount + failedCount === files.length) {
                        finishUpload(uploadedCount, failedCount, uploadedImages);
                    }
                    return;
                }

                const imagekit = new ImageKit(imageKitConfig);
                imagekit.upload({
                    file: file,
                    fileName: `product_${Date.now()}_${index}_${file.name}`,
                    folder: '/products',
                    useUniqueFileName: true,
                    tags: ['product', 'responsive'],
                    // Pass fresh authentication parameters
                    token: authParams.token,
                    signature: authParams.signature,
                    expire: authParams.expire
                }, function(err, result) {
                    if (err) {
                        console.error(`Upload error for file ${index + 1}:`, err);
                        failedCount++;
                        
                        // Show specific error message
                        if (err.message) {
                            showToast(`${file.name}: ${err.message}`, 'error');
                        }
                    } else {
                        // Add image with responsive URLs to productImages array
                        productImages.push({
                            image_url: result.url,
                            image_id: result.fileId,
                            alt_text: '',
                            sort_order: productImages.length,
                            is_featured: productImages.length === 0,
                            responsive_urls: generateResponsiveUrls(result.url),
                            file_name: result.name,
                            file_size: result.size,
                            width: result.width,
                            height: result.height
                        });
                        uploadedCount++;
                    }
                    
                    // Check if all uploads are complete
                    if (uploadedCount + failedCount === files.length) {
                        renderProductImages();
                        initImageSortable(); // Initialize drag & drop
                        
                        if (uploadedCount > 0) {
                            showToast(`${uploadedCount} image(s) uploaded successfully`, 'success');
                        }
                        if (failedCount > 0) {
                            showToast(`${failedCount} image(s) failed to upload`, 'error');
                        }
                    }
                });
            })
            .catch(error => {
                console.error(`Auth fetch error for file ${index + 1}:`, error);
                failedCount++;
                
                if (uploadedCount + failedCount === files.length) {
                    showToast('Failed to get authentication parameters', 'error');
                }
            });
    });
}

// Generate responsive URLs for different devices (Shopify-style)
function generateResponsiveUrls(baseUrl) {
    // ImageKit transformation parameters
    const sizes = {
        thumbnail: { width: 150, height: 150, quality: 80 },      // Admin thumbnails
        small: { width: 320, height: 320, quality: 85 },          // Mobile
        medium: { width: 640, height: 640, quality: 85 },         // Tablet
        large: { width: 1024, height: 1024, quality: 90 },        // Desktop
        xlarge: { width: 1920, height: 1920, quality: 90 },       // Large screens
        original: { width: null, height: null, quality: 95 }      // Original size
    };
    
    const responsiveUrls = {};
    
    for (const [sizeName, params] of Object.entries(sizes)) {
        if (params.width && params.height) {
            // Force WebP format for all images
            const transformedUrl = `${baseUrl}?tr=w-${params.width},h-${params.height},q-${params.quality},f-webp,fo-auto`;
            responsiveUrls[sizeName] = transformedUrl;
        } else {
            // Original also in WebP
            responsiveUrls[sizeName] = `${baseUrl}?tr=f-webp,q-${params.quality}`;
        }
    }
    
    return responsiveUrls;
}

function generateResponsiveTransformations() {
    return [
        { name: 'thumbnail', width: 150, height: 150 },
        { name: 'small', width: 320, height: 320 },
        { name: 'medium', width: 640, height: 640 },
        { name: 'large', width: 1024, height: 1024 },
        { name: 'xlarge', width: 1920, height: 1920 }
    ];
}

function renderProductImages() {
    const grid = document.getElementById('productImagesGrid');
    
    if (productImages.length === 0) {
        grid.innerHTML = '<p class="col-span-4 text-sm text-gray-500 text-center py-4">No images uploaded yet</p>';
        return;
    }
    
    grid.innerHTML = productImages.map((img, index) => `
        <div class="sortable-item relative group border-2 border-gray-200 rounded-xl overflow-hidden hover:border-[#0082C3] transition-all cursor-move bg-white shadow-sm hover:shadow-md" data-index="${index}">
            <!-- Drag Handle -->
            <div class="absolute top-2 left-2 z-10 bg-gray-900/75 text-white px-2 py-1 rounded-lg text-xs font-medium flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                </svg>
                ${index + 1}
            </div>
            
            <!-- Featured Badge -->
            ${index === 0 ? `
                <div class="absolute top-2 right-2 z-10 bg-blue-600 text-white px-2.5 py-1 rounded-lg text-xs font-semibold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Featured
                </div>
            ` : ''}
            
            <!-- Image -->
            <img src="${img.responsive_urls?.thumbnail || img.image_url}" 
                 alt="${img.alt_text || 'Product image'}" 
                 class="w-full h-40 object-cover"
                 loading="lazy">
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-3">
                <!-- Image Info -->
                <div class="text-white text-xs space-y-1 mb-2">
                    ${img.width && img.height ? `<p class="font-medium">${img.width} × ${img.height}px</p>` : ''}
                    ${img.file_size ? `<p class="opacity-90">${formatFileSize(img.file_size)}</p>` : ''}
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="setFeaturedImage(${index})" class="flex-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        Feature
                    </button>
                    <button type="button" onclick="viewResponsiveSizes(${index})" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-lg transition-colors" title="View Responsive Sizes">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="removeProductImage(${index})" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors" title="Remove">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    
    // Initialize sortable after rendering
    initImageSortable();
}

// Initialize Sortable.js for drag & drop
function initImageSortable() {
    const grid = document.getElementById('productImagesGrid');
    if (!grid || typeof Sortable === 'undefined') return;
    
    // Destroy existing sortable instance if any
    if (grid.sortableInstance) {
        grid.sortableInstance.destroy();
    }
    
    grid.sortableInstance = Sortable.create(grid, {
        animation: 200,
        handle: '.sortable-item',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Reorder productImages array
            const movedItem = productImages.splice(evt.oldIndex, 1)[0];
            productImages.splice(evt.newIndex, 0, movedItem);
            
            // Update sort orders and featured status
            productImages.forEach((img, i) => {
                img.sort_order = i;
                img.is_featured = i === 0;
            });
            
            // Re-render to update badges and numbers
            renderProductImages();
            showToast('Images reordered', 'success');
        }
    });
}

// Set featured image
function setFeaturedImage(index) {
    if (index === 0) {
        showToast('This image is already featured', 'info');
        return;
    }
    
    // Move image to first position
    const movedImage = productImages.splice(index, 1)[0];
    productImages.unshift(movedImage);
    
    // Update sort orders and featured status
    productImages.forEach((img, i) => {
        img.sort_order = i;
        img.is_featured = i === 0;
    });
    
    renderProductImages();
    showToast('Featured image updated', 'success');
}

// View responsive sizes modal
function viewResponsiveSizes(index) {
    const img = productImages[index];
    if (!img.responsive_urls) {
        showToast('Responsive URLs not available', 'error');
        return;
    }
    
    const sizes = Object.entries(img.responsive_urls).map(([size, url]) => {
        const sizeInfo = {
            thumbnail: '150×150px (Admin)',
            small: '320×320px (Mobile)',
            medium: '640×640px (Tablet)',
            large: '1024×1024px (Desktop)',
            xlarge: '1920×1920px (Large Screen)',
            original: 'Original Size'
        };
        
        return `
            <div class="border border-gray-200 rounded-lg p-3 hover:border-[#0082C3] transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-900 capitalize">${size}</span>
                    <span class="text-xs text-gray-500">${sizeInfo[size] || ''}</span>
                </div>
                <input type="text" value="${url}" readonly class="w-full px-2 py-1 text-xs bg-gray-50 border border-gray-200 rounded font-mono" onclick="this.select()">
                <button onclick="copyToClipboard('${url}')" class="mt-2 w-full px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                    Copy URL
                </button>
            </div>
        `;
    }).join('');
    
    // Show modal with responsive URLs
    const modal = `
        <div id="responsiveSizesModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="closeResponsiveSizesModal(event)">
            <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Responsive Image Sizes</h3>
                    <button onclick="closeResponsiveSizesModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        ${sizes}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modal);
}

function closeResponsiveSizesModal(event) {
    if (!event || event.target.id === 'responsiveSizesModal') {
        const modal = document.getElementById('responsiveSizesModal');
        if (modal) modal.remove();
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('URL copied to clipboard', 'success');
    }).catch(() => {
        showToast('Failed to copy URL', 'error');
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function removeProductImage(index) {
    productImages.splice(index, 1);
    // Update sort orders
    productImages.forEach((img, i) => {
        img.sort_order = i;
        img.is_featured = i === 0;
    });
    renderProductImages();
    showToast('Image removed', 'success');
}

// Variant Generator
function openVariantGenerator() {
    const panel = document.getElementById('variantGeneratorPanel');
    panel.classList.toggle('hidden');
    if (!panel.classList.contains('hidden')) {
        loadVariantAttributes();
    }
}

function closeVariantGenerator() {
    document.getElementById('variantGeneratorPanel').classList.add('hidden');
}

function loadVariantAttributes() {
    fetch('/admin/products/variant-attributes', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            variantAttributes = data.data;
            renderVariantAttributesSelector();
        }
    })
    .catch(error => {
        console.error('Error loading variant attributes:', error);
        showToast('Failed to load attributes', 'error');
    });
}

function renderVariantAttributesSelector() {
    const container = document.getElementById('variantAttributesContainer');
    
    if (variantAttributes.length === 0) {
        container.innerHTML = `
            <div class="text-center py-6">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-sm font-medium text-gray-900 mb-1">No Variant Attributes Found</p>
                <p class="text-xs text-gray-500 mb-3">Create variant attributes (like Color, Size) first to generate product variants</p>
                <a href="/admin/attributes" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Attributes
                </a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = variantAttributes.map(attr => `
        <div class="border border-gray-200 rounded-lg p-3 bg-white">
            <label class="flex items-center gap-2 mb-2">
                <input type="checkbox" class="variant-attr-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded" value="${attr.id}" data-name="${attr.name}" onchange="toggleAttributeValues(${attr.id})">
                <span class="text-sm font-medium text-gray-900">${attr.name}</span>
            </label>
            <div id="attr-values-${attr.id}" class="ml-6 flex flex-wrap gap-2 hidden">
                ${attr.values.map(val => `
                    <label class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded text-xs cursor-pointer hover:bg-gray-200">
                        <input type="checkbox" class="variant-value-checkbox w-3 h-3" data-attr="${attr.id}" data-attr-name="${attr.name}" value="${val.id}" data-value-name="${val.value}">
                        ${val.value}
                    </label>
                `).join('')}
            </div>
        </div>
    `).join('');
}

function toggleAttributeValues(attrId) {
    const container = document.getElementById(`attr-values-${attrId}`);
    const checkbox = document.querySelector(`.variant-attr-checkbox[value="${attrId}"]`);
    
    if (checkbox.checked) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
        // Uncheck all values
        container.querySelectorAll('.variant-value-checkbox').forEach(cb => cb.checked = false);
    }
}

function generateVariants() {
    // Get selected attributes and their values
    const selectedAttrs = [];

    document.querySelectorAll('.variant-attr-checkbox:checked').forEach(attrCheckbox => {
        const attrId = attrCheckbox.value;
        const attrName = attrCheckbox.dataset.name;
        const selectedValues = [];

        document.querySelectorAll(`.variant-value-checkbox[data-attr="${attrId}"]:checked`).forEach(valCheckbox => {
            selectedValues.push({
                id: valCheckbox.value,
                name: valCheckbox.dataset.valueName
            });
        });

        if (selectedValues.length > 0) {
            selectedAttrs.push({ id: attrId, name: attrName, values: selectedValues });
        }
    });

    if (selectedAttrs.length === 0) {
        showToast('Please select at least one attribute and its values to generate variants', 'warning');
        return;
    }

    // Generate all combinations
    const combinations = generateCombinations(selectedAttrs);

    // Build base SKU from product info (same logic as product-level SKU)
    const baseSku = buildBaseSkuFromProduct();

    // Create variants with auto-generated professional SKUs
    productVariants = combinations.map((combo, index) => {
        const variantName = combo.map(c => c.valueName).join(' / ');
        const variantSku  = buildVariantSku(baseSku, combo, index);
        return {
            id: null,
            name: variantName,
            sku: variantSku,
            price: '',
            attributes: combo,
            images: [],
            status: true
        };
    });

    renderVariantsList();
    closeVariantGenerator();
    showToast(`${productVariants.length} variants generated with auto SKUs`, 'success');
}

/**
 * Build the product-level base SKU prefix from name / brand / category.
 * Format: [CAT4]-[BRAND4]-[WORD1][WORD2]-[TYPE3]
 * e.g.  FOOT-KIPS-FOOT-BALL-SIM
 */
function buildBaseSkuFromProduct() {
    const parts = [];

    const categorySelect = document.getElementById('productPrimaryCategory');
    if (categorySelect && categorySelect.value) {
        const catName = categorySelect.selectedOptions[0]?.text || '';
        const catPrefix = catName.replace(/[^A-Z0-9]/gi, '').substring(0, 4).toUpperCase();
        if (catPrefix) parts.push(catPrefix);
    }

    const brandSelect = document.getElementById('productBrand');
    if (brandSelect && brandSelect.value) {
        const brandName = brandSelect.selectedOptions[0]?.text || '';
        const brandPrefix = brandName.replace(/[^A-Z0-9]/gi, '').substring(0, 4).toUpperCase();
        if (brandPrefix) parts.push(brandPrefix);
    }

    const productName = (document.getElementById('productName')?.value || '').trim();
    if (productName) {
        const nameWords = productName
            .toUpperCase()
            .replace(/[^A-Z0-9\s]/g, '')
            .split(/\s+/)
            .filter(w => w.length > 2)
            .slice(0, 2)
            .map(w => w.substring(0, 4));
        parts.push(...nameWords);
    }

    return parts.join('-') || 'PRD';
}

/**
 * Build a professional SKU for one variant.
 * Format: [BASE_SKU]-[ATTR_CODES]-[4-digit-index]
 *
 * Attribute codes:
 *   Color  → hex-like shortcode  e.g. "RED", "BLK", "NVY"
 *   Size   → value itself        e.g. "XS", "M", "XL", "UK8"
 *   Others → first 3 chars upper e.g. "CTN", "POL"
 *
 * Example: FOOT-KIPS-FTBL-RED-XL-0001
 */
function buildVariantSku(baseSku, combo, index) {
    const attrCodes = combo.map(c => {
        const val = c.valueName.toString().toUpperCase().replace(/[^A-Z0-9]/g, '');

        // Color names → standard 3-char codes
        const colorMap = {
            'BLACK':'BLK','WHITE':'WHT','RED':'RED','BLUE':'BLU','NAVY':'NVY',
            'NAVYBLUE':'NVY','SKYBLUE':'SKY','GREEN':'GRN','LIMEGREEN':'LGR',
            'YELLOW':'YLW','ORANGE':'ORG','PINK':'PNK','PURPLE':'PRP',
            'GREY':'GRY','GRAY':'GRY','DARKGREY':'DGR','BROWN':'BRN',
            'BEIGE':'BGE','MULTICOLOR':'MLT'
        };
        if (colorMap[val]) return colorMap[val];

        // Size values → keep as-is if short enough (≤4 chars)
        if (val.length <= 4) return val;

        // Everything else → first 3 chars
        return val.substring(0, 3);
    });

    // Zero-padded sequential index
    const seq = String(index + 1).padStart(4, '0');

    return [baseSku, ...attrCodes, seq].join('-');
}

function generateCombinations(attributes) {
    if (attributes.length === 0) return [];
    if (attributes.length === 1) {
        return attributes[0].values.map(val => [{
            attrId: attributes[0].id,
            attrName: attributes[0].name,
            valueId: val.id,
            valueName: val.name
        }]);
    }
    
    const result = [];
    const firstAttr = attributes[0];
    const restCombinations = generateCombinations(attributes.slice(1));
    
    firstAttr.values.forEach(val => {
        restCombinations.forEach(combo => {
            result.push([{
                attrId: firstAttr.id,
                attrName: firstAttr.name,
                valueId: val.id,
                valueName: val.name
            }, ...combo]);
        });
    });
    
    return result;
}

function renderVariantsList() {
    const container = document.getElementById('variantsListContainer');
    
    if (productVariants.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500 text-center py-8">No variants yet. Click "Generate Variants" to create them.</p>';
        return;
    }
    
    container.innerHTML = `
        <div class="space-y-3">
            <div class="flex items-center justify-between mb-3">
                <h5 class="text-sm font-semibold text-gray-900">${productVariants.length} Variants Generated</h5>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="regenAllVariantSkus()" class="inline-flex items-center gap-1 text-xs text-purple-600 hover:text-purple-700 font-medium border border-purple-200 px-2 py-1 rounded hover:bg-purple-50 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Regen All SKUs
                    </button>
                    <button type="button" onclick="clearVariants()" class="text-xs text-red-600 hover:text-red-700">Clear All</button>
                </div>
            </div>
            ${productVariants.map((variant, index) => `
                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-900">${variant.name}</span>
                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded">${variant.attributes.map(a => a.valueName).join(' • ')}</span>
                        </div>
                        <button type="button" onclick="removeVariant(${index})" class="text-red-600 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Variant Details -->
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="relative">
                            <input type="text" placeholder="SKU" value="${variant.sku}"
                                   id="variantSku_${index}"
                                   onchange="updateVariant(${index}, 'sku', this.value)"
                                   class="w-full px-2 py-1.5 pr-8 border border-gray-300 rounded text-xs font-mono focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <button type="button" title="Regenerate SKU"
                                    onclick="regenVariantSku(${index})"
                                    class="absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </div>
                        <input type="number" step="0.01" placeholder="Price (₹)" value="${variant.price}" onchange="updateVariant(${index}, 'price', this.value)" class="px-2 py-1.5 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <input type="number" min="0" placeholder="Stock Qty" value="${variant.stock_quantity || 0}" onchange="updateVariant(${index}, 'stock_quantity', this.value)" class="px-2 py-1.5 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <input type="number" min="0" placeholder="Compare Price (₹)" value="${variant.compare_price || ''}" onchange="updateVariant(${index}, 'compare_price', this.value)" class="px-2 py-1.5 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    
                    <!-- Variant Images Section -->
                    <div class="border-t pt-3">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-medium text-gray-700">Variant Images</label>
                            <button type="button" onclick="uploadVariantImage(${index})" class="inline-flex items-center gap-1 px-2 py-1 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Upload Image
                            </button>
                        </div>
                        <div id="variantImages_${index}" class="grid grid-cols-5 gap-2">
                            ${variant.images && variant.images.length > 0 ? 
                                variant.images.map((img, imgIndex) => `
                                    <div class="relative group">
                                        <img src="${img.image_url}" alt="Variant image" class="w-full h-20 object-cover rounded border border-gray-200">
                                        <button type="button" onclick="removeVariantImage(${index}, ${imgIndex})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                `).join('') 
                                : 
                                '<p class="col-span-5 text-xs text-gray-400 text-center py-4 border-2 border-dashed border-gray-200 rounded">No images uploaded yet</p>'
                            }
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function updateVariant(index, field, value) {
    if (productVariants[index]) {
        productVariants[index][field] = value;
    }
}

function removeVariant(index) {
    productVariants.splice(index, 1);
    renderVariantsList();
    showToast('Variant removed', 'success');
}

function clearVariants() {
    if (confirm('Are you sure you want to clear all variants?')) {
        productVariants = [];
        renderVariantsList();
        showToast('All variants cleared', 'success');
    }
}

/**
 * Regenerate SKU for a single variant and update the input field live.
 */
function regenVariantSku(index) {
    if (!productVariants[index]) return;
    const baseSku = buildBaseSkuFromProduct();
    const newSku  = buildVariantSku(baseSku, productVariants[index].attributes, index);
    productVariants[index].sku = newSku;
    const input = document.getElementById('variantSku_' + index);
    if (input) {
        input.value = newSku;
        input.classList.add('ring-2', 'ring-purple-400');
        setTimeout(() => input.classList.remove('ring-2', 'ring-purple-400'), 800);
    }
    showToast('SKU regenerated: ' + newSku, 'success');
}

/**
 * Regenerate SKUs for ALL variants at once.
 */
function regenAllVariantSkus() {
    if (productVariants.length === 0) return;
    const baseSku = buildBaseSkuFromProduct();
    productVariants.forEach((v, i) => {
        v.sku = buildVariantSku(baseSku, v.attributes, i);
    });
    renderVariantsList();
    showToast('All variant SKUs regenerated', 'success');
}

// Variant Image Functions
function uploadVariantImage(variantIndex) {
    // Create hidden file input
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.multiple = true;
    
    input.onchange = async (e) => {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;
        
        showToast('Uploading images...', 'info');
        
        try {
            // Upload to ImageKit (you'll need to implement this)
            for (const file of files) {
                const imageUrl = await uploadToImageKit(file);
                
                // Add image to variant
                if (!productVariants[variantIndex].images) {
                    productVariants[variantIndex].images = [];
                }
                
                productVariants[variantIndex].images.push({
                    image_url: imageUrl,
                    image_id: null, // Will be set after ImageKit upload
                    alt_text: productVariants[variantIndex].name,
                    sort_order: productVariants[variantIndex].images.length
                });
            }
            
            renderVariantsList();
            showToast(`${files.length} image(s) uploaded successfully`, 'success');
        } catch (error) {
            console.error('Image upload error:', error);
            showToast('Failed to upload images', 'error');
        }
    };
    
    input.click();
}

function removeVariantImage(variantIndex, imageIndex) {
    if (confirm('Remove this image?')) {
        productVariants[variantIndex].images.splice(imageIndex, 1);
        renderVariantsList();
        showToast('Image removed', 'success');
    }
}

// Placeholder for ImageKit upload - you'll need to implement actual ImageKit integration
async function uploadToImageKit(file) {
    // For now, create a local URL for preview
    // In production, this should upload to ImageKit and return the URL
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            resolve(e.target.result);
        };
        reader.readAsDataURL(file);
    });
    // return data.url;
}


// Utility Functions
function openModal() {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('productModalContent');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    requestAnimationFrame(() => {
        modalContent.style.transform = 'translateX(0)';

        // Show form and init tabs
        const form = document.getElementById('productForm');
        if (form) form.style.visibility = 'visible';

        // Show only active tab via class (CSS handles display)
        TAB_ORDER.forEach(t => {
            const el = document.getElementById(`content-${t}`);
            if (!el) return;
            if (t === (currentTab || 'basic')) {
                el.classList.add('tab-active');
            } else {
                el.classList.remove('tab-active');
            }
        });

        // Initialize collapsible sections
        initCollapsibleSections();

        // Initialize Summernote editors after modal is visible
        setTimeout(() => {
            initSummernote('productShortDescription', 'simple');
            initSummernote('productDescription', 'full');
        }, 100);
    });
}

function closeModal() {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('productModalContent');
    
    // Close all open searchable select dropdowns
    if (typeof searchableSelectInstances !== 'undefined') {
        searchableSelectInstances.forEach(instance => {
            if (instance.isOpen) {
                instance.close();
            }
        });
    }
    
    modalContent.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 500);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'productModal') {
        closeModal();
    }
}

function clearErrors() {
    document.querySelectorAll('[id$="Error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
}

function setLoading(loading) {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    
    if (loading) {
        submitBtn.disabled = true;
        submitBtnText.classList.add('hidden');
        submitBtnLoading.classList.remove('hidden');
        submitBtnLoading.classList.add('flex');
    } else {
        submitBtn.disabled = false;
        submitBtnText.classList.remove('hidden');
        submitBtnLoading.classList.add('hidden');
    }
}

function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium transition-all ${colors[type] || colors.success}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function showConfirmDialog(title, message, onConfirm) {
    const dialog = document.createElement('div');
    dialog.className = 'fixed inset-0 z-50 flex items-center justify-center p-4';
    dialog.innerHTML = `
        <style>
            @keyframes dlg-backdrop {
                from { opacity: 0; }
                to   { opacity: 1; }
            }
            @keyframes dlg-pop {
                0%   { opacity: 0; transform: scale(0.85) translateY(16px); }
                60%  { opacity: 1; transform: scale(1.03) translateY(-3px); }
                80%  { transform: scale(0.98) translateY(1px); }
                100% { opacity: 1; transform: scale(1)    translateY(0); }
            }
            .dlg-bg  { animation: dlg-backdrop .2s ease forwards; }
            .dlg-box { animation: dlg-pop .35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        </style>
        <div class="dlg-bg fixed inset-0 bg-black/40 backdrop-blur-[2px]" onclick="this.parentElement.remove()"></div>
        <div class="dlg-box relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6">
            <div class="flex flex-col items-center text-center gap-3 mb-5">
                <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">${title}</h3>
                    <p class="text-sm text-gray-500 mt-1 leading-relaxed">${message}</p>
                </div>
            </div>
            <div class="flex gap-2.5">
                <button onclick="this.closest('.fixed').remove()"
                        class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button onclick="(${onConfirm.toString()})(); this.closest('.fixed').remove();"
                        class="flex-1 px-4 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </div>
        </div>`;
    document.body.appendChild(dialog);
}

// ── Success Dialog ───────────────────────────────────────────────
function showSuccessDialog(message = 'Deleted successfully') {
    const dialog = document.createElement('div');
    dialog.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4';
    dialog.innerHTML = `
        <style>
            @keyframes succ-backdrop { from{opacity:0} to{opacity:1} }
            @keyframes succ-pop {
                0%   { opacity:0; transform:scale(0.5); }
                60%  { opacity:1; transform:scale(1.1); }
                80%  { transform:scale(0.95); }
                100% { opacity:1; transform:scale(1); }
            }
            @keyframes succ-ring  { 0%{stroke-dashoffset:166;opacity:0} 20%{opacity:1} 100%{stroke-dashoffset:0} }
            @keyframes succ-check { 0%{stroke-dashoffset:48;opacity:0} 40%{opacity:0} 100%{stroke-dashoffset:0;opacity:1} }
            @keyframes succ-msg   { 0%{opacity:0;transform:translateY(8px)} 100%{opacity:1;transform:translateY(0)} }
            .succ-bg    { animation: succ-backdrop .2s ease forwards; }
            .succ-box   { animation: succ-pop .4s cubic-bezier(0.34,1.56,0.64,1) forwards; }
            .succ-ring  { stroke-dasharray:166; stroke-dashoffset:166; animation: succ-ring  .6s .1s cubic-bezier(0.65,0,0.45,1) forwards; }
            .succ-check { stroke-dasharray:48;  stroke-dashoffset:48;  animation: succ-check .4s .5s cubic-bezier(0.65,0,0.45,1) forwards; }
            .succ-msg   { animation: succ-msg .3s .7s ease forwards; opacity:0; }
        </style>
        <div class="succ-bg fixed inset-0 bg-black/30 backdrop-blur-[2px]"></div>
        <div class="succ-box relative bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 w-64">
            <svg class="w-20 h-20" viewBox="0 0 52 52">
                <circle class="succ-ring" cx="26" cy="26" r="25" fill="none" stroke="#22c55e" stroke-width="2"/>
                <path class="succ-check" fill="none" stroke="#22c55e" stroke-width="3"
                      stroke-linecap="round" stroke-linejoin="round" d="M14 27 l8 8 l16-16"/>
            </svg>
            <p class="succ-msg text-sm font-semibold text-gray-700 text-center">${message}</p>
        </div>`;
    document.body.appendChild(dialog);
    setTimeout(() => {
        dialog.style.transition = 'opacity .25s ease';
        dialog.style.opacity = '0';
        setTimeout(() => dialog.remove(), 260);
    }, 1600);
}

// Related Products Management
function openProductSelector(type) {
    currentRelationType = type;
    selectedProductsForRelation.clear();
    
    const titles = {
        'related': 'Select Related Products',
        'upsell': 'Select Upsell Products',
        'cross_sell': 'Select Cross-sell Products'
    };
    
    document.getElementById('selectorModalTitle').textContent = titles[type];
    document.getElementById('productSelectorModal').classList.remove('hidden');
    loadProductsForSelector();
}

function closeProductSelector() {
    document.getElementById('productSelectorModal').classList.add('hidden');
    selectedProductsForRelation.clear();
}

function loadProductsForSelector() {
    const search = document.getElementById('productSelectorSearch').value;
    const currentProductId = document.getElementById('productId').value;
    
    fetch(`/admin/products/list?per_page=50&search=${search}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('productSelectorList');
                const products = data.data.filter(p => p.id != currentProductId);
                
                if (products.length === 0) {
                    container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No products found</p>';
                    return;
                }
                
                container.innerHTML = products.map(product => `
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" value="${product.id}" onchange="toggleProductSelection(${product.id})" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${product.name}</p>
                            <p class="text-xs text-gray-500">${product.brand?.name || 'No Brand'} • ${product.status}</p>
                        </div>
                    </label>
                `).join('');
            }
        });
}

function searchProductsForSelector() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadProductsForSelector();
    }, 300);
}

function toggleProductSelection(productId) {
    if (selectedProductsForRelation.has(productId)) {
        selectedProductsForRelation.delete(productId);
    } else {
        selectedProductsForRelation.add(productId);
    }
}

function addSelectedProducts() {
    if (selectedProductsForRelation.size === 0) {
        showToast('Please select at least one product', 'error');
        return;
    }
    
    selectedProductsForRelation.forEach(productId => {
        if (!relatedProducts[currentRelationType].includes(productId)) {
            relatedProducts[currentRelationType].push(productId);
        }
    });
    
    renderRelatedProducts();
    closeProductSelector();
    showToast('Products added successfully', 'success');
}

function removeRelatedProduct(type, productId) {
    relatedProducts[type] = relatedProducts[type].filter(id => id != productId);
    renderRelatedProducts();
}

function renderRelatedProducts() {
    ['related', 'upsell', 'cross_sell'].forEach(type => {
        const container = document.getElementById(`${type === 'cross_sell' ? 'crossSell' : type}ProductsList`);
        const productIds = relatedProducts[type];
        
        if (productIds.length === 0) {
            container.innerHTML = '<p class="text-xs text-gray-400 text-center py-2">No products added</p>';
            return;
        }
        
        // Fetch product details
        Promise.all(productIds.map(id => 
            fetch(`/admin/products/${id}`).then(res => res.json())
        )).then(responses => {
            container.innerHTML = responses.map(data => {
                if (!data.success) return '';
                const product = data.data;
                return `
                    <div class="flex items-center justify-between p-2 bg-white border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${product.name}</p>
                            <p class="text-xs text-gray-500">${product.brand?.name || 'No Brand'}</p>
                        </div>
                        <button type="button" onclick="removeRelatedProduct('${type}', ${product.id})" class="text-red-600 hover:text-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
            }).join('');
        });
    });
}

// ============================================
// VIDEO MANAGEMENT
// ============================================

function openAddVideoModal() {
    const videoUrl = prompt('Enter YouTube or Vimeo video URL:');
    if (!videoUrl) return;
    
    const videoData = parseVideoUrl(videoUrl);
    if (!videoData) {
        alert('Invalid video URL. Please enter a valid YouTube or Vimeo URL.');
        return;
    }
    
    const title = prompt('Enter video title (optional):') || '';
    
    addVideoToList(videoData, title);
}

function parseVideoUrl(url) {
    // YouTube patterns
    const youtubePatterns = [
        /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/,
        /youtube\.com\/embed\/([^&\s]+)/
    ];
    
    for (const pattern of youtubePatterns) {
        const match = url.match(pattern);
        if (match) {
            return {
                provider: 'youtube',
                video_id: match[1],
                video_url: url,
                thumbnail_url: `https://img.youtube.com/vi/${match[1]}/maxresdefault.jpg`
            };
        }
    }
    
    // Vimeo pattern
    const vimeoPattern = /vimeo\.com\/(\d+)/;
    const vimeoMatch = url.match(vimeoPattern);
    if (vimeoMatch) {
        return {
            provider: 'vimeo',
            video_id: vimeoMatch[1],
            video_url: url,
            thumbnail_url: null // Will be fetched from API if needed
        };
    }
    
    return null;
}

function addVideoToList(videoData, title = '') {
    const video = {
        id: Date.now(),
        title: title,
        provider: videoData.provider,
        video_url: videoData.video_url,
        video_id: videoData.video_id,
        thumbnail_url: videoData.thumbnail_url,
        is_featured: productVideos.length === 0, // First video is featured
        sort_order: productVideos.length
    };
    
    productVideos.push(video);
    renderVideosList();
}

function renderVideosList() {
    const container = document.getElementById('productVideosList');
    
    if (productVideos.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500 text-sm">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                No videos added yet
            </div>
        `;
        return;
    }
    
    container.innerHTML = productVideos.map((video, index) => `
        <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg bg-white hover:border-[#0082C3] transition-colors">
            <div class="w-24 h-16 rounded overflow-hidden bg-gray-100 flex-shrink-0">
                ${video.thumbnail_url 
                    ? `<img src="${video.thumbnail_url}" alt="${video.title}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>`
                }
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${video.title || 'Untitled Video'}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                        video.provider === 'youtube' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'
                    }">
                        ${video.provider === 'youtube' ? 'YouTube' : 'Vimeo'}
                    </span>
                    ${video.is_featured 
                        ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Featured</span>'
                        : ''
                    }
                </div>
            </div>
            <div class="flex items-center gap-2">
                ${!video.is_featured 
                    ? `<button type="button" onclick="setFeaturedVideo(${index})" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Set as Featured">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </button>`
                    : ''
                }
                <button type="button" onclick="removeVideo(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');
}

function setFeaturedVideo(index) {
    productVideos.forEach((video, i) => {
        video.is_featured = i === index;
    });
    renderVideosList();
}

function removeVideo(index) {
    if (confirm('Are you sure you want to remove this video?')) {
        productVideos.splice(index, 1);
        // If removed video was featured, make first video featured
        if (productVideos.length > 0 && !productVideos.some(v => v.is_featured)) {
            productVideos[0].is_featured = true;
        }
        renderVideosList();
    }
}

// ============================================
// FAQ MANAGEMENT
// ============================================

function addFaqItem() {
    const question = prompt('Enter the question:');
    if (!question || question.trim() === '') return;
    
    const answer = prompt('Enter the answer:');
    if (!answer || answer.trim() === '') return;
    
    const faq = {
        id: Date.now(),
        question: question.trim(),
        answer: answer.trim(),
        sort_order: productFaqs.length,
        status: true
    };
    
    productFaqs.push(faq);
    renderFaqsList();
}

function renderFaqsList() {
    const container = document.getElementById('productFaqsList');
    
    if (productFaqs.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500 text-sm">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                No FAQs added yet
            </div>
        `;
        return;
    }
    
    container.innerHTML = productFaqs.map((faq, index) => `
        <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#0082C3] text-white text-xs font-semibold">
                                Q
                            </span>
                            <p class="text-sm font-semibold text-gray-900">${faq.question}</p>
                        </div>
                        <div class="flex items-start gap-2 ml-8">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                A
                            </span>
                            <p class="text-sm text-gray-700 flex-1">${faq.answer}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        ${index > 0 
                            ? `<button type="button" onclick="moveFaqUp(${index})" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Move Up">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>`
                            : ''
                        }
                        ${index < productFaqs.length - 1 
                            ? `<button type="button" onclick="moveFaqDown(${index})" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Move Down">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>`
                            : ''
                        }
                        <button type="button" onclick="editFaq(${index})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="removeFaq(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function editFaq(index) {
    const faq = productFaqs[index];
    
    const question = prompt('Edit question:', faq.question);
    if (question === null) return; // User cancelled
    
    const answer = prompt('Edit answer:', faq.answer);
    if (answer === null) return; // User cancelled
    
    if (question.trim() && answer.trim()) {
        productFaqs[index].question = question.trim();
        productFaqs[index].answer = answer.trim();
        renderFaqsList();
    }
}

function removeFaq(index) {
    if (confirm('Are you sure you want to remove this FAQ?')) {
        productFaqs.splice(index, 1);
        // Update sort order
        productFaqs.forEach((faq, i) => {
            faq.sort_order = i;
        });
        renderFaqsList();
    }
}

function moveFaqUp(index) {
    if (index > 0) {
        [productFaqs[index], productFaqs[index - 1]] = [productFaqs[index - 1], productFaqs[index]];
        // Update sort order
        productFaqs.forEach((faq, i) => {
            faq.sort_order = i;
        });
        renderFaqsList();
    }
}

function moveFaqDown(index) {
    if (index < productFaqs.length - 1) {
        [productFaqs[index], productFaqs[index + 1]] = [productFaqs[index + 1], productFaqs[index]];
        // Update sort order
        productFaqs.forEach((faq, i) => {
            faq.sort_order = i;
        });
        renderFaqsList();
    }
}

// ============================================
// IMPORT/EXPORT FUNCTIONS
// ============================================

function toggleImportExportDropdown() {
    const menu = document.getElementById('importExportMenu');
    menu.classList.toggle('hidden');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(e) {
        if (!document.getElementById('importExportDropdown').contains(e.target)) {
            menu.classList.add('hidden');
            document.removeEventListener('click', closeDropdown);
        }
    });
}

function duplicateProduct(id, name) {
    if (confirm(`Duplicate product "${name}"? This will create a copy with all images, videos, FAQs, and variants.`)) {
        fetch(`/admin/products/${id}/duplicate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Product duplicated successfully', 'success');
                loadProducts(currentPage);
            } else {
                showToast(data.message || 'Failed to duplicate product', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to duplicate product', 'error');
        });
    }
}

function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
    document.getElementById('importExportMenu').classList.add('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importForm').reset();
}

function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
    document.getElementById('importExportMenu').classList.add('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
    document.getElementById('exportForm').reset();
}

function startImport() {
    const form = document.getElementById('importForm');
    const formData = new FormData(form);
    
    // Add field mapping
    const fieldMapping = {};
    document.querySelectorAll('[data-csv-field]').forEach(select => {
        const csvField = select.dataset.csvField;
        const productField = select.value;
        if (productField) {
            fieldMapping[csvField] = productField;
        }
    });
    
    formData.append('field_mapping', JSON.stringify(fieldMapping));
    
    fetch('/admin/products/import', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Import started successfully', 'success');
            closeImportModal();
            // Optionally open jobs modal to track progress
            setTimeout(() => openImportExportJobsModal(), 1000);
        } else {
            showToast(data.message || 'Import failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Import failed', 'error');
    });
}

function startExport() {
    const form = document.getElementById('exportForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (data[key]) {
            if (Array.isArray(data[key])) {
                data[key].push(value);
            } else {
                data[key] = [data[key], value];
            }
        } else {
            data[key] = value;
        }
    }
    
    fetch('/admin/products/export', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Export started successfully', 'success');
            closeExportModal();
            // Optionally open jobs modal to track progress
            setTimeout(() => openImportExportJobsModal(), 1000);
        } else {
            showToast(data.message || 'Export failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Export failed', 'error');
    });
}

function openImportExportJobsModal() {
    document.getElementById('jobsModal').classList.remove('hidden');
    document.getElementById('importExportMenu').classList.add('hidden');
    loadImportExportJobs();
}

function closeJobsModal() {
    document.getElementById('jobsModal').classList.add('hidden');
}

function loadImportExportJobs() {
    fetch('/admin/products/import-export/jobs', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderImportExportJobs(data.data);
        }
    })
    .catch(error => {
        console.error('Error loading jobs:', error);
    });
}

function renderImportExportJobs(jobs) {
    const container = document.getElementById('jobsList');
    
    if (jobs.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                No import/export jobs found
            </div>
        `;
        return;
    }
    
    container.innerHTML = jobs.map(job => `
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium ${job.type === 'import' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'}">
                        ${job.type}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium ${getStatusBadgeColor(job.status)}">
                        ${job.status}
                    </span>
                </div>
                <span class="text-xs text-gray-500">${formatDate(job.created_at)}</span>
            </div>
            
            <div class="mb-2">
                <div class="flex justify-between text-sm mb-1">
                    <span>Progress</span>
                    <span>${job.processed_records}/${job.total_records}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: ${job.progress_percentage || 0}%"></div>
                </div>
            </div>
            
            <div class="flex justify-between text-xs text-gray-600">
                <span>Success: ${job.successful_records}</span>
                <span>Failed: ${job.failed_records}</span>
                ${job.status === 'completed' && job.type === 'export' ? `
                    <a href="/admin/products/export/${job.id}/download" class="text-blue-600 hover:text-blue-800">Download</a>
                ` : ''}
            </div>
        </div>
    `).join('');
}

function getStatusBadgeColor(status) {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-700',
        'processing': 'bg-blue-100 text-blue-700',
        'completed': 'bg-green-100 text-green-700',
        'failed': 'bg-red-100 text-red-700',
        'cancelled': 'bg-gray-100 text-gray-700'
    };
    return colors[status] || 'bg-gray-100 text-gray-700';
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleString();
}

// Show Notification Toast
function showNotification(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-[9999] flex items-center gap-3 ${colors[type] || colors.success} text-white transform transition-all duration-300 translate-x-0`;
    notification.innerHTML = `
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${icons[type] || icons.success}
        </svg>
        <span class="font-medium">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 hover:bg-white/20 rounded p-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Demo Data Fill Function
async function fillDemoData() {
    try {
        // Fetch brands, categories, tags, collections from DB
        const [brandsRes, categoriesRes, tagsRes, collectionsRes] = await Promise.all([
            fetch('/admin/brands/list?per_page=100&status=1',      { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
            fetch('/admin/categories/list?per_page=100&status=1',  { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
            fetch('/admin/tags/list?per_page=100&status=1',        { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
            fetch('/admin/collections/list?per_page=100&status=1', { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
        ]);

        const [bd, cd, td, cold] = await Promise.all([brandsRes.json(), categoriesRes.json(), tagsRes.json(), collectionsRes.json()]);

        const brands      = bd.data   || [];
        const categories  = cd.data   || [];
        const tags        = (td.tags?.data || td.data || []);
        const collections = cold.data || [];

        if (!brands.length || !categories.length) {
            showToast('Please seed brands and categories first', 'warning');
            return;
        }

        const pick  = arr => arr[Math.floor(Math.random() * arr.length)];
        const rBrand  = pick(brands);
        const rCat    = pick(categories);

        // ── TAB 1: DETAILS ──────────────────────────────────
        const demoProducts = [
            { name: 'Kipsta Football Size 5',     slug: 'kipsta-football-size-5',     desc: 'Official-size football for training and matches. Durable thermobonded construction.',  short: 'Durable size 5 football for all levels of play.' },
            { name: 'Kalenji Running Shoes',       slug: 'kalenji-running-shoes-blue', desc: 'Lightweight road running shoes with responsive cushioning and breathable mesh upper.', short: 'Lightweight running shoes for road training.' },
            { name: 'Domyos Yoga Mat 5mm',         slug: 'domyos-yoga-mat-5mm',        desc: 'Non-slip yoga mat with 5mm cushioning for comfort. Easy to roll and carry.',          short: '5mm non-slip yoga mat for home and studio use.' },
            { name: 'Quechua Hiking Backpack 30L', slug: 'quechua-hiking-backpack-30l', desc: '30L hiking backpack with rain cover, padded straps and multiple pockets.',            short: '30L hiking backpack with rain cover included.' },
            { name: 'Artengo Badminton Racket',    slug: 'artengo-badminton-racket',   desc: 'Lightweight aluminium badminton racket for beginners and intermediate players.',       short: 'Aluminium badminton racket for casual play.' },
        ];
        const demo = pick(demoProducts);

        const _s = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };
        const _c = (id, val) => { const el = document.getElementById(id); if (el) el.checked = val; };
        const _inst = id => searchableSelectInstances.find(i => i.select === document.getElementById(id));

        _s('productName',             demo.name);
        _s('productSlug',             demo.slug);

        // Product type — variable for variety
        _s('productType', 'variable');
        const typeInst = _inst('productType');
        if (typeInst) typeInst.setValue('variable');

        // Brand
        _s('productBrand', rBrand.id);
        const brandInst = _inst('productBrand');
        if (brandInst) brandInst.setValue(String(rBrand.id));

        // Descriptions
        _s('productShortDescription', demo.short);
        const descEl = document.getElementById('productDescription');
        if (descEl) {
            if (typeof $ !== 'undefined' && $(descEl).data('summernote')) {
                $(descEl).summernote('code', `<p>${demo.desc}</p><ul><li>High quality materials</li><li>Designed for performance</li><li>Available in multiple sizes</li><li>1 year warranty</li></ul>`);
            } else {
                descEl.value = demo.desc;
            }
        }

        // ── TAB 2: PRICING ──────────────────────────────────
        _s('productStatus',           'active');
        const statusInst = _inst('productStatus');
        if (statusInst) statusInst.setValue('active');

        _s('productAvailability',     'in_stock');
        const availInst = _inst('productAvailability');
        if (availInst) availInst.setValue('in_stock');

        _s('productVisibility',       'visible');
        const visInst = _inst('productVisibility');
        if (visInst) visInst.setValue('visible');

        _s('productRegularPrice',  '2499.00');
        _s('productSalePrice',     '1999.00');
        _s('productCostPrice',     '900.00');
        _s('productSku',           `${rBrand.name.substring(0,4).toUpperCase()}-${demo.slug.substring(0,6).toUpperCase()}-${Math.floor(1000+Math.random()*9000)}`);
        _s('productBarcode',       `890${Math.floor(1000000000 + Math.random()*9000000000)}`);

        // Stock
        _c('productManageStock', true);
        toggleStockFields();
        _s('productStockQuantity',      '75');
        _s('productLowStockThreshold',  '10');
        _c('productAllowBackorder', false);
        updateStockBadge();

        // Shipping
        _s('productWeight', '0.45');
        _s('productLength', '22');
        _s('productWidth',  '18');
        _s('productHeight', '10');

        // ── TAB 3: MEDIA ────────────────────────────────────
        // (Images require actual file upload — skipped)

        // ── TAB 4: VARIANTS ─────────────────────────────────
        // Generate demo variants after attributes load
        setTimeout(() => {
            productVariants = [
                { id:null, name:'Red / M',   sku: `${demo.slug.substring(0,4).toUpperCase()}-RED-M-0001`,   price:'1999.00', compare_price:'2499.00', stock_quantity:20, attributes:[{attrId:null,attrName:'Color',valueId:null,valueName:'Red'},{attrId:null,attrName:'Size',valueId:null,valueName:'M'}],   images:[], status:true },
                { id:null, name:'Blue / L',  sku: `${demo.slug.substring(0,4).toUpperCase()}-BLU-L-0002`,   price:'1999.00', compare_price:'2499.00', stock_quantity:15, attributes:[{attrId:null,attrName:'Color',valueId:null,valueName:'Blue'},{attrId:null,attrName:'Size',valueId:null,valueName:'L'}],  images:[], status:true },
                { id:null, name:'Black / S', sku: `${demo.slug.substring(0,4).toUpperCase()}-BLK-S-0003`,   price:'1999.00', compare_price:'2499.00', stock_quantity:10, attributes:[{attrId:null,attrName:'Color',valueId:null,valueName:'Black'},{attrId:null,attrName:'Size',valueId:null,valueName:'S'}], images:[], status:true },
                { id:null, name:'Black / XL',sku: `${demo.slug.substring(0,4).toUpperCase()}-BLK-XL-0004`,  price:'2099.00', compare_price:'2499.00', stock_quantity: 5, attributes:[{attrId:null,attrName:'Color',valueId:null,valueName:'Black'},{attrId:null,attrName:'Size',valueId:null,valueName:'XL'}],images:[], status:true },
            ];
            renderVariantsList();
        }, 200);

        // ── TAB 5: ORGANIZATION ─────────────────────────────
        // Primary category
        _s('productPrimaryCategory', rCat.id);
        const primaryCatInst = _inst('productPrimaryCategory');
        if (primaryCatInst) primaryCatInst.setValue(String(rCat.id));

        // Tags — pick up to 3 random
        const tagsSelect = document.getElementById('productTags');
        if (tagsSelect && tags.length) {
            const pickedTags = tags.sort(() => 0.5 - Math.random()).slice(0,3);
            Array.from(tagsSelect.options).forEach(o => { o.selected = pickedTags.some(t => String(t.id) === o.value); });
            const tagInst = searchableSelectInstances.find(i => i.select === tagsSelect);
            if (tagInst) tagInst.refresh();
        }

        // Collections — pick up to 2 random
        const colSelect = document.getElementById('productCollections');
        if (colSelect && collections.length) {
            const pickedCols = collections.sort(() => 0.5 - Math.random()).slice(0,2);
            Array.from(colSelect.options).forEach(o => { o.selected = pickedCols.some(c => String(c.id) === o.value); });
            const colInst = searchableSelectInstances.find(i => i.select === colSelect);
            if (colInst) colInst.refresh();
        }

        // Flags
        _c('productIsFeatured',   Math.random() > 0.5);
        _c('productIsNew',        true);
        _c('productIsBestSeller', Math.random() > 0.7);
        _c('productIsDigital',    false);

        // ── TAB 6: SEO ──────────────────────────────────────
        // Auto-generate SEO after filling name/brand/category
        setTimeout(() => autoGenerateSEO(), 100);

        // ── Done ────────────────────────────────────────────
        // Switch to Details tab to show filled data
        switchTab('basic');
        showToast(`Demo data filled — ${demo.name} | Brand: ${rBrand.name}`, 'success');

    } catch (err) {
        console.error('fillDemoData error:', err);
        showToast('Error filling demo data: ' + err.message, 'error');
    }
}

// Helper function to update SearchableSelect display
function updateSearchableSelectDisplay(selectElement, value) {
    if (!selectElement) return;
    
    selectElement.value = value || '';
    
    const wrapper = selectElement.nextElementSibling;
    if (wrapper && wrapper.classList.contains('searchable-select-wrapper')) {
        const displayText = wrapper.querySelector('.searchable-select-text');
        if (displayText) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (selectedOption && selectedOption.value) {
                displayText.textContent = selectedOption.text;
                displayText.classList.remove('text-gray-400');
                displayText.classList.add('text-gray-700');
            }
        }
        
        // Update dropdown options
        const optionsContainer = wrapper.querySelector('.searchable-select-options');
        if (optionsContainer) {
            let optionsHtml = '';
            Array.from(selectElement.options).forEach(opt => {
                const isSelected = opt.value === selectElement.value;
                optionsHtml += `
                    <div class="searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 ${isSelected ? 'bg-blue-50 text-[#0082C3] font-medium' : 'text-gray-700'}" data-value="${opt.value}">
                        ${opt.textContent}
                    </div>
                `;
            });
            optionsContainer.innerHTML = optionsHtml;
        }
    }
}

// Helper function to update SearchableSelect display for multi-select
function updateSearchableMultiSelectDisplay(selectElement) {
    if (!selectElement) return;
    
    const wrapper = selectElement.nextElementSibling;
    if (wrapper && wrapper.classList.contains('searchable-select-wrapper')) {
        const displayText = wrapper.querySelector('.searchable-select-text');
        if (displayText) {
            const selectedOptions = Array.from(selectElement.selectedOptions);
            if (selectedOptions.length > 0) {
                displayText.textContent = `${selectedOptions.length} selected`;
                displayText.classList.remove('text-gray-400');
                displayText.classList.add('text-gray-700');
            } else {
                displayText.textContent = selectElement.getAttribute('data-placeholder') || 'Select options';
                displayText.classList.add('text-gray-400');
                displayText.classList.remove('text-gray-700');
            }
        }
        
        // Update dropdown options with checkboxes
        const optionsContainer = wrapper.querySelector('.searchable-select-options');
        if (optionsContainer) {
            const selectedValues = Array.from(selectElement.selectedOptions).map(opt => opt.value);
            let optionsHtml = '';
            Array.from(selectElement.options).forEach(opt => {
                const isSelected = selectedValues.includes(opt.value);
                optionsHtml += `
                    <div class="searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 ${isSelected ? 'bg-blue-50' : ''}" data-value="${opt.value}">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" ${isSelected ? 'checked' : ''} class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" onclick="event.stopPropagation()">
                            <span class="${isSelected ? 'text-[#0082C3] font-medium' : 'text-gray-700'}">${opt.textContent}</span>
                        </label>
                    </div>
                `;
            });
            optionsContainer.innerHTML = optionsHtml;
        }
    }
}

// ═══════════════════════════════════════════════════════════
// STOCK / INVENTORY
// ═══════════════════════════════════════════════════════════

function toggleStockFields() {
    const checkbox = document.getElementById('productManageStock');
    const container = document.getElementById('stockFieldsContainer');
    if (!checkbox || !container) return;
    container.style.display = checkbox.checked ? 'grid' : 'none';
    if (checkbox.checked) updateStockBadge();
}

function updateStockBadge() {
    const qty       = parseInt(document.getElementById('productStockQuantity')?.value || 0);
    const threshold = parseInt(document.getElementById('productLowStockThreshold')?.value || 5);
    const badge     = document.getElementById('stockStatusBadge');
    const inner     = document.getElementById('stockStatusInner');
    if (!badge || !inner) return;

    badge.classList.remove('hidden');
    if (qty <= 0) {
        inner.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-red-100 text-red-700';
        inner.innerHTML = '🔴 Out of Stock (0 units)';
    } else if (qty <= threshold) {
        inner.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-orange-100 text-orange-700';
        inner.innerHTML = `⚠️ Low Stock (${qty} units remaining)`;
    } else {
        inner.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-green-100 text-green-700';
        inner.innerHTML = `✅ In Stock (${qty} units)`;
    }
}

// ═══════════════════════════════════════════════════════════
// SEO — Auto Generate + Live Preview + Counters
// ═══════════════════════════════════════════════════════════

function updateSeoCounter(fieldId, counterId, max) {
    const field   = document.getElementById(fieldId);
    const counter = document.getElementById(counterId);
    const progId  = fieldId === 'productSeoTitle' ? 'seoTitleProgress' : 'seoDescProgress';
    const prog    = document.getElementById(progId);
    if (!field || !counter) return;

    const len   = field.value.length;
    const pct   = Math.min((len / max) * 100, 100);
    counter.textContent = `${len}/${max}`;

    if (prog) {
        prog.style.width = pct + '%';
        if (pct < 60)       prog.className = 'h-full bg-red-400 transition-all duration-300';
        else if (pct <= 100) prog.className = 'h-full bg-green-500 transition-all duration-300';
        else                 prog.className = 'h-full bg-orange-400 transition-all duration-300';
    }
}

function updateSeoPreview() {
    const slug  = document.getElementById('productSlug')?.value   || 'product-slug';
    const title = document.getElementById('productSeoTitle')?.value;
    const desc  = document.getElementById('productSeoDescription')?.value;
    const name  = document.getElementById('productName')?.value    || 'Product Title';
    const appName = 'Decathlon';

    const previewTitle = (title || `${name} | ${appName}`).substring(0, 60);
    const previewDesc  = (desc  || document.getElementById('productShortDescription')?.value || 'Product description will appear here in Google search results…').substring(0, 160);
    const previewUrl   = `yoursite.com › products › ${slug || 'product-slug'}`;

    const elUrl   = document.getElementById('seoPreviewUrl');
    const elTitle = document.getElementById('seoPreviewTitle');
    const elDesc  = document.getElementById('seoPreviewDesc');

    if (elUrl)   elUrl.textContent   = previewUrl;
    if (elTitle) elTitle.textContent = previewTitle;
    if (elDesc)  elDesc.textContent  = previewDesc;
}

function autoGenerateSEO() {
    const name        = (document.getElementById('productName')?.value || '').trim();
    const brandEl     = document.getElementById('productBrand');
    const brandName   = brandEl?.selectedOptions[0]?.value ? brandEl.selectedOptions[0].text : '';
    const categoryEl  = document.getElementById('productPrimaryCategory');
    const categoryName= categoryEl?.selectedOptions[0]?.value ? categoryEl.selectedOptions[0].text : '';
    const shortDesc   = (document.getElementById('productShortDescription')?.value || '').replace(/<[^>]+>/g,'').trim();
    const tagsEl      = document.getElementById('productTags');
    const tagNames    = tagsEl ? Array.from(tagsEl.selectedOptions).map(o => o.text.toLowerCase()) : [];
    const slug        = document.getElementById('productSlug')?.value || '';
    const appName     = 'Decathlon';

    if (!name) { showToast('Please enter a product name first', 'warning'); return; }

    // ── SEO Title (max 60) ──────────────────────────────────
    // Pattern: "ProductName | Brand — Category | Store"
    let titleParts = [name];
    if (brandName && brandName !== 'Select Brand') titleParts.push(brandName);
    if (categoryName && categoryName !== 'Select Primary Category') titleParts.push(categoryName);
    titleParts.push(appName);

    let seoTitle = titleParts.join(' | ');
    if (seoTitle.length > 60) {
        seoTitle = `${name} | ${appName}`;
    }
    if (seoTitle.length > 60) {
        seoTitle = name.substring(0, 57) + '…';
    }

    // ── SEO Description (max 160) ───────────────────────────
    let base = shortDesc || name;
    let parts = [];
    if (brandName && brandName !== 'Select Brand') parts.push(`by ${brandName}`);
    if (categoryName && categoryName !== 'Select Primary Category') parts.push(`in ${categoryName}`);
    parts.push(`at ${appName} India`);

    let seoDesc = `${base}. Shop ${name}${parts.length ? ' ' + parts.join(', ') : ''}. Free delivery on orders above ₹999.`;
    if (seoDesc.length > 160) seoDesc = seoDesc.substring(0, 157) + '…';

    // ── SEO Keywords ────────────────────────────────────────
    let keywords = [name.toLowerCase()];
    if (brandName && brandName !== 'Select Brand') keywords.push(brandName.toLowerCase());
    if (categoryName && categoryName !== 'Select Primary Category') keywords.push(categoryName.toLowerCase());
    keywords.push(...tagNames.slice(0, 5));
    keywords.push(appName.toLowerCase());
    keywords.push('buy online', 'india');

    // Remove duplicates
    const uniqueKeywords = [...new Set(keywords)].slice(0, 10);

    // ── Fill fields ─────────────────────────────────────────
    const titleInput = document.getElementById('productSeoTitle');
    const descInput  = document.getElementById('productSeoDescription');
    const kwInput    = document.getElementById('productSeoKeywords');

    if (titleInput) { titleInput.value = seoTitle;                    updateSeoCounter('productSeoTitle', 'seoTitleCount', 60); }
    if (descInput)  { descInput.value  = seoDesc;                     updateSeoCounter('productSeoDescription', 'seoDescCount', 160); }
    if (kwInput)    { kwInput.value    = uniqueKeywords.join(', '); }

    updateSeoPreview();
    showToast('SEO generated successfully', 'success');
}

// Auto-update preview when user types in name/slug fields
document.addEventListener('DOMContentLoaded', () => {
    ['productName', 'productSlug', 'productShortDescription'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', updateSeoPreview);
    });
});
// ═══════════════════════════════════════════════════════════

let _attrGroups   = [];   // cached groups with their attributes+values
let _productAttrs = [];   // currently added product attributes [{attributeId, valueId, customValue}]

function openAddAttributeModal() {
    const modal = document.getElementById('addAttributeModal');
    if (!modal) return;

    // Reset selects
    const groupSel = document.getElementById('attrModalGroup');
    const attrSel  = document.getElementById('attrModalAttribute');
    const valSel   = document.getElementById('attrModalValue');
    const customIn = document.getElementById('attrModalCustomValue');

    groupSel.innerHTML = '<option value="">Loading…</option>';
    attrSel.innerHTML  = '<option value="">— select attribute group first —</option>';
    valSel.innerHTML   = '<option value="">— select attribute first —</option>';
    customIn.value     = '';
    document.getElementById('attrModalValueRow').classList.add('hidden');
    document.getElementById('attrModalCustomRow').classList.add('hidden');

    modal.classList.remove('hidden');

    // Load groups with attributes & values
    if (_attrGroups.length === 0) {
        fetch('/admin/attribute-groups/list?per_page=100&status=1', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            // For each group fetch attributes with values
            const groupIds = data.data.map(g => g.id);
            return Promise.all(groupIds.map(gid =>
                fetch(`/admin/attribute-groups/${gid}`, {
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                }).then(r => r.json())
            )).then(details => {
                // Also fetch attributes per group
                return fetch('/admin/attributes/list?per_page=1000&status=1', {
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                }).then(r => r.json()).then(attrData => {
                    _attrGroups = data.data.map(g => ({
                        ...g,
                        attributes: (attrData.data || []).filter(a => a.group_id == g.id)
                    }));
                    _populateAttrGroupSelect();
                });
            });
        })
        .catch(e => console.error('loadAttrGroups error:', e));
    } else {
        _populateAttrGroupSelect();
    }
}

function _populateAttrGroupSelect() {
    const groupSel = document.getElementById('attrModalGroup');
    groupSel.innerHTML = '<option value="">— Select Group —</option>';
    _attrGroups.forEach(g => {
        groupSel.innerHTML += `<option value="${g.id}">${g.name}</option>`;
    });
}

function onAttrGroupChange(groupId) {
    const attrSel  = document.getElementById('attrModalAttribute');
    const valSel   = document.getElementById('attrModalValue');
    const valRow   = document.getElementById('attrModalValueRow');
    const customRow= document.getElementById('attrModalCustomRow');

    attrSel.innerHTML  = '<option value="">— Select Attribute —</option>';
    valSel.innerHTML   = '<option value="">— Select Value —</option>';
    valRow.classList.add('hidden');
    customRow.classList.add('hidden');

    if (!groupId) return;
    const group = _attrGroups.find(g => g.id == groupId);
    if (!group) return;

    group.attributes.forEach(a => {
        attrSel.innerHTML += `<option value="${a.id}" data-type="${a.type}">${a.name}</option>`;
    });
}

function onAttrAttributeChange(attrId) {
    const valSel    = document.getElementById('attrModalValue');
    const valRow    = document.getElementById('attrModalValueRow');
    const customRow = document.getElementById('attrModalCustomRow');

    valSel.innerHTML = '<option value="">Loading values…</option>';
    valRow.classList.add('hidden');
    customRow.classList.add('hidden');

    if (!attrId) return;

    const attrEl   = document.getElementById('attrModalAttribute');
    const selOpt   = attrEl.options[attrEl.selectedIndex];
    const attrType = selOpt ? selOpt.dataset.type : '';

    // text / number / boolean → custom input
    if (['text', 'number', 'boolean'].includes(attrType)) {
        valSel.innerHTML = '';
        customRow.classList.remove('hidden');
        return;
    }

    // Fetch values for this attribute
    fetch(`/admin/attribute-values/list?attribute_id=${attrId}&per_page=500&status=1`, {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const values = data.data || data.attribute_values?.data || [];
        if (values.length === 0) {
            valSel.innerHTML = '<option value="">No values found</option>';
            valRow.classList.remove('hidden');
            return;
        }
        valSel.innerHTML = '<option value="">— Select Value —</option>';
        values.forEach(v => {
            valSel.innerHTML += `<option value="${v.id}">${v.value}</option>`;
        });
        valRow.classList.remove('hidden');
    })
    .catch(e => {
        console.error('loadAttrValues error:', e);
        valSel.innerHTML = '<option value="">Failed to load</option>';
        valRow.classList.remove('hidden');
    });
}

function saveAttributeToProduct() {
    const groupSel  = document.getElementById('attrModalGroup');
    const attrSel   = document.getElementById('attrModalAttribute');
    const valSel    = document.getElementById('attrModalValue');
    const customIn  = document.getElementById('attrModalCustomValue');
    const customRow = document.getElementById('attrModalCustomRow');

    const attrId    = attrSel.value;
    const attrName  = attrSel.options[attrSel.selectedIndex]?.text || '';

    if (!attrId) { showToast('Please select an attribute', 'error'); return; }

    let valueId    = null;
    let valueName  = '';
    let customVal  = '';

    if (!customRow.classList.contains('hidden')) {
        customVal = customIn.value.trim();
        if (!customVal) { showToast('Please enter a value', 'error'); return; }
        valueName = customVal;
    } else {
        valueId = valSel.value;
        valueName = valSel.options[valSel.selectedIndex]?.text || '';
        if (!valueId) { showToast('Please select a value', 'error'); return; }
    }

    // Check duplicate
    const exists = _productAttrs.find(a => a.attributeId == attrId &&
        (valueId ? a.valueId == valueId : a.customValue === customVal));
    if (exists) { showToast('This attribute+value is already added', 'warning'); return; }

    _productAttrs.push({ attributeId: attrId, attrName, valueId, valueName, customValue: customVal });
    _renderProductAttrs();
    closeAddAttributeModal();
    showToast(`Attribute "${attrName}: ${valueName}" added`, 'success');
}

function _renderProductAttrs() {
    const container = document.getElementById('productAttributesList');
    if (!container) return;

    if (_productAttrs.length === 0) {
        container.innerHTML = `
            <div class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2
                             M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="font-medium text-gray-400">No attributes added yet</p>
                <p class="text-xs mt-1 text-gray-400">Click "Add Attribute" above to add product specs</p>
            </div>`;
        return;
    }

    container.innerHTML = _productAttrs.map((a, i) => `
        <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 px-2 py-0.5 rounded">
                    ${a.attrName}
                </span>
                <span class="text-xs text-gray-500">:</span>
                <span class="text-xs text-gray-800 font-medium">${a.valueName}</span>
            </div>
            <button type="button" onclick="removeProductAttr(${i})"
                    class="text-red-400 hover:text-red-600 transition-colors ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>`).join('');
}

function removeProductAttr(index) {
    _productAttrs.splice(index, 1);
    _renderProductAttrs();
}

function closeAddAttributeModal() {
    document.getElementById('addAttributeModal')?.classList.add('hidden');
}

// Reset product attributes on form reset
const _origResetForm = typeof resetForm === 'function' ? resetForm : null;

</script>

<!-- ── Add Attribute Modal ── -->
<div id="addAttributeModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-[200] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2
                             M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3"/>
                </svg>
                <h3 class="text-base font-semibold text-gray-900">Add Product Attribute</h3>
            </div>
            <button type="button" onclick="closeAddAttributeModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 space-y-4">

            <!-- Step 1: Group -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Attribute Group <span class="text-red-500">*</span>
                </label>
                <select id="attrModalGroup"
                        onchange="onAttrGroupChange(this.value)"
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">— Select Group —</option>
                </select>
            </div>

            <!-- Step 2: Attribute -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Attribute <span class="text-red-500">*</span>
                </label>
                <select id="attrModalAttribute"
                        onchange="onAttrAttributeChange(this.value)"
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">— Select attribute group first —</option>
                </select>
            </div>

            <!-- Step 3a: Value select (for select/multiselect/color) -->
            <div id="attrModalValueRow" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Value <span class="text-red-500">*</span>
                </label>
                <select id="attrModalValue"
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">— Select Value —</option>
                </select>
            </div>

            <!-- Step 3b: Custom value (for text/number/boolean) -->
            <div id="attrModalCustomRow" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Value <span class="text-red-500">*</span>
                </label>
                <input type="text" id="attrModalCustomValue"
                       placeholder="e.g. Cotton, 2 Years, Yes…"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <button type="button" onclick="closeAddAttributeModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300
                           rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="button" onclick="saveAttributeToProduct()"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg
                           hover:bg-green-700 transition-colors">
                Add Attribute
            </button>
        </div>
    </div>
</div>
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Import Products</h3>
            <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="importForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                <input type="file" name="file" accept=".csv,.txt" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Maximum file size: 10MB</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="update_existing" value="1" class="rounded">
                        <span class="text-sm text-gray-700">Update existing products</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="update_by_sku" value="1" class="rounded">
                        <span class="text-sm text-gray-700">Match by SKU</span>
                    </label>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Field Mapping</h4>
                <div class="space-y-2 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <span class="font-medium">CSV Column</span>
                        <span class="font-medium">Product Field</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <span>name</span>
                        <select data-csv-field="name" class="px-2 py-1 border rounded">
                            <option value="name">Product Name</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <span>sku</span>
                        <select data-csv-field="sku" class="px-2 py-1 border rounded">
                            <option value="">Skip</option>
                            <option value="sku_prefix">SKU Prefix</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <span>brand</span>
                        <select data-csv-field="brand" class="px-2 py-1 border rounded">
                            <option value="">Skip</option>
                            <option value="brand_name">Brand Name</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <span>category</span>
                        <select data-csv-field="category" class="px-2 py-1 border rounded">
                            <option value="">Skip</option>
                            <option value="category_name">Category Name</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button onclick="closeImportModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
            <button onclick="startImport()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Start Import</button>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Export Products</h3>
            <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="exportForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="csv">CSV</option>
                    <option value="excel">Excel</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Type</label>
                    <select name="product_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Types</option>
                        <option value="simple">Simple</option>
                        <option value="variable">Variable</option>
                        <option value="digital">Digital</option>
                        <option value="service">Service</option>
                    </select>
                </div>
            </div>
        </form>
        
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button onclick="closeExportModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
            <button onclick="startExport()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Start Export</button>
        </div>
    </div>
</div>

<!-- Jobs Modal -->
<div id="jobsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Import/Export Jobs</h3>
            <button onclick="closeJobsModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-6">
            <div id="jobsList" class="space-y-4">
                <!-- Jobs will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endpush

