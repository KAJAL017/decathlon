@extends('admin.layouts.app')

@section('title', 'Attribute Groups Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Attribute Groups</h1>
            <p class="text-sm text-gray-600 mt-1">Organize attributes into logical groups</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Group
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Groups</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalGroups">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeGroups">
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
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="inactiveGroups">
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
            <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadGroups()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadGroups()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
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
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Group</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Attributes</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="groupsTableBody" class="divide-y divide-gray-200 bg-white">
                    <tr class="skeleton-row">
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-4 rounded"></div></td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-32 mb-2"></div>
                            <div class="skeleton-text h-3 w-24"></div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-48"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-16"></div></td>
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
                            <div class="skeleton-text h-4 w-28 mb-2"></div>
                            <div class="skeleton-text h-3 w-20"></div>
                        </td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-40"></div></td>
                        <td class="px-6 py-4"><div class="skeleton-text h-4 w-16"></div></td>
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
<div id="groupModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <div id="groupModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Attribute Group</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new attribute group</p>
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

        <form id="groupForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4">
                <input type="hidden" id="groupId">
                
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900">Group Information</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Group Name <span class="text-red-500">*</span></label>
                            <input type="text" id="groupName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., General, Size & Fit, Technical Specs" required>
                            <p id="groupNameError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-gray-500 text-xs">(Auto-generated)</span></label>
                            <input type="text" id="groupSlug" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="general-size-fit">
                            <p id="groupSlugError" class="hidden text-xs text-red-600 mt-1"></p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea id="groupDescription" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="Brief description of this group"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                            <input type="number" id="groupSortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center gap-2 cursor-pointer mt-6">
                                <input type="checkbox" id="groupStatus" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
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
            <button type="submit" form="groupForm" id="submitBtn" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Create Group</span>
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
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}
</style>

<script>
let currentPage = 1;
let searchTimeout;
let selectedGroups = new Set();

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('searchInput') && document.getElementById('groupsTableBody')) {
        loadGroups();
    }
    
    document.getElementById('groupName')?.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('groupSlug').value = slug;
    });
    
    document.getElementById('groupForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveGroup();
    });
});

function loadGroups(page = 1) {
    currentPage = page;
    
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const perPageSelect = document.getElementById('perPageSelect');
    
    if (!searchInput || !statusFilter || !perPageSelect) {
        console.error('Required elements not found');
        return;
    }
    
    const search = searchInput.value;
    const status = statusFilter.value;
    const perPage = perPageSelect.value;

    const url = `/admin/attribute-groups/list?page=${page}&search=${search}&status=${status}&per_page=${perPage}`;

    const tbody = document.getElementById('groupsTableBody');
    const skeletonRows = tbody.querySelectorAll('.skeleton-row');
    skeletonRows.forEach(row => row.style.display = '');

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderGroups(data.data);
            renderPagination(data.pagination);
            updateStats(data.data, data.pagination);
        }
    })
    .catch(error => {
        console.error('Error loading groups:', error);
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-red-600">
                        Error loading attribute groups. Please refresh the page.
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
            loadGroups(1);
        });
    }, 300);
}

function updateStats(groups, pagination) {
    const totalGroupsEl = document.getElementById('totalGroups');
    const activeGroupsEl = document.getElementById('activeGroups');
    const inactiveGroupsEl = document.getElementById('inactiveGroups');
    
    totalGroupsEl.innerHTML = pagination.total;
    
    fetch('/admin/attribute-groups/list?per_page=1000', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const allGroups = data.data;
            const active = allGroups.filter(g => g.status).length;
            const inactive = allGroups.filter(g => !g.status).length;
            
            activeGroupsEl.innerHTML = active;
            inactiveGroupsEl.innerHTML = inactive;
        }
    });
}

