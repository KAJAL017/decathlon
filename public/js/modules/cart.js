/**
 * Decathlon Cart Module - Pure Vanilla JS
 */
const Cart = {
    init() {
        this.updateCartCount();
        this.bindEvents();
    },

    bindEvents() {
        // Event delegation for "Add to Cart" buttons
        document.addEventListener('click', (e) => {
            const addBtn = e.target.closest('.add-to-cart-btn');
            if (addBtn) {
                e.preventDefault();
                const productId = addBtn.dataset.productId;
                const variantId = addBtn.dataset.variantId || this.getSelectedVariantId();
                const quantity = document.getElementById('quantity-input')?.value || 1;

                if (!variantId) {
                    this.notify('Please select a variant first.', 'warning');
                    return;
                }

                this.add(productId, variantId, quantity);
            }
        });
    },

    getSelectedVariantId() {
        // Implementation depends on product page layout
        const selectedSize = document.querySelector('.size-btn.bg-gray-950');
        return selectedSize ? selectedSize.dataset.variantId : null;
    },

    async add(productId, variantId, quantity = 1) {
        const addBtn = document.querySelector(`.add-to-cart-btn[data-product-id="${productId}"]`);
        this.setLoading(true, addBtn);
        
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity })
            });

            const result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                this.openMiniCart();
            } else {
                this.notify(result.message, 'error');
            }
        } catch (error) {
            this.notify('Something went wrong. Please try again.', 'error');
        } finally {
            this.setLoading(false, addBtn);
        }
    },

    async update(itemId, quantity) {
        if (quantity < 1) return;
        
        this.showSkeleton();
        
        try {
            const response = await fetch(`/cart/update/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity })
            });

            const result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
            } else {
                this.notify(result.message, 'error');
            }
        } catch (error) {
            this.notify('Update failed.', 'error');
        } finally {
            this.hideSkeleton();
        }
    },

    async remove(itemId) {
        const confirmed = await Dialog.confirm({
            title: 'Remove Item?',
            message: 'Are you sure you want to remove this item from your cart?',
            type: 'danger',
            confirmText: 'Remove',
            cancelText: 'Keep'
        });

        if (!confirmed) return;

        this.showSkeleton();

        try {
            const response = await fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                this.notify(result.message, 'success');
            }
        } catch (error) {
            this.notify('Removal failed.', 'error');
        } finally {
            this.hideSkeleton();
        }
    },

    async clear() {
        const confirmed = await Dialog.confirm({
            title: 'Clear Cart?',
            message: 'Are you sure you want to remove all items from your cart?',
            type: 'warning',
            confirmText: 'Clear All',
            cancelText: 'Cancel'
        });

        if (!confirmed) return;

        this.showSkeleton();

        try {
            const response = await fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (result.success) {
                this.updateUI(result.data);
                this.notify('Cart cleared successfully', 'success');
            }
        } catch (error) {
            this.notify('Clear failed.', 'error');
        } finally {
            this.hideSkeleton();
        }
    },

    updateUI(data) {
        // Update counts (Header and Mobile Nav)
        const countElements = document.querySelectorAll('.cart-count');
        countElements.forEach(el => {
            el.innerText = data.total_quantity;
            el.classList.toggle('hidden', data.total_quantity === 0);
        });

        // Update subtotal
        const subtotalElements = document.querySelectorAll('.cart-subtotal');
        subtotalElements.forEach(el => el.innerText = '₹' + data.total_amount);

        // Update Mini Cart HTML
        const miniCartContainer = document.getElementById('mini-cart-items');
        if (miniCartContainer) {
            miniCartContainer.innerHTML = data.mini_cart_html;
        }

        // Update Full Cart Page Items
        const cartItemsContainer = document.getElementById('cart-items-container');
        if (cartItemsContainer && data.cart_items_html) {
            cartItemsContainer.innerHTML = data.cart_items_html;
        }

        // Update Cart Summary
        const cartSummaryContainer = document.getElementById('cart-summary-container');
        if (cartSummaryContainer && data.cart_summary_html) {
            cartSummaryContainer.innerHTML = data.cart_summary_html;
        }
    },

    showSkeleton() {
        const container = document.getElementById('cart-items-container');
        const template = document.getElementById('cart-skeleton-template');
        if (!container || !template) return;

        // Count current items to show same number of skeletons
        const currentItemCount = container.querySelectorAll('.cart-item').length || 1;

        // Save current height to prevent jump
        container.style.minHeight = container.offsetHeight + 'px';
        
        container.innerHTML = '';
        for (let i = 0; i < currentItemCount; i++) {
            container.appendChild(template.content.cloneNode(true));
        }

        // Also dim summary
        const summary = document.getElementById('cart-summary-container');
        if (summary) summary.classList.add('opacity-50', 'pointer-events-none');
    },

    hideSkeleton() {
        const container = document.getElementById('cart-items-container');
        if (container) container.style.minHeight = '';

        const summary = document.getElementById('cart-summary-container');
        if (summary) summary.classList.remove('opacity-50', 'pointer-events-none');
    },

    updateCartCount() {
        fetch('/cart/mini-cart')
            .then(res => res.json())
            .then(result => {
                if (result.success) this.updateUI(result.data);
            });
    },

    openMiniCart() {
        const miniCart = document.getElementById('mini-cart-dropdown');
        if (miniCart) {
            miniCart.classList.remove('opacity-0', 'invisible', 'translate-y-2');
            miniCart.classList.add('opacity-100', 'visible', 'translate-y-0');
            
            // Auto close after 3 seconds
            setTimeout(() => {
                miniCart.classList.add('opacity-0', 'invisible', 'translate-y-2');
                miniCart.classList.remove('opacity-100', 'visible', 'translate-y-0');
            }, 3000);
        }
    },

    setLoading(state, button = null) {
        if (!button) return;

        if (state) {
            button.disabled = true;
            button.dataset.originalHtml = button.innerHTML;
            button.innerHTML = '<span class="spinner"></span>';
            button.classList.add('opacity-80', 'cursor-not-allowed');
        } else {
            button.disabled = false;
            if (button.dataset.originalHtml) {
                button.innerHTML = button.dataset.originalHtml;
            }
            button.classList.remove('opacity-80', 'cursor-not-allowed');
        }
    },

    notify(message, type = 'success') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "bottom", 
            position: "right", 
            stopOnFocus: true, 
            style: {
                background: type === 'success' ? "#10b981" : "#ef4444",
            }
        }).showToast();
    }
};

window.Cart = Cart;
document.addEventListener('DOMContentLoaded', () => Cart.init());
