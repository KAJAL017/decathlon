let currentPage = 1;
let searchTimeout;
let selectedProducts = [];
let collectionRules = [];
let isEditMode = false;

// Load collections on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCollections();
    loadStats();
    // Initialize filter searchable selects
    initializeSearchableSelects();
});

// Load collections with filters
function loadCollections(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const type = document.getElementById('typeFilter').value;
    const status = document.getElementById('statusFilter').value;
    const visibility = document.getElementById('visibilityFilter').value;
    const perPage = document.getElementById('perPageSelect').value;

    const params = new URLSearchParams({
        page: page,
        per_page: perPage,
        ...(search && { search }),
        ...(type && { type }),
        ...(status && { status }),
        ...(visibility && { visibility })
    });

    fetch(`/admin/collections/list?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCollections(data.data);
                renderPagination(data.pagination);
            }
        })
        .catch(error => {
            console.error('Error loading collections:', error);
            showToast('Failed to load collections', 'error');
        });
}

// Render collections table
function renderCollections(collections) {
    const tbody = document.getElementById('collectionsTableBody');
    
    if (collections.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm font-medium">No collections found</p>
                    <p class="text-xs mt-1">Create your first collection to get started</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = collections.map(collection => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="collection-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" value="${collection.id}" onchange="updateBulkActions()">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                        ${collection.image_url 
                            ? `<img src="${collection.image_url}" alt="${collection.name}" class="w-full h-full object-cover">`
                            : `<svg class="w-6 h-6 text-gray-400 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>`
                        }
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${collection.name}</p>
                        <p class="text-xs text-gray-500 truncate">${collection.slug}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium ${
                    collection.type === 'manual' 
                        ? 'bg-yellow-100 text-yellow-800' 
                        : 'bg-pink-100 text-pink-800'
                }">
                    ${collection.type === 'manual' ? 'Manual' : 'Auto'}
                </span>
            </td>
            <td class="px-6 py-4">
                <span class="text-sm text-gray-900 font-medium">${collection.products_count || 0}</span>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium ${
                    collection.visibility === 'visible' 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-gray-100 text-gray-800'
                }">
                    ${collection.visibility === 'visible' ? 'Visible' : 'Hidden'}
                </span>
            </td>
            <td class="px-6 py-4">
                ${collection.is_featured 
                    ? '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">Featured</span>'
                    : '<span class="text-xs text-gray-400">-</span>'
                }
            </td>
            <td class="px-6 py-4">
                <button onclick="toggleStatus(${collection.id})" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium transition-colors ${
                    collection.status 
                        ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                        : 'bg-red-100 text-red-800 hover:bg-red-200'
                }">
                    ${collection.status ? 'Active' : 'Inactive'}
                </button>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editCollection(${collection.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteCollection(${collection.id}, '${collection.name}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Load stats
function loadStats() {
    fetch('/admin/collections/list?per_page=1000')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const collections = data.data;
                document.getElementById('totalCollections').textContent = collections.length;
                document.getElementById('activeCollections').textContent = collections.filter(c => c.status).length;
                document.getElementById('featuredCollections').textContent = collections.filter(c => c.is_featured).length;
                document.getElementById('manualCollections').textContent = collections.filter(c => c.type === 'manual').length;
                document.getElementById('autoCollections').textContent = collections.filter(c => c.type === 'auto').length;
            }
        });
}

// Pagination
function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (pagination.last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let pages = '';
    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
            pages += `
                <button onclick="loadCollections(${i})" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
                    i === pagination.current_page 
                        ? 'bg-[#0082C3] text-white' 
                        : 'text-gray-700 hover:bg-gray-100'
                }">
                    ${i}
                </button>
            `;
        } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
            pages += '<span class="px-2 text-gray-400">...</span>';
        }
    }

    container.innerHTML = `
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Showing ${((pagination.current_page - 1) * pagination.per_page) + 1} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} collections
            </p>
            <div class="flex items-center gap-1">
                <button onclick="loadCollections(${pagination.current_page - 1})" ${pagination.current_page === 1 ? 'disabled' : ''} class="px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                ${pages}
                <button onclick="loadCollections(${pagination.current_page + 1})" ${pagination.current_page === pagination.last_page ? 'disabled' : ''} class="px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    `;
}

// Search with debounce
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadCollections(1);
    }, 500);
}

