@extends('admin.layouts.app')

@section('title', 'Hero Banners Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hero Banners</h1>
            <p class="text-sm text-gray-600 mt-1">Manage the slides in the homepage hero slider</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Banner
        </button>
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
                    placeholder="Search by title or subtitle..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent"
                    onkeyup="debounceSearch()"
                >
            </div>
            <select id="statusFilter" onchange="loadBanners()" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Banners Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Banner</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="bannersTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Loaded via JS -->
                </tbody>
            </table>
        </div>
        <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 bg-gray-50"></div>
    </div>
</div>

<!-- Add/Edit Modal (Slide from Right) -->
<div id="bannerModal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <div id="bannerModalContent" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Hero Banner</h3>
                <p class="text-sm text-gray-600 mt-0.5">Create a new slide for the hero slider</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="bannerForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-4">
                <input type="hidden" id="bannerId">
                
                <!-- Content Section -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Content Information
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Banner Title <span class="text-red-500">*</span></label>
                            <input type="text" id="bannerTitle" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Pro-Grade Rackets" required>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Subtitle</label>
                            <input type="text" id="bannerSubtitle" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Engineered Performance For Every Player">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Price Text</label>
                            <input type="text" id="bannerPriceText" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Starting from ₹299 Onwards">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                            <input type="number" id="bannerSortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Button Text</label>
                            <input type="text" id="bannerButtonText" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., Shop Now">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Button Link</label>
                            <input type="text" id="bannerButtonLink" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="e.g., /shop?category=rackets">
                        </div>
                    </div>
                </div>

                <!-- Styling Section -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        Styling & Colors
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Background Color</label>
                            <div class="flex gap-2">
                                <input type="color" id="bannerBackgroundColorPicker" value="#f5e6d3" oninput="document.getElementById('bannerBackgroundColor').value = this.value" class="w-10 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="bannerBackgroundColor" value="#f5e6d3" class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Accent Color (Teal Shape)</label>
                            <div class="flex gap-2">
                                <input type="color" id="bannerAccentColorPicker" value="#2dd4bf" oninput="document.getElementById('bannerAccentColor').value = this.value" class="w-10 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text" id="bannerAccentColor" value="#2dd4bf" class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent">
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="flex items-center gap-2 cursor-pointer mt-2">
                                <input type="checkbox" id="bannerStatus" checked class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="text-sm font-medium text-gray-700">Active (Visible on homepage)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="space-y-4 border-t pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Banner Image
                    </h4>

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-[#0082C3] transition-colors bg-gray-50">
                        <div id="bannerImagePreview" class="hidden mb-4">
                            <img id="bannerImagePreviewImg" src="" class="max-w-xs mx-auto h-48 object-contain rounded-lg shadow-sm">
                            <button type="button" onclick="removeImage()" class="mt-3 text-sm text-red-600 hover:text-red-700 font-medium">
                                Remove Image
                            </button>
                        </div>
                        
                        <div id="bannerImageUploadArea">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            <button type="button" onclick="openImagePicker()" class="inline-flex items-center gap-2 px-6 py-3 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Select Image
                            </button>
                            <p class="text-sm text-gray-500 mt-4">
                                Recommended: Transparent PNG for products<br>
                                <span class="text-xs">PNG, JPG, WEBP up to 5MB</span>
                            </p>
                        </div>
                        <input type="hidden" id="bannerImageUrl">
                        <input type="hidden" id="bannerImageId">
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 sticky bottom-0">
            <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" form="bannerForm" id="submitBtn" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
                <span id="submitBtnText">Create Banner</span>
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPage = 1;
let searchTimeout;
let selectedBanners = new Set();

document.addEventListener('DOMContentLoaded', () => {
    loadBanners();
});

function loadBanners(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;

    const url = `/admin/banners/list?page=${page}&search=${search}&status=${status}`;

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderBanners(data.data);
            renderPagination(data.pagination);
        }
    })
    .catch(error => console.error('Error loading banners:', error));
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadBanners(1), 300);
}

