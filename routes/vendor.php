<?php
// routes/vendor.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\RegistrationController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Api\VendorWebhookController; // Notez le chemin Api\

// ===========================================
// ROUTES WEBHOOKS (PUBLIQUES - PAS DE MIDDLEWARE AUTH)
// ===========================================
Route::post('/api/webhooks/vendor/shipping', [VendorWebhookController::class, 'handleShipping'])
    ->name('webhooks.vendor.shipping');

Route::post('/api/webhooks/vendor/delivery', [VendorWebhookController::class, 'handleDelivery'])
    ->name('webhooks.vendor.delivery');

// Route de test pour vérifier que les webhooks fonctionnent
Route::get('/api/webhooks/test', function() {
    return response()->json([
        'message' => 'Webhook API is working',
        'timestamp' => now()->toIso8601String(),
        'routes' => [
            'shipping' => route('webhooks.vendor.shipping', [], false),
            'delivery' => route('webhooks.vendor.delivery', [], false)
        ]
    ]);
})->name('webhooks.test');

// ===========================================
// ROUTES VENDEUR (inscription et dashboard)
// ===========================================

// Routes d'inscription (accessibles à tous les utilisateurs connectés)
Route::middleware(['auth'])->group(function () {
    
    // Inscription vendeur
    Route::get('/vendor/register', [RegistrationController::class, 'create'])->name('vendor.register');
    Route::post('/vendor/register', [RegistrationController::class, 'store'])->name('vendor.register.store');
    Route::get('/vendor/pending', [RegistrationController::class, 'pending'])->name('vendor.pending');
});

// Routes protégées pour les vendeurs approuvés
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des produits
    Route::resource('products', ProductController::class);
    
    // Commandes reçues
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Profil boutique
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::patch('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
});