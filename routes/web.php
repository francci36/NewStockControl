<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index')->middleware('auth');
Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index')->middleware('auth');
Route::get('/commandes/create', [CommandeController::class, 'create'])->name('commandes.create')->middleware('auth');
Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store')->middleware('auth');
Route::get('/fournisseurs', [SupplierController::class, 'index'])->name('fournisseurs.index')->middleware('auth');
Route::get('/fournisseurs/create', [SupplierController::class, 'create'])->name('fournisseurs.create')->middleware('auth');
Route::post('/fournisseurs', [SupplierController::class, 'store'])->name('fournisseurs.store')->middleware('auth');
Route::delete('/fournisseurs/{id}', [SupplierController::class, 'destroy'])->name('fournisseurs.destroy')->middleware('auth');
Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index')->middleware('auth');

// Routes d'authentification
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);

// Routes de profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
