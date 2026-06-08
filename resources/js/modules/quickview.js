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
        this.elements.backdrop.addEventListener('click', () => this.close());
        window.QuickView = this;
    }

    async open(slug) {
        if (this.isOpen) return;

        this.isOpen = true;
        this.selectedAttributes = {};
        
        // Immediate UI feedback: Show modal with skeleton
        this.elements.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        this.renderSkeleton();

        // Hardware-accelerated animation
        requestAnimationFrame(() => {
            this.elements.panel.classList.remove('translate-y-4', 'opacity-0', 'scale-95');
            this.elements.panel.classList.add('translate-y-0', 'opacity-100', 'scale-100');
        });

        try {
            const response = await fetch(`${this.baseUrl}/${slug}`);
            if (!response.ok) throw new Error('Failed to fetch');
            const product = await response.json();
            
            this.currentProduct = product;
            
            // Auto-select first variant's attributes
            if (product.variants && product.variants.length) {
                this.selectedAttributes = { ...product.variants[0].attributes };
                this.selectedVariantId = product.variants[0].id;
            }

            // Minimal delay to ensure smooth transition from skeleton to content
            setTimeout(() => this.render(product), 50);
        } catch (error) {
            this.elements.content.innerHTML = `
                <div class="p-20 text-center col-span-2">
                    <p class="text-red-500 font-bold uppercase tracking-widest text-[13px]">Product could not be loaded</p>
                    <button onclick="window.QuickView.close()" class="mt-4 bg-gray-100 px-6 py-2 rounded-lg text-[11px] font-black uppercase tracking-wider hover:bg-gray-200 transition-colors">Close</button>
                </div>`;
        }
    }

    close() {
        this.isOpen = false;
        this.elements.panel.classList.add('translate-y-4', 'opacity-0', 'scale-95');
        this.elements.panel.classList.remove('translate-y-0', 'opacity-100', 'scale-100');

        setTimeout(() => {
            this.elements.modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
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
                        ${p.options.map(opt => `
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[11px] font-black text-gray-500 uppercase tracking-widest">${opt.name}</span>
                                    <span class="text-[11px] font-bold text-gray-900 uppercase">${this.selectedAttributes[opt.name] || 'Select'}</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    ${opt.values.map(val => {
                                        const isSelected = this.selectedAttributes[opt.name] === val.label;
                                        return `
                                            <button onclick="window.QuickView.selectAttribute('${opt.name}', '${val.label}')" 
                                                    class="px-4 py-2 rounded-lg border-2 ${isSelected ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-100 hover:border-gray-300 text-gray-700'} text-[12px] font-bold transition-all">
                                                ${val.label}
                                            </button>
                                        `;
                                    }).join('')}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button id="qv-add-btn" onclick="window.QuickView.addToCart()" 
                            class="w-full bg-[#183a9e] hover:bg-[#0c246b] text-white py-4 rounded-xl text-[13px] font-black uppercase tracking-widest transition-all shadow-lg flex items-center justify-center gap-2 ${!this.selectedVariantId ? 'opacity-50 cursor-not-allowed' : ''}"
                            ${!this.selectedVariantId ? 'disabled' : ''}>
                        Add to cart
                    </button>
                    <a href="/product/${p.slug}" class="text-center text-[12px] font-bold text-gray-500 hover:text-[#0082C3] transition-colors py-2">View Full Details &rarr;</a>
                </div>
            </div>
        `;
    }

    selectAttribute(name, value) {
        this.selectedAttributes[name] = value;
        this.resolveVariant();
        this.render(this.currentProduct);
    }

    resolveVariant() {
        const variant = this.currentProduct.variants.find(v => {
            return Object.entries(this.selectedAttributes).every(([name, value]) => v.attributes[name] === value);
        });

        this.selectedVariantId = variant ? variant.id : null;
    }

    addToCart() {
        if (!this.selectedVariantId || !this.currentProduct) {
            if (window.Cart) window.Cart.notify('Please select all options.', 'warning');
            return;
        }

        if (window.Cart) {
            window.Cart.add(this.currentProduct.id, this.selectedVariantId);
            this.close();
        }
    }
}
