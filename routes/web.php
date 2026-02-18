<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Contrôleurs principaux (backend)
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

// Contrôleurs de la boutique (avec alias)
use App\Http\Controllers\Shop\HomeController as ShopHomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\CartController as ShopCartController;
use App\Http\Controllers\Shop\CheckoutController as ShopCheckoutController;

// ===========================================
// ROUTES PUBLIQUES - BOUTIQUE (page d'accueil)
// ===========================================
Route::get('/', [ShopHomeController::class, 'index'])->name('shop.home');

// Routes de la boutique
Route::prefix('shop')->name('shop.')->group(function () {
    // Page d'accueil (redirige vers la racine)
    Route::get('/', function() {
        return redirect('/');
    })->name('home');
    
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
    });
});

// Routes pour les clients
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    // Historique des commandes
    Route::get('/orders', [App\Http\Controllers\Client\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Client\OrderController::class, 'show'])->name('orders.show');
    
    // Profil client
    Route::get('/profile', [App\Http\Controllers\Client\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Client\ProfileController::class, 'updatePassword'])->name('profile.password');
});

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
    
    // API interne
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/products/{supplier}', [OrderController::class, 'getProductsBySupplier'])->name('products.by-supplier');
        Route::get('/stock-data', [DashboardController::class, 'getStockData'])->name('stock.data');
        Route::get('/transactions-data', [DashboardController::class, 'transactionsData'])->name('transactions.data');
    });
    
    // Profile admin
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// Routes de test (admin uniquement)
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