import './bootstrap';
import Search from './modules/search';
import Cart from './modules/cart';
import Wishlist from './modules/wishlist';
import QuickView from './modules/quickview';
import Shop from './modules/shop';
import Slider from './modules/slider';
import Product from './modules/product';

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Search
    if (document.getElementById('header-search-input')) {
        new Search({
            searchUrl: window.appConfig?.routes?.searchSuggestions || '/api/search',
            shopUrl: window.appConfig?.routes?.shop || '/shop'
        });
    }

    // Initialize Quick View
    if (document.getElementById('quick-view-modal')) {
        new QuickView({
            baseUrl: window.appConfig?.routes?.quickView || '/api/quick-view'
        });
    }

    // Initialize Shop (Filters, Sidebar)
    if (document.querySelector('main.lg\\:col-span-3') || document.getElementById('price-panel')) {
        new Shop();
    }

    // Initialize Product Page
    if (document.getElementById('quantity-input') || document.querySelector('.size-btn')) {
        const productData = window.productData || {};
        new Product({
            productId: productData.id,
            initialVariantId: productData.variant_id,
            productData: productData
        });
    }

    // Initialize Sliders
    if (document.getElementById('slider-track')) {
        new Slider({
            trackId: 'slider-track',
            paginationId: 'pagination'
        });
    }
    if (document.getElementById('banner-track')) {
        new Slider({
            trackId: 'banner-track',
            paginationId: 'banner-pagination',
            interval: 4000
        });
    }
});
