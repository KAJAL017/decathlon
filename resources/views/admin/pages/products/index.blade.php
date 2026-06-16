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
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Import/Export
                    <i data-lucide="arrow-down" class="w-4 h-4"></i>
                </button>
                <div id="importExportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="py-1">
                        <button onclick="openImportModal()" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Import Products
                        </button>
                        <button onclick="openExportModal()" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export Products
                        </button>
                        <hr class="my-1">
                        <a href="/admin/products/import/template" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Download Template
                        </a>
                        <button onclick="openImportExportJobsModal()" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i data-lucide="clipboard" class="w-4 h-4"></i>
                            View Jobs
                        </button>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Add Product
            </a>
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
                    <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
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
                    <i data-lucide="circle-check" class="w-6 h-6 text-green-600"></i>
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
                    <i data-lucide="pencil" class="w-6 h-6 text-yellow-600"></i>
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
                    <i data-lucide="star" class="w-6 h-6 text-purple-600"></i>
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
                    <i data-lucide="tag" class="w-6 h-6 text-pink-600"></i>
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
                    <i data-lucide="trending-up" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Bulk Actions -->
    <div class="bg-white rounded-xl border border-gray-200 p-2 shadow-sm">
        <div class="flex flex-wrap lg:flex-nowrap items-center gap-2">
            <!-- Bulk Actions -->
            <div id="bulkActionsContainer" class="hidden flex-shrink-0 flex items-center gap-2 bg-blue-50 px-3 h-10 rounded-xl border border-blue-100">
                <select id="bulkActionSelect" class="bg-transparent text-blue-700 text-xs font-bold uppercase tracking-tight focus:outline-none cursor-pointer h-full">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="feature">Feature</option>
                    <option value="delete">Delete</option>
                </select>
                <button onclick="applyBulkAction()" class="bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest hover:bg-blue-700 transition-colors">
                    Go
                </button>
                <span id="selectedCount" class="text-[10px] font-bold text-blue-500 whitespace-nowrap"></span>
            </div>

            <!-- Search -->
            <div class="flex-1 min-w-[240px] relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-4 w-4 text-gray-400 group-focus-within:text-[#0082C3] transition-colors"></i>
                </div>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search products..." 
                    class="block w-full pl-10 pr-4 h-10 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent focus:bg-white transition-all"
                    onkeyup="debounceSearch()"
                >
            </div>

            <!-- Dropdown Filters -->
            <div class="flex items-center gap-2 flex-nowrap overflow-x-auto lg:overflow-visible no-scrollbar">
                <div class="w-[140px] flex-shrink-0">
                    <select id="brandFilter" data-searchable data-placeholder="All Brands" onchange="loadProducts()" class="w-full px-3 h-10 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#0082C3] outline-none transition-all">
                        <option value="">All Brands</option>
                    </select>
                </div>
                <div class="w-[160px] flex-shrink-0">
                    <select id="categoryFilter" data-searchable data-placeholder="All Categories" onchange="loadProducts()" class="w-full px-3 h-10 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#0082C3] outline-none transition-all">
                        <option value="">All Categories</option>
                    </select>
                </div>
                <div class="w-[120px] flex-shrink-0">
                    <select id="typeFilter" data-searchable data-placeholder="All Types" onchange="loadProducts()" class="w-full px-3 h-10 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#0082C3] outline-none transition-all">
                        <option value="">All Types</option>
                        <option value="simple">Simple</option>
                        <option value="variable">Variable</option>
                        <option value="digital">Digital</option>
                        <option value="service">Service</option>
                    </select>
                </div>
                <div class="w-[120px] flex-shrink-0">
                    <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadProducts()" class="w-full px-3 h-10 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#0082C3] outline-none transition-all">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="w-[130px] flex-shrink-0 border-l border-gray-100 ml-1 pl-2">
                    <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadProducts()" class="w-full px-3 h-10 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#0082C3] outline-none transition-all">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
            </div>
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


<!-- Product Selector Modal -->
<div id="productSelectorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900" id="selectorModalTitle">Select Products</h3>
            <button type="button" onclick="closeProductSelector()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
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
let isFirstLoad = true;

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
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
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
                <i data-lucide="settings" class="w-10 h-10 mx-auto mb-2 text-gray-400"></i>
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
        if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
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
        if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
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
                    <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
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
        
        const imageUrl = product.featured_image?.thumbnail_url || product.featured_image?.image_url || '';

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
                                <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
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
                        <button onclick="duplicateProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}')" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Duplicate">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                        <a href="/admin/products/${product.id}/edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>
                        <button onclick="deleteProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.variants_count || 0})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
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

