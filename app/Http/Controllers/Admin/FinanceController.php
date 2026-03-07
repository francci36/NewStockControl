<?php
// app/Http/Controllers/Admin/FinanceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\Commission;
use App\Models\Payout;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product; // Ajout pour utiliser l'accesseur admin_name
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    /**
     * Dashboard financier
     */
    public function index(Request $request)
    {
        // Par défaut, prendre TOUTES les transactions (pas de filtre de période)
        $period = $request->get('period', 'all'); // Changé de 'month' à 'all'
        
        // Calculer les dates selon la période
        switch ($period) {
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'quarter':
                $startDate = now()->startOfQuarter();
                $endDate = now()->endOfQuarter();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'all':
            default:
                $startDate = null; // Pas de filtre de date
                $endDate = null;
                break;
        }

        // Statistiques globales avec TOUTES les transactions si période = all
        $stats = [
            'revenus' => $period === 'all' 
                ? FinancialTransaction::income()->sum('total')
                : FinancialTransaction::income()
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->sum('total'),
                
            'depenses' => $period === 'all'
                ? FinancialTransaction::expense()->sum('total')
                : FinancialTransaction::expense()
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->sum('total'),
                
            'benefice' => $period === 'all'
                ? (FinancialTransaction::income()->sum('total') - FinancialTransaction::expense()->sum('total'))
                : (FinancialTransaction::income()
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->sum('total') 
                    - FinancialTransaction::expense()
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->sum('total')),
                
            'commissions' => Commission::whereBetween('created_at', [$startDate ?? now()->subYear(), $endDate ?? now()])
                ->where('status', 'paid')
                ->sum('amount'),
                
            'commissions_pending' => Commission::whereBetween('created_at', [$startDate ?? now()->subYear(), $endDate ?? now()])
                ->where('status', 'pending')
                ->sum('amount'),
                
            'paiements_vendeurs' => Payout::whereBetween('created_at', [$startDate ?? now()->subYear(), $endDate ?? now()])
                ->where('status', 'completed')
                ->sum('amount'),
                
            'total_transactions' => $period === 'all'
                ? FinancialTransaction::count()
                : FinancialTransaction::whereBetween('transaction_date', [$startDate, $endDate])->count(),
        ];

        // Graphique des revenus par mois (toujours sur 12 mois)
        $revenusParMois = FinancialTransaction::income()
            ->select(
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('transaction_date', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // ===== CORRECTION : Top 10 des produits vendus avec noms formatés =====
        $topProduits = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                // Traiter le nom du produit pour afficher la version française
                $name = $item->name;
                
                // Si c'est une chaîne JSON, la décoder
                if (is_string($name) && str_starts_with($name, '{')) {
                    $decoded = json_decode($name, true);
                    if (is_array($decoded)) {
                        // Prendre la version française, sinon anglais, sinon la première
                        $item->formatted_name = $decoded['fr'] ?? $decoded['en'] ?? array_values($decoded)[0] ?? 'Produit';
                    } else {
                        $item->formatted_name = $name;
                    }
                } else {
                    $item->formatted_name = $name;
                }
                
                return $item;
            });

        // Commissions par vendeur
        $commissionsParVendeur = Vendor::with(['user'])
            ->withSum(['commissions' => function($query) use ($startDate, $endDate) {
                $query->where('status', 'paid')
                    ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                        return $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
            }], 'amount')
            ->withCount(['commissions' => function($query) use ($startDate, $endDate) {
                $query->where('status', 'pending')
                    ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                        return $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
            }])
            ->having('commissions_sum_amount', '>', 0)
            ->orHaving('commissions_count', '>', 0)
            ->orderBy('commissions_sum_amount', 'desc')
            ->limit(10)
            ->get();

        // Dernières transactions (TOUJOURS les 20 dernières, sans filtre)
        $transactions = FinancialTransaction::with(['user', 'order', 'vendor', 'supplier'])
            ->orderBy('transaction_date', 'desc')
            ->limit(20)
            ->get();

        // Statistiques des dépenses par catégorie
        $expenseCategories = FinancialTransaction::where('type', 'expense')
            ->select('category', DB::raw('SUM(total) as total'))
            ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                return $query->whereBetween('transaction_date', [$startDate, $endDate]);
            })
            ->groupBy('category')
            ->get();

        return view('admin.finance.index', compact(
            'stats',
            'revenusParMois',
            'topProduits',
            'commissionsParVendeur',
            'transactions',
            'period',
            'expenseCategories'
        ));
    }
    /**
 * Liste des transactions financières
 */