// Modal functions
function openAddModal() {
    isEditMode = false;
    document.getElementById('modalTitle').textContent = 'Add Collection';
    document.getElementById('collectionForm').reset();
    document.getElementById('collectionId').value = '';
    selectedProducts = [];
    collectionRules = [];
    removeCollectionImage();
    switchTab('basic');
    openModal();
}

function openModal() {
    const modal = document.getElementById('collectionModal');
    const modalContent = document.getElementById('collectionModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.style.transform = 'translateX(0)';
        // Initialize searchable selects after modal opens
        setTimeout(() => {
            initializeSearchableSelects();
            // Initialize Summernote editor
            initSummernote('collectionDescription', 'full');
        }, 100);
    }, 10);
}

function closeModal() {
    const modalContent = document.getElementById('collectionModalContent');
    modalContent.style.transform = 'translateX(100%)';
    setTimeout(() => {
        document.getElementById('collectionModal').classList.add('hidden');
    }, 500);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'collectionModal') {
        closeModal();
    }
}

// Tab switching
function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
    
    document.getElementById(`tab-${tabName}`).classList.add('active');
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
}

// Handle type change
function handleTypeChange() {
    const type = document.getElementById('collectionType').value;
    const productsTab = document.getElementById('tab-products');
    const rulesTab = document.getElementById('tab-rules');
    
    if (type === 'manual') {
        productsTab.classList.remove('opacity-50', 'pointer-events-none');
        rulesTab.classList.add('opacity-50', 'pointer-events-none');
    } else {
        productsTab.classList.add('opacity-50', 'pointer-events-none');
        rulesTab.classList.remove('opacity-50', 'pointer-events-none');
    }
}

// Auto-generate slug
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('collectionName');
    const slugInput = document.getElementById('collectionSlug');
    
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!isEditMode || slugInput.value === '') {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    }
});

// ImageKit integration
function openImageKitPicker(type) {
    if (typeof window.imageKitPicker === 'function') {
        window.imageKitPicker((url) => {
            if (type === 'collection') {
                document.getElementById('collectionImageUrl').value = url;
                document.getElementById('collectionImagePreview').innerHTML = `
                    <img src="${url}" alt="Collection" class="w-full h-full object-cover">
                `;
            }
        });
    } else {
        showToast('ImageKit picker not available', 'error');
    }
}

function removeCollectionImage() {
    document.getElementById('collectionImageUrl').value = '';
    document.getElementById('collectionImagePreview').innerHTML = `
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    `;
}

// Save collection
function saveCollection() {
    const id = document.getElementById('collectionId').value;
    const name = document.getElementById('collectionName').value.trim();
    const slug = document.getElementById('collectionSlug').value.trim();
    const description = getSummernoteContent('collectionDescription');
    const imageUrl = document.getElementById('collectionImageUrl').value;
    const type = document.getElementById('collectionType').value;
    const visibility = document.getElementById('collectionVisibility').value;
    const sortOrder = document.getElementById('collectionSortOrder').value;
    const status = document.getElementById('collectionStatus').value;
    const isFeatured = document.getElementById('collectionFeatured').checked;
    const seoTitle = document.getElementById('collectionSeoTitle').value.trim();
    const seoDescription = document.getElementById('collectionSeoDescription').value.trim();

    if (!name) {
        showToast('Please enter collection name', 'error');
        switchTab('basic');
        document.getElementById('collectionName').focus();
        return;
    }

    const formData = {
        name,
        slug: slug || undefined,
        description,
        image_url: imageUrl,
        type,
        visibility,
        sort_order: sortOrder || 0,
        status: status === '1',
        is_featured: isFeatured,
        seo_title: seoTitle,
        seo_description: seoDescription
    };

    if (type === 'manual') {
        formData.product_ids = selectedProducts.map(p => p.id);
    } else {
        formData.rules = collectionRules;
    }

    const url = id ? `/admin/collections/${id}` : '/admin/collections';
    const method = id ? 'PUT' : 'POST';

    document.getElementById('saveBtn').disabled = true;
    document.getElementById('saveBtn').textContent = 'Saving...';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal();
            loadCollections(currentPage);
            loadStats();
        } else {
            if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    showToast(data.errors[key][0], 'error');
                });
            } else {
                showToast(data.message || 'Failed to save collection', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while saving', 'error');
    })
    .finally(() => {
        document.getElementById('saveBtn').disabled = false;
        document.getElementById('saveBtn').textContent = 'Save Collection';
    });
}