function renderGroups(groups) {
    const tbody = document.getElementById('groupsTableBody');
    
    if (groups.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">No attribute groups found</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filters</p>
                        </div>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = groups.map(group => {
        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="group-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" data-id="${group.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="font-semibold text-gray-900">${group.name}</div>
                <div class="text-xs text-gray-500 mt-0.5">${group.slug}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-600 max-w-md truncate">${group.description || '-'}</div>
            </td>
            <td class="px-6 py-4">
                <span class="text-sm text-blue-600 font-medium">${group.attributes_count || 0} attributes</span>
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${group.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${group.status ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} rounded-md text-xs font-medium hover:opacity-80 transition-opacity">
                    <span class="w-1.5 h-1.5 rounded-full ${group.status ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${group.status ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editGroup(${group.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Edit group">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteGroup(${group.id})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete group">
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
        container.innerHTML = `<div class="text-sm text-gray-600">Showing ${pagination.total} ${pagination.total === 1 ? 'group' : 'groups'}</div>`;
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
        pages += `<button onclick="loadGroups(1)" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">1</button>`;
        if (startPage > 2) pages += `<span class="px-2 text-gray-400">...</span>`;
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pages += `<button onclick="loadGroups(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'} rounded-lg transition-colors">${i}</button>`;
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) pages += `<span class="px-2 text-gray-400">...</span>`;
        pages += `<button onclick="loadGroups(${pagination.last_page})" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">${pagination.last_page}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">${((pagination.current_page - 1) * pagination.per_page) + 1}</span> to 
                <span class="font-medium">${Math.min(pagination.current_page * pagination.per_page, pagination.total)}</span> of 
                <span class="font-medium">${pagination.total}</span> groups
            </div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Attribute Group';
    document.getElementById('submitBtnText').textContent = 'Create Group';
    document.getElementById('groupForm').reset();
    document.getElementById('groupId').value = '';
    document.getElementById('groupStatus').checked = true;
    document.getElementById('groupSortOrder').value = 0;
    
    clearErrors();
    
    const modal = document.getElementById('groupModal');
    const modalContent = document.getElementById('groupModalContent');
    
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

// Fill Demo Data Function
function fillDemoData() {
    // Sample demo attribute groups
    const demoGroups = [
        {
            name: 'General',
            slug: 'general',
            description: 'Basic product information and general attributes',
            sortOrder: 1
        },
        {
            name: 'Size & Fit',
            slug: 'size-fit',
            description: 'Size, dimensions, and fitting information for products',
            sortOrder: 2
        },
        {
            name: 'Technical Specs',
            slug: 'technical-specs',
            description: 'Technical specifications and detailed product features',
            sortOrder: 3
        },
        {
            name: 'Material & Care',
            slug: 'material-care',
            description: 'Material composition and care instructions',
            sortOrder: 4
        },
        {
            name: 'Performance',
            slug: 'performance',
            description: 'Performance characteristics and ratings',
            sortOrder: 5
        },
        {
            name: 'Design & Style',
            slug: 'design-style',
            description: 'Design elements, colors, and style attributes',
            sortOrder: 6
        },
        {
            name: 'Compatibility',
            slug: 'compatibility',
            description: 'Compatibility information with other products or systems',
            sortOrder: 7
        },
        {
            name: 'Warranty & Support',
            slug: 'warranty-support',
            description: 'Warranty details and customer support information',
            sortOrder: 8
        }
    ];
    
    // Pick a random demo group
    const randomGroup = demoGroups[Math.floor(Math.random() * demoGroups.length)];
    
    // Fill the form
    document.getElementById('groupName').value = randomGroup.name;
    document.getElementById('groupSlug').value = randomGroup.slug;
    document.getElementById('groupDescription').value = randomGroup.description;
    document.getElementById('groupSortOrder').value = randomGroup.sortOrder;
    document.getElementById('groupStatus').checked = true;
    
    showNotification('Demo data filled successfully! You can now modify and save.', 'success');
    
    // Add a subtle highlight animation to the form
    const form = document.getElementById('groupForm');
    form.style.animation = 'pulse 0.5s ease-in-out';
    setTimeout(() => {
        form.style.animation = '';
    }, 500);
}

function closeModal() {
    const modal = document.getElementById('groupModal');
    const modalContent = document.getElementById('groupModalContent');
    
    modalContent.style.transition = 'transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1)';
    modalContent.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'groupModal') {
        closeModal();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('groupModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    }
});

function clearErrors() {
    ['groupNameError', 'groupSlugError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
            el.textContent = '';
        }
    });
    
    ['groupName', 'groupSlug'].forEach(id => {
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

function saveGroup() {
    clearErrors();
    
    const id = document.getElementById('groupId').value;
    const name = document.getElementById('groupName').value;
    const slug = document.getElementById('groupSlug').value;
    const description = document.getElementById('groupDescription').value;
    const status = document.getElementById('groupStatus').checked;
    const sortOrder = document.getElementById('groupSortOrder').value;
    
    const url = id ? `/admin/attribute-groups/${id}` : '/admin/attribute-groups';
    const method = id ? 'PUT' : 'POST';
    
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    
    submitBtn.disabled = true;
    submitBtnText.classList.add('hidden');
    submitBtnLoading.classList.remove('hidden');
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name,
            slug,
            description,
            status,
            sort_order: sortOrder
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal();
            loadGroups(currentPage);
        } else if (data.errors) {
            Object.keys(data.errors).forEach(field => {
                const fieldName = field.replace('_', '');
                const camelField = 'group' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
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

function editGroup(id) {
    fetch(`/admin/attribute-groups/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const group = data.data;
            
            // Open modal first
            const modal = document.getElementById('groupModal');
            const modalContent = document.getElementById('groupModalContent');
            
            modalContent.style.transform = 'translateX(100%)';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Set modal title and button text
            document.getElementById('modalTitle').textContent = 'Edit Attribute Group';
            document.getElementById('submitBtnText').textContent = 'Update Group';
            
            // Set form values
            document.getElementById('groupId').value = group.id;
            document.getElementById('groupName').value = group.name;
            document.getElementById('groupSlug').value = group.slug;
            document.getElementById('groupDescription').value = group.description || '';
            document.getElementById('groupStatus').checked = group.status;
            document.getElementById('groupSortOrder').value = group.sort_order;
            
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
        console.error('Error loading group:', error);
        showNotification('Failed to load group data', 'error');
    });
}

function deleteGroup(id) {
    showConfirmDialog({
        title: 'Delete Attribute Group?',
        message: 'This action cannot be undone. Make sure no attributes are assigned to this group.',
        type: 'danger',
        confirmText: 'Delete',
        onConfirm: () => {
            fetch(`/admin/attribute-groups/${id}`, {
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
                    loadGroups(currentPage);
                } else {
                    showNotification(data.message || 'Failed to delete group', 'error');
                }
            });
        }
    });
}

