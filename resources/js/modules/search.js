/**
 * Dynamic Search Module
 * Handles AJAX search suggestions, overlays, and UI updates
 */
export default class Search {
    constructor(options = {}) {
        this.searchUrl = options.searchUrl || '/api/search';
        this.shopUrl = options.shopUrl || '/shop';
        this.timer = null;
        
        this.elements = {
            headerInput: document.getElementById('header-search-input'),
            overlay: document.getElementById('search-overlay'),
            overlayInput: document.getElementById('overlay-search-input'),
            defaultState: document.getElementById('search-default-state'),
            resultsState: document.getElementById('search-results-state'),
            productGrid: document.getElementById('product-results-grid'),
            categoryList: document.getElementById('category-results-list'),
            categoryDiv: document.getElementById('category-results'),
            noResults: document.getElementById('no-results'),
            backdrop: document.getElementById('search-backdrop'),
            
            // Default state elements
            trendingSection: document.getElementById('trending-section'),
            trendingChips: document.getElementById('trending-chips'),
            popularSection: document.getElementById('popular-section'),
            popularGrid: document.getElementById('popular-grid'),
            recentSection: document.getElementById('recently-viewed-section'),
            recentGrid: document.getElementById('recently-viewed-grid')
        };

        if (this.elements.headerInput) {
            this.init();
        }
    }

