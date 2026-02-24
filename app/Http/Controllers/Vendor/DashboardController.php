<?php
// app/Http/Controllers/Vendor/DashboardController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        
        // Statistiques
        $stats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('is_active', true)->count(),
            'total_orders' => 0, // À implémenter avec les commandes
            'total_sales' => 0,   // À implémenter avec les commandes
            'pending_orders' => 0, // À implémenter avec les commandes
            'revenue_month' => 0,  // À implémenter avec les commandes
        ];

        // Derniers produits
        $recentProducts = $vendor->products()
            ->latest()
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact('vendor', 'stats', 'recentProducts'));
    }

    public function settings()
    {
        $vendor = auth()->user()->vendor;
        return view('vendor.settings', compact('vendor'));
    }

    public function updateSettings(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $request->validate([
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name,' . $vendor->id,
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'bank_account' => 'required|string|max:50',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['shop_name', 'description', 'address', 'phone', 'bank_account']);

        // Gestion du logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('vendor-logos', 'public');
            $data['logo'] = $path;
        }

        $vendor->update($data);

        return redirect()->route('vendor.settings')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}