public function transactions(Request $request)
{
    $query = FinancialTransaction::with(['user', 'order', 'vendor', 'supplier']);
    
    // Filtres
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    if ($request->filled('date_from')) {
        $query->where('transaction_date', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->where('transaction_date', '<=', $request->date_to);
    }
    
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('reference', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }
    
    $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);
    
    // ===== STATISTIQUES DES COMMISSIONS =====
    $commissionsPending = Commission::where('status', 'pending')->count();
    $commissionsPendingAmount = Commission::where('status', 'pending')->sum('amount');
    
    return view('admin.finance.transactions', compact(
        'transactions',
        'commissionsPending',
        'commissionsPendingAmount'
    ));
}

    /**
     * Gestion des commissions vendeurs
     */
    public function commissions(Request $request)
    {
        $query = Commission::with(['vendor', 'order', 'orderItem.product']);
        
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $commissions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $totalPending = Commission::where('status', 'pending')->sum('amount');
        $totalPaid = Commission::where('status', 'paid')->sum('amount');
        
        $vendors = Vendor::whereHas('commissions')->get();
        
        return view('admin.finance.commissions', compact(
            'commissions',
            'totalPending',
            'totalPaid',
            'vendors'
        ));
    }

    /**
     * Paiement des commissions (création d'un payout)
     */
    public function payCommissions(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'commission_ids' => 'required|array',
            'commission_ids.*' => 'exists:commissions,id',
            'payment_method' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $vendor = Vendor::findOrFail($request->vendor_id);
            $commissions = Commission::whereIn('id', $request->commission_ids)
                ->where('vendor_id', $vendor->id)
                ->where('status', 'pending')
                ->get();

            if ($commissions->isEmpty()) {
                throw new \Exception('Aucune commission sélectionnée ou déjà payée.');
            }

            $totalAmount = $commissions->sum('amount');
            $reference = 'PAY-' . strtoupper(uniqid());

            // Créer le payout
            $payout = Payout::create([
                'vendor_id' => $vendor->id,
                'reference' => $reference,
                'amount' => $totalAmount,
                'commission_ids' => $commissions->pluck('id')->toArray(),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Mettre à jour le statut des commissions
            Commission::whereIn('id', $commissions->pluck('id'))
                ->update(['status' => 'paid', 'paid_at' => now()]);

            // Créer une transaction financière
            FinancialTransaction::create([
                'reference' => $reference,
                'type' => 'expense',
                'category' => 'commission',
                'amount' => $totalAmount,
                'tax' => 0,
                'total' => $totalAmount,
                'status' => 'completed',
                'payment_method' => $request->payment_method,
                'description' => "Paiement des commissions pour {$vendor->admin_shop_name}",
                'transaction_date' => now(),
                'user_id' => auth()->id(),
                'vendor_id' => $vendor->id,
                'metadata' => json_encode([
                    'commission_ids' => $commissions->pluck('id')->toArray(),
                    'payout_id' => $payout->id,
                ]),
            ]);

            DB::commit();

            return redirect()->route('admin.finance.commissions')
                ->with('success', "Paiement de " . number_format($totalAmount, 2) . " € effectué avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur paiement commissions: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Export des données financières
     */
    public function export(Request $request)
    {
        $query = FinancialTransaction::with(['user', 'order', 'vendor', 'supplier']);
        
        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->get();
        
        $filename = 'financial_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // En-têtes
        fputcsv($handle, ['Date', 'Référence', 'Type', 'Catégorie', 'Montant HT', 'TVA', 'Total TTC', 'Statut', 'Description']);
        
        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction->transaction_date->format('d/m/Y H:i'),
                $transaction->reference,
                $transaction->type === 'income' ? 'Revenu' : 'Dépense',
                $transaction->category,
                number_format($transaction->amount, 2) . ' €',
                number_format($transaction->tax, 2) . ' €',
                number_format($transaction->total, 2) . ' €',
                $transaction->status,
                $transaction->description,
            ]);
        }
        
        fclose($handle);
        exit;
    }
}