<?php
// routes/shop.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\HomeController as ShopHomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\CartController as ShopCartController;
use App\Http\Controllers\Shop\CheckoutController as ShopCheckoutController;

// ===========================================
// ROUTES DE LA BOUTIQUE
// ===========================================

// Page d'accueil de la boutique (racine du site)
Route::get('/', [ShopHomeController::class, 'index'])->name('shop.home');

// Routes préfixées par /shop
Route::prefix('shop')->name('shop.')->group(function () {
    
    // Page d'accueil de la boutique (alias)
    Route::get('/', [ShopHomeController::class, 'index'])->name('home');
    
    // Produits
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ShopProductController::class, 'index'])->name('index');
        Route::get('/{product}', [ShopProductController::class, 'show'])->name('show');
    });
    
    // Panier (accessible à tous)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [ShopCartController::class, 'index'])->name('index');
        Route::post('/add', [ShopCartController::class, 'add'])->name('add');
        Route::patch('/{cart}', [ShopCartController::class, 'update'])->name('update');
        Route::delete('/{cart}', [ShopCartController::class, 'remove'])->name('remove');
        Route::delete('/', [ShopCartController::class, 'clear'])->name('clear');
    });
    
    // Checkout (nécessite un compte)
    Route::middleware(['auth'])->prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [ShopCheckoutController::class, 'index'])->name('index');
        Route::post('/', [ShopCheckoutController::class, 'process'])->name('process');
        Route::get('/confirmation/{order}', [ShopCheckoutController::class, 'confirmation'])->name('confirmation');
        Route::post('/create-payment-intent', [ShopCheckoutController::class, 'createPaymentIntent'])->name('create-payment-intent');
    });
});