<?php
// app/Http/Controllers/Vendor/DashboardController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // ← IMPORT AJOUTÉ !
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'Vous devez être un vendeur pour accéder à cette page.');
        }
        
        // Récupérer l'ID du vendeur
        $vendorId = $vendor->id;
        
        // ===========================================
        // 1. STATISTIQUES DE BASE
        // ===========================================
        $stats = [
            // Produits
            'total_products' => $vendor->products()->count(),
            
            // Commandes totales (commandes qui contiennent des produits de ce vendeur)
            'total_orders' => $vendor->orders()->count(),
            
            // Commandes par statut - avec précision de la table 'orders'
            'pending_orders' => $vendor->orders()
                ->where('orders.status', 'pending')
                ->count(),
            'processing_orders' => $vendor->orders()
                ->where('orders.status', 'processing')
                ->count(),
            'shipped_orders' => $vendor->orders()
                ->where('orders.status', 'shipped')
                ->count(),
            'delivered_orders' => $vendor->orders()
                ->whereIn('orders.status', ['delivered', 'arrived'])
                ->count(),
            'cancelled_orders' => $vendor->orders()
                ->where('orders.status', 'cancelled')
                ->count(),
            
            // Chiffre d'affaires
            'revenue_month' => $vendor->orders()
                ->whereMonth('orders.created_at', now()->month)
                ->whereYear('orders.created_at', now()->year)
                ->sum('orders.total_amount'),
            
            'revenue_last_month' => $vendor->orders()
                ->whereMonth('orders.created_at', now()->subMonth()->month)
                ->whereYear('orders.created_at', now()->subMonth()->year)
                ->sum('orders.total_amount'),
            
            'revenue_total' => $vendor->orders()->sum('orders.total_amount'),
            
            // Statistiques avancées
            'avg_order_value' => $vendor->orders()->avg('orders.total_amount') ?? 0,
            'unique_customers' => $vendor->orders()
                ->distinct('orders.user_id')
                ->count('orders.user_id'),
        ];

        // ===========================================
        // 2. DONNÉES POUR LES GRAPHIQUES
        // ===========================================
        
        // Données pour le graphique des ventes mensuelles (6 derniers mois)
        $monthlySales = [];
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('m');
            $year = $date->format('Y');
            
            $total = $vendor->orders()
                ->whereMonth('orders.created_at', $month)
                ->whereYear('orders.created_at', $year)
                ->sum('orders.total_amount');
            
            $months[] = $date->format('M Y');
            $monthlySales[] = $total ?: 0;
        }

        // Données pour la répartition des statuts
        $statusDistribution = [
            'pending' => $vendor->orders()->where('orders.status', 'pending')->count(),
            'processing' => $vendor->orders()->where('orders.status', 'processing')->count(),
            'shipped' => $vendor->orders()->where('orders.status', 'shipped')->count(),
            'delivered' => $vendor->orders()->whereIn('orders.status', ['delivered', 'arrived'])->count(),
            'cancelled' => $vendor->orders()->where('orders.status', 'cancelled')->count(),
        ];

        // ===========================================
        // 3. PRODUITS LES PLUS VENDUS (CORRIGÉ)
        // ===========================================
        // Récupérer les IDs des produits les plus vendus
        $topProductIds = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->select('products.id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->pluck('products.id')
            ->toArray();

        // Récupérer les produits avec leurs relations
        $topProducts = collect();
        if (!empty($topProductIds)) {
            $products = Product::whereIn('id', $topProductIds)
                ->with('category')
                ->get()
                ->keyBy('id');
            
            // Ajouter les quantités vendues
            $quantities = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.vendor_id', $vendorId)
                ->select('products.id', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('products.id')
                ->pluck('total_sold', 'id')
                ->toArray();

            foreach ($topProductIds as $id) {
                if (isset($products[$id])) {
                    $product = $products[$id];
                    $product->total_sold = $quantities[$id] ?? 0;
                    $topProducts->push($product);
                }
            }
        }

        // ===========================================
        // 4. PRODUITS RÉCENTS (5 derniers)
        // ===========================================
        $recentProducts = $vendor->products()
            ->with('category')
            ->latest()
            ->limit(5)
            ->get();
        
        // ===========================================
        // 5. COMMANDES RÉCENTES (5 dernières)
        // ===========================================
        $recentOrders = $vendor->orders()
            ->with('user')
            ->latest('orders.created_at')
            ->limit(5)
            ->get();
        
        // ===========================================
        // 6. RETOUR DE LA VUE
        // ===========================================
        return view('vendor.dashboard', compact(
            'stats', 
            'recentProducts', 
            'recentOrders', 
            'months', 
            'monthlySales', 
            'statusDistribution',
            'topProducts'
        ));
    }

    public function settings()
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'Vous devez d\'abord vous inscrire comme vendeur.');
        }
        
        return view('vendor.settings', compact('vendor'));
    }

    public function updateSettings(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'Vous devez d\'abord vous inscrire comme vendeur.');
        }

        $request->validate([
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name,' . $vendor->id,
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'bank_account' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'shop_name', 
            'description', 
            'address',
            'city',
            'postal_code',
            'country',
            'phone', 
            'bank_account'
        ]);

        // Gestion du logo
        if ($request->hasFile('logo')) {
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            $path = $request->file('logo')->store('vendors/logos', 'public');
            $data['logo'] = $path;
        }

        $vendor->update($data);

        return redirect()->route('vendor.settings')
            ->with('success', __('messages.settings_updated'));
    }

    public function removeLogo(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        if ($vendor && $vendor->logo) {
            // Supprimer le fichier
            if (Storage::disk('public')->exists($vendor->logo)) {
                Storage::disk('public')->delete($vendor->logo);
            }
            
            // Mettre à jour la base de données
            $vendor->update(['logo' => null]);
            
            return back()->with('success', __('messages.logo_removed'));
        }
        
        return back()->with('error', __('messages.logo_not_found'));
    }

}