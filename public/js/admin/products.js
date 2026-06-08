/*
 * Product Management - Optimized Admin Logic
 */

let currentPage = 1;
let searchTimeout;
let selectedProducts = new Set();
let currentTab = 'basic';
let productImages = [];
let productVideos = [];
let productFaqs = [];
let productVariants = [];
let variantAttributes = [];
let relatedProducts = { related: [], upsell: [], cross_sell: [] };
let currentRelationType = '';
let selectedProductsForRelation = new Set();
let _attrGroups = [];
let _productAttrs = [];

// Cached Data
const cachedData = {
    brands: null,
    categories: null,
    tags: null,
    collections: null
};

// Collapsible sections state
let collapsibleState = {};

function loadCollapsibleState() {
    const saved = localStorage.getItem('productFormCollapsibleState');
    if (saved) {
        try {
            collapsibleState = JSON.parse(saved);
        } catch (e) {
            collapsibleState = {};
        }
    }
}

function saveCollapsibleState() {
    localStorage.setItem('productFormCollapsibleState', JSON.stringify(collapsibleState));
}

function toggleCollapsible(sectionId) {
    const section = document.querySelector(`[data-section="${sectionId}"]`);
    if (section) {
        const header = section.querySelector('.collapsible-header');
        const content = section.querySelector('.collapsible-content');
        const icon = section.querySelector('.collapsible-icon');
        if (!content || !header) return;
        
        const isActive = header.classList.contains('active');
        if (isActive) {
            header.classList.remove('active');
            content.classList.remove('active');
            if (icon) icon.style.transform = 'rotate(-90deg)';
            collapsibleState[sectionId] = false;
        } else {
            header.classList.add('active');
            content.classList.add('active');
            if (icon) icon.style.transform = 'rotate(0deg)';
            collapsibleState[sectionId] = true;
        }
        saveCollapsibleState();
        return;
    }
}

function initCollapsibleSections() {
    loadCollapsibleState();
    const defaultOpenSections = ['section-pricing', 'product-status', 'product-categories', 'product-tags'];
    
    document.querySelectorAll('[data-section]').forEach(section => {
        const sectionId = section.getAttribute('data-section');
        const header = section.querySelector('.collapsible-header');
        const content = section.querySelector('.collapsible-content');
        const icon = section.querySelector('.collapsible-icon');
        if (!content || !header) return;
        
        const shouldBeOpen = collapsibleState.hasOwnProperty(sectionId) ? collapsibleState[sectionId] : defaultOpenSections.includes(sectionId);
        if (shouldBeOpen) {
            header.classList.add('active');
            content.classList.add('active');
            if (icon) icon.style.transform = 'rotate(0deg)';
            collapsibleState[sectionId] = true;
        } else {
            header.classList.remove('active');
            content.classList.remove('active');
            if (icon) icon.style.transform = 'rotate(-90deg)';
            collapsibleState[sectionId] = false;
        }
    });
    saveCollapsibleState();
}

// Optimized Data Loaders
async function fetchAndCache(key, url, callback) {
    if (cachedData[key]) {
        if (callback) callback(cachedData[key]);
        return;
    }
    try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const data = await res.json();
        if (data.success) {
            cachedData[key] = data.data || data.tags?.data || data;
            if (callback) callback(cachedData[key]);
        }
    } catch (err) {
        console.error(`Error loading ${key}:`, err);
    }
}

function loadBrands() {
    fetchAndCache('brands', '/admin/brands/list?per_page=1000&status=1', (brands) => {
        const brandSelect = document.getElementById('productBrand');
        const brandFilter = document.getElementById('brandFilter');
        const optionsHtml = brands.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
        
        if (brandSelect) {
            brandSelect.innerHTML = '<option value="">Select Brand</option>' + optionsHtml;
            refreshSearchableSelect(brandSelect);
        }
        if (brandFilter) {
            brandFilter.innerHTML = '<option value="">All Brands</option>' + optionsHtml;
            refreshSearchableSelect(brandFilter);
        }
    });
}

function loadCategories() {
    fetchAndCache('categories', '/admin/categories/list?per_page=1000&status=1', (categories) => {
        const categorySelect = document.getElementById('productPrimaryCategory');
        const additionalSelect = document.getElementById('productAdditionalCategories');
        const categoryFilter = document.getElementById('categoryFilter');
        const optionsHtml = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        
        if (categorySelect) {
            categorySelect.innerHTML = '<option value="">Select Category</option>' + optionsHtml;
            refreshSearchableSelect(categorySelect);
        }
        if (additionalSelect) {
            additionalSelect.innerHTML = optionsHtml;
            refreshSearchableSelect(additionalSelect);
        }
        if (categoryFilter) {
            categoryFilter.innerHTML = '<option value="">All Categories</option>' + optionsHtml;
            refreshSearchableSelect(categoryFilter);
        }
    });
}

function loadTags() {
    fetchAndCache('tags', '/admin/tags/list?per_page=1000&status=1', (tags) => {
        const tagsSelect = document.getElementById('productTags');
        if (tagsSelect) {
            tagsSelect.innerHTML = tags.map(t => `<option value="${t.id}">${t.name}</option>`).join('');
            refreshSearchableSelect(tagsSelect);
        }
    });
}

function loadCollections() {
    fetchAndCache('collections', '/admin/collections/list?per_page=1000&status=1', (collections) => {
        const collectionsSelect = document.getElementById('productCollections');
        if (collectionsSelect) {
            collectionsSelect.innerHTML = collections.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
            refreshSearchableSelect(collectionsSelect);
        }
    });
}

