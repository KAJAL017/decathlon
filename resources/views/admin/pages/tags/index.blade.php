@extends('admin.layouts.app')

@section('title', 'Tags Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tags</h1>
            <p class="text-sm text-gray-600 mt-1">Manage product tags for better organization</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Tag
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Tags</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalTags">
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
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeTags">
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
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="inactiveTags">
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
                    placeholder="Search by name, slug or description..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadTags()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadTags()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Tags Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tag</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="tagsTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Skeleton Loader -->
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-4 rounded"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-32 mb-2"></div>
                                    <div class="skeleton-text h-3 w-24"></div>
                                </div>
                            </div>
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
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-28 mb-2"></div>
                                    <div class="skeleton-text h-3 w-20"></div>
                                </div>
                            </div>
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
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-36 mb-2"></div>
                                    <div class="skeleton-text h-3 w-28"></div>
                                </div>
                            </div>
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
<div id="tagModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <!-- Modal Content -->
    <div id="tagModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Tag</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new product tag</p>
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
        <form id="tagForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4">
                <input type="hidden" id="tagId">
                
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
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tag Name <span class="text-red-500">*</span></label>
                            <input type="text" id="tagName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., New Arrival" required>
                            <p id="tagNameError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-gray-500 text-xs">(Auto-generated)</span></label>
                            <input type="text" id="tagSlug" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="new-arrival">
                            <p id="tagSlugError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea id="tagDescription" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Brief description of the tag"></textarea>
                            <p id="tagDescriptionError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                            <input type="number" id="tagSortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                            <p id="tagSortOrderError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                            <label class="flex items-center gap-2 cursor-pointer mt-2">
                                <input type="checkbox" id="tagStatus" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button type="button" onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="button" onclick="saveTag()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                <span id="saveButtonText">Save Tag</span>
            </button>
        </div>
    </div>
</div>


<!-- View Details Modal -->
<div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeViewModal(event)">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                <h3 class="text-lg font-semibold text-gray-900">Tag Details</h3>
                <button onclick="closeViewModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div id="viewModalContent" class="px-6 py-4">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="dialog-panel inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="flex justify-center pt-8 pb-4">
                <div class="dialog-avatar relative">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="text-center px-6 pb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Tag</h3>
                <p class="text-sm text-gray-600 mb-1">Are you sure you want to delete this tag?</p>
                <p class="text-sm font-semibold text-gray-900" id="deleteTagName"></p>
                <p class="text-xs text-red-600 mt-2">This action cannot be undone.</p>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="confirmDelete()" class="px-6 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Delete Tag
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Global Variables
let currentPage = 1;
let searchTimeout;
let selectedTags = new Set();
let deleteTagId = null;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTags();
    initSlugGeneration();
});

// Load Tags
function loadTags(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const perPage = document.getElementById('perPageSelect').value;

    // Show skeleton
    document.querySelectorAll('.skeleton-row').forEach(row => row.classList.remove('hidden'));

    fetch(`/admin/tags/list?page=${page}&search=${search}&status=${status}&per_page=${perPage}`)
        .then(response => response.json())
        .then(data => {
            // Hide skeleton
            document.querySelectorAll('.skeleton-row').forEach(row => row.classList.add('hidden'));

            // Update stats
            updateStats(data.stats);

            // Update table
            updateTable(data.tags);

            // Update pagination
            updatePagination(data.tags);
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load tags', 'error');
        });
}

// Update Stats
function updateStats(stats) {
    document.getElementById('totalTags').textContent = stats.total || 0;
    document.getElementById('activeTags').textContent = stats.active || 0;
    document.getElementById('inactiveTags').textContent = stats.inactive || 0;
}

