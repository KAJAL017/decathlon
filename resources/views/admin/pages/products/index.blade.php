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
    <div id="productModalContent" class="fixed right-0 top-0 h-full w-full max-w-5xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Product</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new product with variants and attributes</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Tabs Navigation -->
        <div class="px-6 py-3 border-b border-gray-200 bg-white flex-shrink-0">
            <div class="flex items-center gap-2">
                <button onclick="switchTab('basic')" id="tab-basic" class="tab-btn active px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Basic Info
                    </span>
                </button>
                <button onclick="switchTab('media')" id="tab-media" class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Media
                    </span>
                </button>
                <button onclick="switchTab('variants')" id="tab-variants" class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        Variants
                    </span>
                </button>
                <button onclick="switchTab('related')" id="tab-related" class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        Related
                    </span>
                </button>
                <button onclick="switchTab('seo')" id="tab-seo" class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        SEO
                    </span>
                </button>
                <button onclick="switchTab('faqs')" id="tab-faqs" class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        FAQs
                    </span>
                </button>
            </div>
        </div>

        <!-- Modal Body with Tabs -->
        <form id="productForm" class="flex-1 overflow-y-auto">
            <input type="hidden" id="productId">
            
            <!-- Tab: Basic Info -->
            <div id="content-basic" class="tab-content active px-6 py-4">
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900">Basic Information</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" id="productName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Nike Air Max 270" required>
                            <p id="productNameError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-gray-500 text-xs">(Auto-generated)</span></label>
                            <input type="text" id="productSlug" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="nike-air-max-270">
                            <p id="productSlugError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SKU Prefix</label>
                            <input type="text" id="productSkuPrefix" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., NIKE">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Type <span class="text-red-500">*</span></label>
                            <select id="productType" data-searchable data-placeholder="Select Type" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                                <option value="simple">Simple Product</option>
                                <option value="variable">Variable Product (with variants)</option>
                                <option value="digital">Digital Product</option>
                                <option value="service">Service</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Brand</label>
                            <select id="productBrand" data-searchable data-placeholder="Select Brand" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                                <option value="">Select Brand</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Primary Category</label>
                            <select id="productCategory" data-searchable data-placeholder="Select Category" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                                <option value="">Select Category</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Short Description</label>
                            <textarea id="productShortDescription" data-editor="simple" rows="2" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Brief product description (max 500 chars)"></textarea>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea id="productDescription" data-editor="full" rows="4" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Detailed product description"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Product Settings -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900">Product Settings</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select id="productStatus" data-searchable data-placeholder="Select Status" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Availability <span class="text-red-500">*</span></label>
                            <select id="productAvailability" data-searchable data-placeholder="Select Availability" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" onchange="toggleAvailableDate()" required>
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="pre_order">Pre-Order</option>
                                <option value="backorder">Backorder</option>
                            </select>
                        </div>

                        <div id="availableDateContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Available Date</label>
                            <input type="date" id="productAvailableDate" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">When will this product be available?</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Visibility</label>
                            <select id="productVisibility" data-searchable data-placeholder="Select Visibility" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                                <option value="visible">Visible (Everywhere)</option>
                                <option value="hidden">Hidden</option>
                                <option value="catalog_only">Catalog Only</option>
                                <option value="search_only">Search Only</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Publish Date</label>
                            <input type="datetime-local" id="productPublishedAt" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Schedule when product goes live</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Unpublish Date</label>
                            <input type="datetime-local" id="productUnpublishedAt" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Auto-unpublish after this date</p>
                        </div>

                        <div class="col-span-2 flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="productFeatured" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Featured</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="productNew" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">New</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="productBestSeller" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Best Seller</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="productIsDigital" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Digital Product</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Digital Product Settings (shown when is_digital is checked) -->
                <div id="digitalProductSettings" class="space-y-4 border-t pt-6 mt-6 hidden">
                    <h4 class="text-sm font-semibold text-gray-900">Digital Product Settings</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Download URL</label>
                            <input type="url" id="productDownloadUrl" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="https://example.com/download/file.zip">
                            <p class="text-xs text-gray-500 mt-1">Direct link to downloadable file</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Download Limit</label>
                            <input type="number" min="0" id="productDownloadLimit" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="0">
                            <p class="text-xs text-gray-500 mt-1">Max downloads per purchase (0 = unlimited)</p>
                        </div>
                    </div>
                </div>

                <!-- Product Tags -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900">Product Tags</h4>
                    <p class="text-xs text-gray-500">Add tags for better search and filtering (press Enter to add)</p>
                    
                    <div>
                        <div class="flex flex-wrap gap-2 mb-2" id="productTagsContainer">
                            <!-- Tags will appear here -->
                        </div>
                        <input type="text" id="productTagInput" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Type tag and press Enter (e.g., summer, sports, running)">
                    </div>
                </div>

                <!-- Dimensions -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900">Dimensions & Weight</h4>
                    
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Weight (kg)</label>
                            <input type="number" step="0.01" id="productWeight" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Length (cm)</label>
                            <input type="number" step="0.01" id="productLength" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Width (cm)</label>
                            <input type="number" step="0.01" id="productWidth" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Height (cm)</label>
                            <input type="number" step="0.01" id="productHeight" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tab: Media -->
            <div id="content-media" class="tab-content hidden px-6 py-4">
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

                    <!-- Videos Section -->
                    <div class="space-y-4 border-t pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Product Videos</h4>
                                <p class="text-xs text-gray-500">Add YouTube or Vimeo videos</p>
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
                            <div class="text-center py-8 text-gray-500 text-sm">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                No videos added yet
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Variants -->
            <div id="content-variants" class="tab-content hidden px-6 py-4">
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
                </div>
            </div>

            <!-- Tab: SEO -->
            <div id="content-seo" class="tab-content hidden px-6 py-4">
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900">SEO Settings</h4>
                    <p class="text-xs text-gray-500">Optimize your product for search engines</p>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SEO Title</label>
                            <input type="text" id="productSeoTitle" maxlength="60" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Product title for search engines">
                            <p class="text-xs text-gray-500 mt-1"><span id="seoTitleCount">0</span>/60 characters</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SEO Description</label>
                            <textarea id="productSeoDescription" maxlength="160" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Product description for search engines"></textarea>
                            <p class="text-xs text-gray-500 mt-1"><span id="seoDescCount">0</span>/160 characters</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SEO Keywords</label>
                            <input type="text" id="productSeoKeywords" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="keyword1, keyword2, keyword3">
                            <p class="text-xs text-gray-500 mt-1">Separate keywords with commas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: FAQs -->
            <div id="content-faqs" class="tab-content hidden px-6 py-4">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">Frequently Asked Questions</h4>
                            <p class="text-xs text-gray-500">Add common questions and answers about this product</p>
                        </div>
                        <button type="button" onclick="addFaqItem()" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add FAQ
                        </button>
                    </div>

                    <!-- FAQs List -->
                    <div id="productFaqsList" class="space-y-3">
                        <div class="text-center py-8 text-gray-500 text-sm">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            No FAQs added yet
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Related Products -->
            <div id="content-related" class="tab-content hidden px-6 py-4">
                <div class="space-y-6">
                    <!-- Related Products Section -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Related Products</h4>
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

                    <!-- Upsell Products Section -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Upsell Products</h4>
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

                    <!-- Cross-sell Products Section -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Cross-sell Products</h4>
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
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
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

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('searchInput') && document.getElementById('productsTableBody')) {
        loadProducts();
        loadBrands();
        loadCategories();
    }
    
    // Auto-slug generation
    document.getElementById('productName')?.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('productSlug').value = slug;
    });
    
    // SEO character counters
    document.getElementById('productSeoTitle')?.addEventListener('input', function() {
        document.getElementById('seoTitleCount').textContent = this.value.length;
    });
    
    document.getElementById('productSeoDescription')?.addEventListener('input', function() {
        document.getElementById('seoDescCount').textContent = this.value.length;
    });

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

    // Product tags input
    document.getElementById('productTagInput')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const tagValue = this.value.trim();
            if (tagValue) {
                addProductTag(tagValue);
                this.value = '';
            }
        }
    });
    
    // Form submission
    document.getElementById('productForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveProduct();
    });
});