function refreshSearchableSelect(el) {
    if (typeof searchableSelectInstances !== 'undefined') {
        const instance = searchableSelectInstances.find(inst => inst.select === el);
        if (instance) instance.refresh();
    }
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        requestAnimationFrame(() => loadProducts(1));
    }, 300);
}

function loadProducts(page = 1) {
    currentPage = page;
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    const search = searchInput.value;
    const brand = document.getElementById('brandFilter')?.value || '';
    const category = document.getElementById('categoryFilter')?.value || '';
    const type = document.getElementById('typeFilter')?.value || '';
    const status = document.getElementById('statusFilter')?.value || '';
    const perPage = document.getElementById('perPageSelect')?.value || 10;

    const url = `/admin/products/list?page=${page}&search=${search}&brand_id=${brand}&category_id=${category}&product_type=${type}&status=${status}&per_page=${perPage}`;

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderProducts(data.data);
            renderPagination(data.pagination);
            updateStats(data.stats);
        }
    })
    .catch(err => console.error('Error loading products:', err));
}

function renderProducts(products) {
    const tbody = document.getElementById('productsTableBody');
    if (products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="px-6 py-12 text-center text-gray-500">No products found</td></tr>';
        return;
    }

    const statusColors = { active: 'bg-green-100 text-green-700', inactive: 'bg-red-100 text-red-700', draft: 'bg-yellow-100 text-yellow-700' };
    const typeColors = { simple: 'bg-blue-100 text-blue-700', variable: 'bg-purple-100 text-purple-700', digital: 'bg-cyan-100 text-cyan-700', service: 'bg-orange-100 text-orange-700' };

    tbody.innerHTML = products.map(product => {
        const imageUrl = product.featured_image?.image_url || '';
        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4"><input type="checkbox" class="product-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded" value="${product.id}" onchange="updateBulkActions()"></td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        ${imageUrl ? `<img src="${imageUrl}" class="w-12 h-12 object-cover rounded-lg border border-gray-100 shadow-sm flex-shrink-0">` : `<div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center text-gray-300 border border-dashed border-gray-200 flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`}
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-600 transition-colors">${product.name}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-mono font-bold rounded uppercase tracking-tighter">${product.sku_prefix || 'NO-SKU'}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">${product.product_type}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">${product.brand?.name || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-600">${product.category?.name || '-'}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs font-medium ${typeColors[product.product_type]}">${product.product_type}</span></td>
                <td class="px-6 py-4 text-sm text-gray-600">${product.variants_count || 0}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">₹${product.min_price || '0.00'}</td>
                <td class="px-6 py-4"><button onclick="toggleStatus(${product.id})" class="px-2 py-1 rounded text-xs font-medium ${statusColors[product.status]}">${product.status}</button></td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="/admin/products/${product.id}/edit" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded inline-flex"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                        <button onclick="deleteProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}')" class="p-1.5 text-red-600 hover:bg-red-50 rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function updateStats(stats) {
    if (!stats) return;
    const ids = ['totalProducts', 'activeProducts', 'draftProducts', 'featuredProducts', 'newProducts', 'bestSellerProducts', 'trendingProducts'];
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = stats[id.replace('Products', '').toLowerCase()] || 0;
    });
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (!container || pagination.last_page <= 1) { container.innerHTML = ''; return; }
    
    let pages = [];
    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)) pages.push(i);
        else if (pages[pages.length-1] !== '...') pages.push('...');
    }

    container.innerHTML = `
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} results</p>
            <div class="flex gap-1">
                ${pages.map(p => p === '...' ? '<span class="px-2 py-1">...</span>' : `<button onclick="loadProducts(${p})" class="px-3 py-1 border rounded ${p === pagination.current_page ? 'bg-[#0082C3] text-white' : 'hover:bg-gray-50'}">${p}</button>`).join('')}
            </div>
        </div>
    `;
}

// Modal & Tab Logic
const TAB_ORDER = ['basic', 'pricing', 'media', 'variants', 'organization', 'seo', 'advanced'];

function switchTab(tabName) {
    currentTab = tabName;
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.toggle('active', btn.id === `tab-${tabName}`));
    TAB_ORDER.forEach(t => {
        const el = document.getElementById(`content-${t}`);
        if (el) el.classList.toggle('tab-active', t === tabName);
    });
    
    // Lazy init Summernote on Basic tab
    if (tabName === 'basic') {
        setTimeout(() => {
            initSummernote('productShortDescription', 'simple');
            initSummernote('productDescription', 'full');
        }, 50);
    }
    updateTabNavigation();
}

function updateTabNavigation() {
    const idx = TAB_ORDER.indexOf(currentTab);
    const prevBtn = document.getElementById('prevTabBtn');
    const nextBtn = document.getElementById('nextTabBtn');
    const submitBtn = document.getElementById('submitBtn');

    if (prevBtn) prevBtn.classList.toggle('hidden', idx === 0);
    if (nextBtn) nextBtn.classList.toggle('hidden', idx === TAB_ORDER.length - 1);
    if (submitBtn) submitBtn.classList.toggle('hidden', idx !== TAB_ORDER.length - 1);
}

function nextTab() {
    const idx = TAB_ORDER.indexOf(currentTab);
    if (idx < TAB_ORDER.length - 1) switchTab(TAB_ORDER[idx + 1]);
}

function previousTab() {
    const idx = TAB_ORDER.indexOf(currentTab);
    if (idx > 0) switchTab(TAB_ORDER[idx - 1]);
}

function openAddModal() {
    resetForm();
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('submitBtnText').textContent = 'Create Product';
    switchTab('basic');
    loadTags(); loadCategories(); loadBrands(); loadCollections();
    openModal();
}