// Update Table
function updateTable(tags) {
    const tbody = document.getElementById('tagsTableBody');
    
    if (tags.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">No tags found</p>
                    <p class="text-gray-400 text-sm mt-1">Create your first tag to get started</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = tags.data.map(tag => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="tag-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" 
                    data-id="${tag.id}" 
                    onchange="updateBulkActions()"
                    ${selectedTags.has(tag.id) ? 'checked' : ''}>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${tag.name}</p>
                        <p class="text-xs text-gray-500 truncate">${tag.slug}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    ${tag.products_count || 0}
                </span>
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${tag.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${tag.status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} text-xs font-medium rounded-md hover:opacity-80 transition-opacity">
                    <span class="w-1.5 h-1.5 rounded-full ${tag.status ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${tag.status ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4">
                <span class="text-sm text-gray-600">${tag.sort_order || 0}</span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="viewTag(${tag.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    <button onclick="editTag(${tag.id})" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteTag(${tag.id}, '${tag.name.replace(/'/g, "\\'")}', ${tag.products_count || 0})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update Pagination
function updatePagination(tags) {
    const container = document.getElementById('paginationContainer');
    
    if (tags.last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let paginationHTML = '<div class="flex items-center justify-between">';
    paginationHTML += `<p class="text-sm text-gray-600">Showing ${tags.from} to ${tags.to} of ${tags.total} tags</p>`;
    paginationHTML += '<div class="flex items-center gap-2">';

    // Previous button
    if (tags.current_page > 1) {
        paginationHTML += `<button onclick="loadTags(${tags.current_page - 1})" class="px-3 py-1.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Previous</button>`;
    }

    // Page numbers
    for (let i = 1; i <= tags.last_page; i++) {
        if (i === 1 || i === tags.last_page || (i >= tags.current_page - 1 && i <= tags.current_page + 1)) {
            paginationHTML += `<button onclick="loadTags(${i})" class="px-3 py-1.5 ${i === tags.current_page ? 'bg-[#0082C3] text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'} text-sm font-medium rounded-lg transition-colors">${i}</button>`;
        } else if (i === tags.current_page - 2 || i === tags.current_page + 2) {
            paginationHTML += '<span class="px-2 text-gray-500">...</span>';
        }
    }

    // Next button
    if (tags.current_page < tags.last_page) {
        paginationHTML += `<button onclick="loadTags(${tags.current_page + 1})" class="px-3 py-1.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Next</button>`;
    }

    paginationHTML += '</div></div>';
    container.innerHTML = paginationHTML;
}


// Search with debounce
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadTags(1);
    }, 500);
}

// Bulk Actions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.tag-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedTags.add(parseInt(checkbox.dataset.id));
        } else {
            selectedTags.delete(parseInt(checkbox.dataset.id));
        }
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.tag-checkbox:checked');
    const bulkContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    const selectAll = document.getElementById('selectAll');
    
    selectedTags.clear();
    checkboxes.forEach(cb => selectedTags.add(parseInt(cb.dataset.id)));
    
    if (selectedTags.size > 0) {
        bulkContainer.classList.remove('hidden');
        bulkContainer.classList.add('flex', 'items-center', 'gap-2');
        selectedCount.textContent = `${selectedTags.size} selected`;
    } else {
        bulkContainer.classList.add('hidden');
        bulkContainer.classList.remove('flex', 'items-center', 'gap-2');
    }
    
    selectAll.checked = checkboxes.length > 0 && checkboxes.length === document.querySelectorAll('.tag-checkbox').length;
}

function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (!action) {
        showToast('Please select an action', 'error');
        return;
    }
    
    if (selectedTags.size === 0) {
        showToast('Please select at least one tag', 'error');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`Are you sure you want to delete ${selectedTags.size} tag(s)?`)) {
            return;
        }
    }
    
    fetch('/admin/tags/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            action: action,
            ids: Array.from(selectedTags)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            selectedTags.clear();
            document.getElementById('bulkActionSelect').value = '';
            loadTags(currentPage);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to perform bulk action', 'error');
    });
}

// Modal Functions
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Tag';
    document.getElementById('tagForm').reset();
    document.getElementById('tagId').value = '';
    document.getElementById('tagStatus').checked = true;
    document.getElementById('tagSortOrder').value = 0;
    
    // Clear errors
    document.querySelectorAll('[id$="Error"]').forEach(el => el.classList.add('hidden'));
    
    openModal();
}

function openModal() {
    const modal = document.getElementById('tagModal');
    const modalContent = document.getElementById('tagModalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        modalContent.style.transform = 'translateX(0)';
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('tagModal');
    const modalContent = document.getElementById('tagModalContent');
    
    modalContent.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 500);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'tagModal') {
        closeModal();
    }
}

// Slug Generation
function initSlugGeneration() {
    const nameInput = document.getElementById('tagName');
    const slugInput = document.getElementById('tagSlug');
    
    nameInput.addEventListener('input', function() {
        if (!document.getElementById('tagId').value) {
            slugInput.value = generateSlug(this.value);
        }
    });
}

function generateSlug(text) {
    return text
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
}

