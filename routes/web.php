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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Admin\VendorController; // ← AJOUTER CET IMPORT

// ===========================================
// CHARGEMENT DES ROUTES DE LA BOUTIQUE ET VENDEUR
// ===========================================
require __DIR__.'/shop.php';
require __DIR__.'/vendor.php';   

// ===========================================
// ROUTES D'AUTHENTIFICATION (communes)
// ===========================================
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

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

    // categories
       Route::prefix('categories')->name('categories.')->group(function () {
        Route::post('/quick-create', [CategoryController::class, 'quickCreate'])->name('quick-create');
        // Vous pouvez ajouter d'autres routes pour les catégories ici plus tard
    });
    
    
    // Fournisseurs
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::post('/store-with-products', [SupplierController::class, 'storeWithProducts'])->name('storeWithProducts');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::patch('/{supplier}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
    });
    
    // Commandes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create/{supplier_id?}', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
    });
    
    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
    });
    
    // Rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
    });
    
    // ===========================================
    // ROUTES ADMIN POUR LES VENDEURS (AJOUTÉES ICI)
    // ===========================================
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
        Route::post('/{vendor}/approve', [VendorController::class, 'approve'])->name('approve');
        Route::post('/{vendor}/reject', [VendorController::class, 'reject'])->name('reject');
        Route::post('/{vendor}/suspend', [VendorController::class, 'suspend'])->name('suspend');
        Route::post('/{vendor}/commission', [VendorController::class, 'updateCommission'])->name('commission');
    });
    
    // API interne
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/products/{supplier}', [OrderController::class, 'getProductsBySupplier'])->name('products.by-supplier');
        Route::get('/stock-data', [DashboardController::class, 'getStockData'])->name('stock.data');
        Route::get('/transactions-data', [DashboardController::class, 'transactionsData'])->name('transactions.data');
        Route::get('/api/categories', function() {
                    return App\Models\Category::where('is_active', true)->get(['id', 'name']);
                })->middleware('auth');
                
    });
    
    // Profile admin
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
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
            return 'Test route - à configurer';
        });
    });
}