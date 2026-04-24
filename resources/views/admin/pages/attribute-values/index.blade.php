@extends('admin.layouts.app')

@section('title', 'Attribute Values Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Attribute Values</h1>
            <p class="text-sm text-gray-600 mt-1">Manage values for product attributes</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.attributes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Attributes
            </a>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Value
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Values</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalValues">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeValues">
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
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="inactiveValues">
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
                    <p class="text-sm font-medium text-gray-600">Color Values</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="colorValues">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
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
                    placeholder="Search by value or slug..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="attributeFilter" data-searchable data-placeholder="All Attributes" onchange="loadValues()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Attributes</option>
            </select>
            <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadValues()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadValues()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Attribute</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="valuesTableBody" class="divide-y divide-gray-200 bg-white">
                    <tr class="skeleton-row">
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-4 rounded"></div></td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24 mb-2"></div>
                            <div class="skeleton-text h-3 w-20"></div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-20 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-8 w-8 rounded"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-8"></div></td>
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
                            <div class="skeleton-text h-4 w-20 mb-2"></div>
                            <div class="skeleton-text h-3 w-16"></div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-20 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-8 w-8 rounded"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-8"></div></td>
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


<!-- Modal -->
<div id="valueModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <div id="valueModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Attribute Value</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new attribute value</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="valueForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4">
                <input type="hidden" id="valueId">
                
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900">Value Information</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Attribute <span class="text-red-500">*</span></label>
                            <select id="valueAttribute" data-searchable data-placeholder="Select Attribute" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                                <option value="">Select Attribute</option>
                            </select>
                            <p id="valueAttributeError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Value <span class="text-red-500">*</span></label>
                            <input type="text" id="valueName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Red, XL, Cotton" required>
                            <p id="valueNameError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-gray-500 text-xs">(Auto-generated)</span></label>
                            <input type="text" id="valueSlug" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="red-xl-cotton">
                            <p id="valueSlugError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2" id="colorCodeField" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Color Code</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="valueColorPicker" class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" id="valueColorCode" class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="#FF0000">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Only for color type attributes</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                            <input type="number" id="valueSortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center gap-2 cursor-pointer mt-6">
                                <input type="checkbox" id="valueStatus" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 sticky bottom-0">
            <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" form="valueForm" id="submitBtn" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Create Value</span>
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
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}
.skeleton-row { animation: fadeIn 0.3s ease-in; }
.skeleton-text {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
let currentPage = 1;
let searchTimeout;
let selectedValues = new Set();
let allAttributes = [];
let urlParams = new URLSearchParams(window.location.search);
let preselectedAttributeId = urlParams.get('attribute_id');


document.addEventListener('DOMContentLoaded', () => {
    // Initialize SearchableSelect for all select boxes with data-searchable (except those that will be populated later)
    document.querySelectorAll('select[data-searchable]').forEach(select => {
        // Skip valueAttribute and attributeFilter as they will be initialized after loading data
        if (select.id !== 'valueAttribute' && select.id !== 'attributeFilter') {
            new SearchableSelect(select);
        }
    });
    
    if (document.getElementById('searchInput') && document.getElementById('valuesTableBody')) {
        loadAttributes();
        loadValues();
    }
    
    document.getElementById('valueName')?.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('valueSlug').value = slug;
    });
    
    document.getElementById('valueColorPicker')?.addEventListener('input', function() {
        document.getElementById('valueColorCode').value = this.value.toUpperCase();
    });
    
    document.getElementById('valueColorCode')?.addEventListener('input', function() {
        const color = this.value;
        if (/^#[0-9A-F]{6}$/i.test(color)) {
            document.getElementById('valueColorPicker').value = color;
        }
    });
    
    document.getElementById('valueForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveValue();
    });
});

function toggleColorField(attributeId) {
    const selectedAttr = allAttributes.find(a => a.id == attributeId);
    const colorField = document.getElementById('colorCodeField');
    if (selectedAttr && selectedAttr.type === 'color') {
        colorField.style.display = 'block';
    } else {
        colorField.style.display = 'none';
    }
}