function editProduct(id) {
    loadTags(); loadCategories(); loadBrands(); loadCollections();
    fetch(`/admin/products/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            populateForm(data.data);
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('submitBtnText').textContent = 'Update Product';
            switchTab('basic');
            openModal();
        }
    });
}

function openModal() {
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('productModalContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        modalContent.style.transform = 'translateX(0)';
        const form = document.getElementById('productForm');
        if (form) form.style.visibility = 'visible';
    });
}

function closeModal() {
    const modalContent = document.getElementById('productModalContent');
    modalContent.style.transform = 'translateX(100%)';
    setTimeout(() => {
        document.getElementById('productModal').classList.add('hidden');
        document.body.style.overflow = '';
    }, 500);
}

function resetForm() {
    const form = document.getElementById('productForm');
    if (form) form.reset();
    document.getElementById('productId').value = '';
    productImages = []; productVideos = []; productFaqs = []; productVariants = []; _productAttrs = [];
    renderVideosList(); renderFaqsList(); renderProductImages(); renderVariantsList(); _renderProductAttrs();
    clearErrors();
}

function populateForm(p) {
    const s = (id, val) => { const el = document.getElementById(id); if (el) { el.value = val || ''; refreshSearchableSelect(el); } };
    const c = (id, val) => { const el = document.getElementById(id); if (el) el.checked = !!val; };
    const h = (id, show) => { const el = document.getElementById(id); if (el) el.classList.toggle('hidden', !show); };

    s('productId', p.id);
    s('productName', p.name);
    s('productSlug', p.slug);
    s('productType', p.product_type);
    s('productBrand', p.brand_id || '');
    s('productPrimaryCategory', p.category_id || '');
    
    setSummernoteContent('productShortDescription', p.short_description || '');
    setSummernoteContent('productDescription', p.description || '');
    
    s('productStatus', p.status);
    s('productVisibility', p.visibility || 'visible');
    s('productAvailability', p.availability_status || 'in_stock');
    s('productAvailableDate', p.available_date || '');
    h('availableDateContainer', ['pre_order', 'backorder'].includes(p.availability_status));

    c('productIsFeatured', p.is_featured);
    c('productIsNew', p.is_new);
    c('productIsBestSeller', p.is_best_seller);
    c('productIsTrending', p.is_trending);
    c('productIsDigital', p.is_digital);
    h('digitalProductSettings', p.is_digital);
    if (p.is_digital) {
        s('productDownloadUrl', p.download_url);
        s('productDownloadLimit', p.download_limit);
    }

    // Pricing & Inventory
    s('productRegularPrice', p.min_price || p.variants?.[0]?.price || '');
    s('productSalePrice', p.variants?.[0]?.compare_price || '');
    s('productSku', p.sku_prefix || '');
    
    const manageStock = !!p.manage_stock;
    c('productManageStock', manageStock);
    s('productStockQuantity', p.stock_quantity || 0);
    s('productLowStockThreshold', p.low_stock_threshold || 5);
    c('productAllowBackorder', !!p.allow_backorder);
    const stockFields = document.getElementById('stockFieldsContainer');
    if (stockFields) stockFields.style.display = manageStock ? 'grid' : 'none';
    if (manageStock && typeof updateStockBadge === 'function') updateStockBadge();

    // Dimensions
    s('productWeight', p.weight);
    s('productLength', p.length);
    s('productWidth', p.width);
    s('productHeight', p.height);

    // SEO
    s('productSeoTitle', p.seo_title);
    s('productSeoDescription', p.seo_description);
    s('productSeoKeywords', p.seo_keywords);

    // Multi-selects
    const syncMulti = (id, ids) => {
        const el = document.getElementById(id);
        if (el) {
            Array.from(el.options).forEach(opt => opt.selected = ids.includes(parseInt(opt.value)));
            refreshSearchableSelect(el);
        }
    };
    syncMulti('productTags', (p.tags || []).map(t => t.id));
    syncMulti('productAdditionalCategories', (p.categories || []).map(c => c.id));
    syncMulti('productCollections', (p.collections || []).map(c => c.id));

    productImages = (p.images || []).map(img => ({ ...img, url: img.image_url }));
    productVideos = p.videos || [];
    productFaqs = p.faqs || [];
    productVariants = p.variants || [];
    
    // Load related products via API
    relatedProducts = { related: [], upsell: [], cross_sell: [] };
    if (p.id) {
        ['related', 'upsell', 'cross_sell'].forEach(type => {
            fetch(`/admin/products/${p.id}/related/${type}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    relatedProducts[type] = data.data.map(item => item.id);
                    if (typeof renderRelatedProducts === 'function') renderRelatedProducts();
                }
            });
        });
    }
    
    renderProductImages(); 
    if (typeof renderVideosList === 'function') renderVideosList();
    if (typeof renderFaqsList === 'function') renderFaqsList();
    if (typeof renderVariantsList === 'function') renderVariantsList();
    if (typeof _renderProductAttrs === 'function') _renderProductAttrs();
}

