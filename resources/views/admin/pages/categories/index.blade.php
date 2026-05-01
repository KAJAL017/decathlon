@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
            <p class="text-sm text-gray-600 mt-1">Manage product categories, subcategories and hierarchy</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Category
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalCategories">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeCategories">
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
                    <p class="text-sm font-medium text-gray-600">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="inactiveCategories">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Featured</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="featuredCategories">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Parent</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="parentCategories">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Bulk Actions -->
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex items-center gap-4">
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
                    placeholder="Search by name, slug or description..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="parentFilter" data-searchable data-placeholder="All Categories" onchange="loadCategories()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Categories</option>
                <option value="root">Root Categories Only</option>
            </select>
            <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadCategories()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadCategories()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Categories Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Parent</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Skeleton Loader -->
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-4 rounded"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-12 h-12 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-32 mb-2"></div>
                                    <div class="skeleton-text h-3 w-24"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-16"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-16 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-8"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-4 rounded"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-12 h-12 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-28 mb-2"></div>
                                    <div class="skeleton-text h-3 w-20"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-20"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-16"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-16 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-8"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-4 rounded"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-12 h-12 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-36 mb-2"></div>
                                    <div class="skeleton-text h-3 w-28"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-16"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-16 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-8"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
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


<!-- Add/Edit Modal (Slide from Right) -->
<div id="categoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <!-- Backdrop (No transition - instant) -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <!-- Modal Content (Ultra smooth jelly slide) -->
    <div id="categoryModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Category</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new product category</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="fillDemoData()" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-all shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Demo
                </button>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="categoryForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4">
                <input type="hidden" id="categoryId">
                
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Basic Information
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Category Name <span class="text-red-500">*</span></label>
                            <input type="text" id="categoryName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Sports Equipment" required>
                            <p id="categoryNameError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-gray-500 text-xs">(Auto-generated)</span></label>
                            <input type="text" id="categorySlug" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="sports-equipment">
                            <p id="categorySlugError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea id="categoryDescription" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Brief description of the category"></textarea>
                            <p id="categoryDescriptionError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Parent Category</label>
                            <div class="relative">
                                <div id="categoryParentWrapper" class="relative">
                                    <input 
                                        type="text" 
                                        id="categoryParentSearch" 
                                        placeholder="Search or select parent category..."
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                                        autocomplete="off"
                                    >
                                    <input type="hidden" id="categoryParent">
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div id="categoryParentDropdown" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    <div class="p-2">
                                        <div class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded cursor-pointer" data-value="" data-label="None (Root Category)">
                                            None (Root Category)
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p id="categoryParentError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                            <input type="number" id="categorySortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                            <p id="categorySortOrderError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Display Settings
                    </h4>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="categoryStatus" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="categoryFeatured" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Featured</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="categoryShowInMenu" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Show in Menu</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Images Section -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Category Image (ImageKit)
                    </h4>

                    <!-- Category Image - Full Width -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-[#0082C3] transition-colors bg-gray-50">
                            <div id="categoryImagePreview" class="hidden mb-4">
                                <img id="categoryImagePreviewImg" src="" class="w-full max-w-md mx-auto h-48 object-cover rounded-lg shadow-sm">
                                <button type="button" onclick="removeImage()" class="mt-3 text-sm text-red-600 hover:text-red-700 font-medium">
                                    Remove Image
                                </button>
                            </div>
                            <div id="categoryImageProgress" class="hidden mb-4 max-w-md mx-auto">
                                <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                    <div id="categoryImageProgressBar" class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">
                                    <span id="categoryImageProgressText">0%</span> • 
                                    <span id="categoryImageSize">0 KB</span>
                                </p>
                            </div>
                            <div id="categoryImageUploadArea">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <button type="button" onclick="openImageKit('image')" class="inline-flex items-center gap-2 px-6 py-3 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Upload Image
                                </button>
                                <p class="text-sm text-gray-500 mt-4">
                                    <span class="font-medium">Recommended:</span> 500x500px or higher<br>
                                    <span class="text-xs">PNG, JPG, WEBP up to 5MB</span>
                                </p>
                            </div>
                            <input type="hidden" id="categoryImageUrl">
                            <input type="hidden" id="categoryImageId">
                            <input type="hidden" id="categoryImageResponsive">
                            <input type="hidden" id="categoryImageWidth">
                            <input type="hidden" id="categoryImageHeight">
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            SEO Settings
                        </h4>
                        <button type="button" onclick="autoGenerateSEO()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 text-xs font-medium rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Auto Generate
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Meta Title</label>
                            <input type="text" id="categoryMetaTitle" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="SEO optimized title">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Meta Description</label>
                            <textarea id="categoryMetaDescription" rows="2" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="SEO meta description"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Meta Keywords</label>
                            <input type="text" id="categoryMetaKeywords" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="keyword1, keyword2, keyword3">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 sticky bottom-0">
            <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" form="categoryForm" id="submitBtn" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Create Category</span>
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

@endsection

@push('scripts')
<style>
/* Performance Optimizations */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* GPU Acceleration for animations */
.transform,
.transition-transform,
.transition-all {
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000px;
}

/* Skeleton Loader Styles */
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

.skeleton-row { 
    animation: fadeIn 0.3s ease-in;
    will-change: opacity;
}

.skeleton-text,
.skeleton-avatar {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
    will-change: background-position;
}

.skeleton-avatar { border-radius: 8px; }

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Pulse animation for demo data fill */
@keyframes pulse {
    0%, 100% { 
        transform: scale(1);
        opacity: 1;
    }
    50% { 
        transform: scale(1.01);
        opacity: 0.95;
    }
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Optimize hover effects */
button, a, .cursor-pointer {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Modal backdrop optimization */
#categoryModal {
    transition: opacity 0.3s ease-out;
}

#categoryModal.hidden {
    opacity: 0;
    pointer-events: none;
}

/* Optimize table rendering */
table {
    contain: layout style paint;
}

/* Reduce repaints */
.hover\:bg-gray-50:hover,
.hover\:bg-blue-50:hover,
.hover\:bg-red-50:hover {
    will-change: background-color;
}
</style>

<script src="https://unpkg.com/imagekit-javascript/dist/imagekit.min.js"></script>

<script>
const imagekit = new ImageKit({
    publicKey: "{{ config('imagekit.public_key') }}",
    urlEndpoint: "{{ config('imagekit.url_endpoint') }}",
    authenticationEndpoint: "{{ route('imagekit.auth') }}"
});

let currentPage = 1;
let searchTimeout;
let currentImageType = null;
let selectedCategories = new Set();
let allParentCategories = [];

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('searchInput') && document.getElementById('categoriesTableBody')) {
        loadCategories();
        loadParentCategories();
        
        // Initialize custom parent category searchable select
        initializeParentCategorySelect();
    }
    
    function initializeParentCategorySelect() {
        const searchInput = document.getElementById('categoryParentSearch');
        const hiddenInput = document.getElementById('categoryParent');
        const dropdown = document.getElementById('categoryParentDropdown');
        
        if (!searchInput || !hiddenInput || !dropdown) return;
        
        // Custom implementation for parent category select
        searchInput.addEventListener('focus', () => {
            dropdown.classList.remove('hidden');
        });
        
        searchInput.addEventListener('input', (e) => {
            filterParentCategories(e.target.value);
        });
        
        searchInput.addEventListener('blur', (e) => {
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        });
        
        dropdown.addEventListener('mousedown', (e) => {
            e.preventDefault();
        });
        
        // Handle option clicks
        dropdown.addEventListener('click', (e) => {
            const option = e.target.closest('[data-value]');
            if (option) {
                hiddenInput.value = option.dataset.value;
                searchInput.value = option.dataset.label;
                dropdown.classList.add('hidden');
            }
        });
    }
    
    document.getElementById('categoryName')?.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('categorySlug').value = slug;
    });
});