// Save Tag
function saveTag() {
    const tagId = document.getElementById('tagId').value;
    const url = tagId ? `/admin/tags/${tagId}` : '/admin/tags';
    const method = tagId ? 'PUT' : 'POST';
    
    const data = {
        name: document.getElementById('tagName').value,
        slug: document.getElementById('tagSlug').value,
        description: document.getElementById('tagDescription').value,
        sort_order: document.getElementById('tagSortOrder').value,
        status: document.getElementById('tagStatus').checked ? 1 : 0
    };
    
    // Clear previous errors
    document.querySelectorAll('[id$="Error"]').forEach(el => el.classList.add('hidden'));
    
    // Disable button
    const saveBtn = document.getElementById('saveButtonText');
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal();
            loadTags(currentPage);
        } else {
            if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const errorElement = document.getElementById(`tag${key.charAt(0).toUpperCase() + key.slice(1)}Error`);
                    if (errorElement) {
                        errorElement.textContent = data.errors[key][0];
                        errorElement.classList.remove('hidden');
                    }
                });
            }
            showToast(data.message || 'Validation failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to save tag', 'error');
    })
    .finally(() => {
        saveBtn.textContent = originalText;
    });
}

// Edit Tag
function editTag(id) {
    fetch(`/admin/tags/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tag = data.tag;
                
                document.getElementById('modalTitle').textContent = 'Edit Tag';
                document.getElementById('tagId').value = tag.id;
                document.getElementById('tagName').value = tag.name;
                document.getElementById('tagSlug').value = tag.slug;
                document.getElementById('tagDescription').value = tag.description || '';
                document.getElementById('tagSortOrder').value = tag.sort_order || 0;
                document.getElementById('tagStatus').checked = tag.status;
                
                openModal();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load tag', 'error');
        });
}

// View Tag
function viewTag(id) {
    fetch(`/admin/tags/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tag = data.tag;
                
                const content = `
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Basic Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Name</p>
                                    <p class="text-sm font-medium text-gray-900">${tag.name}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Slug</p>
                                    <p class="text-sm font-medium text-gray-900">${tag.slug}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Status</p>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 ${tag.status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} text-xs font-medium rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full ${tag.status ? 'bg-green-600' : 'bg-red-600'}"></span>
                                        ${tag.status ? 'Active' : 'Inactive'}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Sort Order</p>
                                    <p class="text-sm font-medium text-gray-900">${tag.sort_order || 0}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Products Count</p>
                                    <p class="text-sm font-medium text-gray-900">${tag.products_count || 0}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Created</p>
                                    <p class="text-sm font-medium text-gray-900">${new Date(tag.created_at).toLocaleDateString()}</p>
                                </div>
                            </div>
                        </div>
                        ${tag.description ? `
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Description</h4>
                            <p class="text-sm text-gray-600">${tag.description}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('viewModalContent').innerHTML = content;
                document.getElementById('viewModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load tag', 'error');
        });
}

function closeViewModal(event) {
    if (!event || event.target.id === 'viewModal' || event.currentTarget.id === 'viewModal') {
        document.getElementById('viewModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Delete Tag
function deleteTag(id, name, productsCount) {
    deleteTagId = id;
    document.getElementById('deleteTagName').textContent = name;
    
    if (productsCount > 0) {
        document.getElementById('deleteTagName').innerHTML = `${name}<br><span class="text-xs text-orange-600 mt-1 inline-block">Warning: This tag is used by ${productsCount} product(s)</span>`;
    }
    
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
    deleteTagId = null;
}

function confirmDelete() {
    if (!deleteTagId) return;
    
    fetch(`/admin/tags/${deleteTagId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeDeleteModal();
            loadTags(currentPage);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to delete tag', 'error');
    });
}

// Toggle Status
function toggleStatus(id) {
    fetch(`/admin/tags/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadTags(currentPage);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to toggle status', 'error');
    });
}

// Demo Data
function fillDemoData() {
    const demoTags = [
        { name: 'New Arrival', description: 'Latest products in our collection' },
        { name: 'Best Seller', description: 'Most popular products' },
        { name: 'Sale', description: 'Products on sale' },
        { name: 'Featured', description: 'Featured products' },
        { name: 'Limited Edition', description: 'Limited stock items' }
    ];
    
    const randomTag = demoTags[Math.floor(Math.random() * demoTags.length)];
    
    document.getElementById('tagName').value = randomTag.name;
    document.getElementById('tagSlug').value = generateSlug(randomTag.name);
    document.getElementById('tagDescription').value = randomTag.description;
    document.getElementById('tagSortOrder').value = Math.floor(Math.random() * 10);
    document.getElementById('tagStatus').checked = true;
}

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} animate-slide-in`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.animate-slide-out {
    animation: slide-out 0.3s ease-in;
}

.skeleton-text {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 4px;
}

.skeleton-avatar {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}
</style>
@endpush
