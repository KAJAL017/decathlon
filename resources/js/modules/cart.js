/**
 * Decathlon Cart Module - Pure Vanilla JS
 */
const Cart = {
    _activeBtn: null,
    _busy: false,

    init() {
        this.updateCartCount();
        this.bindEvents();
    },

    bindEvents() {
        document.addEventListener('click', (e) => {
            const addBtn = e.target.closest('.add-to-cart-btn');
            if (!addBtn) return;
            e.preventDefault();
            e.stopPropagation();

            if (this._busy) return;

            const productId = addBtn.dataset.productId;
            let variantId = addBtn.dataset.variantId;

            if (!variantId) {
                const sel = document.querySelector('.size-btn.bg-black') || document.querySelector('.size-btn.bg-gray-950');
                if (sel) variantId = sel.dataset.variantId;
            }

            if (!variantId) {
                var firstSizeBtn = document.querySelector('.size-btn');
                if (firstSizeBtn) variantId = firstSizeBtn.dataset.variantId;
            }

            if (!variantId) return;

            var quantityEl = document.getElementById('quantity-input');
            var quantity = quantityEl ? parseInt(quantityEl.value, 10) || 1 : 1;

            this._activeBtn = addBtn;
            this.add(productId, variantId, quantity);
        });
    },

    async add(productId, variantId, quantity) {
        var btn = this._activeBtn;
        this._busy = true;
        if (btn) this.showSpinner(btn);

        try {
            var response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity: quantity })
            });

            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                this.openMiniCart();
                return { success: true };
            }
            return { success: false };
        } catch (err) {
            return { success: false };
        } finally {
            if (btn) this.hideSpinner(btn);
            this._busy = false;
            this._activeBtn = null;
        }
    },

    showSpinner(btn) {
        if (!btn) return;

        btn.dataset.originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.style.opacity = '0.6';
        btn.style.pointerEvents = 'none';

        var spinner = document.createElement('span');
        spinner.className = 'cart-btn-spinner';
        spinner.style.cssText = 'display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:cartSpin 0.6s linear infinite;vertical-align:middle;margin-right:6px;';

        btn.innerHTML = '';
        btn.appendChild(spinner);
        btn.appendChild(document.createTextNode('Adding\u2026'));
    },

    hideSpinner(btn) {
        if (!btn) return;

        btn.disabled = false;
        btn.style.opacity = '';
        btn.style.pointerEvents = '';

        if (btn.dataset.originalContent) {
            btn.innerHTML = btn.dataset.originalContent;
            delete btn.dataset.originalContent;
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons({ nodes: [btn] });
        }
    },

    async update(itemId, quantity) {
        if (quantity < 1) return;
        try {
            var response = await fetch('/cart/update/' + itemId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: quantity })
            });
            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                if (window.location.pathname === '/cart') window.location.reload();
            }
        } catch (e) { /* silent */ }
    },

    async remove(itemId) {
        if (!confirm('Remove this item from cart?')) return;
        try {
            var response = await fetch('/cart/remove/' + itemId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                if (window.location.pathname === '/cart') window.location.reload();
            }
        } catch (e) { /* silent */ }
    },

    async clear() {
        if (!confirm('Clear all items from cart?')) return;
        try {
            var response = await fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                if (window.location.pathname === '/cart') window.location.reload();
            }
        } catch (e) { /* silent */ }
    },

    updateUI(data) {
        if (!data) return;

        var countElements = document.querySelectorAll('.cart-count');
        countElements.forEach(function(el) {
            var newCount = data.total_quantity || 0;
            var oldCount = parseInt(el.innerText) || 0;
            el.innerText = newCount;
            el.classList.toggle('hidden', newCount === 0);
            if (newCount !== oldCount && newCount > 0) {
                el.classList.remove('cart-bounce');
                void el.offsetWidth;
                el.classList.add('cart-bounce');
            }
        });

        var subtotalElements = document.querySelectorAll('.cart-subtotal');
        subtotalElements.forEach(function(el) {
            el.innerText = '\u20B9' + (data.total_amount || '0.00');
        });

        var miniCartContainer = document.getElementById('mini-cart-items');
        if (miniCartContainer) {
            miniCartContainer.innerHTML = data.mini_cart_html || data.html || '';
        }
    },

    updateCartCount() {
        fetch('/cart/mini-cart')
            .then(function(res) { return res.json(); })
            .then(function(result) {
                if (result.success) Cart.updateUI(result.data);
            })
            .catch(function() {});
    },

    openMiniCart() {
        var miniCart = document.getElementById('mini-cart-dropdown');
        if (miniCart) {
            miniCart.classList.remove('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
            miniCart.classList.add('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');

            var closeHandler = function(e) {
                if (!miniCart.contains(e.target) && !e.target.closest('#cart-nav-item')) {
                    Cart.closeMiniCart();
                    document.removeEventListener('click', closeHandler);
                }
            };
            setTimeout(function() { document.addEventListener('click', closeHandler); }, 0);
        }
    },

    closeMiniCart() {
        var miniCart = document.getElementById('mini-cart-dropdown');
        if (miniCart) {
            miniCart.classList.add('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
            miniCart.classList.remove('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
        }
    }
};

window.Cart = Cart;
export default Cart;
document.addEventListener('DOMContentLoaded', function() { Cart.init(); });