// Helper function to reset parent category select (Global scope)
function resetParentCategorySelect() {
    const searchInput = document.getElementById('categoryParentSearch');
    const hiddenInput = document.getElementById('categoryParent');
    if (searchInput) searchInput.value = '';
    if (hiddenInput) hiddenInput.value = '';
}

// Helper function to set parent category select value (Global scope)
function setParentCategoryValue(value) {
    const hiddenInput = document.getElementById('categoryParent');
    const searchInput = document.getElementById('categoryParentSearch');
    const dropdown = document.getElementById('categoryParentDropdown');
    
    if (!hiddenInput || !searchInput || !dropdown) return;
    
    hiddenInput.value = value || '';
    
    // Find the option with matching value
    const option = dropdown.querySelector(`[data-value="${value}"]`);
    if (option) {
        searchInput.value = option.dataset.label;
    } else if (!value) {
        searchInput.value = '';
    }
}

function loadCategories(page = 1) {
    currentPage = page;
    
    const searchInput = document.getElementById('searchInput');
    const parentFilter = document.getElementById('parentFilter');
    const statusFilter = document.getElementById('statusFilter');
    const perPageSelect = document.getElementById('perPageSelect');
    
    if (!searchInput || !parentFilter || !statusFilter || !perPageSelect) {
        console.error('Required elements not found');
        return;
    }
    
    const search = searchInput.value;
    const parent = parentFilter.value;
    const status = statusFilter.value;
    const perPage = perPageSelect.value;

    const url = `/admin/categories/list?page=${page}&search=${search}&parent_id=${parent}&status=${status}&per_page=${perPage}`;

    const tbody = document.getElementById('categoriesTableBody');
    const skeletonRows = tbody.querySelectorAll('.skeleton-row');
    skeletonRows.forEach(row => row.style.display = '');

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderCategories(data.data);
            renderPagination(data.pagination);
            updateStats(data.data, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error loading categories:', error);
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-red-600">
                        Error loading categories. Please refresh the page.
                    </td>
                </tr>
            `;
        }
    });
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        requestAnimationFrame(() => {
            loadCategories(1);
        });
    }, 300); // Reduced from 500ms to 300ms for faster response
}

function updateStats(categories, pagination) {
    const totalCategoriesEl = document.getElementById('totalCategories');
    const activeCategoriesEl = document.getElementById('activeCategories');
    const inactiveCategoriesEl = document.getElementById('inactiveCategories');
    const featuredCategoriesEl = document.getElementById('featuredCategories');
    const parentCategoriesEl = document.getElementById('parentCategories');
    
    totalCategoriesEl.innerHTML = pagination.total;
    
    fetch('/admin/categories/list?per_page=1000', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const allCategories = data.data;
            const active = allCategories.filter(c => c.is_active).length;
            const inactive = allCategories.filter(c => !c.is_active).length;
            const featured = allCategories.filter(c => c.is_featured).length;
            const parents = allCategories.filter(c => !c.parent_id).length;
            
            activeCategoriesEl.innerHTML = active;
            inactiveCategoriesEl.innerHTML = inactive;
            featuredCategoriesEl.innerHTML = featured;
            parentCategoriesEl.innerHTML = parents;
        }
    });
}

function renderCategories(categories) {
    const tbody = document.getElementById('categoriesTableBody');
    
    if (categories.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">No categories found</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filters</p>
                        </div>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = categories.map(category => {
        const categoryImage = category.image_url 
            ? category.image_url
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(category.name) + '&size=48&background=0082C3&color=fff';
        
        const badges = [];
        if (category.is_featured) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs">⭐ Featured</span>');
        }
        if (category.show_in_menu) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">📋 Menu</span>');
        }
        
        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="category-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" data-id="${category.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <img src="${categoryImage}" class="w-12 h-12 rounded-lg border-2 border-gray-200 object-cover" alt="${category.name}">
                    <div>
                        <div class="font-semibold text-gray-900">${category.name}</div>
                        ${badges.length > 0 ? '<div class="flex gap-1 mt-1">' + badges.join('') + '</div>' : ''}
                        ${category.description ? `<div class="text-xs text-gray-500 line-clamp-1 mt-1">${category.description}</div>` : ''}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                ${category.parent ? `<span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-medium">${category.parent.name}</span>` : '<span class="text-gray-400 text-sm">Root</span>'}
            </td>
            <td class="px-6 py-4">
                <span class="text-sm text-gray-600">${category.products_count || 0}</span>
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${category.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${category.is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} rounded-md text-xs font-medium hover:opacity-80 transition-opacity">
                    <span class="w-1.5 h-1.5 rounded-full ${category.is_active ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${category.is_active ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${category.sort_order}</td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="viewCategory(${category.id})" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View category">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    <button onclick="editCategory(${category.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Edit category">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteCategory(${category.id})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete category">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `}).join('');
}


function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (pagination.last_page <= 1) { 
        container.innerHTML = `<div class="text-sm text-gray-600">Showing ${pagination.total} ${pagination.total === 1 ? 'category' : 'categories'}</div>`;
        return; 
    }
    
    let pages = '';
    const maxPages = 5;
    let startPage = Math.max(1, pagination.current_page - Math.floor(maxPages / 2));
    let endPage = Math.min(pagination.last_page, startPage + maxPages - 1);
    
    if (endPage - startPage < maxPages - 1) {
        startPage = Math.max(1, endPage - maxPages + 1);
    }
    
    if (startPage > 1) {
        pages += `<button onclick="loadCategories(1)" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">1</button>`;
        if (startPage > 2) pages += `<span class="px-2 text-gray-400">...</span>`;
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pages += `<button onclick="loadCategories(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'} rounded-lg transition-colors">${i}</button>`;
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) pages += `<span class="px-2 text-gray-400">...</span>`;
        pages += `<button onclick="loadCategories(${pagination.last_page})" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">${pagination.last_page}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">${((pagination.current_page - 1) * pagination.per_page) + 1}</span> to 
                <span class="font-medium">${Math.min(pagination.current_page * pagination.per_page, pagination.total)}</span> of 
                <span class="font-medium">${pagination.total}</span> categories
            </div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}

function loadParentCategories() {
    fetch('/admin/categories/list?per_page=1000', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const currentId = document.getElementById('categoryId').value;
            
            // Prepare options for searchable select
            const options = [
                { value: '', label: 'None (Root Category)' }
            ];
            
            data.data.forEach(category => {
                if (category.id != currentId) {
                    // Build hierarchical label
                    let label = category.name;
                    if (category.parent) {
                        label = category.parent.name + ' > ' + category.name;
                    }
                    
                    options.push({
                        value: category.id,
                        label: label
                    });
                }
            });
            
            // Store for later use
            allParentCategories = options;
            
            // Render all options initially
            renderParentCategoryOptions(allParentCategories);
        }
    });
}

function filterParentCategories(searchTerm) {
    const term = searchTerm.toLowerCase();
    const filtered = allParentCategories.filter(option => 
        option.label.toLowerCase().includes(term)
    );
    renderParentCategoryOptions(filtered);
}

function renderParentCategoryOptions(options) {
    const dropdown = document.getElementById('categoryParentDropdown');
    const container = dropdown.querySelector('.p-2');
    
    if (!container) return;
    
    container.innerHTML = '';
    
    if (options.length === 0) {
        container.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">No categories found</div>';
        return;
    }
    
    options.forEach(option => {
        const div = document.createElement('div');
        div.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded cursor-pointer';
        div.setAttribute('data-value', option.value);
        div.setAttribute('data-label', option.label);
        div.textContent = option.label;
        container.appendChild(div);
    });
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Category';
    document.getElementById('submitBtnText').textContent = 'Create Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryStatus').checked = true;
    document.getElementById('categoryFeatured').checked = false;
    document.getElementById('categoryShowInMenu').checked = true;
    document.getElementById('categorySortOrder').value = 0;
    
    document.getElementById('categoryImagePreview').classList.add('hidden');
    document.getElementById('categoryImageUrl').value = '';
    document.getElementById('categoryImageId').value = '';
    
    // Show upload area
    const uploadArea = document.getElementById('categoryImageUploadArea');
    if (uploadArea) uploadArea.classList.remove('hidden');
    
    // Reset parent category select
    resetParentCategorySelect();
    
    loadParentCategories();
    
    // Show modal with slide animation
    const modal = document.getElementById('categoryModal');
    const modalContent = document.getElementById('categoryModalContent');
    
    // Ensure starting position
    modalContent.style.transform = 'translateX(100%)';
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Force reflow
    modalContent.offsetHeight;
    
    // Slide in with double RAF for smooth animation
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            modalContent.style.transform = 'translateX(0)';
        });
    });
}

// Fill Demo Data Function
function fillDemoData() {
    // Sample demo data
    const demoCategories = [
        {
            name: 'Sports Equipment',
            slug: 'sports-equipment',
            description: 'High-quality sports equipment for all your athletic needs. From professional gear to beginner-friendly options.',
            sortOrder: 1,
            featured: true,
            showInMenu: true
        },
        {
            name: 'Outdoor Gear',
            slug: 'outdoor-gear',
            description: 'Essential outdoor equipment for camping, hiking, and adventure activities. Durable and weather-resistant products.',
            sortOrder: 2,
            featured: true,
            showInMenu: true
        },
        {
            name: 'Fitness & Gym',
            slug: 'fitness-gym',
            description: 'Complete range of fitness equipment and accessories for home and commercial gyms. Build your perfect workout space.',
            sortOrder: 3,
            featured: false,
            showInMenu: true
        },
        {
            name: 'Water Sports',
            slug: 'water-sports',
            description: 'Dive into adventure with our water sports collection. Swimming, surfing, diving, and more aquatic activities.',
            sortOrder: 4,
            featured: true,
            showInMenu: true
        },
        {
            name: 'Team Sports',
            slug: 'team-sports',
            description: 'Equipment and apparel for football, basketball, cricket, volleyball, and other team sports. Play together, win together.',
            sortOrder: 5,
            featured: false,
            showInMenu: true
        },
        {
            name: 'Cycling & Bikes',
            slug: 'cycling-bikes',
            description: 'Premium bicycles and cycling accessories for road, mountain, and city riding. Ride with confidence.',
            sortOrder: 6,
            featured: true,
            showInMenu: true
        },
        {
            name: 'Running & Athletics',
            slug: 'running-athletics',
            description: 'Professional running shoes, apparel, and accessories for track, trail, and marathon runners.',
            sortOrder: 7,
            featured: false,
            showInMenu: true
        },
        {
            name: 'Yoga & Wellness',
            slug: 'yoga-wellness',
            description: 'Yoga mats, blocks, straps, and wellness products for mindful practice and healthy living.',
            sortOrder: 8,
            featured: true,
            showInMenu: true
        }
    ];
    
    // Pick a random demo category
    const randomCategory = demoCategories[Math.floor(Math.random() * demoCategories.length)];
    
    // Fill the form
    document.getElementById('categoryName').value = randomCategory.name;
    document.getElementById('categorySlug').value = randomCategory.slug;
    document.getElementById('categoryDescription').value = randomCategory.description;
    document.getElementById('categorySortOrder').value = randomCategory.sortOrder;
    document.getElementById('categoryFeatured').checked = randomCategory.featured;
    document.getElementById('categoryShowInMenu').checked = randomCategory.showInMenu;
    document.getElementById('categoryStatus').checked = true;
    
    // Show success notification
    showNotification('Demo data filled successfully! You can now modify and save.', 'success');
    
    // Add a subtle highlight animation to the form
    const form = document.getElementById('categoryForm');
    form.style.animation = 'pulse 0.5s ease-in-out';
    setTimeout(() => {
        form.style.animation = '';
    }, 500);
}

function closeModal() {
    const modal = document.getElementById('categoryModal');
    const modalContent = document.getElementById('categoryModalContent');
    
    // Ultra smooth slide out
    modalContent.style.transition = 'transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1)';
    modalContent.style.transform = 'translateX(100%)';
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'categoryModal') {
        closeModal();
    }
}

function autoGenerateSEO() {
    const categoryName = document.getElementById('categoryName').value;
    const categoryDescription = document.getElementById('categoryDescription').value;
    
    if (!categoryName) {
        showNotification('Please enter category name first', 'error');
        return;
    }
    
    // Generate Meta Title (60 chars max for SEO)
    const metaTitle = categoryName.length > 55 
        ? categoryName.substring(0, 55) + '...' 
        : categoryName + ' - Shop Now';
    
    // Generate Meta Description (160 chars max for SEO)
    let metaDescription = '';
    if (categoryDescription) {
        metaDescription = categoryDescription.length > 155
            ? categoryDescription.substring(0, 155) + '...'
            : categoryDescription;
    } else {
        metaDescription = `Browse our collection of ${categoryName}. Find the best ${categoryName.toLowerCase()} products at great prices. Shop now!`;
    }
    
    // Generate Meta Keywords (from category name)
    const keywords = [
        categoryName.toLowerCase(),
        categoryName.toLowerCase() + ' online',
        'buy ' + categoryName.toLowerCase(),
        categoryName.toLowerCase() + ' shop',
        categoryName.toLowerCase() + ' products'
    ].join(', ');
    
    // Set values
    document.getElementById('categoryMetaTitle').value = metaTitle;
    document.getElementById('categoryMetaDescription').value = metaDescription;
    document.getElementById('categoryMetaKeywords').value = keywords;
    
    showNotification('SEO fields generated successfully!', 'success');
}

function viewCategory(id) {
    fetch(`/admin/categories/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const category = data.data;
            showViewModal(category);
        }
    });
}

