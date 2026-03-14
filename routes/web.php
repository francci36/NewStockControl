<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Contrôleurs principaux (backend)
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;

// NOUVEAUX CONTROLLERS ADMIN
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// ===========================================
// ROUTE POUR CHANGER LA LANGUE (AJOUTÉE ICI)
// ===========================================
Route::get('/language/{locale}', function ($locale) {
    if (!in_array($locale, ['fr', 'en', 'pt_BR', 'es', 'it', 'de'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return redirect()->back();
})->name('language.switch');

// ===========================================
// CHARGEMENT DES ROUTES DE LA BOUTIQUE ET VENDEUR
// ===========================================
require __DIR__.'/shop.php';
// ===========================================
// CHARGEMENT DES ROUTES VENDEUR
require __DIR__.'/vendor.php';   
// ===========================================
// CHARGEMENT DES ROUTES D'API POUR LES WEBHOOKS
require __DIR__.'/api-webhooks.php';

// ===========================================
// ROUTES D'AUTHENTIFICATION (communes)
// ===========================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // Register
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    // Password Reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Logout
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


// ===========================================
// ROUTES CLIENTS (utilisateurs connectés)
// ===========================================
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    
    // Historique des commandes
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/tracking', [ClientOrderController::class, 'tracking'])->name('orders.tracking');
    
    // Profil client
    Route::get('/profile', [ClientProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ClientProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ClientProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Optionnel : si vous voulez aussi gérer la photo de profil
    Route::post('/profile/photo', [ClientProfileController::class, 'updatePhoto'])->name('profile.photo');
});

// ===========================================
// ROUTES BACKEND (protégées, réservées aux admins)
// ===========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/dashboard/transactions-data', [DashboardController::class, 'getTransactionsData'])->name('dashboard.transactions-data');
    Route::get('/dashboard/stock-data', [DashboardController::class, 'getStockData'])->name('dashboard.stock-data');
    
    // Stocks
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::put('/{stock}', [StockController::class, 'update'])->name('update');
        Route::post('/sync', [StockController::class, 'sync'])->name('sync');
    });
    
    // Produits
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create/{supplier_id?}', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Catégories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/quick-create', [CategoryController::class, 'quickCreate'])->name('quick-create');
    });
    
    // Fournisseurs
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::post('/store-with-products', [SupplierController::class, 'storeWithProducts'])->name('storeWithProducts');
        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('show'); 
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::match(['PUT', 'PATCH'], '/{supplier}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
    });
    
    // ===========================================
    // COMMANDES ADMIN (avec contrôleur dédié)
    // ===========================================
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/create/{supplier_id?}', [AdminOrderController::class, 'create'])->name('create');
        Route::post('/', [AdminOrderController::class, 'store'])->name('store');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [AdminOrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [AdminOrderController::class, 'update'])->name('update');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{order}', [AdminOrderController::class, 'destroy'])->name('destroy');
    });
    
    // ===========================================
    // TRANSACTIONS
    // ===========================================
    Route::prefix('transactions')->name('transactions.')->group(function () {
        // Routes principales
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        
        // Routes AJAX / API
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::get('/export/csv', [TransactionController::class, 'export'])->name('export');
        
        // Note: Pas de routes edit/update/destroy car les transactions sont immuables
        // (on ne peut pas modifier une transaction, on peut seulement en créer de nouvelles)
    });
    
    // Rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::post('/generate', [RapportController::class, 'generate'])->name('generate');
        Route::get('/{report}/export', [RapportController::class, 'export'])->name('export');
        Route::get('/refresh-data', [RapportController::class, 'refreshData'])->name('refresh');
    });
    
    // ===========================================
    // ROUTES ADMIN POUR LES VENDEURS
    // ===========================================
    Route::prefix('vendors')->name('vendors.')->group(function () {
        // Routes GET (affichage)
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
        
        // Routes de mise à jour (PATCH pour les modifications partielles)
        Route::patch('/{vendor}/approve', [VendorController::class, 'approve'])->name('approve');
        Route::patch('/{vendor}/reject', [VendorController::class, 'reject'])->name('reject');
        Route::patch('/{vendor}/suspend', [VendorController::class, 'suspend'])->name('suspend');
        
        // Commission reste en POST car c'est une mise à jour spécifique
        Route::post('/{vendor}/commission', [VendorController::class, 'updateCommission'])->name('commission');
    });
    
    // ===========================================
    // API INTERNE
    // ===========================================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/products/{supplier}', [OrderController::class, 'getProductsBySupplier'])->name('products.by-supplier');
        Route::get('/stock-data', [DashboardController::class, 'getStockData'])->name('stock.data');
        Route::get('/transactions-data', [DashboardController::class, 'transactionsData'])->name('transactions.data');
        Route::get('/categories', function() {
            return App\Models\Category::where('is_active', true)->get(['id', 'name']);
        })->middleware('auth')->name('categories.list');
    });
    
    // Profile admin
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Finance
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/transactions', [FinanceController::class, 'transactions'])->name('transactions');
        Route::get('/commissions', [FinanceController::class, 'commissions'])->name('commissions');
        Route::post('/commissions/pay', [FinanceController::class, 'payCommissions'])->name('commissions.pay');
        Route::get('/export', [FinanceController::class, 'export'])->name('export');
    });
});

// ===========================================
// ROUTES POUR LES NOTIFICATIONS
// ===========================================
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::post('/read-all', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    })->name('read-all');
    
    Route::post('/{id}/read', function($id) {
        auth()->user()->notifications()->where('id', $id)->first()->markAsRead();
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    })->name('read');
});

// ===========================================
// ROUTES DE TEST (admin uniquement)
// ===========================================
if (app()->environment('local')) {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/list-tables', function () {
            $tables = DB::select('SHOW TABLES');
            return response()->json($tables);
        });
        
        Route::get('/test-update-stock/{orderId}', function ($orderId) {
            $order = \App\Models\Order::findOrFail($orderId);
            return response()->json([
                'message' => 'Route de test - à configurer',
                'order' => $order
            ]);
        });
    });
}