// Product Tags Management
let productTags = [];

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

function addProductTag(tagName) {
    if (!productTags.includes(tagName)) {
        productTags.push(tagName);
        renderProductTags();
    }
}

function removeProductTag(tagName) {
    productTags = productTags.filter(t => t !== tagName);
    renderProductTags();
}

function renderProductTags() {
    const container = document.getElementById('productTagsContainer');
    if (!container) return;
    
    container.innerHTML = productTags.map(tag => `
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-700 text-sm rounded-full">
            ${tag}
            <button type="button" onclick="removeProductTag('${tag}')" class="hover:text-blue-900">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </span>
    `).join('');
}


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
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const brandSelect = document.getElementById('productBrand');
            const brandFilter = document.getElementById('brandFilter');
            
            if (brandSelect) {
                brandSelect.innerHTML = '<option value="">Select Brand</option>';
                data.data.forEach(brand => {
                    brandSelect.innerHTML += `<option value="${brand.id}">${brand.name}</option>`;
                });
            }
            
            if (brandFilter) {
                brandFilter.innerHTML = '<option value="">All Brands</option>';
                data.data.forEach(brand => {
                    brandFilter.innerHTML += `<option value="${brand.id}">${brand.name}</option>`;
                });
            }
        }
    });
}

function loadCategories() {
    fetch('/admin/categories/list?per_page=1000&status=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const categorySelect = document.getElementById('productCategory');
            const categoryFilter = document.getElementById('categoryFilter');
            
            if (categorySelect) {
                categorySelect.innerHTML = '<option value="">Select Category</option>';
                data.data.forEach(category => {
                    categorySelect.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });
            }
            
            if (categoryFilter) {
                categoryFilter.innerHTML = '<option value="">All Categories</option>';
                data.data.forEach(category => {
                    categoryFilter.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });
            }
        }
    });
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
function switchTab(tabName) {
    currentTab = tabName;
    
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(`tab-${tabName}`).classList.add('active');
    
    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById(`content-${tabName}`).classList.add('active');
    
    // Update navigation buttons
    updateTabNavigation();
}

