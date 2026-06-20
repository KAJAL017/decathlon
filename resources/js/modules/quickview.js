/**
 * Quick View Module
 * Handles Shopify-style product previews and variant selection
 */
export default class QuickView {
    constructor(options = {}) {
        this.baseUrl = options.baseUrl || '/api/quick-view';
        this.isOpen = false;
        this.currentProduct = null;
        this.selectedVariantId = null;
        this.selectedAttributes = {};

        this.elements = {
            modal: document.getElementById('quick-view-modal'),
            backdrop: document.getElementById('quick-view-backdrop'),
            panel: document.getElementById('quick-view-panel'),
            content: document.getElementById('quick-view-content')
        };

        if (this.elements.modal) {
            this.init();
        }
    }

    init() {
        if (this.elements.backdrop) {
            this.elements.backdrop.addEventListener('click', () => this.close());
        }
        window.QuickView = this;
    }

    open(slug) {
        if (this.isOpen) return;

        this.isOpen = true;
        this.selectedAttributes = {};
        
        // Show modal instantly (no animation yet)
        this.elements.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        this.renderSkeleton();

        // Double rAF ensures browser has painted the hidden state before animating
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                this.elements.panel.classList.remove('translate-y-4', 'opacity-0', 'scale-95');
                this.elements.panel.classList.add('translate-y-0', 'opacity-100', 'scale-100');
            });
        });

        fetch(`${this.baseUrl}/${slug}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch');
                return response.json();
            })
            .then(product => {
                this.currentProduct = product;
                
                if (product.variants && product.variants.length) {
                    const firstAttrs = product.variants[0].attributes || {};
                    this.selectedAttributes = {};
                    for (const [key, val] of Object.entries(firstAttrs)) {
                        this.selectedAttributes[key] = (val && typeof val === 'object' && val.value !== undefined) ? val.value : val;
                    }
                    this.selectedVariantId = product.variants[0].id;
                }

                this.render(product);

                // Sync wishlist state after render
                if (window.Wishlist) {
                    window.Wishlist.updateAllUI();
                }
            })
            .catch(error => {
                this.elements.content.innerHTML = `
                    <div class="p-20 text-center col-span-2">
                        <p class="text-red-500 font-bold uppercase tracking-widest text-[13px]">Product could not be loaded</p>
                        <button onclick="window.QuickView.close()" class="mt-4 bg-gray-100 px-6 py-2 rounded-lg text-[11px] font-black uppercase tracking-wider hover:bg-gray-200 transition-colors">Close</button>
                    </div>`;
            });
    }

    close() {
        this.isOpen = false;
        // Animate out
        this.elements.panel.classList.add('translate-y-4', 'opacity-0', 'scale-95');
        this.elements.panel.classList.remove('translate-y-0', 'opacity-100', 'scale-100');

        // Hide after transition (150ms)
        setTimeout(() => {
            this.elements.modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 150);
    }

    renderSkeleton() {
        this.elements.content.innerHTML = `
            <!-- Left: Skeleton Gallery -->
            <div class="bg-gray-50 p-6 flex flex-col gap-4 animate-pulse">
                <div class="aspect-square rounded-xl bg-gray-200"></div>
                <div class="flex gap-2">
                    <div class="w-16 h-16 rounded-lg bg-gray-200"></div>
                    <div class="w-16 h-16 rounded-lg bg-gray-200"></div>
                    <div class="w-16 h-16 rounded-lg bg-gray-200"></div>
                </div>
            </div>

            <!-- Right: Skeleton Info -->
            <div class="p-8 flex flex-col justify-between animate-pulse">
                <div>
                    <div class="h-3 w-24 bg-gray-200 rounded mb-4"></div>
                    <div class="h-8 w-full bg-gray-200 rounded mb-4"></div>
                    <div class="h-6 w-32 bg-gray-200 rounded mb-8"></div>
                    <div class="space-y-3 mb-8">
                        <div class="h-3 w-full bg-gray-200 rounded"></div>
                        <div class="h-3 w-full bg-gray-200 rounded"></div>
                        <div class="h-3 w-2/3 bg-gray-200 rounded"></div>
                    </div>
                    <div class="h-4 w-32 bg-gray-200 rounded mb-4"></div>
                    <div class="flex gap-2 mb-8">
                        <div class="h-10 w-16 bg-gray-200 rounded-lg"></div>
                        <div class="h-10 w-16 bg-gray-200 rounded-lg"></div>
                        <div class="h-10 w-16 bg-gray-200 rounded-lg"></div>
                    </div>
                </div>
                <div class="h-14 w-full bg-gray-200 rounded-xl"></div>
            </div>
        `;
    }

    render(p) {
        const mainImage = p.images[0] || 'https://placehold.co/600x600/f3f4f6/999?text=No+Image';
        const currentVariant = p.variants.find(v => v.id === this.selectedVariantId) || p.variants[0];

        // Normalize options: ensure each value has a string label
        const safeOptions = (p.options || []).map(opt => ({
            name: opt.name,
            values: (opt.values || []).map(v => ({
                label: (v && typeof v === 'object') ? (v.label || v.value || String(Object.values(v)[0] || '')) : String(v || ''),
                color: (v && typeof v === 'object') ? (v.color || null) : null
            }))
        }));

        this.elements.content.innerHTML = `
            <!-- Left: Gallery -->
            <div class="bg-gray-50 p-6 flex flex-col gap-4">
                <div class="aspect-square rounded-xl overflow-hidden border border-gray-100 bg-white">
                    <img id="qv-main-image" src="${mainImage}" class="w-full h-full object-contain">
                </div>
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                    ${p.images.map((img, i) => `
                        <button onclick="document.getElementById('qv-main-image').src='${img}'" class="w-16 h-16 rounded-lg border-2 border-transparent hover:border-[#0082C3] overflow-hidden bg-white flex-shrink-0 transition-all">
                            <img src="${img}" class="w-full h-full object-cover">
                        </button>
                    `).join('')}
                </div>
            </div>

            <!-- Right: Info -->
            <div class="p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[11px] font-black text-[#0082C3] tracking-widest uppercase">${p.brand || 'DECATHLON'}</span>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 leading-tight mb-2">${p.name}</h2>
                    
                    <div class="flex items-baseline gap-3 mb-6">
                        <span id="qv-price" class="text-2xl font-black text-gray-950">₹${Number(currentVariant.price).toFixed(0)}</span>
                        ${currentVariant.compare_price ? `<span class="text-sm text-gray-400 line-through">₹${Number(currentVariant.compare_price).toFixed(0)}</span>` : ''}
                    </div>

                    <p class="text-[13px] text-gray-600 leading-relaxed mb-6 line-clamp-2">${p.description}</p>

                    <!-- Dynamic Options Rows -->
                    <div id="qv-options-container" class="space-y-6 mb-8">
                        ${safeOptions.map(opt => {
                            const isColorOption = /color/i.test(opt.name);
                            return `
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[11px] font-black text-gray-500 uppercase tracking-widest">${opt.name}</span>
                                    <span class="text-[11px] font-bold text-gray-900 uppercase">${this.selectedAttributes[opt.name] || 'Select'}</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    ${opt.values.map(val => {
                                        const isSelected = String(this.selectedAttributes[opt.name] || '') === String(val.label);
                                        if (isColorOption) {
                                            const swatchBg = val.color || val.label;
                                            const borderColor = isSelected ? 'border-gray-900 ring-2 ring-gray-200' : 'border-gray-200 hover:border-gray-400';
                                            return `
                                                <button onclick="window.QuickView.selectAttribute('${opt.name.replace(/'/g, "\\'")}', '${val.label.replace(/'/g, "\\'")}')"
                                                        class="w-10 h-10 rounded-lg border-2 ${borderColor} overflow-hidden flex items-center justify-center p-0.5 transition-all duration-200"
                                                        title="${val.label}">
                                                    <span class="w-full h-full rounded-md block" style="background-color: ${swatchBg};"></span>
                                                </button>
                                            `;
                                        } else {
                                            const btnClass = isSelected ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-100 hover:border-gray-300 text-gray-700';
                                            return `
                                                <button onclick="window.QuickView.selectAttribute('${opt.name.replace(/'/g, "\\'")}', '${val.label.replace(/'/g, "\\'")}')"
                                                        class="px-4 py-2 rounded-lg border-2 ${btnClass} text-[12px] font-bold transition-all">
                                                    ${val.label}
                                                </button>
                                            `;
                                        }
                                    }).join('')}
                                </div>
                            </div>
                            `;
                        }).join('')}
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex gap-2">
                        <button id="qv-add-btn" onclick="window.QuickView.addToCart()" 
                                class="flex-1 bg-[#183a9e] hover:bg-[#0c246b] text-white py-4 rounded-xl text-[13px] font-black uppercase tracking-widest transition-all shadow-lg flex items-center justify-center gap-2 ${!this.selectedVariantId ? 'opacity-50 cursor-not-allowed' : ''}"
                                ${!this.selectedVariantId ? 'disabled' : ''}>
                            Add to cart
                        </button>
                        <button class="wishlist-btn w-12 h-12 border border-gray-300 hover:border-black text-gray-600 hover:text-red-500 rounded-xl transition-all duration-200 flex items-center justify-center bg-white shadow-sm flex-shrink-0"
                                data-product-id="${p.id}"
                                title="Add to Wishlist">
                            <span class="heart-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            </span>
                            <span class="heart-filled hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            </span>
                        </button>
                    </div>
                    <a href="/product/${p.slug}" class="text-center text-[12px] font-bold text-gray-500 hover:text-[#0082C3] transition-colors py-2">View Full Details &rarr;</a>
                </div>
            </div>
        `;
    }

    selectAttribute(name, value) {
        this.selectedAttributes[name] = String(value);
        this.resolveVariant();
        this.render(this.currentProduct);
    }

    resolveVariant() {
        if (!this.currentProduct || !this.currentProduct.variants) return;
        
        const variant = this.currentProduct.variants.find(v => {
            return Object.entries(this.selectedAttributes).every(([name, value]) => {
                const attrVal = v.attributes[name];
                // Normalize: handle both flat strings and nested objects
                const normalized = (attrVal && typeof attrVal === 'object' && attrVal.value !== undefined) ? attrVal.value : attrVal;
                return String(normalized) === String(value);
            });
        });

        this.selectedVariantId = variant ? variant.id : null;
    }

    async addToCart() {
        if (!this.selectedVariantId || !this.currentProduct) {
            return;
        }

        const addBtn = document.getElementById('qv-add-btn');
        if (!addBtn || !window.Cart) return;

        // Show loading state on QuickView button
        const originalContent = addBtn.innerHTML;
        addBtn.disabled = true;
        addBtn.classList.add('opacity-60', 'cursor-not-allowed');
        addBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Adding...';

        try {
            const result = await window.Cart.add(this.currentProduct.id, this.selectedVariantId);
            if (result && result.success) {
                this.close();
            }
        } finally {
            addBtn.disabled = false;
            addBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            addBtn.innerHTML = originalContent;
        }
    }
}
