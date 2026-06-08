@extends('admin.layouts.app')

@section('title', isset($productId) ? 'Edit Product' : 'Add New Product')

@section('content')
<div id="productFormContainer" class="w-full">
    <div id="productModalContent" class="relative w-full bg-white flex flex-col rounded-xl overflow-hidden border border-gray-200 shadow-sm">
        
        <!-- Header -->
        <div class="px-8 py-5 border-b border-gray-200 bg-white flex items-center justify-between z-10 shrink-0">
            <div class="flex items-center gap-4 min-w-0">
                <div class="bg-blue-50 p-2.5 rounded-xl shrink-0">
                    <svg class="w-6 h-6 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900 leading-tight truncate">
                        {{ isset($productId) ? 'Edit Product' : 'Add New Product' }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-0.5 font-medium truncate">Configure your product data, variants, and SEO settings.</p>
                </div>
            </div>
            <div class="flex items-center gap-4 shrink-0">
                <button type="button" onclick="fillDemoData()" class="hidden md:inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white text-sm font-semibold rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Auto-Fill Demo
                </button>
                <a href="{{ route('admin.products.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80 z-10 overflow-x-auto custom-scrollbar shrink-0">
            <div class="flex items-center gap-2 min-w-max">
                @foreach([
                    'basic' => ['Details', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    'pricing' => ['Pricing & Inventory', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'media' => ['Media', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    'variants' => ['Variants', 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'],
                    'organization' => ['Organization', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                    'seo' => ['SEO', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                    'advanced' => ['Advanced', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z']
                ] as $id => $info)
                <button type="button" onclick="switchTab('{{ $id }}')" id="tab-{{ $id }}" class="tab-btn {{ $id === 'basic' ? 'active' : '' }} flex-shrink-0 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info[1] }}"/></svg>
                        {{ $info[0] }}
                    </span>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Form Body -->
        <form id="productForm" class="flex-1 overflow-y-auto overflow-x-hidden min-w-0 bg-gray-50/30">
            <input type="hidden" id="productId" value="{{ $productId }}">

            <!-- TAB 1: DETAILS -->
            <div id="content-basic" class="tab-content active w-full p-6 lg:p-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-8">
                    <!-- Title & Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="productName" maxlength="200" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 font-medium focus:ring-2 focus:ring-[#0082C3] focus:border-transparent transition-all shadow-sm"
                                   placeholder="e.g., Kipsta Football Size 5">
                            <div class="flex items-center justify-between mt-2">
                                <p id="productNameError" class="hidden text-xs text-red-600 font-medium"></p>
                                <p class="text-xs text-gray-400 font-medium ml-auto"><span id="productNameCount">0</span> / 200</p>
                            </div>
                        </div>

                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                URL Handle (Slug) <span class="text-gray-400 font-normal text-xs ml-1">(Auto-generated)</span>
                            </label>
                            <div class="flex items-center min-w-0">
                                <input type="text" id="productSlug"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#0082C3] focus:border-transparent transition-all shadow-sm font-mono"
                                       placeholder="kipsta-football-size-5">
                            </div>
                            <p id="productSlugError" class="hidden text-xs text-red-600 font-medium mt-2"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Product Type <span class="text-red-500">*</span>
                            </label>
                            <select id="productType" data-searchable data-placeholder="Select Type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3]">
                                <option value="simple">Simple Product</option>
                                <option value="variable">Variable Product (with variants)</option>
                                <option value="digital">Digital Product</option>
                                <option value="service">Service</option>
                            </select>
                        </div>

                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Brand</label>
                            <select id="productBrand" data-searchable data-placeholder="Select Brand"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3]">
                                <option value="">Select Brand</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mt-6 space-y-8">
                    <!-- Descriptions -->
                    <div class="min-w-0">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Short Description</label>
                        <p class="text-xs text-gray-500 mb-3">A brief summary appearing on product cards and near the price.</p>
                        <div class="min-w-0">
                            <textarea id="productShortDescription" data-editor="simple" class="w-full"></textarea>
                        </div>
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Detailed Description</label>
                        <p class="text-xs text-gray-500 mb-3">The full HTML description including features, materials, and benefits.</p>
                        <div class="min-w-0">
                            <textarea id="productDescription" data-editor="full" class="w-full"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: PRICING & INVENTORY -->
            <div id="content-pricing" class="tab-content w-full p-6 lg:p-8 space-y-6">
                <!-- Pricing logic same as before... -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Visibility & Status
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Product Status <span class="text-red-500">*</span></label>
                            <select id="productStatus" data-searchable data-placeholder="Select Status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3]">
                                <option value="active">Active (Visible)</option>
                                <option value="draft">Draft (Hidden)</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Catalog Visibility</label>
                            <select id="productVisibility" data-searchable data-placeholder="Select Visibility"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3]">
                                <option value="visible">Visible everywhere</option>
                                <option value="catalog_only">Catalog only</option>
                                <option value="search_only">Search only</option>
                                <option value="hidden">Hidden</option>
                            </select>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Availability <span class="text-red-500">*</span></label>
                            <select id="productAvailability" data-searchable data-placeholder="Select Availability" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3]">
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="pre_order">Pre-Order</option>
                                <option value="backorder">Backorder</option>
                            </select>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Available Date</label>
                            <input type="date" id="productAvailableDate" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pricing & SKU
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Regular Price <span class="text-red-500">*</span></label>
                            <div class="relative min-w-0">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₹</span>
                                <input type="number" step="0.01" min="0" id="productRegularPrice" required
                                       class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm font-medium focus:ring-[#0082C3] shadow-sm" placeholder="0.00">
                            </div>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Sale Price</label>
                            <div class="relative min-w-0">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₹</span>
                                <input type="number" step="0.01" min="0" id="productSalePrice"
                                       class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm font-medium focus:ring-[#0082C3] shadow-sm" placeholder="0.00">
                            </div>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Base SKU</label>
                            <input type="text" id="productSku" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm font-mono focus:ring-[#0082C3] shadow-sm" placeholder="e.g. KIP-FB-5">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Inventory & Shipping
                    </h4>
                    
                    <div class="flex items-center gap-3 mb-6 p-4 bg-gray-50 border border-gray-200 rounded-xl shadow-sm min-w-0">
                        <input type="checkbox" id="productManageStock" class="w-5 h-5 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3] cursor-pointer">
                        <label for="productManageStock" class="text-sm font-semibold text-gray-800 cursor-pointer flex-1 min-w-0 truncate">
                            Track stock quantity for this product
                        </label>
                    </div>

                    <div id="stockFieldsContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 hidden">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Stock Quantity</label>
                            <input type="number" min="0" id="productStockQuantity" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="0">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Low Stock Threshold</label>
                            <input type="number" min="0" id="productLowStockThreshold" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="5">
                        </div>
                        <div class="flex items-end pb-3 min-w-0">
                            <label class="flex items-center gap-2 cursor-pointer min-w-0">
                                <input type="checkbox" id="productAllowBackorder" class="w-4 h-4 text-[#0082C3] rounded shrink-0">
                                <span class="text-sm text-gray-700 font-medium truncate">Allow Backorders</span>
                            </label>
                        </div>
                    </div>

                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-t border-gray-100 pt-6">Dimensions</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" id="productWeight" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="0.00">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Length (cm)</label>
                            <input type="number" step="0.01" min="0" id="productLength" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="0.00">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Width (cm)</label>
                            <input type="number" step="0.01" min="0" id="productWidth" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="0.00">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Height (cm)</label>
                            <input type="number" step="0.01" min="0" id="productHeight" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: MEDIA -->
            <div id="content-media" class="tab-content w-full p-6 lg:p-8">
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-2xl p-10 text-center hover:border-[#0082C3] transition-colors shadow-sm">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h4 class="text-lg font-bold text-gray-900 mb-1">Product Images</h4>
                    <p class="text-sm text-gray-500 mb-6">First image becomes the featured hero.</p>
                    <button type="button" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-300 text-gray-800 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                        Browse Files
                    </button>
                </div>
                <div id="productImagesGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-6"></div>
            </div>

            <!-- TAB 4: VARIANTS -->
            <div id="content-variants" class="tab-content w-full p-6 lg:p-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm min-h-[300px]">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                        <div class="min-w-0">
                            <h4 class="text-lg font-bold text-gray-900 truncate">Variant Matrix</h4>
                            <p class="text-sm text-gray-500 mt-1">Manage sizes, colors, and SKU matrices.</p>
                        </div>
                        <button type="button" onclick="openVariantGenerator()" class="shrink-0 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-bold rounded-xl hover:bg-[#006ba3] transition-colors shadow-sm">
                            + Generate Variants
                        </button>
                    </div>
                    <div id="variantsListContainer" class="space-y-3 min-w-0"></div>
                </div>
            </div>

            <!-- TAB 5: ORGANIZATION -->
            <div id="content-organization" class="tab-content w-full p-6 lg:p-8 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-6">Categorization</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Primary Category</label>
                            <select id="productPrimaryCategory" data-searchable data-placeholder="Select Primary Category" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm"></select>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Additional Categories</label>
                            <select id="productAdditionalCategories" multiple data-searchable data-placeholder="Select Categories" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm"></select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Tags</label>
                            <select id="productTags" multiple data-searchable data-placeholder="Select Tags" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm"></select>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Collections</label>
                            <select id="productCollections" multiple data-searchable data-placeholder="Select Collections" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm"></select>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-6">Product Flags</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6 min-w-0">
                        @foreach(['Featured', 'New', 'BestSeller', 'Trending', 'Digital'] as $flag)
                        <label class="flex items-center gap-3 cursor-pointer group min-w-0">
                            <input type="checkbox" id="productIs{{ $flag }}" class="w-5 h-5 text-[#0082C3] border-gray-300 rounded">
                            <span class="text-sm font-medium text-gray-700">{{ $flag }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- TAB 6: SEO -->
            <div id="content-seo" class="tab-content w-full p-6 lg:p-8 space-y-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-8">
                    <div class="min-w-0">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">SEO Title</label>
                        <input type="text" id="productSeoTitle" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="Max 60 characters">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">SEO Description</label>
                        <textarea id="productSeoDescription" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="Max 160 characters"></textarea>
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">SEO Keywords</label>
                        <input type="text" id="productSeoKeywords" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-[#0082C3] shadow-sm" placeholder="Comma separated">
                    </div>
                </div>
            </div>
            
            <!-- TAB 7: ADVANCED -->
            <div id="content-advanced" class="tab-content w-full p-6 lg:p-8 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm min-w-0">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Product Attributes</h4>
                            <p class="text-xs text-gray-500 mt-1">Technical specifications.</p>
                        </div>
                        <button type="button" onclick="openAddAttributeModal()" class="shrink-0 px-4 py-2 border border-gray-300 bg-white text-sm font-bold text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm transition-colors">
                            + Add Attribute
                        </button>
                    </div>
                    <div id="productAttributesList" class="space-y-2 min-w-0"></div>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="px-8 py-5 border-t border-gray-200 bg-white flex items-center justify-between z-10 shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <button type="button" onclick="previousTab()" id="prevTabBtn" class="hidden px-5 py-2.5 border border-gray-300 bg-white text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                ← Previous
            </button>
            <div class="flex items-center gap-3 ml-auto shrink-0 min-w-0">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 border border-gray-300 bg-white text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="button" onclick="nextTab()" id="nextTabBtn" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-colors shadow-sm">
                    Next Step →
                </button>
                <button type="submit" form="productForm" id="submitBtn" class="hidden px-8 py-2.5 bg-[#0082C3] text-white text-sm font-bold rounded-xl hover:bg-[#006ba3] transition-all shadow-md hover:shadow-lg">
                    <span id="submitBtnText">Save Product</span>
                    <span id="submitBtnLoading" class="hidden flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .tab-btn.active { color: #0082C3; background: white; border: 1px solid #e0f2fe; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    #productForm .tab-content { display: none !important; }
    #productForm .tab-content.tab-active { display: block !important; }
</style>

@include('admin.pages.products.partials.modals')
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/products.js') }}"></script>
@endpush
