@extends('admin.layouts.app')

@section('title', 'Admin Users')
@section('page-title', 'Admin Users')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Admin Users</h1>
            <p class="text-sm text-gray-600 mt-1">Manage admin users, roles and access permissions</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Add Admin User
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalUsers">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeUsers">
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
                    <p class="text-sm font-medium text-gray-600">Inactive Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="inactiveUsers">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="ban" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Roles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalRolesCard">
                        <span class="skeleton-text inline-block h-8 w-12"></span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex items-center gap-4">
            <div class="flex-1 relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search by name or email..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="roleFilter" data-searchable data-placeholder="All Roles" onchange="loadUsers()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                @endforeach
            </select>
            <select id="statusFilter" data-searchable data-placeholder="All Status" onchange="loadUsers()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <select id="perPageSelect" data-searchable data-placeholder="Per Page" onchange="loadUsers()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Skeleton Loader -->
                    <tr class="skeleton-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="skeleton-avatar w-10 h-10 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-32 mb-2"></div>
                                    <div class="skeleton-text h-3 w-20"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-40"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-24 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-16 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24"></div>
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
                                <div class="skeleton-avatar w-10 h-10 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-28 mb-2"></div>
                                    <div class="skeleton-text h-3 w-16"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-36"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-20 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-16 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-28"></div>
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
                                <div class="skeleton-avatar w-10 h-10 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="skeleton-text h-4 w-36 mb-2"></div>
                                    <div class="skeleton-text h-3 w-24"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-44"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-24 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-6 w-16 rounded-md"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-24"></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="skeleton-text h-4 w-28"></div>
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
<div id="userModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <!-- Modal Content (Slide from right) -->
    <div id="userModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Admin User</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new admin user account</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="userForm" class="flex-1 overflow-y-auto" enctype="multipart/form-data">
            <div class="px-6 py-4 space-y-5">
                <input type="hidden" id="userId">
                
                <!-- Profile Image -->
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img id="profilePreview" src="https://ui-avatars.com/api/?name=User&size=80&background=0082C3&color=fff" class="w-20 h-20 rounded-full border-2 border-gray-200">
                        <label for="profileImage" class="absolute bottom-0 right-0 w-7 h-7 bg-[#0082C3] rounded-full flex items-center justify-center cursor-pointer hover:bg-[#006ba3] transition-colors">
                            <i data-lucide="camera" class="w-4 h-4 text-white"></i>
                        </label>
                        <input type="file" id="profileImage" accept="image/*" class="hidden" onchange="previewImage(event)">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Profile Photo</p>
                        <p class="text-xs text-gray-500 mt-0.5">JPG, PNG or GIF. Images are optimized automatically.</p>
                    </div>
                </div>

                <!-- User Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" id="userName" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="John Doe" required>
                        <span class="text-red-500 text-xs mt-1 hidden" id="nameError"></span>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="userEmail" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="john@example.com" required>
                        <span class="text-red-500 text-xs mt-1 hidden" id="emailError"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500" id="passwordRequired">*</span></label>
                        <input type="password" id="userPassword" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="••••••••">
                        <span class="text-red-500 text-xs mt-1 hidden" id="passwordError"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password <span class="text-red-500" id="confirmRequired">*</span></label>
                        <input type="password" id="userPasswordConfirm" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="••••••••">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <select id="userRole" data-searchable data-placeholder="Select Role" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-red-500 text-xs mt-1 hidden" id="role_idError"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select id="userStatus" data-searchable data-placeholder="Select Status" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3">
            <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" form="userForm" id="submitBtn" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Create User</span>
                <span id="submitBtnLoading" class="hidden flex items-center gap-2">
                    <i data-lucide="loader" class="animate-spin h-4 w-4"></i>
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
    border-radius: 50%;
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
// Prevent multiple initializations
if (!window.adminUsersInitialized) {
    window.adminUsersInitialized = true;
    
    let currentPage = 1;
    let searchTimeout;
    let isFirstLoad = true;

    document.addEventListener('DOMContentLoaded', () => {
        // Check if elements exist before loading
        if (document.getElementById('searchInput') && document.getElementById('usersTableBody')) {
            loadUsers();
        }
    });

    function loadUsers(page = 1) {
        currentPage = page;
        
        // Safety check - make sure elements exist
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        const perPageSelect = document.getElementById('perPageSelect');
        
        if (!searchInput || !roleFilter || !statusFilter || !perPageSelect) {
            console.error('Required elements not found');
            return;
        }
        
        const search = searchInput.value;
        const role = roleFilter.value;
        const status = statusFilter.value;
        const perPage = perPageSelect.value;

        const url = `/admin/admin-users/list?page=${page}&search=${search}&role=${role}&status=${status}&per_page=${perPage}`;

        // Show skeleton loader
        const tbody = document.getElementById('usersTableBody');
        const skeletonRows = tbody.querySelectorAll('.skeleton-row');
        skeletonRows.forEach(row => row.style.display = '');

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderUsers(data.data);
                renderPagination(data.pagination);
                updateStats(data.data, data.pagination);
            }
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
        })
        .catch(error => {
            console.error('Error loading users:', error);
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-red-600">
                            Error loading users. Please refresh the page.
                        </td>
                    </tr>
                `;
            }
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
        });
    }

    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadUsers(1), 500);
    }

function updateStats(users, pagination) {
    const totalUsersEl = document.getElementById('totalUsers');
    const activeUsersEl = document.getElementById('activeUsers');
    const inactiveUsersEl = document.getElementById('inactiveUsers');
    const totalRolesEl = document.getElementById('totalRolesCard');
    
    // Remove skeleton and show actual count
    totalUsersEl.innerHTML = pagination.total;
    
    // Set roles count (static value from backend)
    totalRolesEl.innerHTML = {{ count($roles) }};
    
    // Calculate stats from all users via API
    fetch('/admin/admin-users/list?per_page=1000', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const allUsers = data.data;
            const active = allUsers.filter(u => u.is_active).length;
            const inactive = allUsers.filter(u => !u.is_active).length;
            activeUsersEl.innerHTML = active;
            inactiveUsersEl.innerHTML = inactive;
        }
    });
}

function renderUsers(users) {
    const tbody = document.getElementById('usersTableBody');
    
    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <i data-lucide="users" class="w-16 h-16 text-gray-300"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">No users found</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filters</p>
                        </div>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = users.map(user => {
        const profileImg = user.thumbnail_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&size=40&background=0082C3&color=fff`;
        
        const lastLogin = user.last_login 
            ? new Date(user.last_login).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
            : '<span class="text-gray-400">Never</span>';
        
        // Check if Super Admin
        const isSuperAdmin = user.role && user.role.name.toLowerCase() === 'super_admin';
        
        // Role badge colors
        let roleBadgeClass = 'bg-purple-50 text-purple-700';
        if (user.role) {
            const roleName = user.role.name.toLowerCase();
            if (roleName === 'super_admin') roleBadgeClass = 'bg-gradient-to-r from-purple-500 to-purple-600 text-white';
            else if (roleName === 'admin') roleBadgeClass = 'bg-blue-50 text-blue-700';
            else if (roleName === 'manager') roleBadgeClass = 'bg-orange-50 text-orange-700';
            else if (roleName === 'staff') roleBadgeClass = 'bg-gray-50 text-gray-700';
        }
        
        // Disable actions for Super Admin
        const deleteDisabled = isSuperAdmin ? 'opacity-50 cursor-not-allowed' : 'hover:text-red-600 hover:bg-red-50';
        const statusDisabled = isSuperAdmin ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-80';
        
        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <img src="${profileImg}" class="w-10 h-10 rounded-full border-2 border-gray-200" alt="${user.name}">
                    <div>
                        <div class="font-semibold text-gray-900">${user.name}</div>
                        <div class="text-xs text-gray-500">ID: ${user.id}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">${user.email}</div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 ${roleBadgeClass} rounded-md text-xs font-medium">
                        ${isSuperAdmin ? '<i data-lucide="shield-check" class="w-3.5 h-3.5"></i>' : '<i data-lucide="shield" class="w-3.5 h-3.5"></i>'}
                        ${user.role ? user.role.display_name : 'No Role'}
                    </span>
                    ${isSuperAdmin ? '<span class="inline-flex items-center px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">🛡️ Protected</span>' : ''}
                </div>
            </td>
            <td class="px-6 py-4">
                <button onclick="${isSuperAdmin ? 'return false' : `toggleStatus(${user.id})`}" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${user.is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} rounded-md text-xs font-medium ${statusDisabled} transition-opacity" ${isSuperAdmin ? 'disabled title="Super Admin status cannot be changed"' : ''}>
                    <span class="w-1.5 h-1.5 rounded-full ${user.is_active ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${user.is_active ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${lastLogin}</td>
            <td class="px-6 py-4 text-sm text-gray-600">
                ${new Date(user.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="viewUser(${user.id})" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View details">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                    <button onclick="editUser(${user.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Edit user">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <button onclick="${isSuperAdmin ? 'return false' : `deleteUser(${user.id})`}" class="p-2 text-gray-600 ${deleteDisabled} rounded-lg transition-colors" title="${isSuperAdmin ? 'Super Admin cannot be deleted' : 'Delete user'}" ${isSuperAdmin ? 'disabled' : ''}>
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </td>
        </tr>
    `}).join('');
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (pagination.last_page <= 1) { 
        container.innerHTML = `<div class="text-sm text-gray-600">Showing ${pagination.total} ${pagination.total === 1 ? 'user' : 'users'}</div>`;
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
        pages += `<button onclick="loadUsers(1)" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">1</button>`;
        if (startPage > 2) pages += `<span class="px-2 text-gray-400">...</span>`;
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pages += `<button onclick="loadUsers(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'} rounded-lg transition-colors">${i}</button>`;
    }
    
    if (endPage < pagination.last_page) {
        if (endPage < pagination.last_page - 1) pages += `<span class="px-2 text-gray-400">...</span>`;
        pages += `<button onclick="loadUsers(${pagination.last_page})" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">${pagination.last_page}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">${((pagination.current_page - 1) * pagination.per_page) + 1}</span> to 
                <span class="font-medium">${Math.min(pagination.current_page * pagination.per_page, pagination.total)}</span> of 
                <span class="font-medium">${pagination.total}</span> users
            </div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function closeModal() {
    const modal = document.getElementById('userModal');
    const modalContent = document.getElementById('userModalContent');
    
    // Slide out animation
    modalContent.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'userModal') {
        closeModal();
    }
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Admin User';
    document.getElementById('submitBtnText').textContent = 'Create User';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('profilePreview').src = 'https://ui-avatars.com/api/?name=User&size=80&background=0082C3&color=fff';
    document.getElementById('passwordRequired').classList.remove('hidden');
    document.getElementById('confirmRequired').classList.remove('hidden');
    document.getElementById('userPassword').required = true;
    document.getElementById('userPasswordConfirm').required = true;
    
    const modal = document.getElementById('userModal');
    const modalContent = document.getElementById('userModalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Trigger slide animation
    modalContent.offsetHeight; // Force reflow
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            modalContent.style.transform = 'translateX(0)';
        });
    });
}

function editUser(id) {
    fetch(`/admin/admin-users/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const user = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Admin User';
            document.getElementById('submitBtnText').textContent = 'Update User';
            document.getElementById('userId').value = user.id;
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userRole').value = user.role_id || '';
            document.getElementById('userStatus').value = user.is_active ? '1' : '0';
            
            const profileImg = user.profile_image 
                ? (user.profile_image.startsWith('http') ? user.profile_image : (user.profile_image.startsWith('uploads/') ? `/${user.profile_image}` : `/uploads/profiles/${user.profile_image}`))
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&size=80&background=0082C3&color=fff`;
            document.getElementById('profilePreview').src = profileImg;
            
            document.getElementById('passwordRequired').classList.add('hidden');
            document.getElementById('confirmRequired').classList.add('hidden');
            document.getElementById('userPassword').required = false;
            document.getElementById('userPasswordConfirm').required = false;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPasswordConfirm').value = '';
            
            const modal = document.getElementById('userModal');
            const modalContent = document.getElementById('userModalContent');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Trigger slide animation
            modalContent.offsetHeight; // Force reflow
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    modalContent.style.transform = 'translateX(0)';
                });
            });
        }
    });
}

document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('userId').value;
    const url = userId ? `/admin/admin-users/${userId}` : '/admin/admin-users';
    const method = userId ? 'PUT' : 'POST';
    
    const formData = new FormData();
    formData.append('name', document.getElementById('userName').value);
    formData.append('email', document.getElementById('userEmail').value);
    formData.append('role_id', document.getElementById('userRole').value);
    formData.append('is_active', document.getElementById('userStatus').value);
    
    const password = document.getElementById('userPassword').value;
    if (password) {
        formData.append('password', password);
        formData.append('password_confirmation', document.getElementById('userPasswordConfirm').value);
    }
    
    const profileImage = document.getElementById('profileImage').files[0];
    if (profileImage) {
        formData.append('profile_image', profileImage);
    }
    
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }

    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtnText').classList.add('hidden');
    document.getElementById('submitBtnLoading').classList.remove('hidden');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal();
            loadUsers(currentPage);
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

function toggleStatus(id) {
    fetch(`/admin/admin-users/${id}/toggle-status`, {
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
        if (data.success) loadUsers(currentPage);
    });
}

function deleteUser(id) {
    showConfirmDialog({
        title: 'Delete User',
        message: 'Are you sure you want to delete this user? This action cannot be undone and will remove all associated data.',
        confirmText: 'Delete User',
        cancelText: 'Cancel',
        type: 'danger',
        onConfirm: function() {
            fetch(`/admin/admin-users/${id}`, {
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
                if (data.success) loadUsers(currentPage);
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
        type: 'warning',
        onConfirm: null,
        onCancel: null
    };
    
    const config = { ...defaults, ...options };
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
                <div class="dialog-backdrop fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="dialog-panel inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
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
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">${config.title}</h3>
                            <div class="mt-2">
                                <p class="text-base text-gray-600 leading-relaxed">${config.message}</p>
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
    
    document.body.insertAdjacentHTML('beforeend', dialogHTML);
    const dialog = document.getElementById(dialogId);
    
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
    
    document.getElementById(dialogId + '_confirm').addEventListener('click', function() {
        closeDialog();
        if (config.onConfirm) config.onConfirm();
    });
    
    document.getElementById(dialogId + '_cancel').addEventListener('click', function() {
        closeDialog();
        if (config.onCancel) config.onCancel();
    });
    
    dialog.addEventListener('click', function(e) {
        if (e.target === dialog || e.target.classList.contains('dialog-backdrop')) {
            closeDialog();
            if (config.onCancel) config.onCancel();
        }
    });
    
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

// Add dialog styles
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

// Format date/time professionally
function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    
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

// View User Modal
function viewUser(id) {
    fetch(`/admin/admin-users/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showUserViewModal(data.data);
        }
    });
}

function showUserViewModal(user) {
    const modalId = 'viewUserModal_' + Date.now();
    
    const statusBadge = user.status === 'active' 
        ? '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Active</span>'
        : '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800"><span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>Inactive</span>';
    
    const roleBadge = user.role ? `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">👤 ${user.role.name}</span>` : '';
    
    const modalHTML = `
        <div id="${modalId}" class="fixed inset-0 z-[60] overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="view-modal-backdrop fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="view-modal-panel inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-[#0082C3] to-[#006ba3] px-6 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                                    ${user.profile_image 
                                        ? `<img src="${user.profile_image.startsWith('http') ? user.profile_image : '/' + user.profile_image}" class="w-full h-full object-cover" alt="${user.name}">`
                                        : `<div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">${user.name.charAt(0).toUpperCase()}</div>`
                                    }
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">${user.name}</h3>
                                    <p class="text-blue-100 text-sm mt-1">${user.email}</p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('${modalId}').querySelector('.view-modal-close-btn').click()" class="text-white hover:text-gray-200 transition-colors">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            ${statusBadge}
                            ${roleBadge}
                        </div>
                        
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b pb-2">Basic Information</h4>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Full Name</label>
                                        <p class="text-base text-gray-900 font-medium mt-1">${user.name}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Email Address</label>
                                        <p class="text-sm text-gray-700 font-mono bg-gray-50 px-3 py-2 rounded-lg mt-1">${user.email}</p>
                                    </div>
                                    
                                    ${user.phone ? `
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Phone Number</label>
                                        <p class="text-base text-gray-900 font-medium mt-1">${user.phone}</p>
                                    </div>
                                    ` : ''}
                                    
                                    ${user.role ? `
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Role</label>
                                        <p class="text-base text-gray-900 font-medium mt-1">${user.role.name}</p>
                                        ${user.role.description ? `<p class="text-xs text-gray-500 mt-1">${user.role.description}</p>` : ''}
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            
                            <!-- Activity & Stats -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b pb-2">Activity & Stats</h4>
                                
                                <div class="space-y-3">
                                    ${user.last_login ? `
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4">
                                        <label class="text-xs font-medium text-green-700 uppercase">Last Login</label>
                                        <p class="text-base font-semibold text-green-900 mt-1">${formatDateTime(user.last_login)}</p>
                                    </div>
                                    ` : `
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4">
                                        <label class="text-xs font-medium text-gray-700 uppercase">Last Login</label>
                                        <p class="text-base font-semibold text-gray-900 mt-1">Never logged in</p>
                                    </div>
                                    `}
                                    
                                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                            <label class="text-xs font-medium text-gray-500">Created</label>
                                        </div>
                                        <p class="text-sm text-gray-700 font-medium">${formatDateTime(user.created_at)}</p>
                                    </div>
                                    
                                    ${user.updated_at ? `
                                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="refresh-cw" class="w-4 h-4 text-gray-400"></i>
                                            <label class="text-xs font-medium text-gray-500">Last Updated</label>
                                        </div>
                                        <p class="text-sm text-gray-700 font-medium">${formatDateTime(user.updated_at)}</p>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Permissions Section -->
                        ${user.role && user.role.permissions && user.role.permissions.length > 0 ? `
                        <div class="mt-6 pt-6 border-t">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Permissions (${user.role.permissions.length})</h4>
                            <div class="flex flex-wrap gap-2">
                                ${user.role.permissions.map(perm => `
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <i data-lucide="circle-check" class="w-3 h-3 mr-1.5"></i>
                                        ${perm.name}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" onclick="editUser(${user.id}); document.getElementById('${modalId}').querySelector('.view-modal-close-btn').click();" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            Edit User
                        </button>
                        <button type="button" class="view-modal-close-btn inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = document.getElementById(modalId);
    
    requestAnimationFrame(function() {
        modal.classList.remove('hidden');
        requestAnimationFrame(function() {
            modal.querySelector('.view-modal-backdrop').style.opacity = '1';
            modal.querySelector('.view-modal-panel').style.transform = 'scale(1)';
            modal.querySelector('.view-modal-panel').style.opacity = '1';
        });
    });
    
    modal.querySelector('.view-modal-close-btn').addEventListener('click', function() {
        closeViewModal();
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target.classList.contains('view-modal-backdrop')) {
            closeViewModal();
        }
    });
    
    const escapeHandler = function(e) {
        if (e.key === 'Escape') closeViewModal();
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


function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white transform transition-all duration-300 translate-x-0`;
    notification.innerHTML = `
        <i data-lucide="check" class="w-5 h-5"></i>
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
    if (e.key === 'Escape' && !document.getElementById('userModal').classList.contains('hidden')) {
        closeModal();
    }
});

} // Close window.adminUsersInitialized check
</script>
@endpush
