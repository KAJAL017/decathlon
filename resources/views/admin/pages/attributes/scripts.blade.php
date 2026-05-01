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
        'color': 'bg-purple-100 text-purple-700',
        'text': 'bg-green-100 text-green-700',
        'number': 'bg-orange-100 text-orange-700'
    };

    tbody.innerHTML = attributes.map(attribute => {
        const badges = [];
        if (attribute.is_variant) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">Variant</span>');
        }
        if (attribute.is_filterable) {
            badges.push('<span class="inline-flex items-center px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs">Filter</span>');
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
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 ${typeColors[attribute.type] || 'bg-gray-100 text-gray-700'} rounded-md text-xs font-medium capitalize">${attribute.type}</span>
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
                    <button onclick="viewAttribute(${attribute.id})" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View attribute">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
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
    document.getElementById('attributeRequired').checked = false;
    document.getElementById('attributeSortOrder').value = 0;
    
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

// Fill Demo Data Function
function fillDemoData() {
    // Sample demo attributes data
    const demoAttributes = [
        {
            name: 'Color',
            slug: 'color',
            type: 'color',
            displayType: 'color_swatch',
            sortOrder: 1,
            variant: true,
            filterable: true,
            required: false
        },
        {
            name: 'Size',
            slug: 'size',
            type: 'select',
            displayType: 'radio',
            sortOrder: 2,
            variant: true,
            filterable: true,
            required: true
        },
        {
            name: 'Material',
            slug: 'material',
            type: 'select',
            displayType: 'dropdown',
            sortOrder: 3,
            variant: false,
            filterable: true,
            required: false
        },
        {
            name: 'Weight',
            slug: 'weight',
            type: 'number',
            displayType: 'dropdown',
            unit: 'kg',
            sortOrder: 4,
            variant: false,
            filterable: true,
            required: false
        },
        {
            name: 'Brand',
            slug: 'brand',
            type: 'select',
            displayType: 'dropdown',
            sortOrder: 5,
            variant: false,
            filterable: true,
            required: false
        },
        {
            name: 'Waterproof',
            slug: 'waterproof',
            type: 'boolean',
            displayType: 'checkbox',
            sortOrder: 6,
            variant: false,
            filterable: true,
            required: false
        },
        {
            name: 'Gender',
            slug: 'gender',
            type: 'select',
            displayType: 'radio',
            sortOrder: 7,
            variant: false,
            filterable: true,
            required: false
        },
        {
            name: 'Pattern',
            slug: 'pattern',
            type: 'multiselect',
            displayType: 'checkbox',
            sortOrder: 8,
            variant: false,
            filterable: true,
            required: false
        }
    ];
    
    // Pick a random demo attribute
    const randomAttribute = demoAttributes[Math.floor(Math.random() * demoAttributes.length)];
    
    // Fill the form
    document.getElementById('attributeName').value = randomAttribute.name;
    document.getElementById('attributeSlug').value = randomAttribute.slug;
    document.getElementById('attributeType').value = randomAttribute.type;
    document.getElementById('attributeDisplayType').value = randomAttribute.displayType;
    document.getElementById('attributeSortOrder').value = randomAttribute.sortOrder;
    document.getElementById('attributeVariant').checked = randomAttribute.variant;
    document.getElementById('attributeFilterable').checked = randomAttribute.filterable;
    document.getElementById('attributeRequired').checked = randomAttribute.required;
    document.getElementById('attributeStatus').checked = true;
    
    // Show unit field if type is number
    if (randomAttribute.type === 'number' && randomAttribute.unit) {
        document.getElementById('unitField').style.display = 'block';
        document.getElementById('attributeUnit').value = randomAttribute.unit;
    }
    
    showNotification('Demo data filled successfully! You can now modify and save.', 'success');
    
    // Add a subtle highlight animation to the form
    const form = document.getElementById('attributeForm');
    form.style.animation = 'pulse 0.5s ease-in-out';
    setTimeout(() => {
        form.style.animation = '';
    }, 500);
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
    const isVariant = document.getElementById('attributeVariant').checked;
    const isFilterable = document.getElementById('attributeFilterable').checked;
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
            type,
            is_variant: isVariant,
            is_filterable: isFilterable,
            is_required: isRequired,
            status,
            sort_order: sortOrder
        })
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
    fetch(`/admin/attributes/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const attr = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Attribute';
            document.getElementById('submitBtnText').textContent = 'Update Attribute';
            document.getElementById('attributeId').value = attr.id;
            document.getElementById('attributeName').value = attr.name;
            document.getElementById('attributeSlug').value = attr.slug;
            document.getElementById('attributeType').value = attr.type;
            document.getElementById('attributeVariant').checked = attr.is_variant;
            document.getElementById('attributeFilterable').checked = attr.is_filterable;
            document.getElementById('attributeRequired').checked = attr.is_required;
            document.getElementById('attributeStatus').checked = attr.status;
            document.getElementById('attributeSortOrder').value = attr.sort_order;
            
            openAddModal();
        }
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
    // Reuse existing notification system from categories
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showConfirmDialog(options) {
    // Reuse existing confirmation dialog from categories
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
