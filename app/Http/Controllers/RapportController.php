<?php
// app/Http/Controllers/RapportController.php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Order;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Vendor; // AJOUT : Importer le modèle Vendor
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RapportController extends Controller
{
    public function index()
    {
        // Automatisation des rapports
        $this->updateReports();

        // Chiffre d'affaires mensuel (ventes clients)
        $chiffreAffaires = Transaction::whereMonth('created_at', now()->month)
            ->where('type', 'exit')
            ->sum(DB::raw('quantity * price'));

        // Total des achats fournisseurs du mois
        $totalAchats = Order::whereMonth('created_at', now()->month)
            ->where('type', 'supplier')
            ->sum('total_amount');

        // Bénéfice brut estimé
        $benefice = $chiffreAffaires - $totalAchats;

        // Produits en stock critique
        $seuilCritique = config('inventory.stock_critique', 10);
        $stockCritique = Stock::where('quantity', '<=', $seuilCritique)->count();

        // Statistiques clients
        $clientsActifs = User::where('role', 'user')->count();
        $nombreCommandesClients = Order::where('type', 'client')->count();
        $panierMoyen = Order::where('type', 'client')
            ->avg('total_amount') ?? 0;

        // Nombre total de commandes (tous types)
        $totalCommandes = Order::count();

        // Commandes par statut
        $commandesParStatut = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // Produits en stock (pour le graphique) - AVEC NOMS TRADUITS
        $produitsEnStock = Stock::with('product')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($stock) {
                // Ajouter le nom traduit pour le graphique
                if ($stock->product) {
                    $stock->product->admin_name = $stock->product->admin_name;
                }
                return $stock;
            });

        // Statistiques des fournisseurs - AVEC NOMS TRADUITS
        $fournisseurs = Supplier::withCount('orders')
            ->get()
            ->map(function ($supplier) {
                $totalAmount = Order::where('supplier_id', $supplier->id)
                    ->where('type', 'supplier')
                    ->sum('total_amount');
                
                $supplier->orders_sum_total_amount = $totalAmount;
                $supplier->admin_name = $supplier->admin_name; // Ajout de l'accesseur
                return $supplier;
            });

        // ===== NOUVELLES DONNÉES POUR LES VENDEURS MARKETPLACE =====
        $vendeurs = Vendor::with(['user', 'products', 'orders'])
            ->withCount('products')
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->get()
            ->map(function ($vendor) {
                // Ajouter les accesseurs admin
                $vendor->admin_shop_name = $vendor->admin_shop_name;
                return $vendor;
            });

        $caMarketplace = $vendeurs->sum('orders_sum_total_amount');
        $commandesMarketplace = $vendeurs->sum('orders_count');
        $commissionMoyenne = round($vendeurs->where('status', 'active')->avg('commission_rate') ?? 0, 1);
        
        $revenuCommissions = $vendeurs->sum(function($vendeur) {
            return ($vendeur->orders_sum_total_amount ?? 0) * ($vendeur->commission_rate / 100);
        });

        // Rapports générés
        $reports = Report::with([
            'transactions' => function ($query) {
                $query->whereIn('type', ['entry', 'exit']);
            },
            'stocks'
        ])->paginate(10);

        return view('rapports.index', compact(
            'chiffreAffaires',
            'totalAchats',
            'benefice',
            'stockCritique',
            'clientsActifs',
            'nombreCommandesClients',
            'panierMoyen',
            'totalCommandes',
            'commandesParStatut',
            'produitsEnStock',
            'fournisseurs',
            'reports',
            // NOUVELLES VARIABLES
            'vendeurs',
            'caMarketplace',
            'commandesMarketplace',
            'commissionMoyenne',
            'revenuCommissions'
        ));
    }

    private function updateReports()
    {
        $rapportVentes = Report::firstOrCreate([
            'name' => 'Rapport des Ventes',
            'description' => 'Analyse des ventes mensuelles',
        ]);

        $rapportAchats = Report::firstOrCreate([
            'name' => 'Rapport des Achats',
            'description' => 'Analyse des achats fournisseurs',
        ]);

        $rapportStocks = Report::firstOrCreate([
            'name' => 'Rapport des Stocks',
            'description' => 'État des stocks actuels',
        ]);

        $rapportMarketplace = Report::firstOrCreate([ // NOUVEAU
            'name' => 'Rapport Marketplace',
            'description' => 'Performance des vendeurs marketplace',
        ]);

        // Associer les transactions aux rapports
        Transaction::where('type', 'exit')
            ->whereNull('report_id')
            ->update(['report_id' => $rapportVentes->id]);

        Transaction::where('type', 'entry')
            ->whereNull('report_id')
            ->update(['report_id' => $rapportAchats->id]);

        // Stocks critiques
        $seuilCritique = config('inventory.stock_critique', 10);
        Stock::where('quantity', '<=', $seuilCritique)
            ->whereNull('report_id')
            ->update(['report_id' => $rapportStocks->id]);
    }

    /**
     * Exporte un rapport au format PDF
     */
    public function export(Report $report)
    {
        try {
            // Charger les relations
            $report->load(['transactions', 'stocks']);
            
            // Récupérer les données supplémentaires
            $chiffreAffaires = Transaction::whereMonth('created_at', now()->month)
                ->where('type', 'exit')
                ->sum(DB::raw('quantity * price'));
                
            $totalAchats = Order::whereMonth('created_at', now()->month)
                ->where('type', 'supplier')
                ->sum('total_amount');
                
            $stockCritique = Stock::where('quantity', '<=', 10)->count();
            
            $clientsActifs = User::where('role', 'user')->count();
            $nombreCommandesClients = Order::where('type', 'client')->count();
            $panierMoyen = Order::where('type', 'client')->avg('total_amount') ?? 0;
            
            $commandesParStatut = Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();
                
            $produitsEnStock = Stock::with('product')
                ->orderBy('quantity', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($stock) {
                    if ($stock->product) {
                        $stock->product->admin_name = $stock->product->admin_name;
                    }
                    return $stock;
                });
                
            $fournisseurs = Supplier::withCount('orders')
                ->withSum('orders', 'total_amount')
                ->get()
                ->map(function ($supplier) {
                    $supplier->admin_name = $supplier->admin_name;
                    return $supplier;
                });

            // ===== DONNÉES MARKETPLACE POUR LE PDF =====
            $vendeurs = Vendor::with(['user', 'products', 'orders'])
                ->withCount('products')
                ->withCount('orders')
                ->withSum('orders', 'total_amount')
                ->get()
                ->map(function ($vendor) {
                    $vendor->admin_shop_name = $vendor->admin_shop_name;
                    return $vendor;
                });

            $caMarketplace = $vendeurs->sum('orders_sum_total_amount');
            $commandesMarketplace = $vendeurs->sum('orders_count');
            $commissionMoyenne = round($vendeurs->where('status', 'active')->avg('commission_rate') ?? 0, 1);
            $revenuCommissions = $vendeurs->sum(function($vendeur) {
                return ($vendeur->orders_sum_total_amount ?? 0) * ($vendeur->commission_rate / 100);
            });

            // Générer le PDF
            $pdf = Pdf::loadView('rapports.pdf', compact(
                'report',
                'chiffreAffaires',
                'totalAchats',
                'stockCritique',
                'clientsActifs',
                'nombreCommandesClients',
                'panierMoyen',
                'commandesParStatut',
                'produitsEnStock',
                'fournisseurs',
                // NOUVELLES VARIABLES POUR LE PDF
                'vendeurs',
                'caMarketplace',
                'commandesMarketplace',
                'commissionMoyenne',
                'revenuCommissions'
            ));

            // Télécharger le PDF
            return $pdf->download('rapport_' . $report->id . '_' . date('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            Log::error('Erreur export PDF: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'export du rapport: ' . $e->getMessage());
        }
    }

    /**
     * Rafraîchir les données (pour l'AJAX)
     */
    public function refreshData()
    {
        // Vérifier s'il y a des changements récents
        $lastTransaction = Transaction::latest()->first();
        $lastOrder = Order::latest()->first();
        $lastReport = Report::latest()->first();
        
        $lastUpdate = max(
            $lastTransaction?->updated_at ?? now()->subDay(),
            $lastOrder?->updated_at ?? now()->subDay(),
            $lastReport?->updated_at ?? now()->subDay()
        );
        
        return response()->json([
            'changed' => $lastUpdate->gt(now()->subMinutes(5)),
            'last_update' => $lastUpdate->format('d/m/Y H:i:s')
        ]);
    }
}