function updateTabNavigation() {
    const tabs = ['basic', 'media', 'variants', 'related', 'seo'];
    const currentIndex = tabs.indexOf(currentTab);
    
    const prevBtn = document.getElementById('prevTabBtn');
    const nextBtn = document.getElementById('nextTabBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    // Show/hide previous button
    if (currentIndex > 0) {
        prevBtn.classList.remove('hidden');
    } else {
        prevBtn.classList.add('hidden');
    }
    
    // Show/hide next/submit button
    if (currentIndex < tabs.length - 1) {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    } else {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    }
}

function nextTab() {
    const tabs = ['basic', 'media', 'variants', 'related', 'seo'];
    const currentIndex = tabs.indexOf(currentTab);
    if (currentIndex < tabs.length - 1) {
        switchTab(tabs[currentIndex + 1]);
    }
}

function previousTab() {
    const tabs = ['basic', 'media', 'variants', 'related', 'seo'];
    const currentIndex = tabs.indexOf(currentTab);
    if (currentIndex > 0) {
        switchTab(tabs[currentIndex - 1]);
    }
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
    openModal();
}

function editProduct(id) {
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
    document.getElementById('productId').value = '';
    document.getElementById('productName').value = '';
    document.getElementById('productSlug').value = '';
    document.getElementById('productSkuPrefix').value = '';
    document.getElementById('productType').value = 'simple';
    document.getElementById('productBrand').value = '';
    document.getElementById('productCategory').value = '';
    document.getElementById('productShortDescription').value = '';
    document.getElementById('productDescription').value = '';
    document.getElementById('productStatus').value = 'draft';
    document.getElementById('productAvailability').value = 'in_stock';
    document.getElementById('productAvailableDate').value = '';
    document.getElementById('availableDateContainer').classList.add('hidden');
    document.getElementById('productVisibility').value = 'visible';
    document.getElementById('productPublishedAt').value = '';
    document.getElementById('productUnpublishedAt').value = '';
    document.getElementById('productFeatured').checked = false;
    document.getElementById('productNew').checked = false;
    document.getElementById('productBestSeller').checked = false;
    document.getElementById('productIsDigital').checked = false;
    document.getElementById('productDownloadUrl').value = '';
    document.getElementById('productDownloadLimit').value = '';
    document.getElementById('digitalProductSettings').classList.add('hidden');
    document.getElementById('productWeight').value = '';
    document.getElementById('productLength').value = '';
    document.getElementById('productWidth').value = '';
    document.getElementById('productHeight').value = '';
    document.getElementById('productSeoTitle').value = '';
    document.getElementById('productSeoDescription').value = '';
    document.getElementById('productSeoKeywords').value = '';
    productImages = [];
    productVideos = [];
    productFaqs = [];
    productVariants = [];
    productTags = [];
    relatedProducts = { related: [], upsell: [], cross_sell: [] };
    renderProductTags();
    renderVideosList();
    renderFaqsList();
    renderRelatedProducts();
    clearErrors();
}

function populateForm(product) {
    document.getElementById('productId').value = product.id;
    document.getElementById('productName').value = product.name;
    document.getElementById('productSlug').value = product.slug;
    document.getElementById('productSkuPrefix').value = product.sku_prefix || '';
    document.getElementById('productType').value = product.product_type;
    document.getElementById('productBrand').value = product.brand_id || '';
    document.getElementById('productCategory').value = product.category_id || '';
    setSummernoteContent('productShortDescription', product.short_description || '');
    setSummernoteContent('productDescription', product.description || '');
    document.getElementById('productStatus').value = product.status;
    document.getElementById('productVisibility').value = product.visibility || 'visible';
    
    // Format datetime for input fields
    if (product.published_at) {
        const publishedDate = new Date(product.published_at);
        document.getElementById('productPublishedAt').value = publishedDate.toISOString().slice(0, 16);
    }
    if (product.unpublished_at) {
        const unpublishedDate = new Date(product.unpublished_at);
        document.getElementById('productUnpublishedAt').value = unpublishedDate.toISOString().slice(0, 16);
    }
    
    document.getElementById('productFeatured').checked = product.is_featured;
    document.getElementById('productNew').checked = product.is_new;
    document.getElementById('productBestSeller').checked = product.is_best_seller;
    document.getElementById('productIsDigital').checked = product.is_digital;
    
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
    
    // Load tags
    if (product.tags && product.tags.length > 0) {
        productTags = product.tags.map(t => t.name);
        renderProductTags();
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
        sku_prefix: document.getElementById('productSkuPrefix').value,
        product_type: document.getElementById('productType').value,
        brand_id: document.getElementById('productBrand').value || null,
        category_id: document.getElementById('productCategory').value || null,
        short_description: getSummernoteContent('productShortDescription'),
        description: getSummernoteContent('productDescription'),
        status: document.getElementById('productStatus').value,
        availability_status: document.getElementById('productAvailability').value,
        available_date: document.getElementById('productAvailableDate').value || null,
        visibility: document.getElementById('productVisibility').value,
        published_at: document.getElementById('productPublishedAt').value || null,
        unpublished_at: document.getElementById('productUnpublishedAt').value || null,
        is_digital: document.getElementById('productIsDigital').checked ? 1 : 0,
        download_url: document.getElementById('productDownloadUrl').value || null,
        download_limit: document.getElementById('productDownloadLimit').value || null,
        is_featured: document.getElementById('productFeatured').checked ? 1 : 0,
        is_new: document.getElementById('productNew').checked ? 1 : 0,
        is_best_seller: document.getElementById('productBestSeller').checked ? 1 : 0,
        weight: document.getElementById('productWeight').value || null,
        length: document.getElementById('productLength').value || null,
        width: document.getElementById('productWidth').value || null,
        height: document.getElementById('productHeight').value || null,
        seo_title: document.getElementById('productSeoTitle').value,
        seo_description: document.getElementById('productSeoDescription').value,
        seo_keywords: document.getElementById('productSeoKeywords').value,
        tags: productTags,
        videos: productVideos,
        faqs: productFaqs
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
        `Delete "${name}"?${variantsCount > 0 ? ` Has ${variantsCount} variant(s).` : ''}`,
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
                    showToast(data.message, 'success');
                    loadProducts(currentPage);
                } else {
                    showToast(data.message || 'Operation failed', 'error');
                }
            });
        }
    );
}