// Edit collection
function editCollection(id) {
    isEditMode = true;
    fetch(`/admin/collections/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const collection = data.data;
                
                document.getElementById('modalTitle').textContent = 'Edit Collection';
                document.getElementById('collectionId').value = collection.id;
                document.getElementById('collectionName').value = collection.name;
                document.getElementById('collectionSlug').value = collection.slug;
                setSummernoteContent('collectionDescription', collection.description || '');
                document.getElementById('collectionType').value = collection.type;
                document.getElementById('collectionVisibility').value = collection.visibility;
                document.getElementById('collectionSortOrder').value = collection.sort_order || 0;
                document.getElementById('collectionStatus').value = collection.status ? '1' : '0';
                document.getElementById('collectionFeatured').checked = collection.is_featured;
                document.getElementById('collectionSeoTitle').value = collection.seo_title || '';
                document.getElementById('collectionSeoDescription').value = collection.seo_description || '';
                
                if (collection.image_url) {
                    document.getElementById('collectionImageUrl').value = collection.image_url;
                    document.getElementById('collectionImagePreview').innerHTML = `
                        <img src="${collection.image_url}" alt="Collection" class="w-full h-full object-cover">
                    `;
                } else {
                    removeCollectionImage();
                }

                selectedProducts = collection.products || [];
                collectionRules = collection.rules || [];
                
                handleTypeChange();
                renderSelectedProducts();
                renderRules();
                
                openModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load collection', 'error');
        });
}

// Delete collection
function deleteCollection(id, name) {
    if (!confirm(`Are you sure you want to delete "${name}"?`)) {
        return;
    }

    fetch(`/admin/collections/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadCollections(currentPage);
            loadStats();
        } else {
            showToast(data.message || 'Failed to delete collection', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while deleting', 'error');
    });
}

