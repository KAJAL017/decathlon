/**
 * Decathlon Cart Module - Pure Vanilla JS
 * Full AJAX operations, custom dialogs, real-time UI updates
 */
const Cart = {
    _activeBtn: null,
    _busy: false,
    _cartPage: false,
    _summaryLoading: false,
    _summaryFadeTimeout: null,

    init() {
        this._cartPage = window.location.pathname === '/cart' || window.location.pathname === '/cart/';
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
        if (this._cartPage) this._showSummarySkeleton();

        try {
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            var response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity: quantity })
            });

            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                if (this._cartPage && result.data.cart_summary_html) {
                    this._hideSummarySkeleton(result.data.cart_summary_html);
                }
                this.openMiniCart();
                this._removeFromWishlistIfOnPage(productId);
                return { success: true };
            }
            if (this._cartPage) this._hideSummarySkeleton();
            return { success: false };
        } catch (err) {
            if (this._cartPage) this._hideSummarySkeleton();
            return { success: false };
        } finally {
            if (btn) this.hideSpinner(btn);
            this._busy = false;
            this._activeBtn = null;
        }
    },

    _removeFromWishlistIfOnPage(productId) {
        var isWishlistPage = window.location.pathname === '/wishlist' || window.location.pathname === '/wishlist/';
        if (!isWishlistPage) return;

        if (window.Wishlist && typeof window.Wishlist.remove === 'function' && window.Wishlist.isWishlisted(productId)) {
            window.Wishlist.remove(productId);
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

    /**
     * Show a small inline spinner on a cart page element
     */
    _showInlineSpinner(container) {
        if (!container) return;
        container.dataset.originalHtml = container.innerHTML;
        container.innerHTML = '<span class="cart-btn-spinner" style="display:inline-block;width:14px;height:14px;border:2px solid rgba(0,0,0,0.15);border-top-color:#0082C3;border-radius:50%;animation:cartSpin 0.6s linear infinite;vertical-align:middle;"></span>';
    },

    _hideInlineSpinner(container) {
        if (!container) return;
        if (container.dataset.originalHtml) {
            container.innerHTML = container.dataset.originalHtml;
            delete container.dataset.originalHtml;
        }
    },

    _buildSummarySkeleton() {
        return '<div class="bg-white rounded-2xl p-5 md:p-6 h-full">'
            + '<h2 class="text-sm font-black text-gray-950 uppercase tracking-wider pb-4 border-b border-gray-100">Order Summary</h2>'
            + '<div class="space-y-3">'
            + '<div class="flex justify-between items-center"><div class="summary-skel-bar" style="width:42%"></div><div class="summary-skel-bar" style="width:22%"></div></div>'
            + '<div class="flex justify-between items-center"><div class="summary-skel-bar" style="width:30%"></div><div class="summary-skel-bar" style="width:14%"></div></div>'
            + '<div class="flex justify-between items-center"><div class="summary-skel-bar" style="width:36%"></div><div class="summary-skel-bar" style="width:8%"></div></div>'
            + '</div>'
            + '<div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">'
            + '<div class="summary-skel-bar-lg" style="width:24%"></div>'
            + '<div class="summary-skel-bar-lg" style="width:28%"></div>'
            + '</div>'
            + '<div class="mt-5 space-y-3">'
            + '<div class="summary-skel-btn w-full"></div>'
            + '<div class="flex justify-center"><div class="summary-skel-bar" style="width:40%"></div></div>'
            + '</div>'
            + '</div>';
    },

    _showSummarySkeleton() {
        if (!this._cartPage) return;
        var container = document.getElementById('cart-summary-container');
        if (!container) return;

        if (this._summaryFadeTimeout) {
            clearTimeout(this._summaryFadeTimeout);
            this._summaryFadeTimeout = null;
        }

        var existing = document.getElementById('summary-skeleton-overlay');
        if (existing) {
            existing.style.transition = 'none';
            existing.style.opacity = '0.92';
            void existing.offsetWidth;
            existing.style.transition = 'opacity 150ms ease-in-out';
            existing.style.opacity = '1';
            return;
        }

        this._summaryLoading = true;

        var overlay = document.createElement('div');
        overlay.id = 'summary-skeleton-overlay';
        overlay.className = 'summary-overlay';
        overlay.innerHTML = this._buildSummarySkeleton();
        container.appendChild(overlay);

        void overlay.offsetWidth;
        overlay.style.transition = 'opacity 150ms ease-in-out';
        overlay.style.opacity = '1';
        overlay.classList.add('active');
    },

    _hideSummarySkeleton(newHtml) {
        if (!this._cartPage) return;
        var container = document.getElementById('cart-summary-container');
        if (!container) return;

        if (newHtml) {
            var temp = document.createElement('div');
            temp.innerHTML = newHtml;
            var newContent = temp.querySelector('h2')
                ? temp
                : temp;
            while (container.childNodes.length > 0) {
                container.removeChild(container.lastChild);
            }
            while (newContent.childNodes.length > 0) {
                container.appendChild(newContent.childNodes[0]);
            }
        }

        var overlay = document.getElementById('summary-skeleton-overlay');
        if (!overlay) {
            this._summaryLoading = false;
            return;
        }

        overlay.style.opacity = '0';
        var self = this;
        this._summaryFadeTimeout = setTimeout(function() {
            if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
            self._summaryLoading = false;
            self._summaryFadeTimeout = null;
        }, 160);
    },

    _disableCartControls() {
        var qtyBtns = document.querySelectorAll('#cart-items-container button');
        qtyBtns.forEach(function(btn) { btn.disabled = true; btn.style.pointerEvents = 'none'; });
        var checkoutLinks = document.querySelectorAll('#cart-summary-container a[href]');
        checkoutLinks.forEach(function(a) { a.style.pointerEvents = 'none'; a.style.opacity = '0.5'; });
    },

    _enableCartControls() {
        var qtyBtns = document.querySelectorAll('#cart-items-container button');
        qtyBtns.forEach(function(btn) { btn.disabled = false; btn.style.pointerEvents = ''; });
        var checkoutLinks = document.querySelectorAll('#cart-summary-container a[href]');
        checkoutLinks.forEach(function(a) { a.style.pointerEvents = ''; a.style.opacity = ''; });
    },

    async update(itemId, quantity) {
        if (quantity < 1) return;

        var qtyEl = document.querySelector('.cart-item[data-id="' + itemId + '"] .cart-qty-value');
        if (qtyEl) this._showInlineSpinner(qtyEl);
        this._showSummarySkeleton();
        this._disableCartControls();

        try {
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            var response = await fetch('/cart/update/' + itemId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: quantity })
            });
            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                if (this._cartPage) {
                    this._hideSummarySkeleton(result.data.cart_summary_html);
                    this._updateCartPageUI(result.data);
                }
            } else {
                if (qtyEl) this._hideInlineSpinner(qtyEl);
                this._hideSummarySkeleton();
                this._enableCartControls();
                if (typeof Toastify !== 'undefined') {
                    Toastify({ text: result.message || 'Update failed', duration: 3000, gravity: 'top', position: 'right', style: { background: '#ef4444' } }).showToast();
                }
            }
        } catch (e) {
            if (qtyEl) this._hideInlineSpinner(qtyEl);
            this._hideSummarySkeleton();
            this._enableCartControls();
        }
    },

    async remove(itemId) {
        var confirmed = await window.Dialog.confirm({
            title: 'Remove Item',
            message: 'Are you sure you want to remove this item from your cart?',
            type: 'danger',
            confirmText: 'Remove',
            cancelText: 'Keep'
        });
        if (!confirmed) return;

        var row = document.querySelector('.cart-item[data-id="' + itemId + '"]');
        if (row) {
            row.style.transition = 'opacity 0.3s, transform 0.3s';
            row.style.opacity = '0.5';
            row.style.pointerEvents = 'none';
        }

        this._showSummarySkeleton();
        this._disableCartControls();

        try {
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            var response = await fetch('/cart/remove/' + itemId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                if (this._cartPage) {
                    this._hideSummarySkeleton(result.data.cart_summary_html);
                    this._updateCartPageUI(result.data);
                }
                if (typeof Toastify !== 'undefined') {
                    Toastify({ text: 'Item removed from cart', duration: 3000, gravity: 'top', position: 'right', style: { background: '#10b981' } }).showToast();
                }
            }
        } catch (e) {
            this._hideSummarySkeleton();
            this._enableCartControls();
        }
    },

    async clear() {
        var confirmed = await window.Dialog.confirm({
            title: 'Clear Cart',
            message: 'Remove all items from your cart? This cannot be undone.',
            type: 'danger',
            confirmText: 'Clear All',
            cancelText: 'Cancel'
        });
        if (!confirmed) return;

        this._showSummarySkeleton();
        this._disableCartControls();

        try {
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            var response = await fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            var result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                if (this._cartPage) {
                    this._hideSummarySkeleton(result.data.cart_summary_html);
                    this._updateCartPageUI(result.data);
                }
                if (typeof Toastify !== 'undefined') {
                    Toastify({ text: 'Cart cleared', duration: 3000, gravity: 'top', position: 'right', style: { background: '#10b981' } }).showToast();
                }
            }
        } catch (e) {
            this._hideSummarySkeleton();
            this._enableCartControls();
        }
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

    /**
     * Update the cart page (items list + summary) without page reload
     */
    _updateCartPageUI(data) {
        if (!data) return;

        var itemsContainer = document.getElementById('cart-items-container');

        if (itemsContainer && data.cart_items_html !== undefined) {
            itemsContainer.innerHTML = data.cart_items_html;
            if (typeof lucide !== 'undefined') lucide.createIcons({ nodes: [itemsContainer] });
        }

        this._cartPage = true;
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