// ImageKit Integration
function openImageKitUpload() {
    const imageKitConfig = {
        publicKey: "{{ config('imagekit.public_key') }}",
        urlEndpoint: "{{ config('imagekit.url_endpoint') }}",
        authenticationEndpoint: "{{ route('imagekit.auth') }}"
    };
    
    if (typeof ImageKit === 'undefined') {
        showToast('ImageKit SDK not loaded', 'error');
        return;
    }
    
    const imagekit = new ImageKit(imageKitConfig);
    
    imagekit.upload({
        file: null,
        fileName: `product_${Date.now()}`,
        folder: '/products',
        useUniqueFileName: true,
        onUploadStart: (evt) => {
            showToast('Uploading image...', 'info');
        },
        onUploadProgress: (evt) => {
            const percent = Math.round((evt.loaded / evt.total) * 100);
            console.log(`Upload progress: ${percent}%`);
        }
    }, function(err, result) {
        if (err) {
            console.error('Upload error:', err);
            showToast('Image upload failed', 'error');
            return;
        }
        
        // Add image to productImages array
        productImages.push({
            image_url: result.url,
            image_id: result.fileId,
            alt_text: '',
            sort_order: productImages.length,
            is_featured: productImages.length === 0
        });
        
        renderProductImages();
        showToast('Image uploaded successfully', 'success');
    });
}