function populateForm(p) {
    const s = (id, val) => { const el = document.getElementById(id); if (el) el.value = val || ''; };
    const c = (id, val) => { const el = document.getElementById(id); if (el) el.checked = !!val; };
    const h = (id, show) => { const el = document.getElementById(id); if (el) el.classList.toggle('hidden', !show); };

    s('productId', p.id);
    s('productName', p.name);
    s('productSlug', p.slug);
    s('productType', p.product_type);
    s('productBrand', p.brand_id || '');
    s('productPrimaryCategory', p.category_id || '');
    
    setSummernoteContent('productShortDescription', p.short_description || '');
    setSummernoteContent('productDescription', p.description || '');
    
    s('productStatus', p.status);
    s('productVisibility', p.visibility || 'visible');
    s('productAvailability', p.availability_status || 'in_stock');
    s('productAvailableDate', p.available_date || '');
    h('availableDateContainer', ['pre_order', 'backorder'].includes(p.availability_status));

    c('productIsFeatured', p.is_featured);
    c('productIsNew', p.is_new);
    c('productIsBestSeller', p.is_best_seller);
    c('productIsTrending', p.is_trending);
    c('productIsDigital', p.is_digital);
    h('digitalProductSettings', p.is_digital);
    if (p.is_digital) {
        s('productDownloadUrl', p.download_url);
        s('productDownloadLimit', p.download_limit);
    }

    // Pricing & Inventory
    s('productRegularPrice', p.min_price || p.variants?.[0]?.price || '');
    s('productSalePrice', p.variants?.[0]?.compare_price || '');
    s('productSku', p.sku_prefix || '');
    
    const manageStock = !!p.manage_stock;
    c('productManageStock', manageStock);
    s('productStockQuantity', p.stock_quantity || 0);
    s('productLowStockThreshold', p.low_stock_threshold || 5);
    c('productAllowBackorder', !!p.allow_backorder);
    const stockFields = document.getElementById('stockFieldsContainer');
    if (stockFields) stockFields.style.display = manageStock ? 'grid' : 'none';
    if (manageStock && typeof updateStockBadge === 'function') updateStockBadge();

    // Dimensions
    s('productWeight', p.weight);
    s('productLength', p.length);
    s('productWidth', p.width);
    s('productHeight', p.height);

    // SEO
    s('productSeoTitle', p.seo_title);
    s('productSeoDescription', p.seo_description);
    s('productSeoKeywords', p.seo_keywords);

    // Multi-selects
    const syncMulti = (id, ids) => {
        const el = document.getElementById(id);
        if (el) {
            Array.from(el.options).forEach(opt => opt.selected = (ids || []).includes(parseInt(opt.value)));
            if (typeof refreshSearchableSelect === 'function') refreshSearchableSelect(el);
            else if (typeof searchableSelectInstances !== 'undefined') {
                const inst = searchableSelectInstances.find(i => i.select === el);
                if (inst) inst.refresh();
            }
        }
    };
    syncMulti('productTags', (p.tags || []).map(t => t.id));
    syncMulti('productAdditionalCategories', (p.categories || []).map(c => c.id));
    syncMulti('productCollections', (p.collections || []).map(c => c.id));

    productImages = (p.images || []).map(img => ({ ...img, url: img.image_url }));
    productVideos = p.videos || [];
    productFaqs = p.faqs || [];
    productVariants = p.variants || [];
    
    renderProductImages(); 
    if (typeof renderVideosList === 'function') renderVideosList();
    if (typeof renderFaqsList === 'function') renderFaqsList();
    if (typeof renderVariantsList === 'function') renderVariantsList();
    if (typeof _renderProductAttrs === 'function') _renderProductAttrs();
}