// Beautiful View Modal
function showViewModal(category) {
    const modalId = 'viewModal_' + Date.now();
    
    const statusBadge = category.is_active 
        ? '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Active</span>'
        : '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800"><span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>Inactive</span>';
    
    const featuredBadge = category.is_featured 
        ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">⭐ Featured</span>'
        : '';
    
    const menuBadge = category.show_in_menu 
        ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">📋 In Menu</span>'
        : '';
    
    const modalHTML = `
        <div id="${modalId}" class="fixed inset-0 z-[60] overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div class="view-modal-backdrop fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
                
                <!-- Center alignment -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <!-- Modal panel -->
                <div class="view-modal-panel inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <!-- Header with gradient -->
                    <div class="bg-gradient-to-r from-[#0082C3] to-[#006ba3] px-6 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-lg">
                                    ${category.image_url 
                                        ? `<img src="${category.image_url}" class="w-full h-full object-cover rounded-xl" alt="${category.name}">`
                                        : `<svg class="w-8 h-8 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                        </svg>`
                                    }
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">${category.name}</h3>
                                    <p class="text-blue-100 text-sm mt-1">Category Details</p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('${modalId}').querySelector('.view-modal-close-btn').click()" class="text-white hover:text-gray-200 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            ${statusBadge}
                            ${featuredBadge}
                            ${menuBadge}
                        </div>
                        
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b pb-2">Basic Information</h4>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Category Name</label>
                                        <p class="text-base text-gray-900 font-medium mt-1">${category.name}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Slug</label>
                                        <p class="text-sm text-gray-700 font-mono bg-gray-50 px-3 py-2 rounded-lg mt-1">${category.slug}</p>
                                    </div>
                                    
                                    ${category.description ? `
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Description</label>
                                        <p class="text-sm text-gray-700 mt-1 leading-relaxed">${category.description}</p>
                                    </div>
                                    ` : ''}
                                    
                                    ${category.parent ? `
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Parent Category</label>
                                        <p class="text-base text-gray-900 font-medium mt-1">${category.parent.name}</p>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            
                            <!-- Stats & Settings -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b pb-2">Statistics & Settings</h4>
                                
                                <div class="space-y-3">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                                        <label class="text-xs font-medium text-blue-700 uppercase">Products Count</label>
                                        <p class="text-3xl font-bold text-blue-900 mt-1">${category.products_count || 0}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Sort Order</label>
                                        <p class="text-base text-gray-900 font-medium mt-1">${category.sort_order}</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <label class="text-xs font-medium text-gray-500">Created</label>
                                        </div>
                                        <p class="text-sm text-gray-700 font-medium">${formatDateTime(category.created_at)}</p>
                                    </div>
                                    
                                    ${category.updated_at ? `
                                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            <label class="text-xs font-medium text-gray-500">Last Updated</label>
                                        </div>
                                        <p class="text-sm text-gray-700 font-medium">${formatDateTime(category.updated_at)}</p>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Images Section -->
                        ${category.image_url || category.banner_url || category.icon_url ? `
                        <div class="mt-6 pt-6 border-t">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Images</h4>
                            <div class="grid grid-cols-3 gap-4">
                                ${category.image_url ? `
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase block mb-2">Category Image</label>
                                    <img src="${category.image_url}" class="w-full h-32 object-cover rounded-lg shadow-md" alt="Category">
                                </div>
                                ` : ''}
                                ${category.banner_url ? `
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase block mb-2">Banner</label>
                                    <img src="${category.banner_url}" class="w-full h-32 object-cover rounded-lg shadow-md" alt="Banner">
                                </div>
                                ` : ''}
                                ${category.icon_url ? `
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase block mb-2">Icon</label>
                                    <img src="${category.icon_url}" class="w-full h-32 object-cover rounded-lg shadow-md" alt="Icon">
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        ` : ''}
                        
                        <!-- SEO Section -->
                        ${category.meta_title || category.meta_description || category.meta_keywords ? `
                        <div class="mt-6 pt-6 border-t">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">SEO Information</h4>
                            <div class="space-y-3 bg-gray-50 rounded-xl p-4">
                                ${category.meta_title ? `
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Meta Title</label>
                                    <p class="text-sm text-gray-700 mt-1">${category.meta_title}</p>
                                </div>
                                ` : ''}
                                ${category.meta_description ? `
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Meta Description</label>
                                    <p class="text-sm text-gray-700 mt-1">${category.meta_description}</p>
                                </div>
                                ` : ''}
                                ${category.meta_keywords ? `
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Meta Keywords</label>
                                    <p class="text-sm text-gray-700 mt-1">${category.meta_keywords}</p>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" onclick="editCategory(${category.id}); document.getElementById('${modalId}').querySelector('.view-modal-close-btn').click();" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Category
                        </button>
                        <button type="button" class="view-modal-close-btn inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add to DOM
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = document.getElementById(modalId);
    
    // Show modal with animation
    requestAnimationFrame(function() {
        modal.classList.remove('hidden');
        requestAnimationFrame(function() {
            modal.querySelector('.view-modal-backdrop').style.opacity = '1';
            modal.querySelector('.view-modal-panel').style.transform = 'scale(1)';
            modal.querySelector('.view-modal-panel').style.opacity = '1';
        });
    });
    
    // Close button
    modal.querySelector('.view-modal-close-btn').addEventListener('click', function() {
        closeViewModal();
    });
    
    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target.classList.contains('view-modal-backdrop')) {
            closeViewModal();
        }
    });
    
    // Close on Escape key
    const escapeHandler = function(e) {
        if (e.key === 'Escape') {
            closeViewModal();
        }
    };
    document.addEventListener('keydown', escapeHandler);
    
    function closeViewModal() {
        const backdrop = modal.querySelector('.view-modal-backdrop');
        const panel = modal.querySelector('.view-modal-panel');
        
        backdrop.style.opacity = '0';
        panel.style.transform = 'scale(0.95)';
        panel.style.opacity = '0';
        
        setTimeout(function() {
            modal.remove();
            document.removeEventListener('keydown', escapeHandler);
        }, 300);
    }
}

// Add view modal styles
if (!document.getElementById('viewModalStyles')) {
    const style = document.createElement('style');
    style.id = 'viewModalStyles';
    style.textContent = `
        .view-modal-backdrop {
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }
        .view-modal-panel {
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    `;
    document.head.appendChild(style);
}

// Format date/time professionally
function formatDateTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    // Relative time for recent dates
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    
    // Format: Mar 4, 2026 at 11:06 PM
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const month = months[date.getMonth()];
    const day = date.getDate();
    const year = date.getFullYear();
    
    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    
    return `${month} ${day}, ${year} at ${hours}:${minutes} ${ampm}`;
}


function editCategory(id) {
    fetch(`/admin/categories/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const category = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('submitBtnText').textContent = 'Update Category';
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categorySlug').value = category.slug;
            document.getElementById('categoryDescription').value = category.description || '';
            document.getElementById('categorySortOrder').value = category.sort_order;
            document.getElementById('categoryStatus').checked = category.is_active;
            document.getElementById('categoryFeatured').checked = category.is_featured;
            document.getElementById('categoryShowInMenu').checked = category.show_in_menu;
            
            if (category.image_url) {
                document.getElementById('categoryImageUrl').value = category.image_url;
                document.getElementById('categoryImageId').value = category.image_id || '';
                document.getElementById('categoryImagePreviewImg').src = category.image_url;
                document.getElementById('categoryImagePreview').classList.remove('hidden');
            }
            
            if (category.banner_url) {
                document.getElementById('categoryBannerUrl').value = category.banner_url;
                document.getElementById('categoryBannerId').value = category.banner_id || '';
                document.getElementById('categoryBannerPreviewImg').src = category.banner_url;
                document.getElementById('categoryBannerPreview').classList.remove('hidden');
            }
            
            if (category.icon_url) {
                document.getElementById('categoryIconUrl').value = category.icon_url;
                document.getElementById('categoryIconId').value = category.icon_id || '';
                document.getElementById('categoryIconPreviewImg').src = category.icon_url;
                document.getElementById('categoryIconPreview').classList.remove('hidden');
            }
            
            document.getElementById('categoryMetaTitle').value = category.meta_title || '';
            document.getElementById('categoryMetaDescription').value = category.meta_description || '';
            document.getElementById('categoryMetaKeywords').value = category.meta_keywords || '';
            
            // Load parent categories first, then set value
            loadParentCategories();
            
            // Set parent category value after options are loaded
            setTimeout(() => {
                setParentCategoryValue(category.parent_id || '');
            }, 100);
            
            // Show modal with slide animation
            const modal = document.getElementById('categoryModal');
            const modalContent = document.getElementById('categoryModalContent');
            
            // Ensure starting position
            modalContent.style.transform = 'translateX(100%)';
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Force reflow
            modalContent.offsetHeight;
            
            // Slide in with double RAF for smooth animation
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    modalContent.style.transform = 'translateX(0)';
                });
            });
        }
    });
}