function loadAttributes() {
    fetch('/admin/attribute-values/attributes', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            allAttributes = data.data;
            const attributeSelect = document.getElementById('valueAttribute');
            const attributeFilter = document.getElementById('attributeFilter');
            
            if (attributeSelect) {
                // Find and remove any existing custom dropdown wrapper
                const existingWrapper = attributeSelect.parentElement.querySelector('.searchable-select-wrapper');
                if (existingWrapper) {
                    existingWrapper.remove();
                }
                
                // Show original select and update options
                attributeSelect.style.display = '';
                attributeSelect.innerHTML = '<option value="">Select Attribute</option>';
                data.data.forEach(attr => {
                    attributeSelect.innerHTML += `<option value="${attr.id}">${attr.name} (${attr.type})</option>`;
                });
                
                // Initialize SearchableSelect
                new SearchableSelect(attributeSelect);
                
                // Add change event listener AFTER SearchableSelect initialization
                attributeSelect.addEventListener('change', function() {
                    toggleColorField(this.value);
                });
            }
            
            if (attributeFilter) {
                // Find and remove any existing custom dropdown wrapper
                const existingWrapper = attributeFilter.parentElement.querySelector('.searchable-select-wrapper');
                if (existingWrapper) {
                    existingWrapper.remove();
                }
                
                // Show original select and update options
                attributeFilter.style.display = '';
                attributeFilter.innerHTML = '<option value="">All Attributes</option>';
                data.data.forEach(attr => {
                    attributeFilter.innerHTML += `<option value="${attr.id}">${attr.name}</option>`;
                });
                
                if (preselectedAttributeId) {
                    attributeFilter.value = preselectedAttributeId;
                }
                
                // Initialize SearchableSelect
                new SearchableSelect(attributeFilter);
            }
        }
    })
    .catch(error => {
        console.error('Error loading attributes:', error);
    });
}

function loadValues(page = 1) {
    currentPage = page;
    
    const searchInput = document.getElementById('searchInput');
    const attributeFilter = document.getElementById('attributeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const perPageSelect = document.getElementById('perPageSelect');
    
    if (!searchInput || !attributeFilter || !statusFilter || !perPageSelect) {
        console.error('Required elements not found');
        return;
    }
    
    const search = searchInput.value;
    const attribute = attributeFilter.value;
    const status = statusFilter.value;
    const perPage = perPageSelect.value;

    const url = `/admin/attribute-values/list?page=${page}&search=${search}&attribute_id=${attribute}&status=${status}&per_page=${perPage}`;

    const tbody = document.getElementById('valuesTableBody');
    const skeletonRows = tbody.querySelectorAll('.skeleton-row');
    skeletonRows.forEach(row => row.style.display = '');

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderValues(data.data);
            renderPagination(data.pagination);
            updateStats(data.data, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error loading values:', error);
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-red-600">
                        Error loading attribute values. Please refresh the page.
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
            loadValues(1);
        });
    }, 300);
}

function updateStats(values, pagination) {
    const totalValuesEl = document.getElementById('totalValues');
    const activeValuesEl = document.getElementById('activeValues');
    const inactiveValuesEl = document.getElementById('inactiveValues');
    const colorValuesEl = document.getElementById('colorValues');
    
    totalValuesEl.innerHTML = pagination.total;
    
    fetch('/admin/attribute-values/list?per_page=1000', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const allValues = data.data;
            const active = allValues.filter(v => v.status).length;
            const inactive = allValues.filter(v => !v.status).length;
            const colors = allValues.filter(v => v.color_code).length;
            
            activeValuesEl.innerHTML = active;
            inactiveValuesEl.innerHTML = inactive;
            colorValuesEl.innerHTML = colors;
        }
    });
}

