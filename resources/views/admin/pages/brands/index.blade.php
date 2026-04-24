@extends('admin.layouts.app')

@section('title', 'Brands Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Brands</h1>
            <p class="text-sm text-gray-600 mt-1">Manage product brands and manufacturers</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Brand
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Brands</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalBrands">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeBrands">
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
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="inactiveBrands">
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
                    <p class="text-sm font-medium text-gray-600">With Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="brandsWithProducts">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
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
                    placeholder="Search by name or slug..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="statusFilter" onchange="loadBrands()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPageSelect" onchange="loadBrands()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Brands Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Brand</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Website</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="brandsTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Skeleton Loader -->
                    <tr class="skeleton-row">
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-4 rounded"></div></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-12 h-12 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-32 mb-2"></div>
                                    <div class="skeleton-text h-3 w-24"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-24"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-16"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-8"></div></td>
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


<!-- Add/Edit Modal -->
<div id="brandModal" class="hidden fixed inset-0 z-50 overflow-hidden" onclick="closeModalOnBackdrop(event)">
    <!-- Backdrop -->
    <div id="brandModalBackdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <!-- Modal Content - No animations -->
    <div id="brandModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Brand</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new product brand</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="fillDemoData()" class="px-3 py-1.5 text-xs font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <form id="brandForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4 space-y-4">
                <input type="hidden" id="brandId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Brand Name <span class="text-red-500">*</span></label>
                    <input type="text" id="brandName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Nike" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-gray-500 text-xs">(Auto-generated)</span></label>
                    <input type="text" id="brandSlug" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="nike">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea id="brandDescription" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Brief description of the brand"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Website URL</label>
                    <input type="url" id="brandWebsite" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="https://example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand Logo (ImageKit)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#0082C3] transition-colors">
                        <div id="brandLogoPreview" class="hidden mb-3">
                            <img id="brandLogoPreviewImg" src="" class="w-24 h-24 object-contain mx-auto rounded-lg">
                        </div>
                        <button type="button" onclick="openImageKit()" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload Logo
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Recommended: 200x200px</p>
                        <input type="hidden" id="brandLogoUrl">
                        <input type="hidden" id="brandLogoId">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                        <input type="number" id="brandSortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer mt-8">
                            <input type="checkbox" id="brandStatus" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </div>
        </form>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="button" id="saveBrandBtn" onclick="saveBrand()" class="px-4 py-2.5 text-sm font-medium text-white bg-[#0082C3] rounded-lg hover:bg-[#006ba3] transition-colors inline-flex items-center gap-2">
                <svg id="saveBtnLoader" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="submitBtnText">Create Brand</span>
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/imagekit-javascript/dist/imagekit.min.js"></script>

<script>
const imagekit = new ImageKit({
    publicKey: "{{ config('imagekit.public_key') }}",
    urlEndpoint: "{{ config('imagekit.url_endpoint') }}",
    authenticationEndpoint: "{{ route('imagekit.auth') }}"
});

let currentPage = 1;
let searchTimeout;
let selectedBrands = new Set();

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('searchInput') && document.getElementById('brandsTableBody')) {
        loadBrands();
    }
    
    document.getElementById('brandName')?.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('brandSlug').value = slug;
    });
});

function loadBrands(page = 1) {
    currentPage = page;
    
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const perPageSelect = document.getElementById('perPageSelect');
    
    const search = searchInput.value;
    const status = statusFilter.value;
    const perPage = perPageSelect.value;

    const url = `/admin/brands/list?page=${page}&search=${search}&status=${status}&per_page=${perPage}`;

    const tbody = document.getElementById('brandsTableBody');
    const skeletonRows = tbody.querySelectorAll('.skeleton-row');
    skeletonRows.forEach(row => row.style.display = '');

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderBrands(data.data);
            renderPagination(data.pagination);
            updateStats(data.data, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error loading brands:', error);
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-600">Error loading brands. Please refresh the page.</td></tr>`;
    });
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadBrands(1), 300);
}

