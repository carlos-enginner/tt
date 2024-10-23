<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Checkout\CheckoutController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Webhook\WebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('welcome');
});

// checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('tasks.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/status', [CheckoutController::class, 'status'])->name('checkout.status');

Route::withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::post('/webhook', [WebhookController::class, 'handle']);

    // service purchase
    Route::post('/purchase/orders', [PurchaseController::class, 'newOrder'])->name('purchase.newOrder');
    Route::get('/purchase/orders/{id}', [PurchaseController::class, 'getOrder'])->name('purchase.getOrder');

   // service payments
    Route::post('/payment/orders', [PurchaseController::class, 'newOrder'])->name('purchase.newOrder');
});