document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous errors
    clearValidationErrors();
    
    const categoryId = document.getElementById('categoryId').value;
    const url = categoryId ? `/admin/categories/${categoryId}` : '/admin/categories';
    const method = categoryId ? 'PUT' : 'POST';
    
    const formData = {
        name: document.getElementById('categoryName').value,
        slug: document.getElementById('categorySlug').value,
        description: document.getElementById('categoryDescription').value,
        parent_id: document.getElementById('categoryParent').value || null,
        sort_order: document.getElementById('categorySortOrder').value,
        is_active: document.getElementById('categoryStatus').checked,
        is_featured: document.getElementById('categoryFeatured').checked,
        show_in_menu: document.getElementById('categoryShowInMenu').checked,
        image_url: document.getElementById('categoryImageUrl').value,
        image_id: document.getElementById('categoryImageId').value,
        image_responsive: document.getElementById('categoryImageResponsive').value,
        image_width: document.getElementById('categoryImageWidth').value,
        image_height: document.getElementById('categoryImageHeight').value,
        banner_url: document.getElementById('categoryBannerUrl')?.value || null,
        banner_id: document.getElementById('categoryBannerId')?.value || null,
        icon_url: document.getElementById('categoryIconUrl')?.value || null,
        icon_id: document.getElementById('categoryIconId')?.value || null,
        meta_title: document.getElementById('categoryMetaTitle').value,
        meta_description: document.getElementById('categoryMetaDescription').value,
        meta_keywords: document.getElementById('categoryMetaKeywords').value,
    };
    
    if (method === 'PUT') {
        formData._method = 'PUT';
    }

    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtnText').classList.add('hidden');
    document.getElementById('submitBtnLoading').classList.remove('hidden');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(res => {
        if (!res.ok && res.status === 422) {
            return res.json().then(data => {
                throw { validation: true, errors: data.errors || {} };
            });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal();
            loadCategories(currentPage);
        } else {
            showNotification(data.message || 'Operation failed', 'error');
        }
    })
    .catch(err => {
        if (err.validation) {
            showValidationErrors(err.errors);
        } else {
            showNotification('An error occurred. Please try again.', 'error');
        }
    })
    .finally(() => {
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('submitBtnText').classList.remove('hidden');
        document.getElementById('submitBtnLoading').classList.add('hidden');
    });
});

