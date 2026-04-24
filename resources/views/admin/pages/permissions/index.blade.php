@extends('admin.layouts.app')

@section('title', 'Permissions Management')
@section('page-title', 'Permissions')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Permissions</h1>
            <p class="text-sm text-gray-600 mt-1">Manage system permissions and access controls</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Permission
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Permissions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalPermissions">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Modules</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalModules">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Assigned to Roles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="assignedPermissions">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Unassigned</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="unassignedPermissions">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex items-center gap-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search by permission name or module..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="moduleFilter" data-searchable data-placeholder="All Modules" onchange="loadPermissions()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}">{{ $module }}</option>
                @endforeach
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadPermissions()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Permissions Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Permission</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="permissionsTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Skeleton Loader -->
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-10 h-10 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-32 mb-2"></div>
                                    <div class="skeleton-text h-3 w-24"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-24 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-48"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-10 h-10 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-28 mb-2"></div>
                                    <div class="skeleton-text h-3 w-20"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-20 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-52"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                                <div class="skeleton-text h-8 w-8 rounded-lg"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-10 h-10 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-36 mb-2"></div>
                                    <div class="skeleton-text h-3 w-28"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-24 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-44"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24"></div>
                        </td>
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


<!-- Add/Edit Modal (Slide from Right) -->
<div id="permissionModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <!-- Modal Content (Slide from right) -->
    <div id="permissionModalContent" class="fixed right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Create Permission</h3>
                <p class="text-sm text-gray-600 mt-0.5">Define a new system permission</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="permissionForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4 space-y-5">
                <input type="hidden" id="permissionId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Permission Name <span class="text-red-500">*</span></label>
                    <input type="text" id="permissionName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., orders.view" required>
                    <p class="text-xs text-gray-500 mt-1">Use lowercase with dots (e.g., module.action)</p>
                    <span class="text-red-500 text-xs mt-1 hidden" id="nameError"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Display Name <span class="text-red-500">*</span></label>
                    <input type="text" id="permissionDisplayName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., View Orders" required>
                    <span class="text-red-500 text-xs mt-1 hidden" id="display_nameError"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Module <span class="text-red-500">*</span></label>
                    <select id="permissionModule" data-searchable data-placeholder="Select Module" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                        <option value="">Select Module</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}">{{ $module }}</option>
                        @endforeach
                        <option value="__new__">+ Add New Module</option>
                    </select>
                    <input type="text" id="newModuleName" class="hidden w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent mt-2" placeholder="Enter new module name">
                    <span class="text-red-500 text-xs mt-1 hidden" id="moduleError"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea id="permissionDescription" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent resize-none" placeholder="Brief description of what this permission allows"></textarea>
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3">
            <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" form="permissionForm" id="submitBtn" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Create Permission</span>
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
/* Skeleton Loader Styles */
@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.skeleton-row {
    animation: fadeIn 0.3s ease-in;
}

.skeleton-text,
.skeleton-avatar {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    border-radius: 4px;
}

