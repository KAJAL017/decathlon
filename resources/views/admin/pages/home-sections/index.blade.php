@extends('admin.layouts.app')

@section('title', 'Homepage Sections Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Homepage Builder</h1>
            <p class="text-sm text-gray-600 mt-1">Manage and reorder dynamic sections on your homepage</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-[#0082C3] text-white text-sm font-black uppercase tracking-widest rounded-xl hover:bg-[#006ba3] transition-all shadow-lg shadow-blue-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Section
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Side: Section List (Draggable) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[30px] border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                    <h2 class="text-xs font-black text-gray-950 uppercase tracking-[0.2em]">Active Layout</h2>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Drag to reorder</span>
                </div>
                
                <div id="sectionsList" class="divide-y divide-gray-50">
                    <!-- Loaded via JS -->
                    <div class="px-6 py-20 text-center text-gray-500">
                        <div class="animate-spin w-8 h-8 border-4 border-[#0082C3] border-t-transparent rounded-full mx-auto mb-4"></div>
                        Loading sections...
                    </div>
                </div>

                <div class="px-8 py-5 bg-gray-50/30 border-t border-gray-50 text-right">
                    <button onclick="saveOrder()" id="saveOrderBtn" class="px-6 py-3 bg-gray-950 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all opacity-50 cursor-not-allowed disabled" disabled>
                        Save New Order
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Side: Quick Stats / Help -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-[#0082C3] rounded-[30px] p-8 text-white shadow-xl shadow-blue-100 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xl font-black uppercase tracking-tight mb-2">Live Preview</h3>
                    <p class="text-blue-50 text-sm font-medium leading-relaxed mb-6 opacity-80">Changes you make here are applied instantly to your homepage.</p>
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-3 px-6 py-3 bg-white text-[#0082C3] text-[10px] font-black uppercase tracking-[0.2em] rounded-full hover:bg-blue-50 transition-all">
                        View Storefront
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
                <svg class="absolute -right-4 -bottom-4 w-40 h-40 text-white opacity-10 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            </div>

            <div class="bg-white rounded-[30px] border border-gray-100 p-8 shadow-sm">
                <h3 class="text-xs font-black text-gray-950 uppercase tracking-[0.2em] mb-6">Pro Builder Tips</h3>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-1">Conversion Focus</p>
                            <p class="text-xs text-gray-500 font-medium leading-relaxed">Place "Service Highlights" and "Featured Product" near the top to build trust early.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-[#0082C3] flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-1">Urgency</p>
                            <p class="text-xs text-gray-500 font-medium leading-relaxed">Use the "Sale Countdown Timer" during festive seasons to boost sales by up to 40%.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="sectionModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" onclick="closeModalOnBackdrop(event)">
    <div class="fixed inset-0 bg-gray-950/60 backdrop-blur-[6px]"></div>
    
    <div id="sectionModalContent" class="fixed right-0 top-0 h-full w-full max-w-xl bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-500" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-8 py-8 border-b border-gray-50 flex items-center justify-between bg-white flex-shrink-0">
            <div>
                <h3 id="modalTitle" class="text-2xl font-black text-gray-950 uppercase tracking-tighter">Add Section</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Design your homepage module</p>
            </div>
            <button onclick="closeModal()" class="w-12 h-12 flex items-center justify-center rounded-[20px] hover:bg-gray-50 text-gray-400 hover:text-gray-950 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="sectionForm" class="flex-1 overflow-y-auto bg-white">
            <div class="px-8 py-8 space-y-10">
                <input type="hidden" id="sectionId">
                
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Core Configuration</h4>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Section Template Type</label>
                            <select id="sectionType" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-[#0082C3] transition-all" required onchange="updateSettingsFields()">
                                <optgroup label="Promotional & High Impact">
                                    <option value="hero_banners">Hero Banners Slider</option>
                                    <option value="countdown_timer">Sale Countdown Timer</option>
                                    <option value="promo_banners">Promo Banners (3-in-1)</option>
                                    <option value="video">Video Background</option>
                                </optgroup>
                                <optgroup label="Product Showcases">
                                    <option value="best_sellers">Best Sellers Showcase (Grid)</option>
                                    <option value="product_slider">Product Slider (Full)</option>
                                    <option value="product_tabs">Product Tabs (Featured/New/Trending)</option>
                                    <option value="collection_row">Collection Highlight Row</option>
                                    <option value="multi_column_products">Multi-Column List (3 Columns)</option>
                                    <option value="featured_product">Featured Product (Solo)</option>
                                    <option value="collection_list">Collections Grid Showcase</option>
                                    <option value="price_points">Price Points Grid</option>
                                </optgroup>
                                <optgroup label="Content & Story">
                                    <option value="image_with_text">Image with Text</option>
                                    <option value="rich_text">Rich Text / Content Block</option>
                                    <option value="gallery">Image Gallery Grid</option>
                                    <option value="testimonials">Testimonials Slider</option>
                                </optgroup>
                                <optgroup label="Trust & Utility">
                                    <option value="service_highlights">Service Highlights (Trust Badges)</option>
                                    <option value="faq_section">FAQ / Accordion Section</option>
                                    <option value="newsletter">Newsletter Subscription</option>
                                    <option value="instagram_feed">Instagram / Social Grid</option>
                                    <option value="category_grid">Category Grid (Round)</option>
                                    <option value="featured_categories">Featured Categories (Slider)</option>
                                    <option value="banner_grid">Banner Grid (2x2 or 4x1)</option>
                                    <option value="promotions">Promotions Section</option>
                                    <option value="brands">Brands Logo Slider</option>
                                    <option value="accordion">Category Accordion List</option>
                                    <option value="hero_categories">Hero Categories (Top Grid)</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Public Title</label>
                                <input type="text" id="sectionTitle" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-[#0082C3] transition-all" placeholder="e.g., Best Sellers">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Public Subtitle</label>
                                <input type="text" id="sectionSubtitle" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-900 placeholder-gray-300 focus:ring-2 focus:ring-[#0082C3] transition-all" placeholder="e.g., Don't miss out">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Settings -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Module Customization</h4>
                    <div id="settingsContainer" class="bg-gray-50 rounded-[30px] p-8 space-y-8">
                        <!-- Dynamically populated based on type -->
                    </div>
                </div>

                <!-- Visibility -->
                <div class="pt-6 flex items-center justify-between bg-blue-50/50 p-8 rounded-[30px] border border-blue-100/50">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-[#0082C3] flex items-center justify-center text-white shadow-lg shadow-blue-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-black text-gray-900 uppercase tracking-widest">Visibility</div>
                            <div class="text-[11px] text-gray-400 font-bold uppercase tracking-tight mt-1">Section will be live on homepage</div>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="sectionStatus" checked class="sr-only peer">
                        <div class="w-16 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#0082C3]"></div>
                    </label>
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-8 py-8 border-t border-gray-100 bg-white flex items-center justify-end gap-4 sticky bottom-0 z-10 shadow-[0_-15px_40px_-20px_rgba(0,0,0,0.1)]">
            <button type="button" onclick="closeModal()" class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-950 transition-colors">
                Discard
            </button>
            <button type="submit" form="sectionForm" id="submitBtn" class="px-10 py-5 bg-gray-950 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-gray-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span id="submitBtnText">Apply Module</span>
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let sortable;
let sectionsData = [];

// Inject data from PHP
const availableItems = {
    categories: @json($categories),
    brands: @json($brands),
    banners: @json($banners),
    promotions: @json($promotions),
    products: @json($products),
    collections: @json(\App\Models\Collection::orderBy('name')->get(['id', 'name']))
};

document.addEventListener('DOMContentLoaded', () => {
    loadSections();
    initSortable();
});

function initSortable() {
    const el = document.getElementById('sectionsList');
    sortable = new Sortable(el, {
        animation: 350,
        handle: '.drag-handle',
        ghostClass: 'bg-blue-50',
        onEnd: () => {
            const btn = document.getElementById('saveOrderBtn');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed', 'disabled');
        }
    });
}

function loadSections() {
    fetch('/admin/home-sections/list', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            sectionsData = data.data;
            renderSections(data.data);
        }
    });
}

function renderSections(sections) {
    const list = document.getElementById('sectionsList');
    
    if (sections.length === 0) {
        list.innerHTML = `
            <div class="px-6 py-20 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </div>
                <h3 class="text-sm font-black text-gray-950 uppercase tracking-widest">No Sections Added</h3>
                <p class="text-xs text-gray-500 mt-1 font-bold">Your homepage is looking a bit empty!</p>
            </div>`;
        return;
    }

    list.innerHTML = sections.map(section => `
        <div class="group flex items-center gap-6 px-8 py-6 bg-white transition-all hover:bg-gray-50/50" data-id="${section.id}">
            <div class="drag-handle cursor-grab active:cursor-grabbing p-2 text-gray-200 hover:text-gray-950 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </div>
            
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] px-2.5 py-1 rounded-lg bg-blue-50 text-[#0082C3]">
                        ${section.type.replace(/_/g, ' ')}
                    </span>
                    ${!section.is_active ? '<span class="text-[9px] font-black uppercase tracking-[0.3em] px-2.5 py-1 rounded-lg bg-red-50 text-red-500">Hidden</span>' : ''}
                </div>
                <h3 class="text-base font-black text-gray-950 uppercase tracking-tight mt-2">${section.title || section.type.replace(/_/g, ' ')}</h3>
                ${section.subtitle ? `<p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">${section.subtitle}</p>` : ''}
            </div>

            <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-all">
                <button onclick="editSection(${section.id})" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-600 hover:text-[#0082C3] hover:border-[#0082C3] hover:shadow-lg hover:shadow-blue-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
                <button onclick="deleteSection(${section.id})" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-600 hover:text-red-500 hover:border-red-100 hover:shadow-lg hover:shadow-red-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
    `).join('');
}

function updateSettingsFields(section = null) {
    const type = document.getElementById('sectionType').value;
    const container = document.getElementById('settingsContainer');
    const settings = section ? section.settings : {};
    
    const generateMultiSelect = (key, label, items, selectedIds = [], help = '') => {
        const options = items.map(item => `
            <option value="${item.id}" ${selectedIds.includes(item.id) ? 'selected' : ''}>
                ${item.name || item.title} ${item.position ? `(${item.position})` : ''}
            </option>
        `).join('');

        return `
            <div>
                <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">${label}</label>
                <select data-setting="${key}" data-searchable data-placeholder="Select ${label}..." data-type="array" multiple class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold text-gray-900 focus:ring-2 focus:ring-[#0082C3] transition-all min-h-[150px]">
                    ${options}
                </select>
                ${help ? `<p class="text-[10px] text-gray-400 mt-2 font-bold uppercase tracking-tighter">${help}</p>` : ''}
                <p class="text-[9px] text-[#0082C3] mt-2 font-black uppercase tracking-widest">Hold Ctrl/Cmd to pick multiple</p>
            </div>`;
    };

    let html = '';
    switch(type) {
        case 'product_tabs':
            html = `
                <div class="p-8 bg-blue-50/50 rounded-3xl border border-blue-100/50">
                    <p class="text-[10px] font-black text-[#0082C3] uppercase tracking-[0.2em]">Tabbed Showcase</p>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-tight mt-2 leading-relaxed">This module creates 3 tabs: Featured, New Arrivals, and Trending. It automatically fetches the top 8 products for each.</p>
                </div>`;
            break;

        case 'collection_row':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Featured Collection</label>
                        <select data-setting="collection_id" data-searchable class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-black">
                            <option value="">Pick a collection to showcase...</option>
                            ${availableItems.collections.map(c => `<option value="${c.id}" ${settings.collection_id == c.id ? 'selected' : ''}>${c.name}</option>`).join('')}
                        </select>
                        <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-tight">Shows big image + 4 products side-by-side</p>
                    </div>
                </div>`;
            break;

        case 'multi_column_products':
            html = `
                <div class="p-8 bg-blue-50/50 rounded-3xl border border-blue-100/50">
                    <p class="text-[10px] font-black text-[#0082C3] uppercase tracking-[0.2em]">Multi-Column List</p>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-tight mt-2 leading-relaxed">This module creates a 3-column footer-style list for New Arrivals, Best Sellers, and Top Rated products.</p>
                </div>`;
            break;

        case 'collection_list':
            html = `
                <div class="space-y-6">
                    ${generateMultiSelect('collection_ids', 'Select Collections', availableItems.collections, settings.collection_ids || [], 'Manual selection overrides dynamic fetch')}
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Limit Items</label>
                        <input type="number" data-setting="limit" value="${settings.limit || 4}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                </div>`;
            break;

        case 'countdown_timer':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Sale End Date & Time</label>
                        <input type="datetime-local" data-setting="end_date" value="${settings.end_date || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold text-gray-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Big Heading Overlay</label>
                        <input type="text" data-setting="title" value="${settings.title || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="SEASON END CLEARANCE">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Small Subtitle Overlay</label>
                        <input type="text" data-setting="subtitle" value="${settings.subtitle || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="UP TO 70% OFF">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Background Image URL</label>
                        <input type="text" data-setting="background_image" value="${settings.background_image || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="High-res lifestyle photo">
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Button Text</label>
                            <input type="text" data-setting="button_text" value="${settings.button_text || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Button Link</label>
                            <input type="text" data-setting="button_link" value="${settings.button_link || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                        </div>
                    </div>
                </div>`;
            break;

        case 'service_highlights':
            html = `
                <div class="p-8 bg-blue-50/50 rounded-3xl border border-blue-100/50">
                    <p class="text-[10px] font-black text-[#0082C3] uppercase tracking-[0.2em]">Store Commitments</p>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-tight mt-2 leading-relaxed">This section displays your global trust badges: Shipping, Warranty, Returns, and Security. No extra configuration needed.</p>
                </div>`;
            break;

        case 'faq_section':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">FAQs (JSON Array)</label>
                        <textarea data-setting="faqs" rows="10" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-[11px] font-mono text-gray-900">${JSON.stringify(settings.faqs || [{question: "Question here?", answer: "Answer here."}], null, 4)}</textarea>
                    </div>
                </div>`;
            break;

        case 'instagram_feed':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Social Username</label>
                        <input type="text" data-setting="username" value="${settings.username || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="@decathlon_official">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Image URLs (JSON Array)</label>
                        <textarea data-setting="images" rows="6" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-[11px] font-mono text-gray-900">${JSON.stringify(settings.images || [], null, 4)}</textarea>
                    </div>
                </div>`;
            break;

        case 'hero_banners':
        case 'promo_banners':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Position Slug</label>
                        <input type="text" data-setting="position" value="${settings.position || (type === 'hero_banners' ? 'hero' : 'promo')}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                    ${generateMultiSelect('banner_ids', 'Pick Banners', availableItems.banners, settings.banner_ids || [])}
                </div>`;
            break;

        case 'rich_text':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Alignment</label>
                        <select data-setting="alignment" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-black">
                            <option value="center" ${settings.alignment === 'center' ? 'selected' : ''}>Center Focus</option>
                            <option value="left" ${settings.alignment === 'left' ? 'selected' : ''}>Left Align</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Heading</label>
                        <input type="text" data-setting="title" value="${settings.title || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Body (HTML Allowed)</label>
                        <textarea data-setting="content" rows="4" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">${settings.content || ''}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <input type="text" data-setting="button_text" value="${settings.button_text || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="BTN TEXT">
                        <input type="text" data-setting="button_link" value="${settings.button_link || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="/link">
                    </div>
                </div>`;
            break;

        case 'image_with_text':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Layout</label>
                        <select data-setting="alignment" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-black">
                            <option value="left" ${settings.alignment === 'left' ? 'selected' : ''}>Image Left, Text Right</option>
                            <option value="right" ${settings.alignment === 'right' ? 'selected' : ''}>Image Right, Text Left</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Image URL</label>
                        <input type="text" data-setting="image_url" value="${settings.image_url || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Heading</label>
                        <input type="text" data-setting="title" value="${settings.title || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                    <textarea data-setting="content" rows="3" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="Enter story text...">${settings.content || ''}</textarea>
                </div>`;
            break;

        case 'featured_product':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Select Product</label>
                        <select data-setting="product_id" data-searchable class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-black">
                            <option value="">Pick a product to highlight...</option>
                            ${availableItems.products.map(p => `<option value="${p.id}" ${settings.product_id == p.id ? 'selected' : ''}>${p.name}</option>`).join('')}
                        </select>
                    </div>
                </div>`;
            break;

        case 'video':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Video Link (YouTube/MP4)</label>
                        <input type="text" data-setting="video_url" value="${settings.video_url || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Overlay Darken</label>
                            <input type="number" step="0.1" data-setting="overlay" value="${settings.overlay || 0.4}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Autoplay</label>
                            <select data-setting="autoplay" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-black">
                                <option value="1" ${settings.autoplay ? 'selected' : ''}>Yes</option>
                                <option value="0" ${!settings.autoplay ? 'selected' : ''}>No</option>
                            </select>
                        </div>
                    </div>
                </div>`;
            break;

        case 'product_slider':
            html = `
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Automatic Filter</label>
                            <select data-setting="filter" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-black">
                                <option value="featured" ${settings.filter === 'featured' ? 'selected' : ''}>Featured Items</option>
                                <option value="best_seller" ${settings.filter === 'best_seller' ? 'selected' : ''}>Best Sellers</option>
                                <option value="latest" ${settings.filter === 'latest' ? 'selected' : ''}>Latest Drops</option>
                                <option value="trending" ${settings.filter === 'trending' ? 'selected' : ''}>Trending Now</option>
                                <option value="deals" ${settings.filter === 'deals' ? 'selected' : ''}>Active Deals</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Limit</label>
                            <input type="number" data-setting="limit" value="${settings.limit || 10}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                        </div>
                    </div>
                    ${generateMultiSelect('product_ids', 'Or Pick Specific Products', availableItems.products, settings.product_ids || [])}
                </div>`;
            break;

        case 'category_grid':
        case 'hero_categories':
        case 'featured_categories':
        case 'accordion':
            html = `
                <div class="space-y-6">
                    ${generateMultiSelect('category_ids', 'Specific Categories', availableItems.categories, settings.category_ids || [])}
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Limit</label>
                        <input type="number" data-setting="limit" value="${settings.limit || 8}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                </div>`;
            break;

        case 'brands':
            html = generateMultiSelect('brand_ids', 'Pick Brands', availableItems.brands, settings.brand_ids || []);
            break;

        case 'promotions':
            html = generateMultiSelect('promotion_ids', 'Specific Promotions', availableItems.promotions, settings.promotion_ids || []);
            break;

        case 'price_points':
            html = `
                <div>
                    <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Price Tiers (Comma separated)</label>
                    <input type="text" data-setting="prices" data-type="array" value="${(settings.prices || [499, 999, 1499, 1999]).join(', ')}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="499, 999, 1499, 1999">
                </div>`;
            break;

        case 'newsletter':
            html = `
                <div class="space-y-6">
                    <input type="text" data-setting="title" value="${settings.title || 'Join the Movement'}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="Heading">
                    <input type="text" data-setting="subtitle" value="${settings.subtitle || ''}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold" placeholder="Subtitle">
                    <div class="grid grid-cols-2 gap-5">
                        <input type="text" data-setting="placeholder" value="${settings.placeholder || 'Enter email'}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                        <input type="text" data-setting="button_text" value="${settings.button_text || 'Subscribe'}" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-sm font-bold">
                    </div>
                </div>`;
            break;

        case 'gallery':
            html = `
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Gallery (JSON Array of {url, link})</label>
                        <textarea data-setting="images" rows="8" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-[11px] font-mono">${JSON.stringify(settings.images || [{url: "", link: ""}], null, 4)}</textarea>
                    </div>
                </div>`;
            break;

        default:
            html = `<div class="p-8 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">Dynamic module selected. Adjust global settings above.</div>`;
    }

    container.innerHTML = html;

    // Re-initialize searchable selects
    container.querySelectorAll('select[data-searchable]').forEach(sel => {
        if (typeof SearchableSelect !== 'undefined') {
            new SearchableSelect(sel, {
                placeholder: sel.dataset.placeholder || 'Select...',
                searchPlaceholder: 'Type to search...'
            });
        }
    });
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Module';
    document.getElementById('submitBtnText').textContent = 'Create Module';
    document.getElementById('sectionForm').reset();
    document.getElementById('sectionId').value = '';
    document.getElementById('sectionStatus').checked = true;
    updateSettingsFields();
    
    const modal = document.getElementById('sectionModal');
    const content = document.getElementById('sectionModalContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => content.style.transform = 'translateX(0)', 10);
}

function closeModal() {
    const modal = document.getElementById('sectionModal');
    const content = document.getElementById('sectionModalContent');
    content.style.transform = 'translateX(100%)';
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 400);
}

function closeModalOnBackdrop(event) {
    if (event.target.id === 'sectionModal') closeModal();
}

function editSection(id) {
    const section = sectionsData.find(s => s.id === id);
    if (!section) return;

    document.getElementById('modalTitle').textContent = 'Edit Module';
    document.getElementById('submitBtnText').textContent = 'Update Module';
    document.getElementById('sectionId').value = section.id;
    document.getElementById('sectionType').value = section.type;
    document.getElementById('sectionTitle').value = section.title || '';
    document.getElementById('sectionSubtitle').value = section.subtitle || '';
    document.getElementById('sectionStatus').checked = section.is_active;
    
    updateSettingsFields(section);
    
    const modal = document.getElementById('sectionModal');
    const content = document.getElementById('sectionModalContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => content.style.transform = 'translateX(0)', 10);
}

document.getElementById('sectionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('sectionId').value;
    const url = id ? `/admin/home-sections/${id}` : '/admin/home-sections';
    
    const settings = {};
    document.querySelectorAll('[data-setting]').forEach(input => {
        const key = input.dataset.setting;
        const type = input.dataset.type;
        
        if (input.multiple) {
            settings[key] = Array.from(input.selectedOptions).map(opt => parseInt(opt.value));
            return;
        }

        let val = input.value;
        if (type === 'array' || key === 'faqs' || key === 'images') {
            try {
                if (key === 'faqs' || key === 'images') {
                    settings[key] = JSON.parse(val);
                } else {
                    settings[key] = val.split(',').map(v => v.trim()).filter(v => v !== '').map(v => isNaN(v) ? v : parseInt(v));
                }
            } catch(e) {
                settings[key] = val;
            }
        } else if (input.type === 'number') {
            settings[key] = parseFloat(val);
        } else {
            settings[key] = val;
        }
    });

    const formData = {
        type: document.getElementById('sectionType').value,
        title: document.getElementById('sectionTitle').value,
        subtitle: document.getElementById('sectionSubtitle').value,
        is_active: document.getElementById('sectionStatus').checked,
        settings: settings
    };

    if (id) formData._method = 'PUT';

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
            loadSections();
            Dialog.alert({ title: 'Success!', message: 'Module saved successfully', type: 'success' });
        }
    });
});

async function saveOrder() {
    const ids = Array.from(document.getElementById('sectionsList').children).map(el => parseInt(el.dataset.id)).filter(id => !isNaN(id));
    const btn = document.getElementById('saveOrderBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    fetch('/admin/home-sections/reorder', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            btn.textContent = 'Save New Order';
            btn.classList.add('opacity-50', 'cursor-not-allowed', 'disabled');
            Dialog.alert({ title: 'Order Saved!', message: 'Homepage reordered successfully.', type: 'success' });
        }
    });
}

async function deleteSection(id) {
    const confirmed = await Dialog.confirm({ title: 'Delete Module', message: 'Remove this section?', type: 'danger' });
    if (!confirmed) return;
    
    fetch(`/admin/home-sections/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadSections();
            Dialog.alert({ title: 'Removed!', message: 'Module deleted.', type: 'success' });
        }
    });
}
</script>
@endpush