// Clear all validation errors
function clearValidationErrors() {
    const errorElements = document.querySelectorAll('[id$="Error"]');
    errorElements.forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    
    const inputElements = document.querySelectorAll('input, textarea, select');
    inputElements.forEach(el => {
        el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    });
}

// Show validation errors inline
function showValidationErrors(errors) {
    const fieldMapping = {
        'name': 'categoryName',
        'slug': 'categorySlug',
        'description': 'categoryDescription',
        'parent_id': 'categoryParent',
        'sort_order': 'categorySortOrder',
        'meta_title': 'categoryMetaTitle',
        'meta_description': 'categoryMetaDescription',
        'meta_keywords': 'categoryMetaKeywords'
    };
    
    let firstErrorField = null;
    
    Object.keys(errors).forEach(field => {
        const fieldId = fieldMapping[field];
        if (fieldId) {
            const inputElement = document.getElementById(fieldId);
            const errorElement = document.getElementById(fieldId + 'Error');
            
            if (inputElement && errorElement) {
                // Show error message
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
                
                // Add error styling to input
                inputElement.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                
                // Track first error field for focus
                if (!firstErrorField) {
                    firstErrorField = inputElement;
                }
            }
        }
    });
    
    // Focus on first error field
    if (firstErrorField) {
        firstErrorField.focus();
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function toggleStatus(id) {
    fetch(`/admin/categories/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        showNotification(data.message, data.success ? 'success' : 'error');
        if (data.success) loadCategories(currentPage);
    });
}

function deleteCategory(id) {
    showConfirmDialog({
        title: 'Delete Category',
        message: 'Are you sure you want to delete this category? This action cannot be undone.',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        type: 'danger',
        onConfirm: function() {
            fetch(`/admin/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                showNotification(data.message, data.success ? 'success' : 'error');
                if (data.success) loadCategories(currentPage);
            });
        }
    });
}

