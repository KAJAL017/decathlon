@extends('admin.layouts.app')

@section('title', 'Attributes Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Attributes</h1>
            <p class="text-sm text-gray-600 mt-1">Manage product attributes for variants and filters</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Attribute
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Attributes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalAttributes">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeAttributes">
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
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="inactiveAttributes">
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
                    <p class="text-sm font-medium text-gray-600">For Variants</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="variantAttributes">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">For Filters</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="filterAttributes">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
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
                    placeholder="Search by name or slug..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="typeFilter" data-searchable data-placeholder="All Types" onchange="loadAttributes()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Types</option>
                <option value="select">Dropdown</option>
                <option value="multiselect">Multiselect</option>
                <option value="color">Color</option>
                <option value="text">Text</option>
                <option value="number">Number</option>
                <option value="boolean">Boolean</option>
            </select>
            <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadAttributes()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadAttributes()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
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
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Attribute</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Values</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Used For</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="attributesTableBody" class="divide-y divide-gray-200 bg-white">
                    <tr class="skeleton-row">
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-4 rounded"></div></td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-32 mb-2"></div>
                            <div class="skeleton-text h-3 w-24"></div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-16"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-24 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="skeleton-row">
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-4 rounded"></div></td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-28 mb-2"></div>
                            <div class="skeleton-text h-3 w-20"></div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-16"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-24 rounded-md"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-6 w-16 rounded-md"></div></td>
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


<!-- Modal -->
<div id="attributeModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <div id="attributeModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Attribute</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new product attribute</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="attributeForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4">
                <input type="hidden" id="attributeId">
                
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900">Basic Information</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Attribute Name <span class="text-red-500">*</span></label>
                            <input type="text" id="attributeName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Color, Size, Material" required>
                            <p id="attributeNameError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-gray-500 text-xs">(Auto-generated)</span></label>
                            <input type="text" id="attributeSlug" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="color-size-material">
                            <p id="attributeSlugError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Type <span class="text-red-500">*</span></label>
                            <select id="attributeType" data-searchable data-placeholder="Select Type" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                                <option value="select">📋 Dropdown (Single Select)</option>
                                <option value="multiselect">📋 Multiselect (Multiple Options)</option>
                                <option value="color">🎨 Color (Color Picker)</option>
                                <option value="text">✏️ Text (Free Text Input)</option>
                                <option value="number">🔢 Number (Numeric Value)</option>
                                <option value="boolean">✅ Boolean (Yes/No)</option>
                            </select>
                            <p id="attributeTypeError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Display Type <span class="text-red-500">*</span></label>
                            <select id="attributeDisplayType" data-searchable data-placeholder="Select Display Type" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                                <option value="dropdown">Dropdown</option>
                                <option value="radio">Radio Buttons</option>
                                <option value="checkbox">Checkboxes</option>
                                <option value="color_swatch">Color Swatches</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">How this attribute will be displayed on frontend</p>
                        </div>

                        <div class="col-span-2" id="unitField" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Unit <span class="text-gray-500 text-xs">(For number type)</span></label>
                            <input type="text" id="attributeUnit" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., kg, cm, GB, liters">
                            <p class="text-xs text-gray-500 mt-1">Optional unit for numeric values</p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Attribute Group</label>
                            <select id="attributeGroup" data-searchable data-placeholder="Select Group" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                                <option value="">No Group</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Organize attributes into groups</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                            <input type="number" id="attributeSortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                        </div>
                    </div>
                </div>


                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900">Attribute Settings</h4>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="attributeVariant" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Use for Variants</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-6">Generate product variants based on this attribute</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="attributeFilterable" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Use for Filters</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-6">Show in frontend product filters</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="attributeSearchable" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Searchable</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-6">Include in product search</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="attributeRequired" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Required for Product</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-6">Mandatory when creating products</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="attributeStatus" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
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
            <button type="submit" form="attributeForm" id="submitBtn" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Create Attribute</span>
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
let selectedAttributes = new Set();


document.addEventListener('DOMContentLoaded', () => {
    // Initialize SearchableSelect for all select boxes with data-searchable
    document.querySelectorAll('select[data-searchable]').forEach(select => {
        // Skip if already initialized (check for wrapper sibling)
        if (select.nextElementSibling && select.nextElementSibling.classList.contains('searchable-select-wrapper')) {
            console.log('SearchableSelect already initialized for:', select.id);
            return;
        }
        console.log('Initializing SearchableSelect for:', select.id);
        new SearchableSelect(select);
    });
    
    if (document.getElementById('searchInput') && document.getElementById('attributesTableBody')) {
        loadAttributes();
        loadAttributeGroups();
    }
    
    document.getElementById('attributeName')?.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('attributeSlug').value = slug;
    });
    
    document.getElementById('attributeType')?.addEventListener('change', function() {
        const unitField = document.getElementById('unitField');
        if (this.value === 'number') {
            unitField.style.display = 'block';
        } else {
            unitField.style.display = 'none';
            document.getElementById('attributeUnit').value = '';
        }
    });
    
    document.getElementById('attributeForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveAttribute();
    });
});

