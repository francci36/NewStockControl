<?php
// app/Http/Controllers/RapportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Report;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        // Produits en stock (pour le graphique)
        $produitsEnStock = Stock::with('product')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get();

        // Statistiques des fournisseurs
        $fournisseurs = Supplier::withCount('orders')
            ->get()
            ->map(function ($supplier) {
                $totalAmount = Order::where('supplier_id', $supplier->id)
                    ->where('type', 'supplier')
                    ->sum('total_amount');
                
                $supplier->orders_sum_total_amount = $totalAmount;
                return $supplier;
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
            'reports'
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
}