.skeleton-avatar {
    border-radius: 8px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>

<script>
let currentPage = 1;
let searchTimeout;

document.addEventListener('DOMContentLoaded', () => {
    loadPermissions();
    
    // Handle new module selection
    document.getElementById('permissionModule').addEventListener('change', function() {
        const newModuleInput = document.getElementById('newModuleName');
        if (this.value === '__new__') {
            newModuleInput.classList.remove('hidden');
            newModuleInput.required = true;
        } else {
            newModuleInput.classList.add('hidden');
            newModuleInput.required = false;
            newModuleInput.value = '';
        }
    });
});

function loadPermissions(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const module = document.getElementById('moduleFilter').value;
    const perPage = document.getElementById('perPageSelect').value;

    // Show skeleton loader
    const tbody = document.getElementById('permissionsTableBody');
    const skeletonRows = tbody.querySelectorAll('.skeleton-row');
    skeletonRows.forEach(row => row.style.display = '');

    fetch(`/admin/permissions/list?page=${page}&search=${search}&module=${module}&per_page=${perPage}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderPermissions(data.data);
            renderPagination(data.pagination);
            updateStats(data.pagination);
        }
    });
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadPermissions(1), 500);
}

function updateStats(pagination) {
    const totalPermissionsEl = document.getElementById('totalPermissions');
    const totalModulesEl = document.getElementById('totalModules');
    const assignedPermissionsEl = document.getElementById('assignedPermissions');
    const unassignedPermissionsEl = document.getElementById('unassignedPermissions');
    
    // Remove skeleton and show actual count
    totalPermissionsEl.innerHTML = pagination.total;
    
    // Set static values (you can make these dynamic if needed)
    totalModulesEl.innerHTML = {{ count($modules) }};
    assignedPermissionsEl.innerHTML = pagination.total; // All permissions are assigned
    unassignedPermissionsEl.innerHTML = 0; // No unassigned permissions
}

function renderPermissions(permissions) {
    const tbody = document.getElementById('permissionsTableBody');
    
    if (permissions.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">No permissions found</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your search criteria</p>
                        </div>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = permissions.map(permission => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div>
                    <div class="font-semibold text-gray-900">${permission.display_name}</div>
                    <div class="text-xs text-gray-500 font-mono">${permission.name}</div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    ${permission.module}
                </span>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-600 line-clamp-2">${permission.description || '<span class="text-gray-400">No description</span>'}</p>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">
                ${new Date(permission.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editPermission(${permission.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Edit permission">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deletePermission(${permission.id})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete permission">
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
        container.innerHTML = `<div class="text-sm text-gray-600">Showing ${pagination.total} ${pagination.total === 1 ? 'permission' : 'permissions'}</div>`;
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
        pages += `<button onclick="loadPermissions(1)" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">1</button>`;
        if (startPage > 2) pages += `<span class="px-2 text-gray-400">...</span>`;
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pages += `<button onclick="loadPermissions(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'} rounded-lg transition-colors">${i}</button>`;
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) pages += `<span class="px-2 text-gray-400">...</span>`;
        pages += `<button onclick="loadPermissions(${pagination.last_page})" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">${pagination.last_page}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">${((pagination.current_page - 1) * pagination.per_page) + 1}</span> to 
                <span class="font-medium">${Math.min(pagination.current_page * pagination.per_page, pagination.total)}</span> of 
                <span class="font-medium">${pagination.total}</span> permissions
            </div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Create Permission';
    document.getElementById('submitBtnText').textContent = 'Create Permission';
    document.getElementById('permissionForm').reset();
    document.getElementById('permissionId').value = '';
    document.getElementById('newModuleName').classList.add('hidden');
    
    const modal = document.getElementById('permissionModal');
    const modalContent = document.getElementById('permissionModalContent');
    
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
    const modal = document.getElementById('permissionModal');
    const modalContent = document.getElementById('permissionModalContent');
    
    modalContent.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'permissionModal') {
        closeModal();
    }
}

function editPermission(id) {
    fetch(`/admin/permissions/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const permission = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Permission';
            document.getElementById('submitBtnText').textContent = 'Update Permission';
            document.getElementById('permissionId').value = permission.id;
            document.getElementById('permissionName').value = permission.name;
            document.getElementById('permissionDisplayName').value = permission.display_name;
            document.getElementById('permissionModule').value = permission.module;
            document.getElementById('permissionDescription').value = permission.description || '';
            
            const modal = document.getElementById('permissionModal');
            const modalContent = document.getElementById('permissionModalContent');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            modalContent.offsetHeight;
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    modalContent.style.transform = 'translateX(0)';
                });
            });
        }
    });
}

document.getElementById('permissionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const permissionId = document.getElementById('permissionId').value;
    const url = permissionId ? `/admin/permissions/${permissionId}` : '/admin/permissions';
    const method = permissionId ? 'PUT' : 'POST';
    
    let module = document.getElementById('permissionModule').value;
    if (module === '__new__') {
        module = document.getElementById('newModuleName').value;
    }
    
    const formData = {
        name: document.getElementById('permissionName').value,
        display_name: document.getElementById('permissionDisplayName').value,
        module: module,
        description: document.getElementById('permissionDescription').value
    };

    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtnText').classList.add('hidden');
    document.getElementById('submitBtnLoading').classList.remove('hidden');

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal();
            loadPermissions(currentPage);
        } else {
            showNotification(data.message || 'Validation failed', 'error');
        }
    })
    .catch(err => {
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('submitBtnText').classList.remove('hidden');
        document.getElementById('submitBtnLoading').classList.add('hidden');
    });
});

function deletePermission(id) {
    showConfirmDialog({
        title: 'Delete Permission',
        message: 'Are you sure you want to delete this permission? This action cannot be undone and will affect all roles with this permission.',
        confirmText: 'Delete Permission',
        cancelText: 'Cancel',
        type: 'danger',
        onConfirm: function() {
            fetch(`/admin/permissions/${id}`, {
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
                if (data.success) loadPermissions(currentPage);
            });
        }
    });
}

