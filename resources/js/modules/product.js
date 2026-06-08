/**
 * Product Module
 * Handles product page interactions: variants, quantity, pincode, accordions
 */
export default class Product {
    constructor(config = {}) {
        this.productId = config.productId;
        this.initialVariantId = config.initialVariantId;
        this.productData = config.productData || {};
        
        this.init();
    }

    init() {
        this.initVariants();
        this.initQuantity();
        this.initAccordions();
        this.initPincode();
        this.initSliders();
        this.trackRecentlyViewed();
    }

    /**
     * Initialize variant selection (Size, Color)
     */
    initVariants() {
        // Size Selection
        const sizeBtns = document.querySelectorAll('.size-btn');
        sizeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const variantId = btn.dataset.variantId;
                const sizeLabel = btn.dataset.size || btn.innerText.trim();
                this.selectVariant(variantId, sizeLabel, btn);
            });
        });

        // Color Selection
        const colorSwatches = document.querySelectorAll('.color-swatch');
        colorSwatches.forEach(swatch => {
            const color = swatch.dataset.color;
            if (color) {
                swatch.addEventListener('click', () => this.selectColor(color, swatch));
            }
        });
    }

    selectVariant(id, size, element) {
        // Update label
        const label = document.getElementById('selected-size-label');
        if (label) label.innerText = size;
        
        // Update buttons UI
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.className = 'size-btn border-2 border-gray-200 text-gray-800 hover:border-black text-xs font-bold px-6 min-w-[3.5rem] h-10 rounded flex items-center justify-center transition-all duration-200';
        });
        element.className = 'size-btn border-2 border-black bg-black text-white text-xs font-bold px-6 min-w-[3.5rem] h-10 rounded flex items-center justify-center transition-all duration-200';

        // Update add to cart button
        const addBtn = document.querySelector('.add-to-cart-btn');
        if (addBtn) addBtn.dataset.variantId = id;
    }

    selectColor(color, element) {
        const label = document.getElementById('selected-color-label');
        if (label) label.innerText = color;
        
        document.querySelectorAll('.color-swatch').forEach(btn => {
            btn.classList.remove('border-gray-900', 'ring-2', 'ring-gray-200');
            btn.classList.add('border-gray-200');
        });
        element.classList.add('border-gray-900', 'ring-2', 'ring-gray-200');
        element.classList.remove('border-gray-200');
    }

    /**
     * Initialize quantity controls
     */
    initQuantity() {
        const qtyBtns = document.querySelectorAll('.qty-btn');
        qtyBtns.forEach(btn => {
            const delta = parseInt(btn.dataset.delta || '0');
            btn.addEventListener('click', () => this.changeQty(delta));
        });
    }

    changeQty(delta) {
        const input = document.getElementById('quantity-input');
        if (!input) return;
        
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > 10) val = 10;
        input.value = val;
    }

    /**
     * Initialize accordions
     */
    initAccordions() {
        const triggers = document.querySelectorAll('.accordion-trigger');
        triggers.forEach(trigger => {
            const contentId = trigger.dataset.target;
            const iconId = trigger.dataset.icon;
            
            if (contentId) {
                trigger.addEventListener('click', () => this.toggleAccordion(contentId, iconId));
            }
        });

        // Set initial state
        ['detail-content', 'specs-content', 'faq-content', 'tech-content'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (id === 'detail-content') {
                     el.style.maxHeight = el.scrollHeight + 'px';
                } else {
                     el.style.maxHeight = '0px';
                }
            }
        });
    }

    toggleAccordion(contentId, iconId) {
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        
        if (!content) return;

        if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
            content.style.maxHeight = content.scrollHeight + 'px';
            if (icon) icon.classList.add('rotate-90');
        } else {
            content.style.maxHeight = '0px';
            if (icon) icon.classList.remove('rotate-90');
        }
    }

    /**
     * Initialize pincode checker
     */
    initPincode() {
        const btn = document.getElementById('pincode-check-btn');
        const input = document.getElementById('pincode-input');
        const info = document.getElementById('delivery-info');

        if (btn && input) {
            btn.addEventListener('click', () => {
                const val = input.value.trim();
                if (val.length === 6 && !isNaN(val)) {
                    info.innerHTML = `
                        <div class="flex items-start gap-2.5 text-xs text-gray-700 animate-fade-in">
                            <span class="text-emerald-600 mt-0.5">✔</span>
                            <div>
                                <span class="font-bold text-emerald-700">Delivery available to ${val}</span>
                                <p class="text-[10px] text-gray-500 font-semibold mt-1">Standard delivery within 2-4 days</p>
                            </div>
                        </div>
                    `;
                    btn.className = "bg-emerald-50 border-l border-gray-300 px-4 text-xs font-extrabold text-emerald-700 uppercase tracking-wider transition-colors";
                    btn.innerHTML = "✔ Active";
                } else {
                    info.innerHTML = `
                        <div class="flex items-start gap-2.5 text-xs text-red-700 animate-fade-in">
                            <span class="text-red-600 mt-0.5">✘</span>
                            <div>
                                <span class="font-bold text-red-700">Invalid Pincode</span>
                                <p class="text-[10px] text-gray-500 font-semibold mt-1">Please enter a valid 6-digit number.</p>
                            </div>
                        </div>
                    `;
                    btn.className = "bg-red-50 border-l border-gray-300 px-4 text-xs font-extrabold text-red-750 uppercase tracking-wider transition-colors";
                    btn.innerHTML = "Retry";
                }
            });
        }
    }

    /**
     * Initialize product sliders (Similar, Bought Together)
     */
    initSliders() {
        const triggers = document.querySelectorAll('.slider-scroll');
        triggers.forEach(trigger => {
            const sliderId = trigger.dataset.slider;
            const direction = trigger.dataset.direction;
            
            if (sliderId && direction) {
                trigger.addEventListener('click', () => this.scrollSlider(sliderId, direction));
            }
        });
    }

    scrollSlider(sliderId, direction) {
        const slider = document.getElementById(sliderId);
        const scrollAmount = 300;
        if (!slider) return;
        
        if (direction === 'left') {
            slider.scrollLeft -= scrollAmount;
        } else {
            slider.scrollLeft += scrollAmount;
        }
    }

    /**
     * Track recently viewed products in LocalStorage
     */
    trackRecentlyViewed() {
        if (!this.productData.id) return;

        try {
            let recent = JSON.parse(localStorage.getItem('recently_viewed') || '[]');
            recent = recent.filter(p => p.id !== this.productData.id);
            recent.unshift(this.productData);
            localStorage.setItem('recently_viewed', JSON.stringify(recent.slice(0, 10)));
        } catch (e) {
            console.error('Failed to track recently viewed', e);
        }
    }
}