    init() {
        // Placeholder rotation
        this.initPlaceholderRotation();

        // Event Listeners
        this.elements.headerInput.addEventListener('click', () => this.openOverlay());
        
        if (this.elements.overlayInput) {
            this.elements.overlayInput.addEventListener('input', (e) => {
                clearTimeout(this.timer);
                const q = e.target.value.trim();
                if (q.length < 2) {
                    this.showDefaultState();
                    return;
                }
                this.timer = setTimeout(() => this.fetchResults(q), 280);
            });

            this.elements.overlayInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeOverlay();
                if (e.key === 'Enter') {
                    const q = e.target.value.trim();
                    if (q) window.location.href = `${this.shopUrl}?q=${encodeURIComponent(q)}`;
                }
            });
        }

        // Global close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.elements.overlay && !this.elements.overlay.classList.contains('hidden')) {
                this.closeOverlay();
            }
        });

        if (this.elements.backdrop) {
            this.elements.backdrop.addEventListener('click', () => this.closeOverlay());
        }

        window.openSearchOverlay = () => this.openOverlay();
        window.closeSearchOverlay = () => this.closeOverlay();
        window.setSearch = (term) => this.setSearch(term);
        
        // Load default state immediately to have it ready
        this.fetchDefaultState();
    }

    initPlaceholderRotation() {
        const placeholders = [
            'Search for "Caps"',
            'Search for "Yoga Mats"',
            'Search for "Running Shoes"',
            'Search for "Badminton Rackets"',
            'Search for "Fishing Rods"',
            'Search for "Cycles"',
            'Search for "Gym Gloves"',
        ];
        let i = 0;
        setInterval(() => {
            i = (i + 1) % placeholders.length;
            if (this.elements.headerInput) this.elements.headerInput.placeholder = placeholders[i];
        }, 2800);
    }

    openOverlay() {
        if (!this.elements.overlay) return;
        this.elements.overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        this.renderRecentlyViewed();
        setTimeout(() => {
            if (this.elements.overlayInput) this.elements.overlayInput.focus();
        }, 80);
    }

    closeOverlay() {
        if (!this.elements.overlay) return;
        this.elements.overlay.classList.add('hidden');
        document.body.style.overflow = '';
        if (this.elements.overlayInput) this.elements.overlayInput.value = '';
        this.showDefaultState();
    }

    setSearch(term) {
        if (this.elements.overlayInput) {
            this.elements.overlayInput.value = term;
            this.elements.overlayInput.dispatchEvent(new Event('input'));
        }
    }

    showDefaultState() {
        if (this.elements.defaultState) this.elements.defaultState.classList.remove('hidden');
        if (this.elements.resultsState) this.elements.resultsState.classList.add('hidden');
    }

    showResultsState() {
        if (this.elements.defaultState) this.elements.defaultState.classList.add('hidden');
        if (this.elements.resultsState) this.elements.resultsState.classList.remove('hidden');
    }

    fetchDefaultState() {
        fetch(`${this.searchUrl}?q=`)
            .then(r => r.json())
            .then(data => {
                if (data.is_default) {
                    this.renderTrending(data.trending);
                    this.renderPopular(data.popular);
                }
            });
    }

    renderTrending(tags) {
        if (!tags || !tags.length || !this.elements.trendingChips) return;
        this.elements.trendingChips.innerHTML = tags.map(t => 
            `<button onclick="setSearch('${t}')" class="px-3.5 py-1.5 rounded-full border border-gray-300 text-[13px] text-gray-700 hover:border-[#0082C3] hover:text-[#0082C3] transition-colors bg-white">${t}</button>`
        ).join('');
        if (this.elements.trendingSection) this.elements.trendingSection.classList.remove('hidden');
    }

    renderPopular(products) {
        if (!products || !products.length || !this.elements.popularGrid) return;
        this.elements.popularGrid.innerHTML = products.map(p => this.popularCardTemplate(p)).join('');
        if (this.elements.popularSection) this.elements.popularSection.classList.remove('hidden');
    }

    renderRecentlyViewed() {
        const recent = JSON.parse(localStorage.getItem('recently_viewed') || '[]');
        if (!recent.length || !this.elements.recentGrid) {
            if (this.elements.recentSection) this.elements.recentSection.classList.add('hidden');
            return;
        }
        this.elements.recentGrid.innerHTML = recent.slice(0, 4).map(p => this.recentCardTemplate(p)).join('');
        if (this.elements.recentSection) this.elements.recentSection.classList.remove('hidden');
    }

    fetchResults(q) {
        this.showResultsState();
        this.elements.noResults.classList.add('hidden');
        this.elements.productGrid.innerHTML = '<p class="text-gray-400 text-[13px]">Searching...</p>';
        this.elements.categoryDiv.classList.add('hidden');

        fetch(`${this.searchUrl}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => this.renderResults(data))
            .catch(() => {
                this.elements.productGrid.innerHTML = '<p class="text-red-400 text-[13px]">Search failed. Try again.</p>';
            });
    }

    renderResults(data) {
        if (data.is_default) {
            this.showDefaultState();
            return;
        }

        // Category chips
        if (data.categories && data.categories.length) {
            this.elements.categoryList.innerHTML = data.categories.map(c =>
                `<a href="${this.shopUrl}?category=${c.slug}" class="px-3 py-1 rounded-full border border-gray-300 text-[12px] text-gray-700 hover:border-[#0082C3] hover:text-[#0082C3] transition-colors bg-white">${c.name}</a>`
            ).join('');
            this.elements.categoryDiv.classList.remove('hidden');
        } else {
            this.elements.categoryDiv.classList.add('hidden');
        }

        // Products
        if (data.products && data.products.length) {
            this.elements.productGrid.innerHTML = data.products.map(p => this.productCardTemplate(p)).join('');
            this.elements.noResults.classList.add('hidden');
        } else if (!data.categories || !data.categories.length) {
            this.elements.productGrid.innerHTML = '';
            this.elements.noResults.classList.remove('hidden');
        } else {
            this.elements.productGrid.innerHTML = '';
        }
    }

    productCardTemplate(p) {
        const stars = this.renderStars(p.rating || 0);
        const imgSrc = p.image || 'https://placehold.co/200x200/f3f4f6/999?text=No+Image';
        const productUrl = `/product/${p.slug}`;
        const addAction = p.has_variants ? `window.QuickView.open('${p.slug}')` : `window.Cart.add(${p.id}, ${p.variant_id || 'null'})`;
        
        return `
        <div class="group border border-gray-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer" onclick="window.location.href='${productUrl}'">
            <div class="bg-gray-50 h-[160px] flex items-center justify-center overflow-hidden">
                <img src="${imgSrc}" alt="${p.name}" class="h-full w-full object-contain group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-3">
                <p class="text-[11px] text-gray-900 font-bold mb-0.5 line-clamp-1">
                    ${p.brand ? '<span class="font-black">' + p.brand + '</span> ' : ''}${p.name}
                </p>
                ${stars}
                ${p.price ? `<p class="text-[14px] font-black text-gray-900 mt-1">&#8377;${Number(p.price).toFixed(0)}</p>` : ''}
                <div class="flex gap-2 mt-2">
                    <button class="add-to-cart-btn flex-1 bg-[#0082C3] text-white text-[11px] font-semibold py-1.5 rounded hover:bg-[#006699] transition-colors" 
                            onclick="event.stopPropagation(); if(window.Cart) ${addAction};">
                        Add to cart
                    </button>
                </div>
            </div>
        </div>`;
    }

    popularCardTemplate(p) {
        const stars = this.renderStars(p.rating || 0);
        const imgSrc = p.image || 'https://placehold.co/200x200/f3f4f6/999?text=No+Image';
        const productUrl = `/product/${p.slug}`;
        const addAction = p.has_variants ? `window.QuickView.open('${p.slug}')` : `window.Cart.add(${p.id}, ${p.variant_id || 'null'})`;
        
        return `
        <div class="flex-shrink-0 w-[150px] border border-gray-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow group relative flex flex-col bg-white cursor-pointer" onclick="window.location.href='${productUrl}'">
            <div class="relative bg-gray-50 h-[110px] flex items-center justify-center overflow-hidden">
                <img src="${imgSrc}" alt="${p.name}" class="h-full w-full object-contain group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-2 flex-1 flex flex-col justify-between">
                <div>
                    <p class="text-[9px] font-black text-[#0082C3] uppercase mb-0.5">${p.brand || 'DECATHLON'}</p>
                    <p class="text-[11px] text-gray-900 font-semibold leading-tight line-clamp-2 mb-1">${p.name}</p>
                    ${stars}
                </div>
                <div>
                    <div class="flex items-baseline gap-1 flex-wrap">
                        <span class="text-[12px] font-black text-gray-900">₹${Number(p.price).toFixed(0)}</span>
                    </div>
                    <button class="w-full mt-2 bg-[#0082C3] text-white text-[10px] font-semibold py-1 rounded hover:bg-[#006699] transition-colors"
                            onclick="event.stopPropagation(); if(window.Cart) ${addAction};">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>`;
    }

    recentCardTemplate(p) {
        const stars = this.renderStars(p.rating || 0);
        const imgSrc = p.image || 'https://placehold.co/200x200/f3f4f6/999?text=No+Image';
        const productUrl = `/product/${p.slug}`;
        const addAction = p.has_variants ? `window.QuickView.open('${p.slug}')` : `window.Cart.add(${p.id}, ${p.variant_id || 'null'})`;
        
        return `
        <div class="border border-gray-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow group relative flex flex-col bg-white cursor-pointer" onclick="window.location.href='${productUrl}'">
            <div class="relative bg-gray-50 h-[120px] flex items-center justify-center overflow-hidden">
                <img src="${imgSrc}" alt="${p.name}" class="h-full w-full object-contain group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-2.5 flex-1 flex flex-col justify-between">
                <div>
                    <p class="text-[10px] font-black text-[#0082C3] uppercase mb-0.5">${p.brand || 'DECATHLON'}</p>
                    <p class="text-[12px] text-gray-900 font-semibold leading-tight line-clamp-2 mb-1">${p.name}</p>
                    ${stars}
                </div>
                <div>
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <span class="text-[13px] font-black text-gray-900">₹${Number(p.price).toFixed(0)}</span>
                    </div>
                    <button class="w-full mt-2 bg-[#0082C3] text-white text-[11px] font-semibold py-1 rounded hover:bg-[#006699] transition-colors"
                            onclick="event.stopPropagation(); if(window.Cart) ${addAction};">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>`;
    }

    renderStars(rating) {
        if (!rating) return '';
        const r = Math.round(rating * 2) / 2;
        let html = '<div class="flex items-center gap-0.5 mt-1">';
        for (let i = 1; i <= 5; i++) {
            if (i <= r) html += '<svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
            else html += '<svg class="w-3 h-3 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
        }
        html += '</div>';
        return html;
    }
}