function loadAttributes(page = 1) {
    currentPage = page;
    
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const perPageSelect = document.getElementById('perPageSelect');
    
    if (!searchInput || !typeFilter || !statusFilter || !perPageSelect) {
        console.error('Required elements not found');
        return;
    }
    
    const search = searchInput.value;
    const type = typeFilter.value;
    const status = statusFilter.value;
    const perPage = perPageSelect.value;

    const url = `/admin/attributes/list?page=${page}&search=${search}&type=${type}&status=${status}&per_page=${perPage}`;

    const tbody = document.getElementById('attributesTableBody');
    const skeletonRows = tbody.querySelectorAll('.skeleton-row');
    skeletonRows.forEach(row => row.style.display = '');

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderAttributes(data.data);
            renderPagination(data.pagination);
            updateStats(data.data, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error loading attributes:', error);
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-red-600">
                        Error loading attributes. Please refresh the page.
                    </td>
                </tr>
            `;
        }
    });
}

function loadAttributeGroups() {
    fetch('/admin/attribute-groups/list?per_page=1000&status=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const groupSelect = document.getElementById('attributeGroup');
            if (groupSelect) {
                // Just update options, don't reinitialize SearchableSelect
                groupSelect.innerHTML = '<option value="">No Group</option>';
                data.data.forEach(group => {
                    groupSelect.innerHTML += `<option value="${group.id}">${group.name}</option>`;
                });
                
                // Trigger change event to update SearchableSelect display
                groupSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    })
    .catch(error => {
        console.error('Error loading attribute groups:', error);
    });
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        requestAnimationFrame(() => {
            loadAttributes(1);
        });
    }, 300);
}

function updateStats(attributes, pagination) {
    const totalAttributesEl = document.getElementById('totalAttributes');
    const activeAttributesEl = document.getElementById('activeAttributes');
    const inactiveAttributesEl = document.getElementById('inactiveAttributes');
    const variantAttributesEl = document.getElementById('variantAttributes');
    const filterAttributesEl = document.getElementById('filterAttributes');
    
    totalAttributesEl.innerHTML = pagination.total;
    
    fetch('/admin/attributes/list?per_page=1000', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const allAttributes = data.data;
            const active = allAttributes.filter(a => a.status).length;
            const inactive = allAttributes.filter(a => !a.status).length;
            const variant = allAttributes.filter(a => a.is_variant).length;
            const filterable = allAttributes.filter(a => a.is_filterable).length;
            
            activeAttributesEl.innerHTML = active;
            inactiveAttributesEl.innerHTML = inactive;
            variantAttributesEl.innerHTML = variant;
            filterAttributesEl.innerHTML = filterable;
        }
    });
}

function renderAttributes(attributes) {
    const tbody = document.getElementById('attributesTableBody');
    
    if (attributes.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">No attributes found</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filters</p>
                        </div>
                    </div>
                </td>
            </tr>`;
        return;
    }

    const typeColors = {
        'select': 'bg-blue-100 text-blue-700',
        'multiselect': 'bg-indigo-100 text-indigo-700',
        'color': 'bg-purple-100 text-purple-700',
        'text': 'bg-green-100 text-green-700',
        'number': 'bg-orange-100 text-orange-700',
        'boolean': 'bg-teal-100 text-teal-700'
    };
    
    const typeIcons = {
        'select': '📋',
        'multiselect': '📋',
        'color': '🎨',
        'text': '✏️',
        'number': '🔢',
        'boolean': '✅'
    };
    
    const typeLabels = {
        'select': 'Dropdown',
        'multiselect': 'Multiselect',
        'color': 'Color',
        'text': 'Text',
        'number': 'Number',
        'boolean': 'Boolean'
    };

    tbody.innerHTML = attributes.map(attribute => {
        const badges = [];
        if (attribute.is_variant) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">Variant</span>');
        }
        if (attribute.is_filterable) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs">Filter</span>');
        }
        if (attribute.is_searchable) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">Searchable</span>');
        }
        if (attribute.is_required) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-xs">Required</span>');
        }
        
        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="attribute-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" data-id="${attribute.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="font-semibold text-gray-900">${attribute.name}</div>
                <div class="text-xs text-gray-500 mt-0.5">${attribute.slug}</div>
                ${attribute.group ? '<div class="text-xs text-gray-400 mt-0.5">Group: ' + attribute.group.name + '</div>' : ''}
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 ${typeColors[attribute.type] || 'bg-gray-100 text-gray-700'} rounded-md text-xs font-medium">${typeIcons[attribute.type] || ''} ${typeLabels[attribute.type] || attribute.type}</span>
            </td>
            <td class="px-6 py-4">
                <a href="/admin/attribute-values?attribute_id=${attribute.id}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">${attribute.values_count || 0} values</a>
            </td>
            <td class="px-6 py-4">
                ${badges.length > 0 ? '<div class="flex gap-1 flex-wrap">' + badges.join('') : '<span class="text-gray-400 text-sm">None</span>'}
                ${badges.length > 0 ? '</div>' : ''}
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${attribute.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${attribute.status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} rounded-md text-xs font-medium hover:opacity-80 transition-opacity">
                    <span class="w-1.5 h-1.5 rounded-full ${attribute.status ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${attribute.status ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="viewAttribute(${attribute.id})" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Manage values">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                    <button onclick="editAttribute(${attribute.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Edit attribute">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteAttribute(${attribute.id})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete attribute">
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
        container.innerHTML = `<div class="text-sm text-gray-600">Showing ${pagination.total} ${pagination.total === 1 ? 'attribute' : 'attributes'}</div>`;
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
        pages += `<button onclick="loadAttributes(1)" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">1</button>`;
        if (startPage > 2) pages += `<span class="px-2 text-gray-400">...</span>`;
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pages += `<button onclick="loadAttributes(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'} rounded-lg transition-colors">${i}</button>`;
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) pages += `<span class="px-2 text-gray-400">...</span>`;
        pages += `<button onclick="loadAttributes(${pagination.last_page})" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">${pagination.last_page}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">${((pagination.current_page - 1) * pagination.per_page) + 1}</span> to 
                <span class="font-medium">${Math.min(pagination.current_page * pagination.per_page, pagination.total)}</span> of 
                <span class="font-medium">${pagination.total}</span> attributes
            </div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}


function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Attribute';
    document.getElementById('submitBtnText').textContent = 'Create Attribute';
    document.getElementById('attributeForm').reset();
    document.getElementById('attributeId').value = '';
    document.getElementById('attributeStatus').checked = true;
    document.getElementById('attributeVariant').checked = false;
    document.getElementById('attributeFilterable').checked = false;
    document.getElementById('attributeSearchable').checked = false;
    document.getElementById('attributeRequired').checked = false;
    document.getElementById('attributeSortOrder').value = 0;
    
    // Reset select boxes and trigger change events for SearchableSelect
    const typeSelect = document.getElementById('attributeType');
    typeSelect.value = 'select';
    typeSelect.dispatchEvent(new Event('change', { bubbles: true }));
    
    const displayTypeSelect = document.getElementById('attributeDisplayType');
    displayTypeSelect.value = 'dropdown';
    displayTypeSelect.dispatchEvent(new Event('change', { bubbles: true }));
    
    const groupSelect = document.getElementById('attributeGroup');
    groupSelect.value = '';
    groupSelect.dispatchEvent(new Event('change', { bubbles: true }));
    
    document.getElementById('attributeUnit').value = '';
    document.getElementById('unitField').style.display = 'none';
    
    clearErrors();
    
    const modal = document.getElementById('attributeModal');
    const modalContent = document.getElementById('attributeModalContent');
    
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
    const modal = document.getElementById('attributeModal');
    const modalContent = document.getElementById('attributeModalContent');
    
    modalContent.style.transition = 'transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1)';
    modalContent.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'attributeModal') {
        closeModal();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('attributeModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    }
});

function clearErrors() {
    ['attributeNameError', 'attributeSlugError', 'attributeTypeError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
            el.textContent = '';
        }
    });
    
    ['attributeName', 'attributeSlug', 'attributeType'].forEach(id => {
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

function saveAttribute() {
    clearErrors();
    
    const id = document.getElementById('attributeId').value;
    const name = document.getElementById('attributeName').value;
    const slug = document.getElementById('attributeSlug').value;
    const type = document.getElementById('attributeType').value;
    const displayType = document.getElementById('attributeDisplayType').value;
    const groupId = document.getElementById('attributeGroup').value;
    const unit = document.getElementById('attributeUnit').value;
    const isVariant = document.getElementById('attributeVariant').checked;
    const isFilterable = document.getElementById('attributeFilterable').checked;
    const isSearchable = document.getElementById('attributeSearchable').checked;
    const isRequired = document.getElementById('attributeRequired').checked;
    const status = document.getElementById('attributeStatus').checked;
    const sortOrder = document.getElementById('attributeSortOrder').value;
    
    const url = id ? `/admin/attributes/${id}` : '/admin/attributes';
    const method = id ? 'PUT' : 'POST';
    
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    
    submitBtn.disabled = true;
    submitBtnText.classList.add('hidden');
    submitBtnLoading.classList.remove('hidden');
    
    const payload = {
        name,
        slug,
        type,
        display_type: displayType,
        is_variant: isVariant,
        is_filterable: isFilterable,
        is_searchable: isSearchable,
        is_required: isRequired,
        status,
        sort_order: sortOrder
    };
    
    if (groupId) {
        payload.group_id = groupId;
    }
    
    if (unit && type === 'number') {
        payload.unit = unit;
    }
    
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
            loadAttributes(currentPage);
        } else if (data.errors) {
            Object.keys(data.errors).forEach(field => {
                const fieldName = field.replace('_', '');
                const camelField = 'attribute' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
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

function editAttribute(id) {
    console.log('Edit clicked for ID:', id);
    
    fetch(`/admin/attributes/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => {
        console.log('Response status:', res.status);
        return res.json();
    })
    .then(data => {
        console.log('Received data:', data);
        
        if (data.success) {
            const attr = data.data;
            console.log('Attribute data:', attr);
            
            // First, open the modal
            const modal = document.getElementById('attributeModal');
            const modalContent = document.getElementById('attributeModalContent');
            
            if (!modal || !modalContent) {
                console.error('Modal elements not found!');
                return;
            }
            
            modalContent.style.transform = 'translateX(100%)';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Set all values
            document.getElementById('modalTitle').textContent = 'Edit Attribute';
            document.getElementById('submitBtnText').textContent = 'Update Attribute';
            document.getElementById('attributeId').value = attr.id;
            document.getElementById('attributeName').value = attr.name || '';
            document.getElementById('attributeSlug').value = attr.slug || '';
            document.getElementById('attributeUnit').value = attr.unit || '';
            document.getElementById('attributeSortOrder').value = attr.sort_order || 0;
            
            console.log('Basic fields set');
            
            // Set checkboxes
            document.getElementById('attributeVariant').checked = !!attr.is_variant;
            document.getElementById('attributeFilterable').checked = !!attr.is_filterable;
            document.getElementById('attributeSearchable').checked = !!attr.is_searchable;
            document.getElementById('attributeRequired').checked = !!attr.is_required;
            document.getElementById('attributeStatus').checked = !!attr.status;
            
            console.log('Checkboxes set');
            
            // Function to update SearchableSelect display
            const updateSearchableSelectDisplay = (selectElement, value) => {
                // Set the native select value
                selectElement.value = value || '';
                
                // Find the SearchableSelect wrapper
                const wrapper = selectElement.nextElementSibling;
                if (wrapper && wrapper.classList.contains('searchable-select-wrapper')) {
                    // Update display text
                    const displayText = wrapper.querySelector('.searchable-select-text');
                    if (displayText) {
                        const selectedOption = selectElement.options[selectElement.selectedIndex];
                        if (selectedOption && selectedOption.value) {
                            displayText.textContent = selectedOption.text;
                            displayText.classList.remove('text-gray-400');
                            displayText.classList.add('text-gray-700');
                        } else {
                            displayText.textContent = selectElement.getAttribute('data-placeholder') || 'Select...';
                            displayText.classList.remove('text-gray-700');
                            displayText.classList.add('text-gray-400');
                        }
                        console.log('Updated', selectElement.id, 'display to:', displayText.textContent);
                    }
                    
                    // Update dropdown options list
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
                        console.log('Updated', selectElement.id, 'options list with', selectElement.options.length, 'options');
                    }
                }
            };
            
            // Set select boxes
            const typeSelect = document.getElementById('attributeType');
            if (typeSelect) {
                updateSearchableSelectDisplay(typeSelect, attr.type || 'select');
                console.log('Type set to:', typeSelect.value);
            }
            
            const displayTypeSelect = document.getElementById('attributeDisplayType');
            if (displayTypeSelect) {
                updateSearchableSelectDisplay(displayTypeSelect, attr.display_type || 'dropdown');
                console.log('Display type set to:', displayTypeSelect.value);
            }
            
            // Set group value - check if groups are loaded first
            const groupSelect = document.getElementById('attributeGroup');
            if (groupSelect) {
                console.log('Group select found, options count:', groupSelect.options.length);
                console.log('Trying to set group_id:', attr.group_id);
                
                // Check if groups are already loaded (has more than 1 option)
                if (groupSelect.options.length > 1) {
                    updateSearchableSelectDisplay(groupSelect, attr.group_id);
                } else {
                    // Groups not loaded yet, wait for them
                    console.log('Groups not loaded, loading now...');
                    fetch('/admin/attribute-groups/list?per_page=1000&status=1', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(groupData => {
                        if (groupData.success) {
                            console.log('Groups loaded:', groupData.data.length);
                            groupSelect.innerHTML = '<option value="">No Group</option>';
                            groupData.data.forEach(group => {
                                groupSelect.innerHTML += `<option value="${group.id}">${group.name}</option>`;
                            });
                            // Now set the value and update display
                            updateSearchableSelectDisplay(groupSelect, attr.group_id);
                        }
                    });
                }
            }
            
            // Show/hide unit field
            const unitField = document.getElementById('unitField');
            if (attr.type === 'number') {
                unitField.style.display = 'block';
            } else {
                unitField.style.display = 'none';
            }
            
            clearErrors();
            
            console.log('All fields populated, animating modal');
            
            // Animate modal in
            modalContent.offsetHeight;
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    modalContent.style.transform = 'translateX(0)';
                });
            });
        } else {
            console.error('API returned success: false', data);
            showNotification(data.message || 'Failed to load attribute', 'error');
        }
    })
    .catch(error => {
        console.error('Error loading attribute:', error);
        showNotification('Failed to load attribute data', 'error');
    });
}