function renderBrands(brands) {
    const tbody = document.getElementById('brandsTableBody');
    
    if (brands.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No brands found</td></tr>`;
        return;
    }

    tbody.innerHTML = brands.map(brand => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="brand-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" value="${brand.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    ${brand.logo_url ? `<img src="${brand.logo_url}" class="w-12 h-12 rounded-lg object-contain bg-gray-50 border border-gray-200" alt="${brand.name}">` : `<div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-medium">${brand.name.substring(0, 2).toUpperCase()}</div>`}
                    <div>
                        <p class="text-sm font-medium text-gray-900">${brand.name}</p>
                        <p class="text-xs text-gray-500">${brand.slug}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                ${brand.website ? `<a href="${brand.website}" target="_blank" class="text-sm text-blue-600 hover:underline">${new URL(brand.website).hostname}</a>` : '<span class="text-sm text-gray-400">-</span>'}
            </td>
            <td class="px-6 py-4">
                <span class="text-sm text-gray-900">${brand.products_count || 0}</span>
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${brand.id})" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium ${brand.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${brand.status ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4">
                <span class="text-sm text-gray-900">${brand.sort_order || 0}</span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editBrand(${brand.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteBrand(${brand.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
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
            <p class="text-sm text-gray-700">Showing <span class="font-medium">${((pagination.current_page - 1) * pagination.per_page) + 1}</span> to <span class="font-medium">${Math.min(pagination.current_page * pagination.per_page, pagination.total)}</span> of <span class="font-medium">${pagination.total}</span> results</p>
            <div class="flex gap-1">
                <button onclick="loadBrands(${pagination.current_page - 1})" ${pagination.current_page === 1 ? 'disabled' : ''} class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                ${pages.map(page => page === '...' ? `<span class="px-3 py-1.5 text-sm">...</span>` : `<button onclick="loadBrands(${page})" class="px-3 py-1.5 text-sm border rounded-lg ${page === pagination.current_page ? 'bg-[#0082C3] text-white border-[#0082C3]' : 'border-gray-300 hover:bg-gray-50'}">${page}</button>`).join('')}
                <button onclick="loadBrands(${pagination.current_page + 1})" ${pagination.current_page === pagination.last_page ? 'disabled' : ''} class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
            </div>
        </div>
    `;
}

function updateStats(brands, pagination) {
    document.getElementById('totalBrands').textContent = pagination.total;
    document.getElementById('activeBrands').textContent = brands.filter(b => b.status).length;
    document.getElementById('inactiveBrands').textContent = brands.filter(b => !b.status).length;
    document.getElementById('brandsWithProducts').textContent = brands.filter(b => b.products_count > 0).length;
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Brand';
    document.getElementById('submitBtnText').textContent = 'Create Brand';
    document.getElementById('brandForm').reset();
    document.getElementById('brandId').value = '';
    document.getElementById('brandStatus').checked = true;
    document.getElementById('brandLogoPreview').classList.add('hidden');
    document.getElementById('brandLogoUrl').value = '';
    document.getElementById('brandLogoId').value = '';
    
    // Instant show - no animation
    document.getElementById('brandModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function fillDemoData() {
    const demoData = [
        {
            name: 'Nike',
            slug: 'nike',
            description: 'Just Do It - Leading athletic footwear and apparel brand',
            website: 'https://www.nike.com',
            logo: 'https://ik.imagekit.io/demo/nike-logo.png'
        },
        {
            name: 'Adidas',
            slug: 'adidas',
            description: 'Impossible is Nothing - German multinational sports brand',
            website: 'https://www.adidas.com',
            logo: 'https://ik.imagekit.io/demo/adidas-logo.png'
        },
        {
            name: 'Apple',
            slug: 'apple',
            description: 'Think Different - Premium technology and electronics brand',
            website: 'https://www.apple.com',
            logo: 'https://ik.imagekit.io/demo/apple-logo.png'
        },
        {
            name: 'Samsung',
            slug: 'samsung',
            description: 'Do What You Can\'t - South Korean electronics giant',
            website: 'https://www.samsung.com',
            logo: 'https://ik.imagekit.io/demo/samsung-logo.png'
        },
        {
            name: 'Sony',
            slug: 'sony',
            description: 'Be Moved - Japanese electronics and entertainment brand',
            website: 'https://www.sony.com',
            logo: 'https://ik.imagekit.io/demo/sony-logo.png'
        }
    ];
    
    const randomBrand = demoData[Math.floor(Math.random() * demoData.length)];
    
    document.getElementById('brandName').value = randomBrand.name;
    document.getElementById('brandSlug').value = randomBrand.slug;
    document.getElementById('brandDescription').value = randomBrand.description;
    document.getElementById('brandWebsite').value = randomBrand.website;
    document.getElementById('brandSortOrder').value = Math.floor(Math.random() * 10);
    document.getElementById('brandStatus').checked = true;
    
    // Optional: Set demo logo
    if (randomBrand.logo) {
        document.getElementById('brandLogoUrl').value = randomBrand.logo;
        document.getElementById('brandLogoPreviewImg').src = randomBrand.logo;
        document.getElementById('brandLogoPreview').classList.remove('hidden');
    }
}

function editBrand(id) {
    fetch(`/admin/brands/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const brand = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Brand';
            document.getElementById('submitBtnText').textContent = 'Update Brand';
            document.getElementById('brandId').value = brand.id;
            document.getElementById('brandName').value = brand.name;
            document.getElementById('brandSlug').value = brand.slug;
            document.getElementById('brandDescription').value = brand.description || '';
            document.getElementById('brandWebsite').value = brand.website || '';
            document.getElementById('brandSortOrder').value = brand.sort_order || 0;
            document.getElementById('brandStatus').checked = brand.status;
            
            if (brand.logo_url) {
                document.getElementById('brandLogoUrl').value = brand.logo_url;
                document.getElementById('brandLogoId').value = brand.logo_id || '';
                document.getElementById('brandLogoPreviewImg').src = brand.logo_url;
                document.getElementById('brandLogoPreview').classList.remove('hidden');
            } else {
                document.getElementById('brandLogoPreview').classList.add('hidden');
            }
            
            // Instant show - no animation
            document.getElementById('brandModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    });
}

function closeModal() {
    // Instant hide - no animation
    document.getElementById('brandModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'brandModal') {
        closeModal();
    }
}

function saveBrand() {
    const id = document.getElementById('brandId').value;
    const url = id ? `/admin/brands/${id}` : '/admin/brands';
    const method = id ? 'PUT' : 'POST';

    // Show loader
    const saveBtn = document.getElementById('saveBrandBtn');
    const loader = document.getElementById('saveBtnLoader');
    const btnText = document.getElementById('submitBtnText');
    
    saveBtn.disabled = true;
    loader.classList.remove('hidden');
    btnText.textContent = 'Saving...';

    const data = {
        name: document.getElementById('brandName').value,
        slug: document.getElementById('brandSlug').value,
        description: document.getElementById('brandDescription').value,
        website: document.getElementById('brandWebsite').value,
        logo_url: document.getElementById('brandLogoUrl').value,
        logo_id: document.getElementById('brandLogoId').value,
        sort_order: document.getElementById('brandSortOrder').value,
        status: document.getElementById('brandStatus').checked ? 1 : 0
    };

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        // Hide loader
        saveBtn.disabled = false;
        loader.classList.add('hidden');
        btnText.textContent = id ? 'Update Brand' : 'Create Brand';
        
        if (data.success) {
            closeModal();
            loadBrands(currentPage);
            alert(data.message);
        } else {
            alert(data.message || 'Error saving brand');
        }
    })
    .catch(error => {
        // Hide loader on error
        saveBtn.disabled = false;
        loader.classList.add('hidden');
        btnText.textContent = id ? 'Update Brand' : 'Create Brand';
        
        console.error('Error:', error);
        alert('Error saving brand');
    });
}

function deleteBrand(id) {
    if (!confirm('Are you sure you want to delete this brand?')) return;

    fetch(`/admin/brands/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadBrands(currentPage);
            alert(data.message);
        } else {
            alert(data.message || 'Error deleting brand');
        }
    });
}

function toggleStatus(id) {
    fetch(`/admin/brands/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadBrands(currentPage);
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.brand-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.brand-checkbox:checked');
    const container = document.getElementById('bulkActionsContainer');
    const count = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        container.classList.remove('hidden');
        count.textContent = `${checkboxes.length} selected`;
    } else {
        container.classList.add('hidden');
    }
}

function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    if (!action) return;

    const checkboxes = document.querySelectorAll('.brand-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);

    if (ids.length === 0) return;

    if (!confirm(`Are you sure you want to ${action} ${ids.length} brands?`)) return;

    fetch('/admin/brands/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action, ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadBrands(currentPage);
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
            alert(data.message);
        }
    });
}

function openImageKit() {
    imagekit.upload({
        file: null,
        fileName: 'brand-logo-' + Date.now(),
        folder: '/brands',
        useUniqueFileName: true,
        tags: ['brand', 'logo']
    }, function(err, result) {
        if (err) {
            console.error('ImageKit upload error:', err);
            alert('Error uploading image');
            return;
        }
        
        document.getElementById('brandLogoUrl').value = result.url;
        document.getElementById('brandLogoId').value = result.fileId;
        document.getElementById('brandLogoPreviewImg').src = result.url;
        document.getElementById('brandLogoPreview').classList.remove('hidden');
    });
}
</script>

@endsection