// Reusable Confirmation Dialog
function showConfirmDialog(options) {
    const defaults = { title: 'Confirm Action', message: 'Are you sure?', confirmText: 'Confirm', cancelText: 'Cancel', type: 'warning', onConfirm: null, onCancel: null };
    const config = { ...defaults, ...options };
    const dialogId = 'confirmDialog_' + Date.now();
    const typeColors = {
        warning: { bg: 'bg-yellow-50', icon: 'text-yellow-600', btn: 'bg-yellow-600 hover:bg-yellow-700', avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=warning&backgroundColor=fef3c7' },
        danger: { bg: 'bg-red-50', icon: 'text-red-600', btn: 'bg-red-600 hover:bg-red-700', avatar: 'https://api.dicebear.com/7.x/bottts/svg?seed=danger&backgroundColor=fee2e2' }
    };
    const colors = typeColors[config.type] || typeColors.warning;
    const iconPaths = {
        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
        danger: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
    };
    
    const dialogHTML = `<div id="${dialogId}" class="fixed inset-0 z-[60] overflow-y-auto hidden"><div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"><div class="dialog-backdrop fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div><span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span><div class="dialog-panel inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"><div class="flex justify-center pt-8 pb-4"><div class="dialog-avatar relative"><div class="w-24 h-24 rounded-full ${colors.bg} p-2 shadow-lg"><img src="${colors.avatar}" class="w-full h-full rounded-full"></div><div class="absolute -bottom-2 -right-2 w-10 h-10 ${colors.bg} rounded-full flex items-center justify-center shadow-md border-4 border-white"><svg class="h-5 w-5 ${colors.icon}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${iconPaths[config.type]}</svg></div></div></div><div class="bg-white px-6 pb-4"><div class="text-center"><h3 class="text-2xl font-bold text-gray-900 mb-3">${config.title}</h3><p class="text-base text-gray-600 leading-relaxed">${config.message}</p></div></div><div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-center gap-3"><button type="button" id="${dialogId}_cancel" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 sm:w-auto transition-all">${config.cancelText}</button><button type="button" id="${dialogId}_confirm" class="w-full inline-flex justify-center rounded-lg border-transparent shadow-sm px-6 py-3 ${colors.btn} text-base font-semibold text-white sm:w-auto transition-all hover:shadow-md">${config.confirmText}</button></div></div></div></div>`;
    
    document.body.insertAdjacentHTML('beforeend', dialogHTML);
    const dialog = document.getElementById(dialogId);
    requestAnimationFrame(() => { dialog.classList.remove('hidden'); requestAnimationFrame(() => { dialog.querySelector('.dialog-backdrop').style.opacity='1'; dialog.querySelector('.dialog-panel').style.transform='scale(1) rotate(0deg)'; dialog.querySelector('.dialog-panel').style.opacity='1'; dialog.querySelector('.dialog-avatar').style.transform='scale(1) rotate(0deg)'; dialog.querySelector('.dialog-avatar').style.opacity='1'; }); });
    
    const closeDialog = () => { dialog.querySelector('.dialog-backdrop').style.opacity='0'; dialog.querySelector('.dialog-panel').style.transform='scale(0.8) rotate(-5deg)'; dialog.querySelector('.dialog-panel').style.opacity='0'; dialog.querySelector('.dialog-avatar').style.transform='scale(0) rotate(-180deg)'; dialog.querySelector('.dialog-avatar').style.opacity='0'; setTimeout(() => dialog.remove(), 300); };
    document.getElementById(dialogId+'_confirm').onclick = () => { closeDialog(); if(config.onConfirm) config.onConfirm(); };
    document.getElementById(dialogId+'_cancel').onclick = closeDialog;
    dialog.onclick = (e) => { if(e.target===dialog || e.target.classList.contains('dialog-backdrop')) closeDialog(); };
}

if (!document.getElementById('confirmDialogStyles')) {
    const style = document.createElement('style');
    style.id = 'confirmDialogStyles';
    style.textContent = `.dialog-backdrop{opacity:0;transition:opacity .3s ease-out}.dialog-panel{opacity:0;transform:scale(0.8) rotate(-5deg);transition:all .4s cubic-bezier(0.34,1.56,0.64,1)}.dialog-avatar{opacity:0;transform:scale(0) rotate(-180deg);transition:all .5s cubic-bezier(0.34,1.56,0.64,1)}`;
    document.head.appendChild(style);
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white transform transition-all duration-300 translate-x-0`;
    notification.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${type === 'success' 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
            }
        </svg>
        <span class="font-medium">${message}</span>
    `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('permissionModal').classList.contains('hidden')) {
        closeModal();
    }
});
</script>
@endpush