function viewAttribute(id) {
    window.location.href = `/admin/attribute-values?attribute_id=${id}`;
}

function deleteAttribute(id) {
    showConfirmDialog({
        title: 'Delete Attribute?',
        message: 'This will also delete all associated attribute values. This action cannot be undone.',
        type: 'danger',
        confirmText: 'Delete',
        onConfirm: () => {
            fetch(`/admin/attributes/${id}`, {
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
                    loadAttributes(currentPage);
                } else {
                    showNotification(data.message || 'Failed to delete attribute', 'error');
                }
            });
        }
    });
}

function toggleStatus(id) {
    fetch(`/admin/attributes/${id}/toggle-status`, {
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
            loadAttributes(currentPage);
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.attribute-checkbox');
    
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedAttributes.add(parseInt(cb.dataset.id));
        } else {
            selectedAttributes.delete(parseInt(cb.dataset.id));
        }
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.attribute-checkbox:checked');
    const bulkContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    const selectAll = document.getElementById('selectAll');
    
    selectedAttributes.clear();
    checkboxes.forEach(cb => selectedAttributes.add(parseInt(cb.dataset.id)));
    
    if (selectedAttributes.size > 0) {
        bulkContainer.classList.remove('hidden');
        selectedCount.textContent = `${selectedAttributes.size} selected`;
    } else {
        bulkContainer.classList.add('hidden');
    }
    
    const allCheckboxes = document.querySelectorAll('.attribute-checkbox');
    selectAll.checked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
}

function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (!action) {
        showNotification('Please select an action', 'error');
        return;
    }
    
    if (selectedAttributes.size === 0) {
        showNotification('Please select at least one attribute', 'error');
        return;
    }
    
    const actionText = action === 'delete' ? 'delete' : action;
    showConfirmDialog({
        title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Attributes?`,
        message: `Are you sure you want to ${actionText} ${selectedAttributes.size} attribute(s)?`,
        type: action === 'delete' ? 'danger' : 'warning',
        confirmText: actionText.charAt(0).toUpperCase() + actionText.slice(1),
        onConfirm: () => {
            fetch('/admin/attributes/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    ids: Array.from(selectedAttributes)
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    selectedAttributes.clear();
                    document.getElementById('selectAll').checked = false;
                    document.getElementById('bulkActionsContainer').classList.add('hidden');
                    loadAttributes(currentPage);
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
    
    const backdrop = document.createElement('div');
    backdrop.className = 'fixed inset-0 bg-gray-900/50 backdrop-blur-sm';
    backdrop.onclick = () => dialog.remove();
    
    const content = document.createElement('div');
    content.className = 'relative bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4';
    content.style.animation = 'scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
    content.innerHTML = `
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
            <button id="cancelBtn" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button id="confirmBtn" class="px-4 py-2 ${options.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-600 hover:bg-yellow-700'} text-white text-sm font-semibold rounded-lg transition-colors">
                ${options.confirmText}
            </button>
        </div>
    `;
    
    dialog.appendChild(backdrop);
    dialog.appendChild(content);
    
    // Add event listeners
    content.querySelector('#cancelBtn').onclick = () => dialog.remove();
    content.querySelector('#confirmBtn').onclick = () => {
        dialog.remove();
        if (options.onConfirm) options.onConfirm();
    };
    
    const style = document.createElement('style');
    style.textContent = '@keyframes scaleIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }';
    document.head.appendChild(style);
    
    document.body.appendChild(dialog);
}
</script>
@endpush