function renderBanners(banners) {
    const tbody = document.getElementById('bannersTableBody');
    
    if (banners.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-16 text-center text-gray-500">No banners found</td></tr>`;
        return;
    }

    tbody.innerHTML = banners.map(banner => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="banner-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" data-id="${banner.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <img src="${banner.image_url}" class="w-16 h-12 rounded bg-gray-100 object-contain p-1 border border-gray-200" style="background-color: ${banner.background_color}" alt="${banner.title}">
                    <div>
                        <div class="font-semibold text-gray-900">${banner.title}</div>
                        <div class="text-xs text-gray-500">${banner.subtitle || ''}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${banner.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${banner.is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'} rounded-md text-xs font-medium">
                    <span class="w-1.5 h-1.5 rounded-full ${banner.is_active ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${banner.is_active ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${banner.sort_order}</td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editBanner(${banner.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteBanner(${banner.id})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
        container.innerHTML = `<div class="text-sm text-gray-600">Showing all ${pagination.total} banners</div>`;
        return; 
    }
    
    let pages = '';
    for (let i = 1; i <= pagination.last_page; i++) {
        pages += `<button onclick="loadBanners(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white' : 'text-gray-700 hover:bg-gray-100'} rounded-lg transition-colors">${i}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">Showing ${bannersTableBody.children.length} of ${pagination.total} banners</div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Hero Banner';
    document.getElementById('submitBtnText').textContent = 'Create Banner';
    document.getElementById('bannerForm').reset();
    document.getElementById('bannerId').value = '';
    document.getElementById('bannerStatus').checked = true;
    document.getElementById('bannerImagePreview').classList.add('hidden');
    document.getElementById('bannerImageUploadArea').classList.remove('hidden');
    
    const modal = document.getElementById('bannerModal');
    const content = document.getElementById('bannerModalContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => content.style.transform = 'translateX(0)', 10);
}

function closeModal() {
    const modal = document.getElementById('bannerModal');
    const content = document.getElementById('bannerModalContent');
    content.style.transform = 'translateX(100%)';
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'bannerModal') closeModal();
}

function editBanner(id) {
    fetch(`/admin/banners/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const banner = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Hero Banner';
            document.getElementById('submitBtnText').textContent = 'Update Banner';
            document.getElementById('bannerId').value = banner.id;
            document.getElementById('bannerTitle').value = banner.title;
            document.getElementById('bannerSubtitle').value = banner.subtitle || '';
            document.getElementById('bannerPriceText').value = banner.price_text || '';
            document.getElementById('bannerSortOrder').value = banner.sort_order;
            document.getElementById('bannerButtonText').value = banner.button_text || '';
            document.getElementById('bannerButtonLink').value = banner.button_link || '';
            document.getElementById('bannerBackgroundColor').value = banner.background_color;
            document.getElementById('bannerBackgroundColorPicker').value = banner.background_color;
            document.getElementById('bannerAccentColor').value = banner.accent_color;
            document.getElementById('bannerAccentColorPicker').value = banner.accent_color;
            document.getElementById('bannerStatus').checked = banner.is_active;
            
            if (banner.image_url) {
                document.getElementById('bannerImageUrl').value = banner.image_url;
                document.getElementById('bannerImageId').value = banner.image_id || '';
                document.getElementById('bannerImagePreviewImg').src = banner.image_url;
                document.getElementById('bannerImagePreview').classList.remove('hidden');
                document.getElementById('bannerImageUploadArea').classList.add('hidden');
            }
            
            openAddModal();
        }
    });
}

