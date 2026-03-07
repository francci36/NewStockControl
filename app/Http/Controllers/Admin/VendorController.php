<?php
// app/Http/Controllers/Admin/VendorController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorController extends Controller
{
    /**
     * Liste des demandes vendeurs
     */
    public function index(Request $request)
    {
        $query = Vendor::with('user');

        // Filtre par statut
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $vendors = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Détail d'une demande - CORRIGÉ
     */
    public function show(Vendor $vendor)
    {
        // Charger les relations
        $vendor->load('user');
        
        // Statistiques - CORRECTION : préciser la table pour 'status'
        $stats = [
            'products_count' => $vendor->products()->count(),
            'orders_count' => $vendor->orders()->count(),
            'pending_orders' => $vendor->orders()
                ->where('orders.status', 'pending')  // ← CORRECTION ICI
                ->count(),
            'revenue' => $vendor->orders()->sum('total_amount')
        ];
        
        // Produits récents
        $recentProducts = $vendor->products()
            ->latest()
            ->limit(5)
            ->get();
        
        // Commandes récentes (optionnel)
        $recentOrders = $vendor->orders()
            ->latest()
            ->limit(5)
            ->get();
        
        return view('admin.vendors.show', compact(
            'vendor', 
            'stats', 
            'recentProducts',
            'recentOrders'
        ));
    }

    /**
     * Approuver un vendeur
     */
    public function approve(Vendor $vendor)
    {
        try {
            DB::beginTransaction();

            // Mettre à jour le statut du vendeur
            $vendor->update([
                'status' => 'active',
                'approved_at' => now(),
            ]);

            // Mettre à jour le rôle de l'utilisateur
            $user = $vendor->user;
            $user->role = 'vendor';
            $user->save();

            // Invalider toutes les sessions de cet utilisateur
            DB::table('sessions')->where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('admin.vendors.index')
                ->with('success', "La boutique {$vendor->shop_name} a été approuvée. L'utilisateur devra se reconnecter.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur approbation vendeur: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Rejeter un vendeur
     */
    public function reject(Request $request, Vendor $vendor)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $vendor->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            Log::info('Vendeur rejeté', [
                'vendor_id' => $vendor->id,
                'reason' => $request->rejection_reason,
                'rejected_by' => auth()->id()
            ]);

            return redirect()->route('admin.vendors.index')
                ->with('success', "La demande de {$vendor->shop_name} a été rejetée.");

        } catch (\Exception $e) {
            Log::error('Erreur rejet vendeur: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Suspendre un vendeur actif
     */
    public function suspend(Request $request, Vendor $vendor)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $vendor->update([
                'status' => 'suspended',
                'rejection_reason' => $request->reason,
            ]);

            Log::info('Vendeur suspendu', [
                'vendor_id' => $vendor->id,
                'reason' => $request->reason,
                'suspended_by' => auth()->id()
            ]);

            return redirect()->route('admin.vendors.index')
                ->with('success', "La boutique {$vendor->shop_name} a été suspendue.");

        } catch (\Exception $e) {
            Log::error('Erreur suspension vendeur: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Modifier la commission d'un vendeur
     */
    public function updateCommission(Request $request, Vendor $vendor)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $vendor->update([
            'commission_rate' => $request->commission_rate,
        ]);

        return redirect()->back()
            ->with('success', "Commission mise à jour à {$request->commission_rate}%");
    }
}