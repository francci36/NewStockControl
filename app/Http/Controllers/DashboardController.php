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

        // 🔥 CORRECTION : Charger les relations et utiliser les accesseurs admin
        $lowStockProducts = Product::with(['supplier', 'category', 'vendor'])
            ->whereNotNull('stock_threshold')
            ->where(function ($query) {
                $query->whereColumn('quantity', '<', 'stock_threshold')
                    ->orWhere('quantity', '<=', 0);
            })
            ->get()
            ->map(function ($product) {
                // Traitement du nom du produit
                $productName = $product->admin_name;
                
                // Traitement du fournisseur
                $supplierName = 'N/A';
                $supplierData = null;
                
                if ($product->supplier) {
                    $supplierName = $product->supplier->admin_name ?? $product->supplier->name;
                    $supplierData = [
                        'id' => $product->supplier->id,
                        'name' => $supplierName,
                        'admin_name' => $product->supplier->admin_name ?? null
                    ];
                } elseif ($product->vendor) {
                    $supplierName = $product->vendor->admin_name ?? $product->vendor->shop_name;
                    $supplierData = [
                        'id' => $product->vendor->id,
                        'name' => $supplierName,
                        'admin_name' => $product->vendor->admin_name ?? null,
                        'type' => 'vendor'
                    ];
                }
                
                return [
                    'id' => $product->id,
                    'name' => $productName,
                    'admin_name' => $productName, // Ajout pour compatibilité
                    'quantity' => $product->quantity,
                    'stock_threshold' => $product->stock_threshold,
                    'supplier_id' => $product->supplier_id ?? $product->vendor_id,
                    'supplier_name' => $supplierName,
                    'supplier' => $supplierData,
                ];
            });

        // 🔥 CORRECTION : Commandes récentes avec noms traduits
        $recentOrders = Order::with(['supplier', 'client'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                $supplierName = null;
                if ($order->supplier) {
                    $supplierName = $order->supplier->admin_name ?? $order->supplier->name;
                }
                
                return [
                    'id' => $order->id,
                    'type' => $order->type,
                    'supplier' => $order->supplier ? [
                        'id' => $order->supplier->id,
                        'name' => $supplierName,
                        'admin_name' => $order->supplier->admin_name ?? null
                    ] : null,
                    'supplier_name' => $supplierName,
                    'client_name' => $order->client->name ?? null,
                    'date' => $order->date->format('d/m/Y'),
                    'products_count' => $order->products->count(),
                    'status' => $order->status,
                ];
            });

        // 🔥 CORRECTION : Données du graphique avec noms traduits
        $topProducts = Product::with(['supplier', 'vendor'])
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get();
            
        $stockChartLabels = $topProducts->map(function ($product) {
            return $product->admin_name;
        });
        
        $stockChartQuantities = $topProducts->pluck('quantity');

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
                'labels' => $stockChartLabels,
                'quantities' => $stockChartQuantities,
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
            'labels' => $transactions->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d/m');
            }),
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
        $products = Product::with(['supplier', 'vendor'])
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'labels' => $products->map(function ($product) {
                return $product->admin_name;
            }),
            'quantities' => $products->pluck('quantity'),
        ]);
    }
}