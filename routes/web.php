<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ActivityLogController;

Route::get('/', [HomeController::class, 'index'])->name('home');

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

// Admin Dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Admin Users Management
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

// Categories Management
Route::get('/admin/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
Route::get('/admin/categories/list', [App\Http\Controllers\Admin\CategoryController::class, 'list'])->name('admin.categories.list');
Route::post('/admin/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
Route::get('/admin/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.categories.show');
Route::put('/admin/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
Route::delete('/admin/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
Route::post('/admin/categories/{id}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle');
Route::post('/admin/categories/bulk-action', [App\Http\Controllers\Admin\CategoryController::class, 'bulkAction'])->name('admin.categories.bulk');

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
Route::get('/admin/products/list', [App\Http\Controllers\Admin\ProductController::class, 'list'])->name('admin.products.list');
Route::get('/admin/products/variant-attributes', [App\Http\Controllers\Admin\ProductController::class, 'getVariantAttributes'])->name('admin.products.variant-attributes');
Route::get('/admin/products/import/template', [App\Http\Controllers\Admin\ProductController::class, 'getImportTemplate'])->name('admin.products.import.template');
Route::get('/admin/products/import-export/jobs', [App\Http\Controllers\Admin\ProductController::class, 'getImportExportJobs'])->name('admin.products.import-export.jobs');
Route::get('/admin/products/export/{jobId}/download', [App\Http\Controllers\Admin\ProductController::class, 'downloadExportFile'])->name('admin.products.export.download');
Route::post('/admin/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
Route::post('/admin/products/bulk-action', [App\Http\Controllers\Admin\ProductController::class, 'bulkAction'])->name('admin.products.bulk');
Route::post('/admin/products/export', [App\Http\Controllers\Admin\ProductController::class, 'exportProducts'])->name('admin.products.export');
Route::post('/admin/products/import', [App\Http\Controllers\Admin\ProductController::class, 'importProducts'])->name('admin.products.import');
Route::get('/admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
Route::put('/admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
Route::delete('/admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');
Route::post('/admin/products/{id}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('admin.products.toggle');
Route::post('/admin/products/{id}/duplicate', [App\Http\Controllers\Admin\ProductController::class, 'duplicate'])->name('admin.products.duplicate');
Route::get('/admin/products/{id}/related/{type}', [App\Http\Controllers\Admin\ProductController::class, 'getRelatedProducts'])->name('admin.products.related.get');
Route::post('/admin/products/{id}/related', [App\Http\Controllers\Admin\ProductController::class, 'syncRelatedProducts'])->name('admin.products.related.sync');

// Collections Management
Route::get('/admin/collections', [App\Http\Controllers\Admin\CollectionController::class, 'index'])->name('admin.collections.index');
Route::get('/admin/collections/list', [App\Http\Controllers\Admin\CollectionController::class, 'list'])->name('admin.collections.list');
Route::post('/admin/collections', [App\Http\Controllers\Admin\CollectionController::class, 'store'])->name('admin.collections.store');
Route::get('/admin/collections/{id}', [App\Http\Controllers\Admin\CollectionController::class, 'show'])->name('admin.collections.show');
Route::put('/admin/collections/{id}', [App\Http\Controllers\Admin\CollectionController::class, 'update'])->name('admin.collections.update');
Route::delete('/admin/collections/{id}', [App\Http\Controllers\Admin\CollectionController::class, 'destroy'])->name('admin.collections.destroy');
Route::post('/admin/collections/{id}/toggle-status', [App\Http\Controllers\Admin\CollectionController::class, 'toggleStatus'])->name('admin.collections.toggle');
Route::post('/admin/collections/bulk-action', [App\Http\Controllers\Admin\CollectionController::class, 'bulkAction'])->name('admin.collections.bulk');

// ImageKit Integration
Route::get('/api/imagekit-auth', [App\Http\Controllers\ImageKitController::class, 'auth'])->name('imagekit.auth');
Route::post('/api/imagekit-upload', [App\Http\Controllers\ImageKitController::class, 'upload'])->name('imagekit.upload');
Route::delete('/api/imagekit-delete', [App\Http\Controllers\ImageKitController::class, 'delete'])->name('imagekit.delete');
