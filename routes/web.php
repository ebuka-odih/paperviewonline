<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['splade'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/product/{product:slug}', [HomeController::class, 'show'])->name('product.show');
    Route::get('/docs', fn () => view('docs'))->name('docs');
    
    // Coming soon password verification
    Route::post('/coming-soon/verify', [HomeController::class, 'verifyComingSoonPassword'])->name('coming-soon.verify');

    // Registers routes to support the interactive components...
    Route::spladeWithVueBridge();

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();

    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [CartController::class, 'view'])->name('cart.index');
    Route::get('/cart/data', [CartController::class, 'getCart'])->name('cart.get');
    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/sidebar', [CartController::class, 'sidebar'])->name('cart.sidebar');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Payment routes
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{order}/initialize', [PaymentController::class, 'initialize'])->name('payment.initialize');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('order.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('order.failed');
});

// Payment webhook (outside splade middleware)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