function renderProductImages() {
    const grid = document.getElementById('productImagesGrid');
    
    if (productImages.length === 0) {
        grid.innerHTML = '<p class="col-span-4 text-sm text-gray-500 text-center py-4">No images uploaded yet</p>';
        return;
    }
    
    grid.innerHTML = productImages.map((img, index) => `
        <div class="relative group border border-gray-200 rounded-lg overflow-hidden">
            <img src="${img.image_url}" alt="${img.alt_text || 'Product image'}" class="w-full h-32 object-cover">
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                ${index === 0 ? '<span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">Featured</span>' : ''}
                <button type="button" onclick="removeProductImage(${index})" class="p-1.5 bg-red-600 text-white rounded hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <div class="absolute top-2 left-2">
                <span class="px-2 py-1 bg-gray-900/75 text-white text-xs rounded">${index + 1}</span>
            </div>
        </div>
    `).join('');
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
        container.innerHTML = '<p class="text-sm text-gray-500">No variant attributes found. Please create attributes first.</p>';
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
            selectedAttrs.push({
                id: attrId,
                name: attrName,
                values: selectedValues
            });
        }
    });
    
    if (selectedAttrs.length === 0) {
        showToast('Please select at least one attribute with values', 'error');
        return;
    }
    
    // Generate all combinations
    const combinations = generateCombinations(selectedAttrs);
    
    // Create variants
    productVariants = combinations.map((combo, index) => {
        const variantName = combo.map(c => c.valueName).join(' / ');
        return {
            id: null,
            name: variantName,
            sku: '',
            price: '',
            compare_price: '',
            cost_price: '',
            attributes: combo,
            status: true
        };
    });
    
    renderVariantsList();
    closeVariantGenerator();
    showToast(`${productVariants.length} variants generated successfully`, 'success');
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
        <div class="space-y-2">
            <div class="flex items-center justify-between mb-3">
                <h5 class="text-sm font-semibold text-gray-900">${productVariants.length} Variants Generated</h5>
                <button type="button" onclick="clearVariants()" class="text-xs text-red-600 hover:text-red-700">Clear All</button>
            </div>
            ${productVariants.map((variant, index) => `
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900">${variant.name}</span>
                            <span class="text-xs text-gray-500">${variant.attributes.map(a => a.valueName).join(' • ')}</span>
                        </div>
                        <button type="button" onclick="removeVariant(${index})" class="text-red-600 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <input type="text" placeholder="SKU" value="${variant.sku}" onchange="updateVariant(${index}, 'sku', this.value)" class="px-2 py-1.5 border border-gray-300 rounded text-xs">
                        <input type="number" step="0.01" placeholder="Price" value="${variant.price}" onchange="updateVariant(${index}, 'price', this.value)" class="px-2 py-1.5 border border-gray-300 rounded text-xs">
                        <input type="number" step="0.01" placeholder="Compare Price" value="${variant.compare_price}" onchange="updateVariant(${index}, 'compare_price', this.value)" class="px-2 py-1.5 border border-gray-300 rounded text-xs">
                        <input type="number" step="0.01" placeholder="Cost Price" value="${variant.cost_price}" onchange="updateVariant(${index}, 'cost_price', this.value)" class="px-2 py-1.5 border border-gray-300 rounded text-xs">
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

// Utility Functions
function openModal() {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('productModalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    requestAnimationFrame(() => {
        modalContent.style.transform = 'translateX(0)';
        
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
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="this.parentElement.remove()"></div>
        <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                    <p class="text-sm text-gray-600 mt-1">${message}</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button onclick="(${onConfirm.toString()})(); this.closest('.fixed').remove();" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(dialog);
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

</script>

<!-- Import Modal -->
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
