@extends('admin.layouts.app')

@section('title', 'Banners Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Banners</h1>
            <p class="text-sm text-gray-600 mt-1">Manage homepage slider banners</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <i data-lucide="plus" class="w-5 h-5"></i>
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
            <div class="flex-1"></div>
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
                        <th class="px-6 py-3.5 text-left w-10">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Link</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
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
    
    <div id="bannerModalContent" class="fixed right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl flex flex-col" style="transform: translateX(100%); transition: transform 400ms cubic-bezier(0.4, 0, 0.2, 1);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Banner</h3>
                <p class="text-sm text-gray-600 mt-0.5">Upload a new banner for the homepage</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="bannerForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-6 space-y-6">
                <input type="hidden" id="bannerId">
                
                <!-- Image Section -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-900">Banner Image <span class="text-red-500">*</span></label>
                    <div class="relative group">
                        <div id="imagePlaceholder" class="aspect-[21/9] w-full border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center bg-gray-50 hover:border-[#0082C3] hover:bg-blue-50/30 transition-all cursor-pointer overflow-hidden" onclick="document.getElementById('bannerImageInput').click()">
                            <div id="uploadUI" class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i data-lucide="upload" class="w-6 h-6 text-gray-400 group-hover:text-[#0082C3]"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700">Click to upload banner</p>
                                <p class="text-xs text-gray-500 mt-1.5">PNG, JPG, WEBP. Images are optimized automatically.</p>
                            </div>
                            <img id="imagePreview" src="" class="hidden absolute inset-0 w-full h-full object-cover">
                            
                            <!-- Loading Overlay -->
                            <div id="uploadLoader" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="loader" class="animate-spin h-8 w-8 text-[#0082C3] mb-2"></i>
                                    <p class="text-xs font-medium text-gray-600">Uploading...</p>
                                </div>
                            </div>
                        </div>
                        <input type="file" id="bannerImageInput" class="hidden" accept="image/*" onchange="handleImageUpload(event)">
                        <input type="hidden" id="bannerImageUrl">
                        <input type="hidden" id="bannerImageId">
                        
                        <button type="button" id="removeImageBtn" onclick="removeImage(event)" class="hidden absolute top-3 right-3 p-1.5 bg-red-600 text-white rounded-lg shadow-lg hover:bg-red-700 transition-colors z-10">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Banner Link (Optional)</label>
                        <input type="url" id="bannerLink" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent transition-all" placeholder="https://example.com/shop">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order <span class="text-red-500">*</span></label>
                        <input type="number" id="bannerSortOrder" value="0" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent transition-all" placeholder="Enter numeric order">
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="bannerStatus" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0082C3]"></div>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Active Status</span>
                                <p class="text-xs text-gray-500">Enable or disable this banner on homepage</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 sticky bottom-0">
            <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" form="bannerForm" id="submitBtn" class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Create Banner</span>
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
window.Toast = {
    show: function(msg, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-[9999] transition-all transform translate-y-0 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.transform = 'translateY(100px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },
    success: function(msg) { this.show(msg, 'success'); },
    error: function(msg) { this.show(msg, 'error'); }
};

let currentPage = 1;
let selectedBanners = new Set();
let isFirstLoad = true;

document.addEventListener('DOMContentLoaded', () => {
    loadBanners();
});

function showTableLoading() {
    const tbody = document.getElementById('bannersTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="px-6 py-12">
                <div class="flex flex-col items-center justify-center">
                    <i data-lucide="loader" class="animate-spin h-8 w-8 text-[#0082C3] mb-3"></i>
                    <p class="text-sm text-gray-500 font-medium">Loading banners...</p>
                </div>
            </td>
        </tr>`;
}

function loadBanners(page = 1) {
    currentPage = page;
    const status = document.getElementById('statusFilter').value;
    const url = `/admin/banners/list?page=${page}&status=${status}&_=${Date.now()}`;

    if (page !== 1 || !document.getElementById('bannersTableBody').innerHTML.trim()) {
        showTableLoading();
    }

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderBanners(data.data);
            renderPagination(data.pagination);
        }

        if (isFirstLoad) {
            isFirstLoad = false;
            if (typeof window.dismissSkeleton === 'function') {
                window.dismissSkeleton();
            }
        }
    })
    .catch(error => {
        console.error('Error loading banners:', error);
        if (isFirstLoad) {
            isFirstLoad = false;
            if (typeof window.dismissSkeleton === 'function') {
                window.dismissSkeleton();
            }
        }
    });
}

function normalizeStorageUrl(url) {
    if (!url) return '';
    return url.replace(/(\/storage)+\//g, '/storage/');
}

function renderBanners(banners) {
    const tbody = document.getElementById('bannersTableBody');

    if (banners.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-16 text-center text-gray-500">No banners found</td></tr>`;
        return;
    }

    tbody.innerHTML = banners.map(banner => {
        const imageUrl = normalizeStorageUrl(banner.image_url || '');

        return `
        <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="banner-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" data-id="${banner.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="w-32 h-16 rounded-lg bg-gray-100 overflow-hidden border border-gray-200 shadow-sm group relative">
                    <img src="${imageUrl}" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');" class="w-full h-full object-cover" alt="Banner">
                    <div class="hidden absolute inset-0 items-center justify-center text-xs font-semibold text-gray-400 bg-gray-100">No Image</div>
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button onclick="previewImage('${imageUrl}')" class="text-white p-1 hover:scale-110 transition-transform">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                ${banner.banner_link ? `<a href="${banner.banner_link}" target="_blank" class="text-sm text-blue-600 hover:underline truncate max-w-[200px] block">${banner.banner_link}</a>` : '<span class="text-gray-400 text-xs italic">No Link</span>'}
            </td>
            <td class="px-6 py-4">
                <span class="text-sm font-medium text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200">${banner.sort_order}</span>
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${banner.id})" class="inline-flex items-center gap-1.5 px-2.5 py-1 ${banner.is_active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'} rounded-full text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full ${banner.is_active ? 'bg-green-600' : 'bg-red-600'}"></span>
                    ${banner.is_active ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editBanner(${banner.id})" class="p-2 text-gray-600 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Edit Banner">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <button onclick="deleteBanner(${banner.id})" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Banner">
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
    if (pagination.last_page <= 1) { 
        container.innerHTML = `<div class="text-sm text-gray-600">Showing all ${pagination.total} banners</div>`;
        return; 
    }
    
    let pages = '';
    for (let i = 1; i <= pagination.last_page; i++) {
        pages += `<button onclick="loadBanners(${i})" class="px-3 py-2 text-sm ${i === pagination.current_page ? 'bg-[#0082C3] text-white font-semibold' : 'text-gray-700 hover:bg-white hover:shadow-sm'} rounded-lg transition-all border ${i === pagination.current_page ? 'border-[#0082C3]' : 'border-transparent'}">${i}</button>`;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600 font-medium">Showing ${pagination.total > 0 ? (pagination.current_page - 1) * pagination.per_page + 1 : 0} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} banners</div>
            <div class="flex items-center gap-1">${pages}</div>
        </div>`;
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Banner';
    document.getElementById('submitBtnText').textContent = 'Create Banner';
    document.getElementById('bannerForm').reset();
    document.getElementById('bannerId').value = '';
    document.getElementById('bannerStatus').checked = true;
    document.getElementById('bannerSortOrder').value = 0;
    removeImage();
    
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

async function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        Dialog.alert({ title: 'Invalid File', message: 'Please select an image file.', type: 'danger' });
        return;
    }

    const loader = document.getElementById('uploadLoader');
    const preview = document.getElementById('imagePreview');
    const uploadUI = document.getElementById('uploadUI');
    const removeBtn = document.getElementById('removeImageBtn');
    const submitBtn = document.getElementById('submitBtn');

    loader.classList.remove('hidden');
    submitBtn.disabled = true;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', 'banners');
    formData.append('folder', 'banners');

    try {
        const response = await fetch('/api/upload', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            document.getElementById('bannerImageUrl').value = data.url;
            document.getElementById('bannerImageId').value = data.fileId;
            preview.src = data.url;
            preview.classList.remove('hidden');
            uploadUI.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        } else {
            Dialog.alert({ title: 'Upload Failed', message: data.message || 'Failed to upload image.', type: 'danger' });
        }
    } catch (error) {
        console.error('Upload error:', error);
        Dialog.alert({ title: 'Error', message: 'An error occurred during upload.', type: 'danger' });
    } finally {
        loader.classList.add('hidden');
        submitBtn.disabled = false;
    }
}

function removeImage(event) {
    if (event) event.stopPropagation();
    document.getElementById('bannerImageUrl').value = '';
    document.getElementById('bannerImageId').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imagePreview').src = '';
    document.getElementById('uploadUI').classList.remove('hidden');
    document.getElementById('removeImageBtn').classList.add('hidden');
    document.getElementById('bannerImageInput').value = '';
}

function editBanner(id) {
    fetch(`/admin/banners/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const banner = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Banner';
            document.getElementById('submitBtnText').textContent = 'Update Banner';
            document.getElementById('bannerId').value = banner.id;
            document.getElementById('bannerSortOrder').value = banner.sort_order;
            document.getElementById('bannerStatus').checked = banner.is_active;
            document.getElementById('bannerLink').value = banner.banner_link || '';
            
            if (banner.image_url) {
                document.getElementById('bannerImageUrl').value = banner.image_url;
                document.getElementById('bannerImageId').value = banner.image_id || '';
                const preview = document.getElementById('imagePreview');
                preview.src = banner.image_url;
                preview.classList.remove('hidden');
                document.getElementById('uploadUI').classList.add('hidden');
                document.getElementById('removeImageBtn').classList.remove('hidden');
            } else {
                removeImage();
            }
            
            const modal = document.getElementById('bannerModal');
            const content = document.getElementById('bannerModalContent');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => content.style.transform = 'translateX(0)', 10);
        }
    });
}

document.getElementById('bannerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const bannerId = document.getElementById('bannerId').value;
    const url = bannerId ? `/admin/banners/${bannerId}` : '/admin/banners';
    
    const imageUrl = document.getElementById('bannerImageUrl').value;
    if (!imageUrl) {
        Dialog.alert({ title: 'Required', message: 'Please upload a banner image.', type: 'warning' });
        return;
    }

    const formData = {
        sort_order: document.getElementById('bannerSortOrder').value,
        is_active: document.getElementById('bannerStatus').checked,
        banner_link: document.getElementById('bannerLink').value,
        image_url: imageUrl,
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
            Toast.success(bannerId ? 'Banner updated' : 'Banner created');
        } else {
            Dialog.alert({ title: 'Error', message: data.message || 'Operation failed', type: 'danger' });
        }
    })
    .catch(error => console.error('Error saving banner:', error));
});

function toggleStatus(id) {
    fetch(`/admin/banners/${id}/toggle-status`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadBanners(currentPage);
            Toast.success('Status updated');
        }
    });
}

async function deleteBanner(id) {
    const confirmed = await Dialog.confirm({ title: 'Delete Banner', message: 'Are you sure?', type: 'danger' });
    if (!confirmed) return;
    
    fetch(`/admin/banners/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadBanners(currentPage);
            Toast.success('Banner deleted');
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.banner-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.banner-checkbox:checked');
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

async function applyBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    const checkboxes = document.querySelectorAll('.banner-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.dataset.id);
    
    if (!action || ids.length === 0) return;
    if (action === 'delete') {
        const confirmed = await Dialog.confirm({ title: 'Delete', message: `Delete ${ids.length} banners?`, type: 'danger' });
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
        body: JSON.stringify({ action: action, ids: ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadBanners(currentPage);
            Toast.success('Bulk action applied');
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
        }
    });
}

function previewImage(url) {
    const imageUrl = normalizeStorageUrl(url || '');
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-[100001] flex items-center justify-center bg-gray-950/60 backdrop-blur-sm p-4';

    const panel = document.createElement('div');
    panel.className = 'w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden';

    const header = document.createElement('div');
    header.className = 'flex items-center justify-between px-6 py-4 border-b border-gray-100';

    const title = document.createElement('h3');
    title.className = 'text-lg font-black text-gray-950';
    title.textContent = 'Banner Preview';

    const closeBtn = document.createElement('button');
    closeBtn.className = 'w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 text-2xl leading-none';
    closeBtn.type = 'button';
    closeBtn.textContent = '×';
    closeBtn.onclick = () => modal.remove();

    const body = document.createElement('div');
    body.className = 'p-6';

    const img = document.createElement('img');
    img.src = imageUrl;
    img.alt = 'Banner preview';
    img.className = 'w-full rounded-lg border border-gray-200 bg-gray-100';
    img.onerror = () => {
        img.replaceWith(document.createTextNode('Unable to load banner image.'));
    };

    modal.addEventListener('click', (event) => {
        if (event.target === modal) modal.remove();
    });

    header.append(title, closeBtn);
    body.appendChild(img);
    panel.append(header, body);
    modal.appendChild(panel);
    document.body.appendChild(modal);
}
</script>
@endpush