function saveProduct() {
    const productIdEl = document.getElementById('productId');
    if (!productIdEl) return;
    const id = productIdEl.value;
    const url = id ? `/admin/products/${id}` : '/admin/products';
    const method = id ? 'PUT' : 'POST';
    
    const data = {
        name: document.getElementById('productName')?.value || '',
        slug: document.getElementById('productSlug')?.value || '',
        product_type: document.getElementById('productType')?.value || 'simple',
        brand_id: document.getElementById('productBrand')?.value || null,
        category_id: document.getElementById('productPrimaryCategory')?.value || null,
        short_description: getSummernoteContent('productShortDescription'),
        description: getSummernoteContent('productDescription'),
        status: document.getElementById('productStatus')?.value || 'draft',
        availability_status: document.getElementById('productAvailability')?.value || 'in_stock',
        available_date: document.getElementById('productAvailableDate')?.value || null,
        visibility: document.getElementById('productVisibility')?.value || 'visible',
        is_digital: document.getElementById('productIsDigital')?.checked ? 1 : 0,
        download_url: document.getElementById('productDownloadUrl')?.value || null,
        download_limit: document.getElementById('productDownloadLimit')?.value || null,
        is_featured: document.getElementById('productIsFeatured')?.checked ? 1 : 0,
        is_new: document.getElementById('productIsNew')?.checked ? 1 : 0,
        is_best_seller: document.getElementById('productIsBestSeller')?.checked ? 1 : 0,
        is_trending: document.getElementById('productIsTrending')?.checked ? 1 : 0,
        manage_stock: document.getElementById('productManageStock')?.checked ? 1 : 0,
        stock_quantity: document.getElementById('productManageStock')?.checked ? (parseInt(document.getElementById('productStockQuantity')?.value) || 0) : null,
        low_stock_threshold: parseInt(document.getElementById('productLowStockThreshold')?.value) || 5,
        allow_backorder: document.getElementById('productAllowBackorder')?.checked ? 1 : 0,
        weight: document.getElementById('productWeight')?.value || null,
        length: document.getElementById('productLength')?.value || null,
        width: document.getElementById('productWidth')?.value || null,
        height: document.getElementById('productHeight')?.value || null,
        seo_title: document.getElementById('productSeoTitle')?.value || '',
        seo_description: document.getElementById('productSeoDescription')?.value || '',
        seo_keywords: document.getElementById('productSeoKeywords')?.value || '',
        tags: Array.from(document.getElementById('productTags')?.selectedOptions || []).map(opt => parseInt(opt.value)).filter(Boolean),
        categories: Array.from(document.getElementById('productAdditionalCategories')?.selectedOptions || []).map(opt => parseInt(opt.value)).filter(Boolean),
        collections: Array.from(document.getElementById('productCollections')?.selectedOptions || []).map(opt => parseInt(opt.value)).filter(Boolean),
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
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
        } else {
            setLoading(false);
            if (data.errors) showValidationErrors(data.errors);
            else showToast(data.message || 'Operation failed', 'error');
        }
    })
    .catch(error => {
        setLoading(false);
        showToast('An error occurred while saving the product', 'error');
        console.error('Save error:', error);
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

function openMultipleImagePicker() {
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.multiple = true;

    fileInput.onchange = function(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) {
            return;
        }

        const validFiles = [];
        for (const file of files) {
            if (!file.type.startsWith('image/')) {
                showToast(`${file.name} is not an image file`, 'error');
                continue;
            }

            validFiles.push(file);
        }

        if (validFiles.length > 0) {
            uploadMultipleImages(validFiles);
        }
    };

    fileInput.click();
}

async function uploadMultipleImages(files) {
    showToast(`Uploading ${files.length} image(s)...`, 'info');

    let uploadedCount = 0;
    let failedCount = 0;

    for (const file of files) {
        try {
            const result = await uploadImageLocal(file);

            productImages.push({
                image_url: result.url,
                image_id: result.fileId || result.filePath || null,
                alt_text: '',
                sort_order: productImages.length,
                is_featured: productImages.length === 0,
                responsive_urls: generateResponsiveUrls(result.url),
                file_name: file.name,
                file_size: file.size,
                width: null,
                height: null
            });
            uploadedCount++;
        } catch (error) {
            console.error(`Upload error for ${file.name}:`, error);
            failedCount++;
            showToast(`${file.name}: ${error.message || 'Upload failed'}`, 'error');
        }
    }

    renderProductImages();
    initImageSortable();

    if (uploadedCount > 0) {
        showToast(`${uploadedCount} image(s) uploaded successfully`, 'success');
    }
    if (failedCount > 0) {
        showToast(`${failedCount} image(s) failed to upload`, 'error');
    }
}

function generateResponsiveUrls(baseUrl) {
    const sizes = {
        thumbnail: 'Admin thumbnail',
        small: 'Mobile',
        medium: 'Tablet',
        large: 'Desktop',
        xlarge: 'Large screen',
        original: 'Original size'
    };

    const responsiveUrls = {};

    for (const sizeName of Object.keys(sizes)) {
        responsiveUrls[sizeName] = baseUrl;
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
                <i data-lucide="menu" class="w-3.5 h-3.5"></i>
                ${index + 1}
            </div>
            
            <!-- Featured Badge -->
            ${index === 0 ? `
                <div class="absolute top-2 right-2 z-10 bg-blue-600 text-white px-2.5 py-1 rounded-lg text-xs font-semibold flex items-center gap-1">
                    <i data-lucide="star" class="w-3.5 h-3.5"></i>
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
                        <i data-lucide="star" class="w-3.5 h-3.5"></i>
                        Feature
                    </button>
                    <button type="button" onclick="viewResponsiveSizes(${index})" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-lg transition-colors" title="View Responsive Sizes">
                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                    </button>
                    <button type="button" onclick="removeProductImage(${index})" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors" title="Remove">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
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
                        <i data-lucide="x" class="w-5 h-5"></i>
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
                <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                <p class="text-sm font-medium text-gray-900 mb-1">No Variant Attributes Found</p>
                <p class="text-xs text-gray-500 mb-3">Create variant attributes (like Color, Size) first to generate product variants</p>
                <a href="/admin/attributes" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3] transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
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
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
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
                            <i data-lucide="x" class="w-4 h-4"></i>
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
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
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
                                <i data-lucide="plus" class="w-3 h-3"></i>
                                Upload Image
                            </button>
                        </div>
                        <div id="variantImages_${index}" class="grid grid-cols-5 gap-2">
                            ${variant.images && variant.images.length > 0 ? 
                                variant.images.map((img, imgIndex) => `
                                    <div class="relative group">
                                        <img src="${img.image_url}" alt="Variant image" class="w-full h-20 object-cover rounded border border-gray-200">
                                        <button type="button" onclick="removeVariantImage(${index}, ${imgIndex})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i data-lucide="x" class="w-3 h-3"></i>
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
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.multiple = true;

    input.onchange = async (e) => {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;

        showToast('Uploading images...', 'info');

        try {
            for (const file of files) {
                const result = await uploadImageLocal(file);

                if (!productVariants[variantIndex].images) {
                    productVariants[variantIndex].images = [];
                }

                productVariants[variantIndex].images.push({
                    image_url: result.url,
                    image_id: result.fileId || result.filePath || null,
                    alt_text: productVariants[variantIndex].name,
                    sort_order: productVariants[variantIndex].images.length
                });
            }

            renderVariantsList();
            showToast(`${files.length} image(s) uploaded successfully`, 'success');
        } catch (error) {
            console.error('Image upload error:', error);
            showToast(error.message || 'Failed to upload images', 'error');
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

async function uploadImageLocal(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', 'products');
    formData.append('folder', 'products');

    const response = await fetch('/api/upload', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    });

    const data = await response.json();

    if (!response.ok || !data.success) {
        throw new Error(data.message || 'Upload failed');
    }

    return data;
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
                    <i data-lucide="trash-2" class="w-7 h-7 text-red-500"></i>
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
                            <i data-lucide="x" class="w-4 h-4"></i>
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
                <i data-lucide="video" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
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
                        <i data-lucide="info" class="w-8 h-8 text-gray-400"></i>
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
                        <i data-lucide="star" class="w-4 h-4"></i>
                    </button>`
                    : ''
                }
                <button type="button" onclick="removeVideo(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
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
                <i data-lucide="help-circle" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
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
                                <i data-lucide="arrow-up" class="w-4 h-4"></i>
                            </button>`
                            : ''
                        }
                        ${index < productFaqs.length - 1 
                            ? `<button type="button" onclick="moveFaqDown(${index})" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Move Down">
                                <i data-lucide="arrow-down" class="w-4 h-4"></i>
                            </button>`
                            : ''
                        }
                        <button type="button" onclick="editFaq(${index})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <button type="button" onclick="removeFaq(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
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
                <i data-lucide="clipboard" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
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
            <i data-lucide="x" class="w-4 h-4"></i>
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
                <i data-lucide="clipboard" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
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
                <i data-lucide="x" class="w-4 h-4"></i>
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
                <i data-lucide="clipboard" class="w-5 h-5 text-green-600"></i>
                </svg>
                <h3 class="text-base font-semibold text-gray-900">Add Product Attribute</h3>
            </div>
            <button type="button" onclick="closeAddAttributeModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
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
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="importForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                <input type="file" name="file" accept=".csv,.txt" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">CSV or TXT import file.</p>
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
                <i data-lucide="x" class="w-5 h-5"></i>
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
                <i data-lucide="x" class="w-5 h-5"></i>
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

