<?php
// routes/api-webhooks.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendorWebhookController;
use App\Http\Controllers\Webhook\DeliveryController;

// ===========================================
// WEBHOOKS VENDEURS (API)
// ===========================================
Route::prefix('api/webhooks/vendor')->name('webhooks.vendor.')->group(function () {
    
    Route::post('/shipping', [VendorWebhookController::class, 'handleShipping'])
        ->name('shipping');
    
    Route::post('/delivery', [VendorWebhookController::class, 'handleDelivery'])
        ->name('delivery');
    
    Route::get('/test', function() {
        return response()->json([
            'message' => 'Vendor Webhook API is working',
            'timestamp' => now()->toIso8601String(),
            'routes' => [
                'shipping' => route('webhooks.vendor.shipping', [], false),
                'delivery' => route('webhooks.vendor.delivery', [], false)
            ]
        ]);
    })->name('test');
});

// ===========================================
// WEBHOOKS TRANSPORTEURS
// ===========================================
Route::prefix('webhook')->name('webhook.')->group(function () {
    
    Route::post('/delivery/{provider}', [DeliveryController::class, 'handle'])
        ->name('delivery');
    
    Route::post('/colissimo', [DeliveryController::class, 'handleColissimo'])
        ->name('colissimo');
    
    Route::post('/ups', [DeliveryController::class, 'handleUps'])
        ->name('ups');
    
    Route::post('/dhl', [DeliveryController::class, 'handleDhl'])
        ->name('dhl');
    
    Route::get('/test', function() {
        return response()->json([
            'message' => 'Carrier Webhook API is working',
            'timestamp' => now()->toIso8601String(),
            'endpoints' => [
                'delivery' => route('webhook.delivery', ['provider' => 'colissimo'], false),
                'colissimo' => route('webhook.colissimo', [], false),
                'ups' => route('webhook.ups', [], false),
                'dhl' => route('webhook.dhl', [], false),
            ]
        ]);
    })->name('test');
});