// Reusable Confirmation Dialog
function showConfirmDialog(options) {
    const defaults = {
        title: 'Confirm Action',
        message: 'Are you sure you want to proceed?',
        confirmText: 'Confirm',
        cancelText: 'Cancel',
        type: 'warning', // 'warning', 'danger', 'info'
        onConfirm: null,
        onCancel: null
    };
    
    const config = { ...defaults, ...options };
    
    // Create dialog HTML
    const dialogId = 'confirmDialog_' + Date.now();
    const typeColors = {
        warning: { bg: 'bg-yellow-50', icon: 'text-yellow-600', btn: 'bg-yellow-600 hover:bg-yellow-700', avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=warning&backgroundColor=fef3c7' },
        danger: { bg: 'bg-red-50', icon: 'text-red-600', btn: 'bg-red-600 hover:bg-red-700', avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=danger&backgroundColor=fee2e2' },
        info: { bg: 'bg-blue-50', icon: 'text-blue-600', btn: 'bg-blue-600 hover:bg-blue-700', avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=info&backgroundColor=dbeafe' }
    };
    
    const colors = typeColors[config.type] || typeColors.warning;
    
    const iconPaths = {
        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
        danger: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
    };
    
    const dialogHTML = `
        <div id="${dialogId}" class="fixed inset-0 z-[60] overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div class="dialog-backdrop fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
                
                <!-- Center alignment trick -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <!-- Dialog panel -->
                <div class="dialog-panel inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Avatar Section -->
                    <div class="flex justify-center pt-8 pb-4">
                        <div class="dialog-avatar relative">
                            <div class="w-24 h-24 rounded-full ${colors.bg} p-2 shadow-lg">
                                <img src="${colors.avatar}" alt="Avatar" class="w-full h-full rounded-full">
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-10 h-10 ${colors.bg} rounded-full flex items-center justify-center shadow-md border-4 border-white">
                                <svg class="h-5 w-5 ${colors.icon}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${iconPaths[config.type] || iconPaths.warning}
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 pb-4">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                ${config.title}
                            </h3>
                            <div class="mt-2">
                                <p class="text-base text-gray-600 leading-relaxed">
                                    ${config.message}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-center gap-3">
                        <button type="button" id="${dialogId}_cancel" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:w-auto transition-all">
                            ${config.cancelText}
                        </button>
                        <button type="button" id="${dialogId}_confirm" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-3 ${colors.btn} text-base font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto transition-all hover:shadow-md">
                            ${config.confirmText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add to DOM
    document.body.insertAdjacentHTML('beforeend', dialogHTML);
    const dialog = document.getElementById(dialogId);
    
    // Show dialog with animation
    requestAnimationFrame(function() {
        dialog.classList.remove('hidden');
        requestAnimationFrame(function() {
            dialog.querySelector('.dialog-backdrop').style.opacity = '1';
            dialog.querySelector('.dialog-panel').style.transform = 'scale(1) rotate(0deg)';
            dialog.querySelector('.dialog-panel').style.opacity = '1';
            dialog.querySelector('.dialog-avatar').style.transform = 'scale(1) rotate(0deg)';
            dialog.querySelector('.dialog-avatar').style.opacity = '1';
        });
    });
    
    // Confirm button
    document.getElementById(dialogId + '_confirm').addEventListener('click', function() {
        closeDialog();
        if (config.onConfirm) config.onConfirm();
    });
    
    // Cancel button
    document.getElementById(dialogId + '_cancel').addEventListener('click', function() {
        closeDialog();
        if (config.onCancel) config.onCancel();
    });
    
    // Close on backdrop click
    dialog.addEventListener('click', function(e) {
        if (e.target === dialog || e.target.classList.contains('dialog-backdrop')) {
            closeDialog();
            if (config.onCancel) config.onCancel();
        }
    });
    
    // Close on Escape key
    const escapeHandler = function(e) {
        if (e.key === 'Escape') {
            closeDialog();
            if (config.onCancel) config.onCancel();
        }
    };
    document.addEventListener('keydown', escapeHandler);
    
    function closeDialog() {
        const backdrop = dialog.querySelector('.dialog-backdrop');
        const panel = dialog.querySelector('.dialog-panel');
        const avatar = dialog.querySelector('.dialog-avatar');
        
        backdrop.style.opacity = '0';
        panel.style.transform = 'scale(0.8) rotate(-5deg)';
        panel.style.opacity = '0';
        avatar.style.transform = 'scale(0) rotate(-180deg)';
        avatar.style.opacity = '0';
        
        setTimeout(function() {
            dialog.remove();
            document.removeEventListener('keydown', escapeHandler);
        }, 300);
    }
}

// Add CSS animations
if (!document.getElementById('confirmDialogStyles')) {
    const style = document.createElement('style');
    style.id = 'confirmDialogStyles';
    style.textContent = `
        .dialog-backdrop {
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }
        .dialog-panel {
            opacity: 0;
            transform: scale(0.8) rotate(-5deg);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .dialog-avatar {
            opacity: 0;
            transform: scale(0) rotate(-180deg);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    `;
    document.head.appendChild(style);
}


function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedCategories.add(parseInt(cb.dataset.id));
        } else {
            selectedCategories.delete(parseInt(cb.dataset.id));
        }
    });
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.category-checkbox:checked');
    const bulkContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCategories.clear();
    checkboxes.forEach(cb => selectedCategories.add(parseInt(cb.dataset.id)));
    
    if (selectedCategories.size > 0) {
        bulkContainer.classList.remove('hidden');
        bulkContainer.classList.add('flex', 'items-center', 'gap-2');
        selectedCount.textContent = `${selectedCategories.size} selected`;
    } else {
        bulkContainer.classList.add('hidden');
        bulkContainer.classList.remove('flex', 'items-center', 'gap-2');
    }
    
    document.getElementById('selectAll').checked = checkboxes.length > 0 && checkboxes.length === document.querySelectorAll('.category-checkbox').length;
}

function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    if (!action) {
        showNotification('Please select an action', 'error');
        return;
    }
    
    if (selectedCategories.size === 0) {
        showNotification('Please select at least one category', 'error');
        return;
    }
    
    if (action === 'delete' && !confirm(`Are you sure you want to delete ${selectedCategories.size} categories?`)) {
        return;
    }
    
    fetch('/admin/categories/bulk-action', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            ids: Array.from(selectedCategories)
        })
    })
    .then(res => res.json())
    .then(data => {
        showNotification(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            selectedCategories.clear();
            document.getElementById('bulkActionSelect').value = '';
            loadCategories(currentPage);
        }
    });
}

function openImageKit(type) {
    currentImageType = type;
    
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Get progress elements
        const progressContainer = document.getElementById('categoryImageProgress');
        const progressBar = document.getElementById('categoryImageProgressBar');
        const progressText = document.getElementById('categoryImageProgressText');
        const sizeText = document.getElementById('categoryImageSize');
        const uploadArea = document.getElementById('categoryImageUploadArea');
        
        // Hide upload area, show progress
        if (uploadArea) uploadArea.classList.add('hidden');
        
        // Reset and show progress container
        progressBar.style.width = '0%';
        progressText.textContent = '0%';
        sizeText.textContent = '0 KB / ' + formatFileSize(file.size);
        progressContainer.classList.remove('hidden');
        
        // Small delay to ensure UI updates before upload starts
        setTimeout(function() {
            // Upload to ImageKit via server with progress tracking
            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', 'categories');
            formData.append('type', type);
            
            const xhr = new XMLHttpRequest();
            
            // Track when upload starts
            xhr.upload.addEventListener('loadstart', function(e) {
                progressBar.style.width = '0%';
                progressText.textContent = '0%';
                sizeText.textContent = '0 KB / ' + formatFileSize(file.size);
            });
            
            // Track upload progress with smooth updates
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentage = Math.round((e.loaded / e.total) * 100);
                    
                    // Smooth transition for progress bar
                    requestAnimationFrame(function() {
                        progressBar.style.width = percentage + '%';
                        progressText.textContent = percentage + '%';
                        sizeText.textContent = formatFileSize(e.loaded) + ' / ' + formatFileSize(e.total);
                    });
                }
            });
            
            // Handle completion
            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        
                        if (data.success) {
                            const imageUrl = data.data.url;
                            const fileId = data.data.fileId;
                            const responsiveUrls = data.data.responsiveUrls;
                            
                            // Store all responsive URLs
                            document.getElementById('categoryImageUrl').value = responsiveUrls.mobile_webp || imageUrl;
                            document.getElementById('categoryImageId').value = fileId;
                            document.getElementById('categoryImageResponsive').value = JSON.stringify(responsiveUrls);
                            
                            // Store dimensions if available
                            if (data.data.width) document.getElementById('categoryImageWidth').value = data.data.width;
                            if (data.data.height) document.getElementById('categoryImageHeight').value = data.data.height;
                            
                            // Show preview with responsive image
                            document.getElementById('categoryImagePreviewImg').src = responsiveUrls.card_webp || responsiveUrls.mobile_webp || imageUrl;
                            document.getElementById('categoryImagePreview').classList.remove('hidden');
                            
                            // Hide progress
                            progressContainer.classList.add('hidden');
                            progressBar.style.width = '0%';
                            
                            showNotification('Image uploaded successfully! Multiple sizes generated for all devices.', 'success');
                        } else {
                            progressContainer.classList.add('hidden');
                            progressBar.style.width = '0%';
                            if (uploadArea) uploadArea.classList.remove('hidden');
                            showNotification(data.message || 'Upload failed', 'error');
                        }
                    } catch (error) {
                        progressContainer.classList.add('hidden');
                        progressBar.style.width = '0%';
                        if (uploadArea) uploadArea.classList.remove('hidden');
                        showNotification('Upload failed. Invalid response.', 'error');
                    }
                } else {
                    progressContainer.classList.add('hidden');
                    progressBar.style.width = '0%';
                    if (uploadArea) uploadArea.classList.remove('hidden');
                    showNotification('Upload failed. Server error.', 'error');
                }
            });
            
            // Handle errors
            xhr.addEventListener('error', function() {
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                if (uploadArea) uploadArea.classList.remove('hidden');
                showNotification('Upload failed. Network error.', 'error');
            });
            
            // Send request
            xhr.open('POST', '/api/imagekit-upload');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        }, 50);
    };
    input.click();
}

// Helper function to format file size
// Helper function to format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Remove uploaded image
function removeImage() {
    // Hide preview
    document.getElementById('categoryImagePreview').classList.add('hidden');
    
    // Clear values
    document.getElementById('categoryImageUrl').value = '';
    document.getElementById('categoryImageId').value = '';
    
    // Show upload area again
    const uploadArea = document.getElementById('categoryImageUploadArea');
    if (uploadArea) {
        uploadArea.classList.remove('hidden');
    }
    
    showNotification('Image removed successfully', 'success');
}

function showNotification(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3 ${colors[type] || colors.success} text-white transform transition-all duration-300 translate-x-0`;
    notification.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${icons[type] || icons.success}
        </svg>
        <span class="font-medium">${message}</span>
    `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('categoryModal').classList.contains('hidden')) {
        closeModal();
    }
});
</script>
@endpush