function toggleStatus(id) {
    fetch(`/admin/attribute-groups/${id}/toggle-status`, {
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
            loadGroups(currentPage);
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.group-checkbox');
    
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedGroups.add(parseInt(cb.dataset.id));
        } else {
            selectedGroups.delete(parseInt(cb.dataset.id));
        }
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.group-checkbox:checked');
    const bulkContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    const selectAll = document.getElementById('selectAll');
    
    selectedGroups.clear();
    checkboxes.forEach(cb => selectedGroups.add(parseInt(cb.dataset.id)));
    
    if (selectedGroups.size > 0) {
        bulkContainer.classList.remove('hidden');
        selectedCount.textContent = `${selectedGroups.size} selected`;
    } else {
        bulkContainer.classList.add('hidden');
    }
    
    const allCheckboxes = document.querySelectorAll('.group-checkbox');
    selectAll.checked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
}

function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (!action) {
        showNotification('Please select an action', 'error');
        return;
    }
    
    if (selectedGroups.size === 0) {
        showNotification('Please select at least one group', 'error');
        return;
    }
    
    const actionText = action === 'delete' ? 'delete' : action;
    showConfirmDialog({
        title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Groups?`,
        message: `Are you sure you want to ${actionText} ${selectedGroups.size} group(s)?`,
        type: action === 'delete' ? 'danger' : 'warning',
        confirmText: actionText.charAt(0).toUpperCase() + actionText.slice(1),
        onConfirm: () => {
            fetch('/admin/attribute-groups/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    ids: Array.from(selectedGroups)
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    selectedGroups.clear();
                    document.getElementById('selectAll').checked = false;
                    document.getElementById('bulkActionsContainer').classList.add('hidden');
                    loadGroups(currentPage);
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