// Toggle status
function toggleStatus(id) {
    fetch(`/admin/collections/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadCollections(currentPage);
            loadStats();
        } else {
            showToast(data.message || 'Failed to toggle status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    });
}

// Product Selector
function openProductSelector() {
    document.getElementById('productSelectorModal').classList.remove('hidden');
    loadProductsForSelector();
}

function closeProductSelector() {
    document.getElementById('productSelectorModal').classList.add('hidden');
}

function loadProductsForSelector() {
    fetch('/admin/products/list?per_page=100&status=active')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderProductSelector(data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load products', 'error');
        });
}

function renderProductSelector(products) {
    const container = document.getElementById('productSelectorList');
    
    if (products.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-gray-500">
                <p class="text-sm">No products found</p>
            </div>
        `;
        return;
    }

    container.innerHTML = products.map(product => {
        const isSelected = selectedProducts.some(p => p.id === product.id);
        return `
            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors ${isSelected ? 'bg-blue-50 border-blue-300' : ''}">
                <input type="checkbox" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" value="${product.id}" ${isSelected ? 'checked' : ''} onchange="toggleProductSelection(${product.id}, '${product.name}', '${product.image_url || ''}')">
                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                    ${product.image_url 
                        ? `<img src="${product.image_url}" alt="${product.name}" class="w-full h-full object-cover">`
                        : `<svg class="w-6 h-6 text-gray-400 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>`
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${product.name}</p>
                    <p class="text-xs text-gray-500">${product.sku || 'No SKU'}</p>
                </div>
            </label>
        `;
    }).join('');
}

function toggleProductSelection(id, name, imageUrl) {
    const index = selectedProducts.findIndex(p => p.id === id);
    if (index > -1) {
        selectedProducts.splice(index, 1);
    } else {
        selectedProducts.push({ id, name, image_url: imageUrl });
    }
}

function confirmProductSelection() {
    renderSelectedProducts();
    closeProductSelector();
    showToast(`${selectedProducts.length} products selected`, 'success');
}

function renderSelectedProducts() {
    const container = document.getElementById('selectedProductsList');
    
    if (selectedProducts.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <p class="text-sm">No products selected yet</p>
                <p class="text-xs mt-1">Click "Add Products" to select products for this collection</p>
            </div>
        `;
        return;
    }

    container.innerHTML = selectedProducts.map((product, index) => `
        <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg bg-white">
            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                ${product.image_url 
                    ? `<img src="${product.image_url}" alt="${product.name}" class="w-full h-full object-cover">`
                    : `<svg class="w-6 h-6 text-gray-400 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>`
                }
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${product.name}</p>
                <p class="text-xs text-gray-500">Position: ${index + 1}</p>
            </div>
            <button type="button" onclick="removeProduct(${product.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `).join('');
}

function removeProduct(id) {
    selectedProducts = selectedProducts.filter(p => p.id !== id);
    renderSelectedProducts();
}

function searchProducts() {
    const search = document.getElementById('productSearchInput').value;
    fetch(`/admin/products/list?per_page=100&status=active&search=${search}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderProductSelector(data.data);
            }
        });
}

// Rules Management
function addRule() {
    const rule = {
        field: 'name',
        operator: 'contains',
        value: ''
    };
    collectionRules.push(rule);
    renderRules();
    initializeSearchableSelects();
}

function renderRules() {
    const container = document.getElementById('rulesContainer');
    
    if (collectionRules.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                <p class="text-sm">No rules defined yet</p>
                <p class="text-xs mt-1">Click "Add Rule" to create automatic product selection rules</p>
            </div>
        `;
        return;
    }

    container.innerHTML = collectionRules.map((rule, index) => `
        <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg bg-white">
            <div class="flex-1 grid grid-cols-3 gap-3">
                <select data-searchable data-placeholder="Select Field" onchange="updateRule(${index}, 'field', this.value)" class="rule-field-select px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <option value="name" ${rule.field === 'name' ? 'selected' : ''}>Product Name</option>
                    <option value="sku" ${rule.field === 'sku' ? 'selected' : ''}>SKU</option>
                    <option value="brand_id" ${rule.field === 'brand_id' ? 'selected' : ''}>Brand</option>
                    <option value="category_id" ${rule.field === 'category_id' ? 'selected' : ''}>Category</option>
                    <option value="price" ${rule.field === 'price' ? 'selected' : ''}>Price</option>
                    <option value="status" ${rule.field === 'status' ? 'selected' : ''}>Status</option>
                    <option value="type" ${rule.field === 'type' ? 'selected' : ''}>Product Type</option>
                    <option value="is_featured" ${rule.field === 'is_featured' ? 'selected' : ''}>Featured</option>
                    <option value="is_new" ${rule.field === 'is_new' ? 'selected' : ''}>New Arrival</option>
                    <option value="is_best_seller" ${rule.field === 'is_best_seller' ? 'selected' : ''}>Best Seller</option>
                </select>
                <select data-searchable data-placeholder="Select Operator" onchange="updateRule(${index}, 'operator', this.value)" class="rule-operator-select px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <option value="equals" ${rule.operator === 'equals' ? 'selected' : ''}>Equals</option>
                    <option value="not_equals" ${rule.operator === 'not_equals' ? 'selected' : ''}>Not Equals</option>
                    <option value="contains" ${rule.operator === 'contains' ? 'selected' : ''}>Contains</option>
                    <option value="greater_than" ${rule.operator === 'greater_than' ? 'selected' : ''}>Greater Than</option>
                    <option value="less_than" ${rule.operator === 'less_than' ? 'selected' : ''}>Less Than</option>
                </select>
                <input type="text" value="${rule.value}" onchange="updateRule(${index}, 'value', this.value)" placeholder="Value" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <button type="button" onclick="removeRule(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `).join('');
}

function initializeSearchableSelects() {
    // Initialize all searchable selects
    document.querySelectorAll('select[data-searchable]').forEach(select => {
        // Skip if already initialized
        if (select.nextElementSibling && select.nextElementSibling.classList.contains('searchable-select-wrapper')) {
            return;
        }
        new SearchableSelect(select, {
            placeholder: select.dataset.placeholder || 'Select...',
            searchPlaceholder: select.dataset.searchPlaceholder || 'Type to search...'
        });
    });
}

function updateRule(index, field, value) {
    collectionRules[index][field] = value;
}

function removeRule(index) {
    collectionRules.splice(index, 1);
    renderRules();
}

// Bulk Actions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.collection-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.collection-checkbox:checked');
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
    const checkboxes = document.querySelectorAll('.collection-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));

    if (!action) {
        showToast('Please select an action', 'error');
        return;
    }

    if (ids.length === 0) {
        showToast('Please select collections', 'error');
        return;
    }

    if (action === 'delete' && !confirm(`Are you sure you want to delete ${ids.length} collections?`)) {
        return;
    }

    fetch('/admin/collections/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ action, ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadCollections(currentPage);
            loadStats();
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
        } else {
            showToast(data.message || 'Bulk action failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    });
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-50 transition-all transform translate-y-0 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(100px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