function saveProduct() {
    const id = document.getElementById('productId').value;
    const url = id ? `/admin/products/${id}` : '/admin/products';
    const method = id ? 'PUT' : 'POST';
    
    const data = {
        name: document.getElementById('productName').value,
        slug: document.getElementById('productSlug').value,
        product_type: document.getElementById('productType').value,
        brand_id: document.getElementById('productBrand').value || null,
        category_id: document.getElementById('productPrimaryCategory').value || null,
        short_description: getSummernoteContent('productShortDescription'),
        description: getSummernoteContent('productDescription'),
        status: document.getElementById('productStatus').value,
        availability_status: document.getElementById('productAvailability').value,
        available_date: document.getElementById('productAvailableDate').value || null,
        visibility: document.getElementById('productVisibility').value,
        is_digital: document.getElementById('productIsDigital').checked ? 1 : 0,
        download_url: document.getElementById('productDownloadUrl').value || null,
        download_limit: document.getElementById('productDownloadLimit').value || null,
        is_featured: document.getElementById('productIsFeatured').checked ? 1 : 0,
        is_new: document.getElementById('productIsNew').checked ? 1 : 0,
        is_best_seller: document.getElementById('productIsBestSeller').checked ? 1 : 0,
        is_trending: document.getElementById('productIsTrending').checked ? 1 : 0,
        manage_stock: document.getElementById('productManageStock').checked ? 1 : 0,
        stock_quantity: document.getElementById('productManageStock').checked ? (parseInt(document.getElementById('productStockQuantity').value) || 0) : null,
        low_stock_threshold: parseInt(document.getElementById('productLowStockThreshold').value) || 5,
        allow_backorder: document.getElementById('productAllowBackorder').checked ? 1 : 0,
        weight: document.getElementById('productWeight').value || null,
        length: document.getElementById('productLength').value || null,
        width: document.getElementById('productWidth').value || null,
        height: document.getElementById('productHeight').value || null,
        seo_title: document.getElementById('productSeoTitle').value,
        seo_description: document.getElementById('productSeoDescription').value,
        seo_keywords: document.getElementById('productSeoKeywords').value,
        tags: Array.from(document.getElementById('productTags').selectedOptions).map(opt => parseInt(opt.value)).filter(Boolean),
        categories: Array.from(document.getElementById('productAdditionalCategories').selectedOptions).map(opt => parseInt(opt.value)).filter(Boolean),
        collections: Array.from(document.getElementById('productCollections').selectedOptions).map(opt => parseInt(opt.value)).filter(Boolean),
        videos: productVideos,
        faqs: productFaqs,
        variants: productVariants.map(v => ({
            sku: v.sku,
            price: v.price,
            compare_price: v.compare_price || null,
            cost_price: v.cost_price || null,
            stock_quantity: v.stock_quantity || 0,
            attributes: v.attributes,
        })),
    };

    clearErrors();
    setLoading(true);

    fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const productId = data.data.id;
            
            // Save related products
            const relatedPromises = [];
            ['related', 'upsell', 'cross_sell'].forEach(type => {
                if (relatedProducts[type].length > 0) {
                    relatedPromises.push(
                        fetch(`/admin/products/${productId}/related`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                type: type,
                                product_ids: relatedProducts[type]
                            })
                        })
                    );
                }
            });
            
            Promise.all(relatedPromises).then(() => {
                setLoading(false);
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '/admin/products';
                }, 1000);
            });
        } else {
            setLoading(false);
            if (data.errors) showValidationErrors(data.errors);
            else showToast(data.message || 'Operation failed', 'error');
        }
    })
    .catch(error => {
        setLoading(false);
        showToast('An error occurred while saving the product', 'error');
        console.error('Save error:', error);
    });
}

// Helpers
function showToast(msg, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-[100] px-6 py-3 rounded shadow-lg text-white text-sm font-medium transition-all ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.transform = 'translateX(200%)'; setTimeout(() => toast.remove(), 300); }, 3000);
}

function setLoading(l) {
    const btn = document.getElementById('submitBtn');
    if (btn) btn.disabled = l;
    document.getElementById('submitBtnText')?.classList.toggle('hidden', l);
    document.getElementById('submitBtnLoading')?.classList.toggle('hidden', !l);
}

