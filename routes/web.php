<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ActivityLogController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NewsletterController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/product/{slug}', [HomeController::class, 'product'])->name('product');

// Newsletter Route
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Customer Auth Routes
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.post');
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// OTP Routes
Route::post('/auth/otp/verify', [CustomerAuthController::class, 'verifyOtp'])->name('auth.otp.verify');
Route::post('/auth/otp/resend', [CustomerAuthController::class, 'resendOtp'])->name('auth.otp.resend');

// Forgot Password Routes
Route::post('/auth/forgot-password', [CustomerAuthController::class, 'forgotPassword'])->name('auth.forgot-password');
Route::post('/auth/reset-password', [CustomerAuthController::class, 'resetPassword'])->name('auth.reset-password');

// Google OAuth Routes
Route::get('/auth/google/redirect', [CustomerAuthController::class, 'googleRedirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [CustomerAuthController::class, 'googleCallback'])->name('auth.google.callback');

// Login Settings API
Route::get('/api/login-settings', [CustomerAuthController::class, 'getLoginSettings'])->name('api.login-settings');

// Checkout Routes (Guest + Auth)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:5,1')->name('checkout.store');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Guest Order Tracking
Route::get('/order/track', [CheckoutController::class, 'track'])->name('order.track');
Route::post('/order/track', [CheckoutController::class, 'trackLookup'])->name('order.track.lookup');

// Cart AJAX Routes
Route::prefix('cart')->name('cart.')->group(function() {
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update/{itemId}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/mini-cart', [CartController::class, 'miniCart'])->name('mini-cart');
});

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/guest-products', [WishlistController::class, 'guestProducts'])->name('wishlist.guest-products');
Route::post('/wishlist/sync', [WishlistController::class, 'syncGuestWishlist'])->name('wishlist.sync');
Route::prefix('wishlist')->name('wishlist.')->group(function() {
    Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    Route::post('/add', [WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [WishlistController::class, 'remove'])->name('remove');
    Route::post('/check', [WishlistController::class, 'check'])->name('check');
    Route::get('/count', [WishlistController::class, 'count'])->name('count');
});

// Search API
Route::get('/api/search', [HomeController::class, 'searchSuggestions'])->name('search.suggestions');
Route::get('/api/quick-view/{slug}', [HomeController::class, 'quickView'])->name('product.quickview');

// Admin Routes
Route::get('/admin', function() {
    if (session('admin_logged_in')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
})->name('admin');

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Password Reset Routes
Route::get('/admin/forgot-password', [AuthController::class, 'showForgotPassword'])->name('admin.forgot-password');
Route::post('/admin/forgot-password', [AuthController::class, 'forgotPassword'])->name('admin.forgot-password.post');
Route::get('/admin/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('admin.reset-password');
Route::post('/admin/reset-password', [AuthController::class, 'resetPassword'])->name('admin.reset-password.post');

// ── All admin routes protected by AdminAuth middleware ──────────
Route::middleware(['admin.auth'])->group(function () {

// Admin Dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/stats', [DashboardController::class, 'stats'])->name('admin.dashboard.stats');

// Admin Users Management
Route::get('/admin/profile', [AdminUserController::class, 'profile'])->name('admin.profile');
Route::put('/admin/profile', [AdminUserController::class, 'updateProfile'])->name('admin.profile.update');

Route::get('/admin/admin-users', [AdminUserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/admin-users/list', [AdminUserController::class, 'list'])->name('admin.users.list');
Route::post('/admin/admin-users', [AdminUserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/admin-users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
Route::put('/admin/admin-users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/admin-users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
Route::post('/admin/admin-users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');

// Roles Management
Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
Route::get('/admin/roles/list', [RoleController::class, 'list'])->name('admin.roles.list');
Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
Route::get('/admin/roles/{id}', [RoleController::class, 'show'])->name('admin.roles.show');
Route::put('/admin/roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::delete('/admin/roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

// Permissions Management
Route::get('/admin/permissions', [App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('admin.permissions.index');
Route::get('/admin/permissions/list', [App\Http\Controllers\Admin\PermissionController::class, 'list'])->name('admin.permissions.list');
Route::post('/admin/permissions', [App\Http\Controllers\Admin\PermissionController::class, 'store'])->name('admin.permissions.store');
Route::get('/admin/permissions/{id}', [App\Http\Controllers\Admin\PermissionController::class, 'show'])->name('admin.permissions.show');
Route::put('/admin/permissions/{id}', [App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('admin.permissions.update');
Route::delete('/admin/permissions/{id}', [App\Http\Controllers\Admin\PermissionController::class, 'destroy'])->name('admin.permissions.destroy');

// Activity Logs
Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
Route::get('/admin/activity-logs/list', [ActivityLogController::class, 'list'])->name('admin.activity-logs.list');
Route::get('/admin/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('admin.activity-logs.show');

// Home Sections Management
Route::get('/admin/home-sections', [App\Http\Controllers\Admin\HomeSectionController::class, 'index'])->name('admin.home-sections.index');
Route::get('/admin/home-sections/list', [App\Http\Controllers\Admin\HomeSectionController::class, 'list'])->name('admin.home-sections.list');
Route::post('/admin/home-sections', [App\Http\Controllers\Admin\HomeSectionController::class, 'store'])->name('admin.home-sections.store');
Route::get('/admin/home-sections/{id}', [App\Http\Controllers\Admin\HomeSectionController::class, 'show'])->name('admin.home-sections.show');
Route::put('/admin/home-sections/{id}', [App\Http\Controllers\Admin\HomeSectionController::class, 'update'])->name('admin.home-sections.update');
Route::delete('/admin/home-sections/{id}', [App\Http\Controllers\Admin\HomeSectionController::class, 'destroy'])->name('admin.home-sections.destroy');
Route::post('/admin/home-sections/reorder', [App\Http\Controllers\Admin\HomeSectionController::class, 'reorder'])->name('admin.home-sections.reorder');

// Banners Management
Route::get('/admin/banners', [App\Http\Controllers\Admin\BannerController::class, 'index'])->name('admin.banners.index');
Route::get('/admin/banners/list', [App\Http\Controllers\Admin\BannerController::class, 'list'])->name('admin.banners.list');
Route::post('/admin/banners', [App\Http\Controllers\Admin\BannerController::class, 'store'])->name('admin.banners.store');
Route::get('/admin/banners/{id}', [App\Http\Controllers\Admin\BannerController::class, 'show'])->name('admin.banners.show');
Route::put('/admin/banners/{id}', [App\Http\Controllers\Admin\BannerController::class, 'update'])->name('admin.banners.update');
Route::delete('/admin/banners/{id}', [App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('admin.banners.destroy');
Route::post('/admin/banners/{id}/toggle-status', [App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('admin.banners.toggle');
Route::post('/admin/banners/bulk-action', [App\Http\Controllers\Admin\BannerController::class, 'bulkAction'])->name('admin.banners.bulk');

// Categories Management
Route::get('/admin/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
Route::get('/admin/categories/list', [App\Http\Controllers\Admin\CategoryController::class, 'list'])->name('admin.categories.list');
Route::post('/admin/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
Route::get('/admin/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.categories.show');
Route::put('/admin/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
Route::delete('/admin/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
Route::post('/admin/categories/{id}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle');
Route::post('/admin/categories/bulk-action', [App\Http\Controllers\Admin\CategoryController::class, 'bulkAction'])->name('admin.categories.bulk');

// Tags Management
Route::get('/admin/tags', [App\Http\Controllers\Admin\TagController::class, 'index'])->name('admin.tags.index');
Route::get('/admin/tags/list', [App\Http\Controllers\Admin\TagController::class, 'list'])->name('admin.tags.list');
Route::post('/admin/tags', [App\Http\Controllers\Admin\TagController::class, 'store'])->name('admin.tags.store');
Route::get('/admin/tags/{id}', [App\Http\Controllers\Admin\TagController::class, 'show'])->name('admin.tags.show');
Route::put('/admin/tags/{id}', [App\Http\Controllers\Admin\TagController::class, 'update'])->name('admin.tags.update');
Route::delete('/admin/tags/{id}', [App\Http\Controllers\Admin\TagController::class, 'destroy'])->name('admin.tags.destroy');
Route::post('/admin/tags/{id}/toggle-status', [App\Http\Controllers\Admin\TagController::class, 'toggleStatus'])->name('admin.tags.toggle');
Route::post('/admin/tags/bulk-action', [App\Http\Controllers\Admin\TagController::class, 'bulkAction'])->name('admin.tags.bulk');

// Attributes Management
Route::get('/admin/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'index'])->name('admin.attributes.index');
Route::get('/admin/attributes/list', [App\Http\Controllers\Admin\AttributeController::class, 'list'])->name('admin.attributes.list');
Route::post('/admin/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'store'])->name('admin.attributes.store');
Route::get('/admin/attributes/{id}', [App\Http\Controllers\Admin\AttributeController::class, 'show'])->name('admin.attributes.show');
Route::put('/admin/attributes/{id}', [App\Http\Controllers\Admin\AttributeController::class, 'update'])->name('admin.attributes.update');
Route::delete('/admin/attributes/{id}', [App\Http\Controllers\Admin\AttributeController::class, 'destroy'])->name('admin.attributes.destroy');
Route::post('/admin/attributes/{id}/toggle-status', [App\Http\Controllers\Admin\AttributeController::class, 'toggleStatus'])->name('admin.attributes.toggle');
Route::post('/admin/attributes/bulk-action', [App\Http\Controllers\Admin\AttributeController::class, 'bulkAction'])->name('admin.attributes.bulk');

// Attribute Groups Management
Route::get('/admin/attribute-groups', [App\Http\Controllers\Admin\AttributeGroupController::class, 'index'])->name('admin.attribute-groups.index');
Route::get('/admin/attribute-groups/attributes', [App\Http\Controllers\Admin\AttributeGroupController::class, 'getAttributes'])->name('admin.attribute-groups.attributes');
Route::get('/admin/attribute-groups/list', [App\Http\Controllers\Admin\AttributeGroupController::class, 'list'])->name('admin.attribute-groups.list');
Route::post('/admin/attribute-groups', [App\Http\Controllers\Admin\AttributeGroupController::class, 'store'])->name('admin.attribute-groups.store');
Route::get('/admin/attribute-groups/{id}', [App\Http\Controllers\Admin\AttributeGroupController::class, 'show'])->name('admin.attribute-groups.show');
Route::put('/admin/attribute-groups/{id}', [App\Http\Controllers\Admin\AttributeGroupController::class, 'update'])->name('admin.attribute-groups.update');
Route::delete('/admin/attribute-groups/{id}', [App\Http\Controllers\Admin\AttributeGroupController::class, 'destroy'])->name('admin.attribute-groups.destroy');
Route::post('/admin/attribute-groups/{id}/toggle-status', [App\Http\Controllers\Admin\AttributeGroupController::class, 'toggleStatus'])->name('admin.attribute-groups.toggle');
Route::post('/admin/attribute-groups/bulk-action', [App\Http\Controllers\Admin\AttributeGroupController::class, 'bulkAction'])->name('admin.attribute-groups.bulk');

// Attribute Values Management
Route::get('/admin/attribute-values', [App\Http\Controllers\Admin\AttributeValueController::class, 'index'])->name('admin.attribute-values.index');
Route::get('/admin/attribute-values/attributes', [App\Http\Controllers\Admin\AttributeValueController::class, 'getAttributes'])->name('admin.attribute-values.attributes');
Route::get('/admin/attribute-values/list', [App\Http\Controllers\Admin\AttributeValueController::class, 'list'])->name('admin.attribute-values.list');
Route::post('/admin/attribute-values', [App\Http\Controllers\Admin\AttributeValueController::class, 'store'])->name('admin.attribute-values.store');
Route::get('/admin/attribute-values/{id}', [App\Http\Controllers\Admin\AttributeValueController::class, 'show'])->name('admin.attribute-values.show');
Route::put('/admin/attribute-values/{id}', [App\Http\Controllers\Admin\AttributeValueController::class, 'update'])->name('admin.attribute-values.update');
Route::delete('/admin/attribute-values/{id}', [App\Http\Controllers\Admin\AttributeValueController::class, 'destroy'])->name('admin.attribute-values.destroy');
Route::post('/admin/attribute-values/{id}/toggle-status', [App\Http\Controllers\Admin\AttributeValueController::class, 'toggleStatus'])->name('admin.attribute-values.toggle');
Route::post('/admin/attribute-values/bulk-action', [App\Http\Controllers\Admin\AttributeValueController::class, 'bulkAction'])->name('admin.attribute-values.bulk');

// Brands Management
Route::get('/admin/brands', [App\Http\Controllers\Admin\BrandController::class, 'index'])->name('admin.brands.index');
Route::get('/admin/brands/list', [App\Http\Controllers\Admin\BrandController::class, 'list'])->name('admin.brands.list');
Route::post('/admin/brands', [App\Http\Controllers\Admin\BrandController::class, 'store'])->name('admin.brands.store');
Route::get('/admin/brands/{id}', [App\Http\Controllers\Admin\BrandController::class, 'show'])->name('admin.brands.show');
Route::put('/admin/brands/{id}', [App\Http\Controllers\Admin\BrandController::class, 'update'])->name('admin.brands.update');
Route::delete('/admin/brands/{id}', [App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('admin.brands.destroy');
Route::post('/admin/brands/{id}/toggle-status', [App\Http\Controllers\Admin\BrandController::class, 'toggleStatus'])->name('admin.brands.toggle');
Route::post('/admin/brands/bulk-action', [App\Http\Controllers\Admin\BrandController::class, 'bulkAction'])->name('admin.brands.bulk');

// Products Management
Route::get('/admin/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
Route::get('/admin/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
Route::get('/admin/products/list', [App\Http\Controllers\Admin\ProductController::class, 'list'])->name('admin.products.list');
Route::get('/admin/products/variant-attributes', [App\Http\Controllers\Admin\ProductController::class, 'getVariantAttributes'])->name('admin.products.variant-attributes');
Route::get('/admin/products/import/template', [App\Http\Controllers\Admin\ProductController::class, 'getImportTemplate'])->name('admin.products.import.template');
Route::get('/admin/products/import-export/jobs', [App\Http\Controllers\Admin\ProductController::class, 'getImportExportJobs'])->name('admin.products.import-export.jobs');
Route::get('/admin/products/export/{jobId}/download', [App\Http\Controllers\Admin\ProductController::class, 'downloadExportFile'])->name('admin.products.export.download');
Route::post('/admin/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
Route::post('/admin/products/bulk-action', [App\Http\Controllers\Admin\ProductController::class, 'bulkAction'])->name('admin.products.bulk');
Route::post('/admin/products/export', [App\Http\Controllers\Admin\ProductController::class, 'exportProducts'])->name('admin.products.export');
Route::post('/admin/products/import', [App\Http\Controllers\Admin\ProductController::class, 'importProducts'])->name('admin.products.import');
Route::get('/admin/products/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.products.edit');
Route::get('/admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
Route::put('/admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
Route::delete('/admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');
Route::post('/admin/products/{id}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('admin.products.toggle');
Route::post('/admin/products/{id}/duplicate', [App\Http\Controllers\Admin\ProductController::class, 'duplicate'])->name('admin.products.duplicate');
Route::get('/admin/products/{id}/related/{type}', [App\Http\Controllers\Admin\ProductController::class, 'getRelatedProducts'])->name('admin.products.related.get');
Route::post('/admin/products/{id}/related', [App\Http\Controllers\Admin\ProductController::class, 'syncRelatedProducts'])->name('admin.products.related.sync');

// Product Page Builder (Sections & Downloads)
Route::get('/admin/products/{id}/builder', [App\Http\Controllers\Admin\ProductController::class, 'builder'])->name('admin.products.builder');
Route::get('/admin/products/{id}/sections', [App\Http\Controllers\Admin\ProductController::class, 'getSections'])->name('admin.products.sections.get');
Route::post('/admin/products/{id}/sections', [App\Http\Controllers\Admin\ProductController::class, 'saveSections'])->name('admin.products.sections.save');
Route::post('/admin/products/{id}/downloads', [App\Http\Controllers\Admin\ProductController::class, 'saveDownloads'])->name('admin.products.downloads.save');

// Email Campaigns
Route::get('/admin/email-campaigns', [App\Http\Controllers\Admin\EmailCampaignController::class, 'index'])->name('admin.email-campaigns.index');
Route::get('/admin/email-campaigns/list', [App\Http\Controllers\Admin\EmailCampaignController::class, 'list'])->name('admin.email-campaigns.list');
Route::post('/admin/email-campaigns', [App\Http\Controllers\Admin\EmailCampaignController::class, 'store'])->name('admin.email-campaigns.store');
Route::post('/admin/email-campaigns/bulk-action', [App\Http\Controllers\Admin\EmailCampaignController::class, 'bulkAction'])->name('admin.email-campaigns.bulk');
Route::get('/admin/email-campaigns/{id}', [App\Http\Controllers\Admin\EmailCampaignController::class, 'show'])->name('admin.email-campaigns.show');
Route::put('/admin/email-campaigns/{id}', [App\Http\Controllers\Admin\EmailCampaignController::class, 'update'])->name('admin.email-campaigns.update');
Route::delete('/admin/email-campaigns/{id}', [App\Http\Controllers\Admin\EmailCampaignController::class, 'destroy'])->name('admin.email-campaigns.destroy');
Route::post('/admin/email-campaigns/{id}/toggle-status', [App\Http\Controllers\Admin\EmailCampaignController::class, 'toggleStatus'])->name('admin.email-campaigns.toggle');
Route::post('/admin/email-campaigns/{id}/duplicate', [App\Http\Controllers\Admin\EmailCampaignController::class, 'duplicate'])->name('admin.email-campaigns.duplicate');

// Stock Management
Route::get('/admin/stock', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('admin.stock.index');
Route::get('/admin/stock/list', [App\Http\Controllers\Admin\StockController::class, 'list'])->name('admin.stock.list');
Route::get('/admin/stock/low-stock', [App\Http\Controllers\Admin\StockController::class, 'getLowStock'])->name('admin.stock.low');
Route::get('/admin/stock/low', function() { return redirect()->route('admin.stock.index', ['tab' => 'low']); })->name('admin.stock.low.page');
Route::get('/admin/stock/history/{productId}', [App\Http\Controllers\Admin\StockController::class, 'getStockHistory'])->name('admin.stock.history');
Route::post('/admin/stock', [App\Http\Controllers\Admin\StockController::class, 'store'])->name('admin.stock.store');
Route::get('/admin/stock/{id}', [App\Http\Controllers\Admin\StockController::class, 'show'])->name('admin.stock.show');
Route::post('/admin/stock/adjust', [App\Http\Controllers\Admin\StockController::class, 'adjustStock'])->name('admin.stock.adjust');

// Reports & Analytics
Route::get('/admin/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
Route::get('/admin/reports/overview', [App\Http\Controllers\Admin\ReportController::class, 'overview'])->name('admin.reports.overview');
Route::get('/admin/reports/products', [App\Http\Controllers\Admin\ReportController::class, 'products'])->name('admin.reports.products');
Route::get('/admin/reports/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('admin.reports.inventory');
Route::get('/admin/reports/reviews', [App\Http\Controllers\Admin\ReportController::class, 'reviews'])->name('admin.reports.reviews');
Route::get('/admin/reports/marketing', [App\Http\Controllers\Admin\ReportController::class, 'marketing'])->name('admin.reports.marketing');
Route::get('/admin/reports/customers', [App\Http\Controllers\Admin\ReportController::class, 'customers'])->name('admin.reports.customers');
Route::get('/admin/reports/catalog', [App\Http\Controllers\Admin\ReportController::class, 'catalog'])->name('admin.reports.catalog');
Route::get('/admin/reports/activity', [App\Http\Controllers\Admin\ReportController::class, 'activity'])->name('admin.reports.activity');

// Reviews Management
Route::get('/admin/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
Route::get('/admin/reviews/list', [App\Http\Controllers\Admin\ReviewController::class, 'list'])->name('admin.reviews.list');
Route::post('/admin/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'store'])->name('admin.reviews.store');
Route::post('/admin/reviews/bulk-action', [App\Http\Controllers\Admin\ReviewController::class, 'bulkAction'])->name('admin.reviews.bulk');
Route::get('/admin/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('admin.reviews.show');
Route::put('/admin/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'update'])->name('admin.reviews.update');
Route::delete('/admin/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
Route::post('/admin/reviews/{id}/reply', [App\Http\Controllers\Admin\ReviewController::class, 'reply'])->name('admin.reviews.reply');
Route::post('/admin/reviews/{id}/toggle-featured', [App\Http\Controllers\Admin\ReviewController::class, 'toggleFeatured'])->name('admin.reviews.featured');
Route::post('/admin/reviews/{id}/toggle-status', [App\Http\Controllers\Admin\ReviewController::class, 'toggleStatus'])->name('admin.reviews.toggle');

// Coupons Management
Route::get('/admin/coupons', [App\Http\Controllers\Admin\CouponController::class, 'index'])->name('admin.coupons.index');
Route::get('/admin/coupons/list', [App\Http\Controllers\Admin\CouponController::class, 'list'])->name('admin.coupons.list');
Route::get('/admin/coupons/generate-code', [App\Http\Controllers\Admin\CouponController::class, 'generateCode'])->name('admin.coupons.generate');
Route::post('/admin/coupons', [App\Http\Controllers\Admin\CouponController::class, 'store'])->name('admin.coupons.store');
Route::get('/admin/coupons/{id}', [App\Http\Controllers\Admin\CouponController::class, 'show'])->name('admin.coupons.show');
Route::put('/admin/coupons/{id}', [App\Http\Controllers\Admin\CouponController::class, 'update'])->name('admin.coupons.update');
Route::delete('/admin/coupons/{id}', [App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('admin.coupons.destroy');
Route::post('/admin/coupons/{id}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('admin.coupons.toggle');
Route::post('/admin/coupons/bulk-action', [App\Http\Controllers\Admin\CouponController::class, 'bulkAction'])->name('admin.coupons.bulk');

// Promotions Management
Route::get('/admin/promotions', [App\Http\Controllers\Admin\PromotionController::class, 'index'])->name('admin.promotions.index');
Route::get('/admin/promotions/list', [App\Http\Controllers\Admin\PromotionController::class, 'list'])->name('admin.promotions.list');
Route::post('/admin/promotions', [App\Http\Controllers\Admin\PromotionController::class, 'store'])->name('admin.promotions.store');
Route::get('/admin/promotions/{id}', [App\Http\Controllers\Admin\PromotionController::class, 'show'])->name('admin.promotions.show');
Route::put('/admin/promotions/{id}', [App\Http\Controllers\Admin\PromotionController::class, 'update'])->name('admin.promotions.update');
Route::delete('/admin/promotions/{id}', [App\Http\Controllers\Admin\PromotionController::class, 'destroy'])->name('admin.promotions.destroy');
Route::post('/admin/promotions/{id}/toggle-status', [App\Http\Controllers\Admin\PromotionController::class, 'toggleStatus'])->name('admin.promotions.toggle');
Route::post('/admin/promotions/bulk-action', [App\Http\Controllers\Admin\PromotionController::class, 'bulkAction'])->name('admin.promotions.bulk');

// Collections Management
Route::get('/admin/collections', [App\Http\Controllers\Admin\CollectionController::class, 'index'])->name('admin.collections.index');
Route::get('/admin/collections/list', [App\Http\Controllers\Admin\CollectionController::class, 'list'])->name('admin.collections.list');
Route::post('/admin/collections', [App\Http\Controllers\Admin\CollectionController::class, 'store'])->name('admin.collections.store');
Route::get('/admin/collections/{id}', [App\Http\Controllers\Admin\CollectionController::class, 'show'])->name('admin.collections.show');
Route::put('/admin/collections/{id}', [App\Http\Controllers\Admin\CollectionController::class, 'update'])->name('admin.collections.update');
Route::delete('/admin/collections/{id}', [App\Http\Controllers\Admin\CollectionController::class, 'destroy'])->name('admin.collections.destroy');
Route::post('/admin/collections/{id}/toggle-status', [App\Http\Controllers\Admin\CollectionController::class, 'toggleStatus'])->name('admin.collections.toggle');
Route::post('/admin/collections/bulk-action', [App\Http\Controllers\Admin\CollectionController::class, 'bulkAction'])->name('admin.collections.bulk');

// System Tools
Route::get('/admin/system-tools', [App\Http\Controllers\Admin\SystemToolsController::class, 'index'])->name('admin.system-tools.index');
Route::post('/admin/system-tools/clear-cache', [App\Http\Controllers\Admin\SystemToolsController::class, 'clearCache'])->name('admin.system-tools.clear-cache');
Route::get('/admin/system-tools/system-info', [App\Http\Controllers\Admin\SystemToolsController::class, 'systemInfo'])->name('admin.system-tools.info');
Route::get('/admin/system-tools/logs', [App\Http\Controllers\Admin\SystemToolsController::class, 'getLogs'])->name('admin.system-tools.logs');
Route::post('/admin/system-tools/clear-logs', [App\Http\Controllers\Admin\SystemToolsController::class, 'clearLogs'])->name('admin.system-tools.clear-logs');
Route::post('/admin/system-tools/shiprocket-pickup-locations', [App\Http\Controllers\Admin\SystemToolsController::class, 'shiprocketPickupLocations'])->name('admin.system-tools.shiprocket-pickup');

// AI Tools
Route::get('/admin/ai-tools', [App\Http\Controllers\Admin\AIToolsController::class, 'index'])->name('admin.ai-tools.index');
Route::post('/admin/ai-tools/test', [App\Http\Controllers\Admin\AIToolsController::class, 'testConnection'])->name('admin.ai-tools.test');
Route::post('/admin/ai-tools/generate', [App\Http\Controllers\Admin\AIToolsController::class, 'generate'])->name('admin.ai-tools.generate');
Route::get('/admin/ai-tools/usage', [App\Http\Controllers\Admin\AIToolsController::class, 'usage'])->name('admin.ai-tools.usage');

// Webhooks Management
Route::get('/admin/webhooks', [App\Http\Controllers\Admin\WebhookController::class, 'index'])->name('admin.webhooks.index');
Route::get('/admin/webhooks/list', [App\Http\Controllers\Admin\WebhookController::class, 'list'])->name('admin.webhooks.list');
Route::get('/admin/webhooks/events', [App\Http\Controllers\Admin\WebhookController::class, 'getEvents'])->name('admin.webhooks.events');
Route::post('/admin/webhooks', [App\Http\Controllers\Admin\WebhookController::class, 'store'])->name('admin.webhooks.store');
Route::get('/admin/webhooks/{id}', [App\Http\Controllers\Admin\WebhookController::class, 'show'])->name('admin.webhooks.show');
Route::put('/admin/webhooks/{id}', [App\Http\Controllers\Admin\WebhookController::class, 'update'])->name('admin.webhooks.update');
Route::delete('/admin/webhooks/{id}', [App\Http\Controllers\Admin\WebhookController::class, 'destroy'])->name('admin.webhooks.destroy');
Route::post('/admin/webhooks/{id}/toggle', [App\Http\Controllers\Admin\WebhookController::class, 'toggleStatus'])->name('admin.webhooks.toggle');
Route::post('/admin/webhooks/{id}/test', [App\Http\Controllers\Admin\WebhookController::class, 'test'])->name('admin.webhooks.test');

// Integrations
Route::get('/admin/integrations', function() { return view('admin.pages.integrations.index'); })->name('admin.integrations.index');

// Localization
Route::get('/admin/localization', function() { return view('admin.pages.localization.index'); })->name('admin.localization.index');

// Customers Management
Route::get('/admin/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customers.index');
Route::get('/admin/customers/list', [App\Http\Controllers\Admin\CustomerController::class, 'list'])->name('admin.customers.list');
Route::post('/admin/customers', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('admin.customers.store');
Route::get('/admin/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('admin.customers.show');
Route::put('/admin/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('admin.customers.update');
Route::delete('/admin/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('admin.customers.destroy');
Route::post('/admin/customers/{id}/toggle-status', [App\Http\Controllers\Admin\CustomerController::class, 'toggleStatus'])->name('admin.customers.toggle');
Route::post('/admin/customers/bulk-action', [App\Http\Controllers\Admin\CustomerController::class, 'bulkAction'])->name('admin.customers.bulk');

// Warehouses Management
Route::get('/admin/warehouses', [App\Http\Controllers\Admin\WarehouseController::class, 'index'])->name('admin.warehouses.index');
Route::get('/admin/warehouses/list', [App\Http\Controllers\Admin\WarehouseController::class, 'list'])->name('admin.warehouses.list');
Route::post('/admin/warehouses', [App\Http\Controllers\Admin\WarehouseController::class, 'store'])->name('admin.warehouses.store');
Route::get('/admin/warehouses/{id}', [App\Http\Controllers\Admin\WarehouseController::class, 'show'])->name('admin.warehouses.show');
Route::put('/admin/warehouses/{id}', [App\Http\Controllers\Admin\WarehouseController::class, 'update'])->name('admin.warehouses.update');
Route::delete('/admin/warehouses/{id}', [App\Http\Controllers\Admin\WarehouseController::class, 'destroy'])->name('admin.warehouses.destroy');
Route::post('/admin/warehouses/{id}/toggle-status', [App\Http\Controllers\Admin\WarehouseController::class, 'toggleStatus'])->name('admin.warehouses.toggle');
Route::post('/admin/warehouses/{id}/set-default', [App\Http\Controllers\Admin\WarehouseController::class, 'setDefault'])->name('admin.warehouses.default');
Route::post('/admin/warehouses/bulk-action', [App\Http\Controllers\Admin\WarehouseController::class, 'bulkAction'])->name('admin.warehouses.bulk');

// Shiprocket
Route::get('/admin/shiprocket/orders/{orderId}/couriers', [App\Http\Controllers\Admin\ShiprocketController::class, 'getCouriers'])->name('admin.shiprocket.couriers');
Route::post('/admin/shiprocket/orders/{orderId}/create', [App\Http\Controllers\Admin\ShiprocketController::class, 'createOrder'])->name('admin.shiprocket.create');
Route::post('/admin/shiprocket/orders/{orderId}/sync', [App\Http\Controllers\Admin\ShiprocketController::class, 'syncStatus'])->name('admin.shiprocket.sync');
Route::get('/admin/shiprocket/track', [App\Http\Controllers\Admin\ShiprocketController::class, 'track'])->name('admin.shiprocket.track');
Route::post('/admin/shiprocket/orders/{orderId}/cancel', [App\Http\Controllers\Admin\ShiprocketController::class, 'cancelOrder'])->name('admin.shiprocket.cancel');

// Orders Management
Route::get('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
Route::get('/admin/orders/list', [App\Http\Controllers\Admin\OrderController::class, 'list'])->name('admin.orders.list');
Route::get('/admin/orders/stats', [App\Http\Controllers\Admin\OrderController::class, 'stats'])->name('admin.orders.stats');
Route::get('/admin/orders/search-products', [App\Http\Controllers\Admin\OrderController::class, 'searchProducts'])->name('admin.orders.search-products');
Route::post('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'store'])->name('admin.orders.store');
Route::get('/admin/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
Route::put('/admin/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('admin.orders.update');
Route::delete('/admin/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
Route::post('/admin/orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.status');

// Order Tracking
Route::get('/admin/order-tracking', [App\Http\Controllers\Admin\OrderTrackingController::class, 'index'])->name('admin.order-tracking.index');
Route::get('/admin/order-tracking/search', [App\Http\Controllers\Admin\OrderTrackingController::class, 'track'])->name('admin.order-tracking.search');
Route::get('/admin/order-tracking/recent', [App\Http\Controllers\Admin\OrderTrackingController::class, 'recent'])->name('admin.order-tracking.recent');

// Returns & Refunds Management
Route::get('/admin/returns', [App\Http\Controllers\Admin\ReturnController::class, 'index'])->name('admin.returns.index');
Route::get('/admin/returns/list', [App\Http\Controllers\Admin\ReturnController::class, 'list'])->name('admin.returns.list');
Route::get('/admin/returns/stats', [App\Http\Controllers\Admin\ReturnController::class, 'stats'])->name('admin.returns.stats');
Route::post('/admin/returns', [App\Http\Controllers\Admin\ReturnController::class, 'store'])->name('admin.returns.store');
Route::get('/admin/returns/{id}', [App\Http\Controllers\Admin\ReturnController::class, 'show'])->name('admin.returns.show');
Route::put('/admin/returns/{id}', [App\Http\Controllers\Admin\ReturnController::class, 'update'])->name('admin.returns.update');
Route::delete('/admin/returns/{id}', [App\Http\Controllers\Admin\ReturnController::class, 'destroy'])->name('admin.returns.destroy');

// Invoices Management
Route::get('/admin/invoices', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('admin.invoices.index');
Route::get('/admin/invoices/list', [App\Http\Controllers\Admin\InvoiceController::class, 'list'])->name('admin.invoices.list');
Route::get('/admin/invoices/stats', [App\Http\Controllers\Admin\InvoiceController::class, 'stats'])->name('admin.invoices.stats');
Route::post('/admin/invoices', [App\Http\Controllers\Admin\InvoiceController::class, 'store'])->name('admin.invoices.store');
Route::get('/admin/invoices/{id}/print', [App\Http\Controllers\Admin\InvoiceController::class, 'print'])->name('admin.invoices.print');
Route::post('/admin/invoices/from-order/{orderId}', [App\Http\Controllers\Admin\InvoiceController::class, 'generateFromOrder'])->name('admin.invoices.from-order');
Route::get('/admin/invoices/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('admin.invoices.show');
Route::put('/admin/invoices/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'update'])->name('admin.invoices.update');
Route::delete('/admin/invoices/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'destroy'])->name('admin.invoices.destroy');

// SMTP Test
Route::post('/admin/smtp/test', [App\Http\Controllers\Admin\SettingController::class, 'testSmtp'])->name('admin.smtp.test');
// Brevo
Route::post('/admin/brevo/save',           [App\Http\Controllers\Admin\BrevoController::class, 'save'])->name('admin.brevo.save');
Route::post('/admin/brevo/test',           [App\Http\Controllers\Admin\BrevoController::class, 'test'])->name('admin.brevo.test');
Route::get('/admin/brevo/account',         [App\Http\Controllers\Admin\BrevoController::class, 'account'])->name('admin.brevo.account');
Route::get('/admin/brevo/lists',           [App\Http\Controllers\Admin\BrevoController::class, 'lists'])->name('admin.brevo.lists');
Route::get('/admin/brevo/stats',           [App\Http\Controllers\Admin\BrevoController::class, 'stats'])->name('admin.brevo.stats');
Route::post('/admin/brevo/disconnect',     [App\Http\Controllers\Admin\BrevoController::class, 'disconnect'])->name('admin.brevo.disconnect');
// Brevo Templates
Route::get('/admin/brevo/seed-templates',         [App\Http\Controllers\Admin\BrevoController::class, 'seedTemplates'])->name('admin.brevo.seed-templates');
Route::get('/admin/brevo/templates',              [App\Http\Controllers\Admin\BrevoController::class, 'getTemplates'])->name('admin.brevo.templates');
Route::post('/admin/brevo/templates',             [App\Http\Controllers\Admin\BrevoController::class, 'createTemplate'])->name('admin.brevo.templates.create');
Route::get('/admin/brevo/templates/{id}',         [App\Http\Controllers\Admin\BrevoController::class, 'getTemplate'])->name('admin.brevo.templates.show');
Route::put('/admin/brevo/templates/{id}',         [App\Http\Controllers\Admin\BrevoController::class, 'updateTemplate'])->name('admin.brevo.templates.update');
Route::delete('/admin/brevo/templates/{id}',      [App\Http\Controllers\Admin\BrevoController::class, 'deleteTemplate'])->name('admin.brevo.templates.delete');
Route::post('/admin/brevo/templates/{id}/test',   [App\Http\Controllers\Admin\BrevoController::class, 'sendTestTemplate'])->name('admin.brevo.templates.test');
Route::get('/admin/brevo/senders',         [App\Http\Controllers\Admin\BrevoController::class, 'getSenders'])->name('admin.brevo.senders');
Route::post('/admin/brevo/senders',        [App\Http\Controllers\Admin\BrevoController::class, 'createSender'])->name('admin.brevo.senders.create');
Route::put('/admin/brevo/senders/{id}',    [App\Http\Controllers\Admin\BrevoController::class, 'updateSender'])->name('admin.brevo.senders.update');
Route::delete('/admin/brevo/senders/{id}', [App\Http\Controllers\Admin\BrevoController::class, 'deleteSender'])->name('admin.brevo.senders.delete');
Route::post('/admin/brevo/senders/{id}/verify', [App\Http\Controllers\Admin\BrevoController::class, 'validateSenderOTP'])->name('admin.brevo.senders.verify');

// Media Settings
Route::get('/admin/settings/media', [App\Http\Controllers\Admin\MediaSettingsController::class, 'index'])->name('admin.settings.media');
Route::post('/admin/settings/media/global', [App\Http\Controllers\Admin\MediaSettingsController::class, 'updateGlobal'])->name('admin.settings.media.global');
Route::put('/admin/settings/media/{id}', [App\Http\Controllers\Admin\MediaSettingsController::class, 'updateType'])->name('admin.settings.media.type.update');
Route::post('/admin/settings/media/{id}/reset', [App\Http\Controllers\Admin\MediaSettingsController::class, 'resetType'])->name('admin.settings.media.type.reset');

// Settings
Route::get('/admin/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
Route::post('/admin/settings/{group}', [App\Http\Controllers\Admin\SettingController::class, 'save'])->name('admin.settings.save');
Route::get('/admin/settings/{group}', [App\Http\Controllers\Admin\SettingController::class, 'get'])->name('admin.settings.get');

// Notifications
Route::get('/admin/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications');

// Media Upload Routes
Route::post('/api/upload', [App\Http\Controllers\UploadController::class, 'upload'])->name('upload.media');
Route::delete('/api/upload/delete', [App\Http\Controllers\UploadController::class, 'delete'])->name('upload.media.delete');
Route::get('/api/media-usage', [App\Http\Controllers\UploadController::class, 'usage'])->name('upload.media.usage');
Route::get('/api/media-files', [App\Http\Controllers\UploadController::class, 'listFiles'])->name('upload.media.files');
Route::post('/api/media-folder', [App\Http\Controllers\UploadController::class, 'createFolder'])->name('upload.media.folder.create');
Route::delete('/api/media-folder', [App\Http\Controllers\UploadController::class, 'deleteFolder'])->name('upload.media.folder.delete');

}); // end admin.auth middleware group

// ── Customer Panel Routes ──────────────────────────────────────
Route::middleware(['customer.panel', 'detect.device'])->prefix('account')->name('customer.')->group(function () {
    Route::get('/', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{orderNumber}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderNumber}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{orderNumber}/return', [App\Http\Controllers\Customer\OrderController::class, 'returnOrder'])->name('orders.return');
    Route::get('/orders/{orderNumber}/track', [App\Http\Controllers\Customer\OrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/{orderNumber}/invoice', [App\Http\Controllers\Customer\OrderController::class, 'invoice'])->name('orders.invoice');

    // Wishlist
    Route::get('/wishlist', [App\Http\Controllers\Customer\WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [App\Http\Controllers\Customer\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/move-to-cart/{id}', [App\Http\Controllers\Customer\WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\Customer\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Addresses
    Route::get('/addresses', [App\Http\Controllers\Customer\AddressController::class, 'index'])->name('addresses');
    Route::post('/addresses', [App\Http\Controllers\Customer\AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{id}', [App\Http\Controllers\Customer\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [App\Http\Controllers\Customer\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{id}/default', [App\Http\Controllers\Customer\AddressController::class, 'setDefault'])->name('addresses.default');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [App\Http\Controllers\Customer\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/password', [App\Http\Controllers\Customer\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Payments
    Route::get('/payments', [App\Http\Controllers\Customer\PaymentController::class, 'index'])->name('payments');
    Route::post('/payments', [App\Http\Controllers\Customer\PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{id}', [App\Http\Controllers\Customer\PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/payments/{id}/default', [App\Http\Controllers\Customer\PaymentController::class, 'setDefault'])->name('payments.default');

    // Coupons
    Route::get('/coupons', [App\Http\Controllers\Customer\CouponController::class, 'index'])->name('coupons');

    // Rewards
    Route::get('/rewards', [App\Http\Controllers\Customer\RewardController::class, 'index'])->name('rewards');
    Route::post('/rewards/redeem', [App\Http\Controllers\Customer\RewardController::class, 'redeem'])->name('rewards.redeem');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Customer\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\Customer\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [App\Http\Controllers\Customer\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // Recently Viewed
    Route::get('/recently-viewed', [App\Http\Controllers\Customer\RecentlyViewedController::class, 'index'])->name('recently-viewed');

    // Support
    Route::get('/support', [App\Http\Controllers\Customer\SupportController::class, 'index'])->name('support');
    Route::post('/support', [App\Http\Controllers\Customer\SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{ticketNumber}', [App\Http\Controllers\Customer\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticketNumber}/reply', [App\Http\Controllers\Customer\SupportController::class, 'reply'])->name('support.reply');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Customer\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Customer\SettingsController::class, 'update'])->name('settings.update');

    // AJAX API endpoints
    Route::post('/recently-viewed/track', [App\Http\Controllers\Customer\RecentlyViewedController::class, 'track'])->name('recently-viewed.track');
});
