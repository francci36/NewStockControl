<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Affiche la liste des commandes (admin)
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'supplier', 'client', 'items.product', 'items.vendor'])
            ->orderBy('created_at', 'desc');
        
        // Filtrer par type (supplier ou client)
        if ($request->has('type') && in_array($request->type, ['supplier', 'client'])) {
            $query->where('type', $request->type);
        } else {
            // Par défaut, afficher les commandes fournisseurs
            $query->where('type', 'supplier');
        }
        
        // Filtrer par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->paginate(20);
        
        // Statistiques
        $totalAmount = $orders->sum('total_amount');
        $pendingCount = $orders->filter(function($order) {
            return $order->status == 'pending';
        })->count();
        
        return view('admin.orders.index', compact('orders', 'totalAmount', 'pendingCount'));
    }
    
    /**
     * Affiche les détails d'une commande
     */
    public function show(Order $order)
{
    \Log::info('=== DÉBUT DÉBOGAGE COMMANDE ===');
    \Log::info('ID Commande: ' . $order->id);
    
    $order->load([
        'user', 
        'supplier', 
        'client', 
        'items.product', 
        'items.vendor',
        'statusHistory.user'
    ]);
    
    // Log des items
    foreach ($order->items as $item) {
        \Log::info('Item ID: ' . $item->id, [
            'product_id' => $item->product_id,
            'product_name' => $item->product->name ?? 'Inconnu',
            'vendor_id' => $item->vendor_id,
            'vendor_data' => $item->vendor ? $item->vendor->toArray() : 'null',
            'quantity' => $item->quantity,
            'price' => $item->price
        ]);
    }
    
    // Grouper les items par source
    $boutiqueItems = $order->items->filter(function($item) {
        return is_null($item->vendor_id);
    });
    
    $vendorItems = $order->items->filter(function($item) {
        return !is_null($item->vendor_id);
    })->groupBy('vendor_id');
    
    \Log::info('Items boutique: ' . $boutiqueItems->count());
    \Log::info('Items marketplace: ' . $vendorItems->count());
    
    // Récupérer les vendeurs
    $vendorIds = $vendorItems->keys();
    \Log::info('IDs vendeurs recherchés: ' . $vendorIds->toJson());
    
    $vendors = Vendor::whereIn('id', $vendorIds)->get()->keyBy('id');
    \Log::info('Vendeurs trouvés: ' . $vendors->count());
    
    foreach ($vendors as $vendor) {
        \Log::info('Vendeur trouvé: ' . $vendor->shop_name . ' (ID: ' . $vendor->id . ')');
    }
    
    \Log::info('=== FIN DÉBOGAGE COMMANDE ===');
    
    return view('admin.orders.show', compact('order', 'boutiqueItems', 'vendorItems', 'vendors'));
}
    
    /**
     * Formulaire de création de commande fournisseur
     */
    public function create(Request $request)
    {
        $supplier_id = $request->get('supplier_id');
        $suppliers = Supplier::orderBy('name')->get();
        $products = $supplier_id ? Product::where('supplier_id', $supplier_id)->get() : collect();
        
        return view('admin.orders.create', compact('suppliers', 'products', 'supplier_id'));
    }
    
    /**
     * Store une nouvelle commande fournisseur
     */
    public function store(Request $request)
    {
        // Déléguer au contrôleur principal
        return app(OrderController::class)->store($request);
    }
    
    /**
     * Formulaire d'édition de commande fournisseur
     */
    public function edit($id)
    {
        return app(OrderController::class)->edit($id);
    }
    
    /**
     * Mise à jour d'une commande fournisseur
     */
    public function update(Request $request, $id)
    {
        return app(OrderController::class)->update($request, $id);
    }
    
    /**
     * Suppression d'une commande
     */
    public function destroy(Order $order)
    {
        return app(OrderController::class)->destroy($order);
    }
}