function clearErrors() { document.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden')); }

function showValidationErrors(errors) {
    Object.keys(errors).forEach(key => {
        const el = document.getElementById(`product${key.charAt(0).toUpperCase() + key.slice(1)}Error`);
        if (el) { el.textContent = errors[key][0]; el.classList.remove('hidden'); }
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('searchInput')) loadProducts();
    initCollapsibleSections();
    
    // Auto-init form if we are on the form page
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.style.visibility = 'visible';
        loadTags(); loadCategories(); loadBrands(); loadCollections();
        
        // If we have a productId (Edit mode), fetch data
        const pathParts = window.location.pathname.split('/');
        if (pathParts.includes('edit')) {
            const id = pathParts[pathParts.length - 2];
            fetch(`/admin/products/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    populateForm(data.data);
                }
            });
        }
        
        productForm.addEventListener('submit', e => { e.preventDefault(); saveProduct(); });
        
        document.getElementById('productName')?.addEventListener('input', function() {
            document.getElementById('productSlug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        });

        document.getElementById('productManageStock')?.addEventListener('change', toggleStockFields);
        document.getElementById('productStockQuantity')?.addEventListener('input', updateStockBadge);
        document.getElementById('productLowStockThreshold')?.addEventListener('input', updateStockBadge);

        // Init tabs
        switchTab('basic');
    }
});

// Renderers for lists
function renderProductImages() {
    const grid = document.getElementById('productImagesGrid');
    if (!grid) return;
    
    if (productImages.length === 0) {
        grid.innerHTML = '<div class="col-span-full py-12 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center bg-gray-50/50"><svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><p class="text-sm font-medium text-gray-400">No images added yet</p></div>';
        return;
    }

    grid.innerHTML = productImages.map((img, i) => `
        <div class="group relative aspect-square bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all">
            <img src="${img.url}" alt="Product Image" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                <button type="button" onclick="removeProductImage(${i})" class="w-8 h-8 flex items-center justify-center bg-white/90 text-red-600 rounded-lg hover:bg-white transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
            ${i === 0 ? '<span class="absolute top-2 left-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] font-bold rounded-md shadow-sm">Featured</span>' : ''}
        </div>
    `).join('');
}

function removeProductImage(i) {
    productImages.splice(i, 1);
    renderProductImages();
    showToast('Image removed', 'info');
}

// ============================================
// VARIANT MANAGEMENT
// ============================================

function renderVariantsList() {
    const container = document.getElementById('variantsListContainer');
    if (!container) return;
    
    if (productVariants.length === 0) {
        container.innerHTML = '<div class="flex flex-col items-center justify-center py-12 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50"><svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg><p class="text-sm font-medium text-gray-400">No variants generated yet</p></div>';
        return;
    }
    
    container.innerHTML = `
        <div class="flex items-center justify-between mb-4 px-1">
            <h5 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px]">${productVariants.length}</span>
                Variants Matrix
            </h5>
            <div class="flex items-center gap-2">
                <button type="button" onclick="regenAllVariantSkus()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-50 transition-all shadow-sm">
                    Regen All SKUs
                </button>
                <button type="button" onclick="clearVariants()" class="px-3 py-1.5 text-red-600 text-xs font-bold hover:bg-red-50 rounded-lg transition-colors">
                    Clear All
                </button>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest w-10 text-center">#</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest min-w-[150px]">Variant / Attributes</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest min-w-[180px]">SKU</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest min-w-[120px]">Price (₹)</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest min-w-[120px]">Sale Price (₹)</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest min-w-[100px]">Stock</th>
                            <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest w-12 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        ${productVariants.map((variant, index) => `
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-bold text-gray-400">${index + 1}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-xs font-bold text-gray-900 truncate">${variant.name || 'Unnamed'}</p>
                                        <p class="text-[10px] text-gray-500 font-medium truncate mt-0.5">${(variant.attributes || []).map(a => a.valueName).join(' • ')}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="relative">
                                        <input type="text" value="${variant.sku || ''}" onchange="updateVariant(${index}, 'sku', this.value)" id="variantSku_${index}"
                                               class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-[11px] font-mono focus:bg-white focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                        <button type="button" onclick="regenVariantSku(${index})" class="absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-blue-600 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" value="${variant.price || ''}" onchange="updateVariant(${index}, 'price', this.value)"
                                           class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-[11px] font-bold focus:bg-white focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" value="${variant.compare_price || ''}" onchange="updateVariant(${index}, 'compare_price', this.value)"
                                           class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-[11px] font-bold text-red-600 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="0.00">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" value="${variant.stock_quantity || 0}" onchange="updateVariant(${index}, 'stock_quantity', this.value)"
                                           class="w-full px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-[11px] font-bold focus:bg-white focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" onclick="removeVariant(${index})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function updateVariant(index, field, value) {
    if (productVariants[index]) {
        productVariants[index][field] = value;
    }
}

function removeVariant(index) {
    productVariants.splice(index, 1);
    renderVariantsList();
    showToast('Variant removed', 'info');
}

function clearVariants() {
    if (confirm('Are you sure you want to clear all variants?')) {
        productVariants = [];
        renderVariantsList();
        showToast('All variants cleared', 'success');
    }
}

function buildBaseSkuFromProduct() {
    const brandEl = document.getElementById('productBrand');
    const brandName = brandEl?.options[brandEl.selectedIndex]?.text || '';
    const prodName = document.getElementById('productName')?.value || '';
    const catEl = document.getElementById('productPrimaryCategory');
    const catName = catEl?.options[catEl.selectedIndex]?.text || '';

    const b = brandName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
    const p = prodName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
    const c = catName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();

    return `${b}${c}${p}`;
}

function buildVariantSku(baseSku, attributes, index) {
    const attrCode = attributes.map(a => a.valueName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 2).toUpperCase()).join('');
    const serial = String(index + 1).padStart(3, '0');
    return `${baseSku}-${attrCode}-${serial}`;
}

function regenVariantSku(index) {
    if (!productVariants[index]) return;
    const baseSku = buildBaseSkuFromProduct();
    const newSku = buildVariantSku(baseSku, productVariants[index].attributes || [], index);
    updateVariant(index, 'sku', newSku);
    const input = document.getElementById(`variantSku_${index}`);
    if (input) input.value = newSku;
    showToast('SKU updated', 'info');
}

function regenAllVariantSkus() {
    const baseSku = buildBaseSkuFromProduct();
    productVariants.forEach((v, i) => {
        v.sku = buildVariantSku(baseSku, v.attributes || [], i);
    });
    renderVariantsList();
    showToast('All SKUs regenerated', 'success');
}

// ============================================
// VIDEO MANAGEMENT
// ============================================

function renderVideosList() {
    const container = document.getElementById('productVideosList');
    if (!container) return;
    
    if (productVideos.length === 0) {
        container.innerHTML = '<div class="text-center py-8 text-gray-500 text-sm italic">No videos added yet</div>';
        return;
    }
    
    container.innerHTML = productVideos.map((video, index) => `
        <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="w-20 h-14 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                ${video.thumbnail_url ? `<img src="${video.thumbnail_url}" class="w-full h-full object-cover">` : '<div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg></div>'}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">${video.title || 'Untitled Video'}</p>
                <p class="text-[10px] text-gray-500 font-mono truncate uppercase tracking-tight">${video.provider} • ${video.video_id}</p>
            </div>
            <button type="button" onclick="removeVideo(${index})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
    `).join('');
}

function removeVideo(index) {
    productVideos.splice(index, 1);
    renderVideosList();
    showToast('Video removed', 'info');
}

// ============================================
// FAQ MANAGEMENT
// ============================================

function renderFaqsList() {
    const container = document.getElementById('productFaqsList');
    if (!container) return;
    
    if (productFaqs.length === 0) {
        container.innerHTML = '<div class="text-center py-8 text-gray-500 text-sm italic">No FAQs added yet</div>';
        return;
    }
    
    container.innerHTML = productFaqs.map((faq, index) => `
        <div class="border border-gray-200 rounded-xl p-4 bg-white shadow-sm space-y-2">
            <div class="flex items-start justify-between gap-4">
                <div class="space-y-1 flex-1">
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Question ${index + 1}</p>
                    <p class="text-sm font-bold text-gray-900">${faq.question}</p>
                </div>
                <button type="button" onclick="removeFaq(${index})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
            <div class="pl-4 border-l-2 border-gray-100">
                <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Answer</p>
                <p class="text-sm text-gray-600 leading-relaxed">${faq.answer}</p>
            </div>
        </div>
    `).join('');
}

function removeFaq(index) {
    productFaqs.splice(index, 1);
    renderFaqsList();
    showToast('FAQ removed', 'info');
}

// ============================================
// ATTRIBUTE MANAGEMENT
// ============================================

function _renderProductAttrs() {
    const container = document.getElementById('productAttributesList');
    if (!container) return;

    if (_productAttrs.length === 0) {
        container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm border-2 border-dashed border-gray-100 rounded-xl">No attributes added yet</div>';
        return;
    }

    container.innerHTML = _productAttrs.map((a, i) => `
        <div class="flex items-center justify-between px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm group hover:border-blue-300 transition-all">
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">${a.attrName}</span>
                <span class="text-xs font-bold text-gray-900">${a.valueName}</span>
            </div>
            <button type="button" onclick="removeProductAttr(${i})" class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-600 transition-all p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>`).join('');
}

function removeProductAttr(i) {
    _productAttrs.splice(i, 1);
    _renderProductAttrs();
    showToast('Attribute removed', 'info');
}

function updateStockBadge() {
    const qty = parseInt(document.getElementById('productStockQuantity')?.value || 0);
    const threshold = parseInt(document.getElementById('productLowStockThreshold')?.value || 5);
    const badge = document.getElementById('stockStatusBadge');
    const inner = document.getElementById('stockStatusInner');
    if (!badge || !inner) return;

    badge.classList.remove('hidden');
    if (qty <= 0) {
        inner.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-red-100 text-red-700';
        inner.innerHTML = '🔴 Out of Stock';
    } else if (qty <= threshold) {
        inner.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-orange-100 text-orange-700';
        inner.innerHTML = `⚠️ Low Stock (${qty})`;
    } else {
        inner.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-green-100 text-green-700';
        inner.innerHTML = `✅ In Stock (${qty})`;
    }
}

function renderRelatedProducts() {
    ['related', 'upsell', 'cross_sell'].forEach(type => {
        const listId = type === 'cross_sell' ? 'crossSellProductsList' : `${type}ProductsList`;
        const container = document.getElementById(listId);
        if (!container) return;
        
        const ids = relatedProducts[type] || [];
        if (ids.length === 0) {
            container.innerHTML = '<p class="text-xs text-gray-400 text-center py-2">No products added</p>';
            return;
        }

        // Fetch minimal product info for display
        Promise.all(ids.map(id => fetch(`/admin/products/${id}`, { headers: { 'Accept': 'application/json' } }).then(r => r.json())))
            .then(responses => {
                container.innerHTML = responses.map(res => {
                    if (!res.success) return '';
                    const p = res.data;
                    return `
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg group">
                            <span class="text-xs font-medium text-gray-700 truncate max-w-[150px]">${p.name}</span>
                            <button type="button" onclick="removeRelatedProduct('${type}', ${p.id})" class="text-gray-400 hover:text-red-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    `;
                }).join('');
            });
    });
}

