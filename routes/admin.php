<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ComingSoonController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/images', [ProductController::class, 'storeImages'])->name('products.images.store');
    Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::post('orders/bulk-update', [OrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders/analytics', [OrderController::class, 'analytics'])->name('orders.analytics');
    
    // Coming Soon
    Route::get('coming-soon', [ComingSoonController::class, 'index'])->name('coming-soon.index');
    Route::put('coming-soon', [ComingSoonController::class, 'update'])->name('coming-soon.update');
    Route::post('coming-soon/toggle', [ComingSoonController::class, 'toggle'])->name('coming-soon.toggle');
});
