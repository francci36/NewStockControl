<?php
// app/Http/Controllers/Vendor/OrderController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Affiche les commandes du vendeur connecté
     */
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;
        
        // Vérifier que le vendeur existe
        if (!$vendor) {
            return redirect()->route('vendor.register')
                ->with('error', 'Vous devez d\'abord vous inscrire comme vendeur.');
        }
        
        // Récupérer les commandes qui contiennent des produits de ce vendeur
        $query = Order::whereHas('items', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->with(['items' => function($query) use ($vendor) {
                // Ne charger que les items du vendeur concerné
                $query->where('vendor_id', $vendor->id)->with('product');
            }, 'user']);
        
        // Filtrer par statut
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('vendor.orders.index', compact('orders', 'vendor'));
    }
    
    /**
     * Affiche les détails d'une commande spécifique pour le vendeur
     */
    public function show($id)
    {
        $vendor = auth()->user()->vendor;
        
        $order = Order::whereHas('items', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->with(['items' => function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id)->with('product');
            }, 'user']) // ← SUPPRIMEZ 'address' DE LA LISTE
            ->findOrFail($id);
        
        // Calculer le total des produits du vendeur dans cette commande
        $vendorTotal = $order->items->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        return view('vendor.orders.show', compact('order', 'vendor', 'vendorTotal'));
    }
    
    /**
     * Mettre à jour le statut d'expédition d'une commande (vendeur)
     */
    public function updateShipping(Request $request, $id)
    {
        $vendor = auth()->user()->vendor;
        
        $order = Order::whereHas('items', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })->findOrFail($id);
        
        $request->validate([
            'tracking_number' => 'nullable|string|max:255',
            'carrier' => 'nullable|string|max:100',
            'status' => 'required|in:shipped,processing'
        ]);
        
        // Mise à jour
        if ($request->status == 'shipped') {
            $order->tracking_number = $request->tracking_number;
            $order->carrier = $request->carrier;
            $order->shipped_at = now();
            
            $order->updateStatusWithHistory(
                'shipped',
                'Commande expédiée par le vendeur',
                auth()->id()
            );
            
            $message = 'Commande marquée comme expédiée avec succès !';
        } else {
            $order->updateStatusWithHistory(
                'processing',
                'Commande en cours de préparation',
                auth()->id()
            );
            
            $message = 'Statut mis à jour avec succès.';
        }
        
        $order->save();
        
        return redirect()->route('vendor.orders.show', $order->id)
            ->with('success', $message);
    }
}