function removeRelatedProduct(type, id) {
    relatedProducts[type] = (relatedProducts[type] || []).filter(rid => rid != id);
    renderRelatedProducts();
}

// ============================================
// VARIANT GENERATOR (MATRIX)
// ============================================

function openVariantGenerator() {
    const modal = document.getElementById('variantGeneratorModal');
    if (!modal) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Always reload to get fresh attributes
    loadVariantAttributes();
}

function closeVariantGenerator() {
    const modal = document.getElementById('variantGeneratorModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function loadVariantAttributes() {
    fetch('/admin/products/variant-attributes', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            variantAttributes = data.data;
            renderVariantAttributesSelector();
        }
    })
    .catch(error => {
        console.error('Error loading variant attributes:', error);
        showToast('Failed to load attributes', 'error');
    });
}

function renderVariantAttributesSelector() {
    const container = document.getElementById('variantAttributesContainer');
    if (!container) return;

    if (variantAttributes.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <p class="text-sm font-medium text-gray-900 mb-1">No Variant Attributes Found</p>
                <p class="text-xs text-gray-500 mb-4">Create variant-enabled attributes first.</p>
                <a href="/admin/attributes" class="inline-flex items-center px-4 py-2 bg-[#0082C3] text-white text-xs font-bold rounded-lg uppercase tracking-widest">Setup Attributes</a>
            </div>`;
        return;
    }

    container.innerHTML = variantAttributes.map(attr => `
        <div class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-200 transition-colors">
            <label class="flex items-center gap-3 mb-3 cursor-pointer group">
                <input type="checkbox" class="variant-attr-checkbox w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" 
                       value="${attr.id}" data-name="${attr.name}" onchange="toggleAttributeValues(${attr.id})">
                <span class="text-sm font-bold text-gray-900 uppercase tracking-tight group-hover:text-[#0082C3] transition-colors">${attr.name}</span>
            </label>
            <div id="attr-values-${attr.id}" class="ml-7 grid grid-cols-2 sm:grid-cols-3 gap-2 hidden">
                ${attr.values.map(val => `
                    <label class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg text-xs font-medium cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all">
                        <input type="checkbox" class="variant-value-checkbox w-3.5 h-3.5 text-[#0082C3] rounded" 
                               data-attr="${attr.id}" data-attr-name="${attr.name}" value="${val.id}" data-value-name="${val.value}">
                        <span class="truncate">${val.value}</span>
                    </label>
                `).join('')}
            </div>
        </div>
    `).join('');
}

function toggleAttributeValues(attrId) {
    const container = document.getElementById(`attr-values-${attrId}`);
    const checkbox = document.querySelector(`.variant-attr-checkbox[value="${attrId}"]`);
    if (!container || !checkbox) return;

    if (checkbox.checked) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
        container.querySelectorAll('.variant-value-checkbox').forEach(cb => cb.checked = false);
    }
}

function generateVariants() {
    const selectedAttrs = [];

    document.querySelectorAll('.variant-attr-checkbox:checked').forEach(attrCheckbox => {
        const attrId = attrCheckbox.value;
        const attrName = attrCheckbox.dataset.name;
        const selectedValues = [];

        document.querySelectorAll(`.variant-value-checkbox[data-attr="${attrId}"]:checked`).forEach(valCheckbox => {
            selectedValues.push({
                id: parseInt(valCheckbox.value),
                name: valCheckbox.dataset.valueName
            });
        });

        if (selectedValues.length > 0) {
            selectedAttrs.push({ id: parseInt(attrId), name: attrName, values: selectedValues });
        }
    });

    if (selectedAttrs.length === 0) {
        showToast('Select at least one attribute and its values', 'warning');
        return;
    }

    // Combinatorial logic
    const combinations = generateCombinations(selectedAttrs);
    const baseSku = buildBaseSkuFromProduct();

    // Preserve existing price/stock if possible, or use global values
    const globalPrice = document.getElementById('productRegularPrice')?.value || '';
    const globalSale = document.getElementById('productSalePrice')?.value || '';

    const newVariants = combinations.map((combo, index) => {
        const variantName = combo.map(c => c.valueName).join(' / ');
        const variantSku  = buildVariantSku(baseSku, combo, index);
        
        return {
            id: null,
            name: variantName,
            sku: variantSku,
            price: globalPrice,
            compare_price: globalSale,
            stock_quantity: 0,
            attributes: combo,
            status: true
        };
    });

    if (productVariants.length > 0) {
        if (!confirm(`This will replace your current ${productVariants.length} variants. Continue?`)) return;
    }

    productVariants = newVariants;
    renderVariantsList();
    closeVariantGenerator();
    showToast(`Generated ${productVariants.length} variants!`, 'success');
}

function generateCombinations(attributes) {
    if (attributes.length === 0) return [];
    if (attributes.length === 1) {
        return attributes[0].values.map(val => [{
            attrId: attributes[0].id,
            attrName: attributes[0].name,
            valueId: val.id,
            valueName: val.name
        }]);
    }

    const result = [];
    const firstAttr = attributes[0];
    const restCombinations = generateCombinations(attributes.slice(1));

    firstAttr.values.forEach(val => {
        restCombinations.forEach(combo => {
            result.push([{
                attrId: firstAttr.id,
                attrName: firstAttr.name,
                valueId: val.id,
                valueName: val.name
            }, ...combo]);
        });
    });

    return result;
}

function toggleStockFields() {
    const checkbox = document.getElementById('productManageStock');
    const container = document.getElementById('stockFieldsContainer');
    if (!checkbox || !container) return;
    
    if (checkbox.checked) {
        container.classList.remove('hidden');
        container.style.display = 'grid';
        updateStockBadge();
    } else {
        container.classList.add('hidden');
        container.style.display = 'none';
    }
}

async function fillDemoData() {
    try {
        showToast('Fetching demo assets...', 'info');

        // Fetch brands, categories, tags, collections from DB
        const [brandsRes, categoriesRes, tagsRes, collectionsRes] = await Promise.all([
            fetch('/admin/brands/list?per_page=100&status=1',      { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
            fetch('/admin/categories/list?per_page=100&status=1',  { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
            fetch('/admin/tags/list?per_page=100&status=1',        { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
            fetch('/admin/collections/list?per_page=100&status=1', { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} }),
        ]);

        const [bd, cd, td, cold] = await Promise.all([brandsRes.json(), categoriesRes.json(), tagsRes.json(), collectionsRes.json()]);

        const brands      = bd.data   || [];
        const categories  = cd.data   || [];
        const tags        = (td.tags?.data || td.data || []);
        const collections = cold.data || [];

        if (!brands.length || !categories.length) {
            showToast('Please seed brands and categories first', 'warning');
            return;
        }

        const pick  = arr => arr[Math.floor(Math.random() * arr.length)];
        const rBrand  = pick(brands);
        const rCat    = pick(categories);

        // ── TAB 1: DETAILS ──────────────────────────────────
        const demoProducts = [
            { name: 'Kipsta Football Size 5',     slug: 'kipsta-football-size-5',     desc: 'Official-size football for training and matches. Durable thermobonded construction.',  short: 'Durable size 5 football for all levels of play.' },
            { name: 'Kalenji Running Shoes',       slug: 'kalenji-running-shoes-blue', desc: 'Lightweight road running shoes with responsive cushioning and breathable mesh upper.', short: 'Lightweight running shoes for road training.' },
            { name: 'Domyos Yoga Mat 5mm',         slug: 'domyos-yoga-mat-5mm',        desc: 'Non-slip yoga mat with 5mm cushioning for comfort. Easy to roll and carry.',          short: '5mm non-slip yoga mat for home and studio use.' },
            { name: 'Quechua Hiking Backpack 30L', slug: 'quechua-hiking-backpack-30l', desc: '30L hiking backpack with rain cover, padded straps and multiple pockets.',            short: '30L hiking backpack with rain cover included.' },
            { name: 'Artengo Badminton Racket',    slug: 'artengo-badminton-racket',   desc: 'Lightweight aluminium badminton racket for beginners and intermediate players.',       short: 'Aluminium badminton racket for casual play.' },
        ];
        const demo = pick(demoProducts);

        const _s = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };
        const _c = (id, val) => { const el = document.getElementById(id); if (el) el.checked = val; };
        const _inst = id => {
            if (typeof searchableSelectInstances === 'undefined') return null;
            return searchableSelectInstances.find(i => i.select === document.getElementById(id));
        };

        _s('productName',             demo.name);
        _s('productSlug',             demo.slug);

        // Product type — variable for variety
        _s('productType', 'variable');
        const typeInst = _inst('productType');
        if (typeInst) typeInst.setValue('variable');

        // Brand
        _s('productBrand', rBrand.id);
        const brandInst = _inst('productBrand');
        if (brandInst) brandInst.setValue(String(rBrand.id));

        // Descriptions
        _s('productShortDescription', demo.short);
        const descEl = document.getElementById('productDescription');
        if (descEl) {
            if (typeof $ !== 'undefined' && $(descEl).data('summernote')) {
                $(descEl).summernote('code', `<p>${demo.desc}</p><ul><li>High quality materials</li><li>Designed for performance</li><li>Available in multiple sizes</li><li>1 year warranty</li></ul>`);
            } else {
                descEl.value = demo.desc;
            }
        }

        // ── TAB 2: PRICING ──────────────────────────────────
        _s('productStatus',           'active');
        const statusInst = _inst('productStatus');
        if (statusInst) statusInst.setValue('active');

        _s('productAvailability',     'in_stock');
        const availInst = _inst('productAvailability');
        if (availInst) availInst.setValue('in_stock');

        _s('productVisibility',       'visible');
        const visInst = _inst('productVisibility');
        if (visInst) visInst.setValue('visible');

        _s('productRegularPrice',  '2499.00');
        _s('productSalePrice',     '1999.00');
        _s('productSku',           `${rBrand.name.substring(0,4).toUpperCase()}-${demo.slug.substring(0,6).toUpperCase()}-${Math.floor(1000+Math.random()*9000)}`);

        // Stock
        _c('productManageStock', true);
        toggleStockFields();
        _s('productStockQuantity',      '75');
        _s('productLowStockThreshold',  '10');
        _c('productAllowBackorder', false);
        updateStockBadge();

        // Shipping
        _s('productWeight', '0.45');
        _s('productLength', '22');
        _s('productWidth',  '18');
        _s('productHeight', '10');

        // ── TAB 4: VARIANTS ─────────────────────────────────
        setTimeout(() => {
            productVariants = [
                { id:null, name:'Red / M',   sku: `${demo.slug.substring(0,4).toUpperCase()}-RED-M-0001`,   price:'1999.00', compare_price:'2499.00', stock_quantity:20, attributes:[{attrId:null,attrName:'Color',valueId:null,valueName:'Red'},{attrId:null,attrName:'Size',valueId:null,valueName:'M'}],   images:[], status:true },
                { id:null, name:'Blue / L',  sku: `${demo.slug.substring(0,4).toUpperCase()}-BLU-L-0002`,   price:'1999.00', compare_price:'2499.00', stock_quantity:15, attributes:[{attrId:null,attrName:'Color',valueId:null,valueName:'Blue'},{attrId:null,attrName:'Size',valueId:null,valueName:'L'}],  images:[], status:true },
                { id:null, name:'Black / S', sku: `${demo.slug.substring(0,4).toUpperCase()}-BLK-S-0003`,   price:'1999.00', compare_price:'2499.00', stock_quantity:10, attributes:[{attrId:null,attrName:'Color',valueId:null,valueName:'Black'},{attrId:null,attrName:'Size',valueId:null,valueName:'S'}], images:[], status:true },
            ];
            renderVariantsList();
        }, 200);

        // ── TAB 5: ORGANIZATION ─────────────────────────────
        _s('productPrimaryCategory', rCat.id);
        const primaryCatInst = _inst('productPrimaryCategory');
        if (primaryCatInst) primaryCatInst.setValue(String(rCat.id));

        const tagsSelect = document.getElementById('productTags');
        if (tagsSelect && tags.length) {
            const pickedTags = tags.sort(() => 0.5 - Math.random()).slice(0,3);
            Array.from(tagsSelect.options).forEach(o => { o.selected = pickedTags.some(t => String(t.id) === o.value); });
            const tagInst = _inst('productTags');
            if (tagInst && typeof tagInst.refresh === 'function') tagInst.refresh();
        }

        const colSelect = document.getElementById('productCollections');
        if (colSelect && collections.length) {
            const pickedCols = collections.sort(() => 0.5 - Math.random()).slice(0,2);
            Array.from(colSelect.options).forEach(o => { o.selected = pickedCols.some(c => String(c.id) === o.value); });
            const colInst = _inst('productCollections');
            if (colInst && typeof colInst.refresh === 'function') colInst.refresh();
        }

        _c('productIsFeatured',   true);
        _c('productIsNew',        true);
        _c('productIsBestSeller', false);
        _c('productIsTrending',   true);

        showToast('Demo data filled! Check all tabs.', 'success');

    } catch (err) {
        console.error('fillDemoData error:', err);
        showToast('Failed to fill demo data', 'error');
    }
}

