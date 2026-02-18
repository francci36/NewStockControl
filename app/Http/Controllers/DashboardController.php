<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Affiche la page du tableau de bord.
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Récupère toutes les données du dashboard en une seule requête AJAX
     */
    public function getDashboardData()
{
    $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
    $completedOrders = Order::where('status', Order::STATUS_ARRIVED)->count();
    $totalProducts = Product::count();
    $totalStockQuantity = Product::sum('quantity');
    
    $lowStockCount = Product::whereNotNull('stock_threshold')
        ->whereColumn('quantity', '<', 'stock_threshold')
        ->count();

    $lowStockProducts = Product::whereNotNull('stock_threshold')
        ->where(function ($query) {
            $query->whereColumn('quantity', '<', 'stock_threshold')
                ->orWhere('quantity', '<=', 0);
        })
        ->with('supplier')
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'threshold' => $product->stock_threshold,
                'supplier_id' => $product->supplier_id,
                'supplier_name' => $product->supplier->name ?? 'N/A',
            ];
        });

    // Commandes récentes (mélange des deux types)
    $recentOrders = Order::with(['supplier', 'client'])
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($order) {
            return [
                'id' => $order->id,
                'type' => $order->type,
                'supplier_name' => $order->supplier->name ?? null,
                'client_name' => $order->client->name ?? null,
                'date' => $order->date->format('d/m/Y'),
                'products_count' => $order->products->count(),
                'status' => $order->status,
            ];
        });

    return response()->json([
        'stats' => [
            'totalSuppliers' => Supplier::count(),
            'totalProducts' => $totalProducts,
            'totalStockQuantity' => $totalStockQuantity,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'lowStockCount' => $lowStockCount,
        ],
        'lowStockProducts' => $lowStockProducts,
        'recentOrders' => $recentOrders,
        'stockChart' => [
            'labels' => Product::where('quantity', '>', 0)->orderBy('quantity', 'desc')->limit(10)->pluck('name'),
            'quantities' => Product::where('quantity', '>', 0)->orderBy('quantity', 'desc')->limit(10)->pluck('quantity'),
        ],
        'lastUpdate' => now()->format('H:i:s'),
    ]);
}

    /**
     * Renvoie les données des transactions pour les graphiques
     */
    public function getTransactionsData(Request $request)
    {
        $period = $request->get('period', 30);
        
        // Transactions par jour
        $transactions = Transaction::selectRaw('DATE(created_at) as date, 
                                                COUNT(*) as count,
                                                SUM(CASE WHEN type = "entry" THEN 1 ELSE 0 END) as entries,
                                                SUM(CASE WHEN type = "exit" THEN 1 ELSE 0 END) as exits')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Statistiques des transactions
        $totalEntries = Transaction::where('type', 'entry')
            ->where('created_at', '>=', now()->subDays($period))
            ->count();
            
        $totalExits = Transaction::where('type', 'exit')
            ->where('created_at', '>=', now()->subDays($period))
            ->count();

        return response()->json([
            'labels' => $transactions->pluck('date'),
            'counts' => $transactions->pluck('count'),
            'entries' => $transactions->pluck('entries'),
            'exits' => $transactions->pluck('exits'),
            'totals' => [
                'entries' => $totalEntries,
                'exits' => $totalExits,
                'total' => $totalEntries + $totalExits
            ]
        ]);
    }

    /**
     * Renvoie les données de stock pour les graphiques
     */
    public function getStockData(Request $request)
    {
        $products = Product::select('name', 'quantity')
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'labels' => $products->pluck('name'),
            'quantities' => $products->pluck('quantity'),
        ]);
    }
}