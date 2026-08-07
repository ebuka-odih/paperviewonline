<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ComingSoonController;
use App\Http\Controllers\Admin\SettingsController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Products
    // Image + variant endpoints are declared before the resource so that their
    // static segments are not swallowed by `products/{product}`.
    Route::post('products/{product}/images', [ProductController::class, 'storeImages'])->name('products.images.store');
    Route::post('products/{product}/images/reorder', [ProductController::class, 'reorderImages'])->name('products.images.reorder');
    Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
    Route::post('products/images/{image}/featured', [ProductController::class, 'setFeaturedImage'])->name('products.images.featured');
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::resource('products', ProductController::class)->except(['show']);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Orders
    // As above: `orders/analytics`, `orders/export` and `orders/bulk-update` must
    // be registered before the resource, otherwise `orders/{order}` matches them
    // first and model binding 404s on "analytics"/"export".
    Route::get('orders/analytics', [OrderController::class, 'analytics'])->name('orders.analytics');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::post('orders/bulk-update', [OrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::resource('orders', OrderController::class);
    
    // Frontpage lock (setting keys and route names stay `coming-soon` for
    // compatibility with the rows already in the settings table).
    Route::get('coming-soon', [ComingSoonController::class, 'index'])->name('coming-soon.index');
    Route::put('coming-soon', [ComingSoonController::class, 'update'])->name('coming-soon.update');
    Route::post('coming-soon/toggle', [ComingSoonController::class, 'toggle'])->name('coming-soon.toggle');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
});