function renderValues(values) {
    const tbody = document.getElementById('valuesTableBody');
    
    if (values.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">No attribute values found</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filters</p>
                        </div>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = values.map(value => {
        const colorPreview = value.color_code 
            ? `<div class="w-8 h-8 rounded border-2 border-gray-300" style="background-color: ${value.color_code};" title="${value.color_code}"></div>`
            : '<span class="text-gray-400 text-sm">-</span>';
        
        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="value-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" data-id="${value.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="font-semibold text-gray-900">${value.value}</div>
                <div class="text-xs text-gray-500 mt-0.5">${value.slug}</div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-medium">${value.attribute ? value.attribute.name : 'N/A'}</span>
            </td>
            <td class="px-6 py-4">
                ${colorPreview}
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${value.sort_order}</td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${value.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${value.status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} rounded-md text-xs font-medium hover:opacity-80 transition-opacity">
                    <span class="w-1.5 h-1.5 rounded-full ${value.status ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${value.status ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editValue(${value.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Edit value">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteValue(${value.id})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete value">
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
        container.innerHTML = `<div class="text-sm text-gray-600">Showing ${pagination.total} ${pagination.total === 1 ? 'value' : 'values'}</div>`;
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
        pages += `<button onclick="loadValues(1)" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">1</button>`;
        if (startPage > 2) pages += `<span class="px-2 text-gray-400">...</span>`;
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pages += `<button onclick="loadValues(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'} rounded-lg transition-colors">${i}</button>`;
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) pages += `<span class="px-2 text-gray-400">...</span>`;
        pages += `<button onclick="loadValues(${pagination.last_page})" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">${pagination.last_page}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">${((pagination.current_page - 1) * pagination.per_page) + 1}</span> to 
                <span class="font-medium">${Math.min(pagination.current_page * pagination.per_page, pagination.total)}</span> of 
                <span class="font-medium">${pagination.total}</span> values
            </div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}


function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Attribute Value';
    document.getElementById('submitBtnText').textContent = 'Create Value';
    document.getElementById('valueForm').reset();
    document.getElementById('valueId').value = '';
    document.getElementById('valueStatus').checked = true;
    document.getElementById('valueSortOrder').value = 0;
    document.getElementById('colorCodeField').style.display = 'none';
    
    if (preselectedAttributeId) {
        document.getElementById('valueAttribute').value = preselectedAttributeId;
        const selectedAttr = allAttributes.find(a => a.id == preselectedAttributeId);
        if (selectedAttr && selectedAttr.type === 'color') {
            document.getElementById('colorCodeField').style.display = 'block';
        }
    }
    
    clearErrors();
    
    const modal = document.getElementById('valueModal');
    const modalContent = document.getElementById('valueModalContent');
    
    modalContent.style.transform = 'translateX(100%)';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    modalContent.offsetHeight;
    
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            modalContent.style.transform = 'translateX(0)';
        });
    });
}

function closeModal() {
    const modal = document.getElementById('valueModal');
    const modalContent = document.getElementById('valueModalContent');
    
    modalContent.style.transition = 'transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1)';
    modalContent.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'valueModal') {
        closeModal();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('valueModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    }
});

function clearErrors() {
    ['valueAttributeError', 'valueNameError', 'valueSlugError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
            el.textContent = '';
        }
    });
    
    ['valueAttribute', 'valueName', 'valueSlug'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('border-red-500');
        }
    });
}

function showFieldError(fieldId, message) {
    const errorEl = document.getElementById(fieldId + 'Error');
    const inputEl = document.getElementById(fieldId);
    
    if (errorEl && inputEl) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
        inputEl.classList.add('border-red-500');
        inputEl.focus();
    }
}

function saveValue() {
    clearErrors();
    
    const id = document.getElementById('valueId').value;
    const attributeId = document.getElementById('valueAttribute').value;
    const value = document.getElementById('valueName').value;
    const slug = document.getElementById('valueSlug').value;
    const colorCode = document.getElementById('valueColorCode').value;
    const status = document.getElementById('valueStatus').checked;
    const sortOrder = document.getElementById('valueSortOrder').value;
    
    const url = id ? `/admin/attribute-values/${id}` : '/admin/attribute-values';
    const method = id ? 'PUT' : 'POST';
    
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    
    submitBtn.disabled = true;
    submitBtnText.classList.add('hidden');
    submitBtnLoading.classList.remove('hidden');
    
    const payload = {
        attribute_id: attributeId,
        value: value,
        slug: slug,
        color_code: colorCode || null,
        status: status,
        sort_order: sortOrder
    };
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal();
            loadValues(currentPage);
        } else if (data.errors) {
            Object.keys(data.errors).forEach(field => {
                const fieldName = field.replace('_', '');
                const camelField = 'value' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
                showFieldError(camelField, data.errors[field][0]);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtnText.classList.remove('hidden');
        submitBtnLoading.classList.add('hidden');
    });
}

function editValue(id) {
    fetch(`/admin/attribute-values/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const val = data.data;
            
            // Open modal first
            const modal = document.getElementById('valueModal');
            const modalContent = document.getElementById('valueModalContent');
            
            modalContent.style.transform = 'translateX(100%)';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Set modal title and button text
            document.getElementById('modalTitle').textContent = 'Edit Attribute Value';
            document.getElementById('submitBtnText').textContent = 'Update Value';
            
            // Set form values
            document.getElementById('valueId').value = val.id;
            document.getElementById('valueName').value = val.value;
            document.getElementById('valueSlug').value = val.slug;
            document.getElementById('valueColorCode').value = val.color_code || '';
            document.getElementById('valueColorPicker').value = val.color_code || '#000000';
            document.getElementById('valueStatus').checked = val.status;
            document.getElementById('valueSortOrder').value = val.sort_order;
            
            // Update attribute select with SearchableSelect
            const attributeSelect = document.getElementById('valueAttribute');
            if (attributeSelect) {
                attributeSelect.value = val.attribute_id;
                
                // Update SearchableSelect display
                const wrapper = attributeSelect.nextElementSibling;
                if (wrapper && wrapper.classList.contains('searchable-select-wrapper')) {
                    const displayText = wrapper.querySelector('.searchable-select-text');
                    if (displayText) {
                        const selectedOption = attributeSelect.options[attributeSelect.selectedIndex];
                        if (selectedOption) {
                            displayText.textContent = selectedOption.text;
                            displayText.classList.remove('text-gray-400');
                            displayText.classList.add('text-gray-700');
                        }
                    }
                    
                    // Update dropdown options
                    const optionsContainer = wrapper.querySelector('.searchable-select-options');
                    if (optionsContainer) {
                        let optionsHtml = '';
                        Array.from(attributeSelect.options).forEach(opt => {
                            const isSelected = opt.value === attributeSelect.value;
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
            
            // Show/hide color field based on attribute type
            const selectedAttr = allAttributes.find(a => a.id == val.attribute_id);
            if (selectedAttr && selectedAttr.type === 'color') {
                document.getElementById('colorCodeField').style.display = 'block';
            } else {
                document.getElementById('colorCodeField').style.display = 'none';
            }
            
            clearErrors();
            
            // Animate modal in
            modalContent.offsetHeight;
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    modalContent.style.transform = 'translateX(0)';
                });
            });
        }
    })
    .catch(error => {
        console.error('Error loading value:', error);
        showNotification('Failed to load value data', 'error');
    });
}

function deleteValue(id) {
    showConfirmDialog({
        title: 'Delete Attribute Value?',
        message: 'This action cannot be undone.',
        type: 'danger',
        confirmText: 'Delete',
        onConfirm: () => {
            fetch(`/admin/attribute-values/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    loadValues(currentPage);
                } else {
                    showNotification(data.message || 'Failed to delete value', 'error');
                }
            });
        }
    });
}

function toggleStatus(id) {
    fetch(`/admin/attribute-values/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            loadValues(currentPage);
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.value-checkbox');
    
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedValues.add(parseInt(cb.dataset.id));
        } else {
            selectedValues.delete(parseInt(cb.dataset.id));
        }
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.value-checkbox:checked');
    const bulkContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    const selectAll = document.getElementById('selectAll');
    
    selectedValues.clear();
    checkboxes.forEach(cb => selectedValues.add(parseInt(cb.dataset.id)));
    
    if (selectedValues.size > 0) {
        bulkContainer.classList.remove('hidden');
        selectedCount.textContent = `${selectedValues.size} selected`;
    } else {
        bulkContainer.classList.add('hidden');
    }
    
    const allCheckboxes = document.querySelectorAll('.value-checkbox');
    selectAll.checked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
}

function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (!action) {
        showNotification('Please select an action', 'error');
        return;
    }
    
    if (selectedValues.size === 0) {
        showNotification('Please select at least one value', 'error');
        return;
    }
    
    const actionText = action === 'delete' ? 'delete' : action;
    showConfirmDialog({
        title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Values?`,
        message: `Are you sure you want to ${actionText} ${selectedValues.size} value(s)?`,
        type: action === 'delete' ? 'danger' : 'warning',
        confirmText: actionText.charAt(0).toUpperCase() + actionText.slice(1),
        onConfirm: () => {
            fetch('/admin/attribute-values/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    ids: Array.from(selectedValues)
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    selectedValues.clear();
                    document.getElementById('selectAll').checked = false;
                    document.getElementById('bulkActionsContainer').classList.add('hidden');
                    loadValues(currentPage);
                }
            });
        }
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showConfirmDialog(options) {
    const dialog = document.createElement('div');
    dialog.id = 'confirmDialog';
    dialog.className = 'fixed inset-0 z-50 flex items-center justify-center';
    dialog.innerHTML = `
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('confirmDialog').remove()"></div>
        <div class="relative bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4" style="animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full ${options.type === 'danger' ? 'bg-red-100' : 'bg-yellow-100'} flex items-center justify-center">
                    <svg class="w-6 h-6 ${options.type === 'danger' ? 'text-red-600' : 'text-yellow-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">${options.title}</h3>
                    <p class="text-sm text-gray-600 mt-1">${options.message}</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button onclick="document.getElementById('confirmDialog').remove()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button onclick="document.getElementById('confirmDialog').remove(); (${options.onConfirm})()" class="px-4 py-2 ${options.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-600 hover:bg-yellow-700'} text-white text-sm font-semibold rounded-lg transition-colors">
                    ${options.confirmText}
                </button>
            </div>
        </div>
    `;
    
    const style = document.createElement('style');
    style.textContent = '@keyframes scaleIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }';
    document.head.appendChild(style);
    
    document.body.appendChild(dialog);
}
</script>
@endpush
