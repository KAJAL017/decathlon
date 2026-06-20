/**
 * Decathlon Wishlist Module - Premium AJAX-powered
 * Supports logged-in users (database) and guests (localStorage)
 */
const Wishlist = {
    _busy: false,
    _productIds: [],
    _isAuthenticated: false,
    _localStorageKey: 'decathlon_wishlist',

    init() {
        this._isAuthenticated = document.querySelector('meta[name="csrf-token"]') !== null && typeof window.customerAuth !== 'undefined' && window.customerAuth;
        this._loadState();
        this.bindEvents();
        this.updateAllUI();
    },

    _loadState() {
        if (this._isAuthenticated) {
            this._syncFromServer();
        } else {
            const stored = localStorage.getItem(this._localStorageKey);
            this._productIds = stored ? JSON.parse(stored) : [];
        }
    },

    _persistLocal() {
        if (!this._isAuthenticated) {
            localStorage.setItem(this._localStorageKey, JSON.stringify(this._productIds));
        }
    },

    async _syncFromServer() {
        try {
            const res = await fetch('/wishlist/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_ids: [] })
            });
            const result = await res.json();
            if (result.success) {
                this._productIds = result.data.product_ids || [];
                this.updateAllUI();
            }
        } catch (e) { /* silent */ }
    },

    bindEvents() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.wishlist-btn');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            if (this._busy) return;

            const productId = btn.dataset.productId;
            if (!productId) return;

            this.toggle(productId, btn);
        });
    },

    async toggle(productId, btn) {
        if (this._busy) return;
        this._busy = true;

        if (btn) this._showSpinner(btn);

        let wasRemoved = false;
        let APIsuccess = false;

        try {
            if (this._isAuthenticated) {
                const res = await fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                const result = await res.json();

                if (result.success) {
                    this._productIds = result.data.product_ids || [];
                    wasRemoved = result.data.status === 'removed';
                    APIsuccess = true;
                    this._showNotification(wasRemoved ? 'Removed from wishlist' : 'Added to wishlist!', !wasRemoved);
                } else {
                    this._showNotification(result.message || 'Something went wrong', false);
                }
            } else {
                const pid = parseInt(productId);
                const idx = this._productIds.indexOf(pid);
                if (idx > -1) {
                    this._productIds.splice(idx, 1);
                    wasRemoved = true;
                    this._showNotification('Removed from wishlist', false);
                } else {
                    this._productIds.push(pid);
                    this._showNotification('Added to wishlist!', true);
                }
                this._persistLocal();
                APIsuccess = true;
            }
        } catch (e) {
            this._showNotification('Something went wrong. Please try again.', false);
        } finally {
            if (btn) this._hideSpinner(btn);
            this.updateAllUI();
            if (APIsuccess && wasRemoved) this._removeWishlistCard(productId);
            this._busy = false;
        }
    },

    async add(productId, btn) {
        if (this._productIds.includes(parseInt(productId))) {
            return;
        }
        await this.toggle(productId, btn);
    },

    async remove(productId, btn) {
        if (!this._productIds.includes(parseInt(productId))) {
            return;
        }
        await this.toggle(productId, btn);
    },

    isWishlisted(productId) {
        return this._productIds.includes(parseInt(productId));
    },

    getCount() {
        return this._productIds.length;
    },

    updateAllUI() {
        const count = this.getCount();

        // Update all wishlist counters
        document.querySelectorAll('.wishlist-count').forEach(el => {
            const old = parseInt(el.innerText) || 0;
            el.innerText = count;
            el.classList.toggle('hidden', count === 0);
            if (count !== old && count > 0) {
                el.classList.remove('wishlist-bounce');
                void el.offsetWidth;
                el.classList.add('wishlist-bounce');
            }
        });

        // Update all heart buttons
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            const pid = parseInt(btn.dataset.productId);
            const isWished = this._productIds.includes(pid);
            this._setHeartState(btn, isWished);
        });

        // Update product card hearts
        document.querySelectorAll('.wishlist-heart-icon').forEach(icon => {
            const pid = parseInt(icon.dataset.productId);
            const isWished = this._productIds.includes(pid);
            const btn = icon.closest('.wishlist-btn') || icon.closest('button');
            if (btn) this._setHeartState(btn, isWished);
        });
    },

    _setHeartState(btn, isWished) {
        const outlineHeart = btn.querySelector('.heart-outline');
        const filledHeart = btn.querySelector('.heart-filled');

        if (outlineHeart && filledHeart) {
            if (isWished) {
                outlineHeart.classList.add('hidden');
                filledHeart.classList.remove('hidden');
                btn.classList.add('wishlisted');
            } else {
                outlineHeart.classList.remove('hidden');
                filledHeart.classList.add('hidden');
                btn.classList.remove('wishlisted');
            }
        } else {
            btn.dataset.wishlisted = isWished ? 'true' : 'false';
            if (isWished) {
                btn.classList.add('wishlisted');
            } else {
                btn.classList.remove('wishlisted');
            }
        }
    },

    _showSpinner(btn) {
        btn.dataset.originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.7';

        const spinner = document.createElement('span');
        spinner.className = 'wishlist-spinner';
        spinner.style.cssText = 'display:inline-block;width:16px;height:16px;border:2px solid rgba(0,0,0,0.2);border-top-color:currentColor;border-radius:50%;animation:wishlistSpin 0.6s linear infinite;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:5;';
        btn.style.position = 'relative';
        btn.appendChild(spinner);
    },

    _hideSpinner(btn) {
        btn.disabled = false;
        btn.style.pointerEvents = '';
        btn.style.opacity = '';

        const spinner = btn.querySelector('.wishlist-spinner');
        if (spinner) spinner.remove();

        if (btn.dataset.originalContent) {
            btn.innerHTML = btn.dataset.originalContent;
            delete btn.dataset.originalContent;
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons({ nodes: [btn] });
        }
    },

    _showNotification(message, success) {
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 2500,
                gravity: 'top',
                position: 'right',
                style: {
                    background: success ? '#10b981' : '#6b7280',
                    fontWeight: '700',
                    textTransform: 'uppercase',
                    letterSpacing: '0.5px',
                    fontSize: '12px',
                    borderRadius: '8px',
                    boxShadow: '0 10px 15px -3px rgba(0,0,0,0.1)'
                }
            }).showToast();
        }
    },

    _removeWishlistCard(productId) {
        const card = document.getElementById('wishlist-item-' + productId);
        if (!card) return;

        const isWishlistPage = window.location.pathname === '/wishlist' || window.location.pathname === '/wishlist/';
        if (!isWishlistPage) return;

        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.95)';

        setTimeout(() => {
            card.remove();
            this._updateWishlistPageState();
        }, 300);
    },

    _updateWishlistPageState() {
        const isWishlistPage = window.location.pathname === '/wishlist' || window.location.pathname === '/wishlist/';
        if (!isWishlistPage) return;

        const serverGrid = document.getElementById('wishlist-server-grid');
        const guestGrid = document.getElementById('wishlist-guest-grid');
        const serverEmpty = document.getElementById('wishlist-empty-state');
        const guestEmpty = document.getElementById('wishlist-guest-empty');
        const guestContinue = document.getElementById('wishlist-guest-continue');
        const pageCountEl = document.querySelector('.wishlist-page-count');

        const hasServerItems = serverGrid && !serverGrid.classList.contains('hidden') && serverGrid.children.length > 0;
        const hasGuestItems = guestGrid && !guestGrid.classList.contains('hidden') && guestGrid.children.length > 0;

        if (!hasServerItems && !hasGuestItems) {
            if (serverGrid) serverGrid.classList.add('hidden');
            if (serverEmpty) serverEmpty.classList.add('hidden');
            if (guestGrid) guestGrid.classList.add('hidden');
            if (guestContinue) guestContinue.classList.add('hidden');
            if (guestEmpty) guestEmpty.classList.remove('hidden');
            if (pageCountEl) pageCountEl.classList.add('hidden');
        } else {
            const totalItems = (serverGrid ? serverGrid.children.length : 0) + (guestGrid ? guestGrid.children.length : 0);
            if (pageCountEl && totalItems > 0) {
                pageCountEl.textContent = totalItems + ' item' + (totalItems > 1 ? 's' : '');
                pageCountEl.classList.remove('hidden');
            }
        }
    }
};

window.Wishlist = Wishlist;
export default Wishlist;
document.addEventListener('DOMContentLoaded', function() { Wishlist.init(); });