document.getElementById('bannerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const bannerId = document.getElementById('bannerId').value;
    const url = bannerId ? `/admin/banners/${bannerId}` : '/admin/banners';
    const method = bannerId ? 'PUT' : 'POST';
    
    const formData = {
        title: document.getElementById('bannerTitle').value,
        subtitle: document.getElementById('bannerSubtitle').value,
        price_text: document.getElementById('bannerPriceText').value,
        sort_order: document.getElementById('bannerSortOrder').value,
        button_text: document.getElementById('bannerButtonText').value,
        button_link: document.getElementById('bannerButtonLink').value,
        background_color: document.getElementById('bannerBackgroundColor').value,
        accent_color: document.getElementById('bannerAccentColor').value,
        is_active: document.getElementById('bannerStatus').checked,
        image_url: document.getElementById('bannerImageUrl').value,
        image_id: document.getElementById('bannerImageId').value,
    };

    if (bannerId) formData._method = 'PUT';

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
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadBanners(currentPage);
            Dialog.alert({
                title: 'Success!',
                message: bannerId ? 'Banner updated successfully' : 'Banner created successfully',
                type: 'success'
            });
        } else {
            Dialog.alert({
                title: 'Error',
                message: data.message || 'Operation failed',
                type: 'danger'
            });
        }
    })
    .catch(error => console.error('Error saving banner:', error));
});

function toggleStatus(id) {
    fetch(`/admin/banners/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) loadBanners(currentPage);
    });
}

async function deleteBanner(id) {
    const confirmed = await Dialog.confirm({
        title: 'Delete Banner',
        message: 'Are you sure you want to delete this banner?',
        type: 'danger'
    });
    
    if (!confirmed) return;
    
    fetch(`/admin/banners/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadBanners(currentPage);
            Dialog.alert({
                title: 'Deleted!',
                message: 'Banner has been deleted successfully.',
                type: 'success'
            });
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.banner-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        if (selectAll.checked) selectedBanners.add(parseInt(cb.dataset.id));
        else selectedBanners.delete(parseInt(cb.dataset.id));
    });
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.banner-checkbox:checked');
    const bulkContainer = document.getElementById('bulkActionsContainer');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedBanners.clear();
    checkboxes.forEach(cb => selectedBanners.add(parseInt(cb.dataset.id)));
    
    if (selectedBanners.size > 0) {
        bulkContainer.classList.remove('hidden');
        bulkContainer.classList.add('flex', 'items-center', 'gap-2');
        selectedCount.textContent = `${selectedBanners.size} selected`;
    } else {
        bulkContainer.classList.add('hidden');
        bulkContainer.classList.remove('flex', 'items-center', 'gap-2');
    }
}

async function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    if (!action || selectedBanners.size === 0) return;
    
    if (action === 'delete') {
        const confirmed = await Dialog.confirm({
            title: 'Delete Banners',
            message: `Are you sure you want to delete ${selectedBanners.size} banners?`,
            type: 'danger'
        });
        if (!confirmed) return;
    }
    
    fetch('/admin/banners/bulk-action', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            ids: Array.from(selectedBanners)
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            selectedBanners.clear();
            loadBanners(currentPage);
            Dialog.alert({
                title: 'Success!',
                message: 'Bulk action applied successfully.',
                type: 'success'
            });
        }
    });
}

function openImagePicker() {
    // Basic image picker using prompt for URL for now, 
    // but usually would use ImageKit integration.
    // Given the ImageKit integration exists in Category, I should ideally use that.
    const url = prompt('Enter Image URL (or use ImageKit integration):');
    if (url) {
        document.getElementById('bannerImageUrl').value = url;
        document.getElementById('bannerImagePreviewImg').src = url;
        document.getElementById('bannerImagePreview').classList.remove('hidden');
        document.getElementById('bannerImageUploadArea').classList.add('hidden');
    }
}

function removeImage() {
    document.getElementById('bannerImageUrl').value = '';
    document.getElementById('bannerImageId').value = '';
    document.getElementById('bannerImagePreview').classList.add('hidden');
    document.getElementById('bannerImageUploadArea').classList.remove('hidden');
}
</script>
@endpush
