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
        this.setLoading(true);
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
            this.setLoading(false);
        }
    },

    async update(itemId, quantity) {
        if (quantity < 1) return;
        
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
                // If on cart page, refresh totals
                if (window.location.pathname === '/cart') {
                    window.location.reload(); // Simple refresh for full cart page consistency
                }
            } else {
                this.notify(result.message, 'error');
            }
        } catch (error) {
            this.notify('Update failed.', 'error');
        }
    },

    async remove(itemId) {
        if (!confirm('Remove this item from cart?')) return;

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
                if (window.location.pathname === '/cart') {
                    window.location.reload();
                }
            }
        } catch (error) {
            this.notify('Removal failed.', 'error');
        }
    },

    async clear() {
        if (!confirm('Clear all items from cart?')) return;

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
                if (window.location.pathname === '/cart') {
                    window.location.reload();
                }
            }
        } catch (error) {
            this.notify('Clear failed.', 'error');
        }
    },

    updateUI(data) {
        if (!data) return;

        // Update counts
        const countElements = document.querySelectorAll('.cart-count');
        countElements.forEach(el => {
            el.innerText = data.total_quantity || 0;
            el.classList.toggle('hidden', (data.total_quantity || 0) === 0);
        });

        // Update subtotal
        const subtotalElements = document.querySelectorAll('.cart-subtotal');
        subtotalElements.forEach(el => el.innerText = '₹' + (data.total_amount || '0.00'));

        // Update Mini Cart HTML
        const miniCartContainer = document.getElementById('mini-cart-items');
        if (miniCartContainer) {
            miniCartContainer.innerHTML = data.mini_cart_html || data.html || '';
        }
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

    setLoading(state) {
        // Add global loader or button spinner logic here
    },

    notify(message, type = 'success') {
        // Use existing toast system if available, or alert
        alert(message);
    }
};

window.Cart = Cart;
export default Cart;
document.addEventListener('DOMContentLoaded', () => Cart.init());
