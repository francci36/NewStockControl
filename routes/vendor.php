<?php
// routes/vendor.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\RegistrationController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Models\Category;

// ===========================================
// ROUTES VENDEUR (inscription et dashboard)
// ===========================================

// Routes d'inscription (accessibles à tous les utilisateurs connectés)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/vendor/register', [RegistrationController::class, 'create'])->name('vendor.register');
    Route::post('/vendor/register', [RegistrationController::class, 'store'])->name('vendor.register.store');
    Route::get('/vendor/pending', [RegistrationController::class, 'pending'])->name('vendor.pending');
});

// Route AJAX pour création rapide de catégorie (accessible aux vendeurs connectés)
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::post('/categories/quick-create', [CategoryController::class, 'quickCreate'])->name('categories.quick-create');
});

// Routes protégées pour les vendeurs approuvés
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    
    // ===========================================
    // DASHBOARD
    // ===========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ===========================================
    // PRODUITS (CRUD complet)
    // ===========================================
    Route::resource('products', ProductController::class);
    
    // ===========================================
    // COMMANDES
    // ===========================================
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/update-shipping', [OrderController::class, 'updateShipping'])->name('update-shipping');
    });
    
    // ===========================================
    // CATÉGORIES (sous-catégories pour les vendeurs)
    // ===========================================
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
    
    // ===========================================
    // PARAMÈTRES
    // ===========================================
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::patch('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');

    // ===========================================
    // API INTERNE (routes AJAX pour les vendeurs)
    // ===========================================
    Route::prefix('api')->name('api.')->group(function () {
        
        // Route pour charger les sous-catégories d'une catégorie principale
        Route::get('/categories/{mainCategory}/subcategories', function($mainCategory) {
            $subcategories = Category::where('parent_id', $mainCategory)
                ->where('is_active', true)
                ->get()
                ->map(function($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->translated_name, // Traduit selon la langue
                    ];
                });
            
            return response()->json($subcategories);
        })->name('categories.subcategories');
        
        // Vous pouvez ajouter d'autres routes API ici
        // Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    });
});