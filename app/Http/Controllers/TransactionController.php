<?php
// app/Http/Controllers/TransactionController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Affiche la liste des transactions
     */
   public function index(Request $request)
    {
        $query = Transaction::with(['product', 'user']);
        
        // Filtre par période
        if ($request->filled('period') && $request->period !== 'all') {
            $query->where('created_at', '>=', now()->subDays($request->period));
        }
        
        // Filtre par type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Filtre par recherche produit
        if ($request->filled('search')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Retourner la vue avec seulement la variable $transactions
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Formulaire de création de transaction
     */
    public function create()
    {
        // Récupérer tous les produits avec leurs relations pour les noms traduits
        $products = Product::with(['supplier', 'vendor'])
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                // Ajouter l'accesseur admin_name pour le JavaScript
                $product->admin_name = $product->admin_name;
                return $product;
            });
        
        // CORRECTION : Utiliser le bon chemin de vue    
        return view('transactions.create', compact('products'));
    }

    /**
     * Enregistre une nouvelle transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:entry,exit',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);
                
                // Vérification du stock pour les sorties
                if ($request->type === 'exit') {
                    if ($product->quantity < $productData['quantity']) {
                        throw new \Exception("Stock insuffisant pour {$product->admin_name}. Stock: {$product->quantity}, demandé: {$productData['quantity']}");
                    }
                }

                // Création de la transaction
                $transaction = Transaction::create([
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                    'total_value' => $product->price * $productData['quantity'],
                    'type' => $request->type,
                    'user_id' => auth()->id(),
                    'notes' => $request->notes,
                ]);

                // Mise à jour du stock du produit
                if ($request->type === 'entry') {
                    $product->quantity += $productData['quantity'];
                    Log::info('Entrée de stock manuelle', [
                        'product' => $product->admin_name,
                        'product_id' => $product->id,
                        'quantite' => $productData['quantity'],
                        'user' => auth()->user()->name
                    ]);
                } else {
                    $product->quantity -= $productData['quantity'];
                    Log::info('Sortie de stock manuelle', [
                        'product' => $product->admin_name,
                        'product_id' => $product->id,
                        'quantite' => $productData['quantity'],
                        'user' => auth()->user()->name
                    ]);
                }
                
                $product->save();

                // Mise à jour de la table stocks
                Stock::updateOrCreate(
                    ['product_id' => $product->id],
                    ['quantity' => $product->quantity]
                );
            }

            DB::commit();

            $message = $request->type === 'entry' 
                ? 'Entrée de stock enregistrée avec succès.' 
                : 'Sortie de stock enregistrée avec succès.';

            return redirect()->route('admin.transactions.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur transaction manuelle: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Récupère les détails d'une transaction pour l'AJAX
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['product', 'user']);
        
        return response()->json([
            'id' => $transaction->id,
            'product_name' => $transaction->product 
                ? ($transaction->product->admin_name ?? $transaction->product->name) 
                : 'Produit supprimé',
            'quantity' => $transaction->quantity,
            'price' => number_format($transaction->price, 2),
            'total' => number_format($transaction->total_value, 2),
            'type' => $transaction->type,
            'type_label' => $transaction->type === 'entry' ? 'Entrée' : 'Sortie',
            'user_name' => $transaction->user->name ?? 'Système',
            'date' => $transaction->created_at->format('d/m/Y H:i'),
            'order_id' => $transaction->order_id,
            'notes' => $transaction->notes,
        ]);
    }

    /**
     * Exporte les transactions en CSV
     */
    public function export(Request $request)
    {
        $query = Transaction::with(['product', 'user']);
        
        if ($request->filled('period') && $request->period !== 'all') {
            $query->where('created_at', '>=', now()->subDays($request->period));
        }
        
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('search')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'transactions_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // En-têtes CSV
        fputcsv($handle, ['ID', 'Date', 'Type', 'Produit', 'Quantité', 'Prix', 'Total', 'Utilisateur', 'Notes']);
        
        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction->id,
                $transaction->created_at->format('d/m/Y H:i'),
                $transaction->type === 'entry' ? 'Entrée' : 'Sortie',
                $transaction->product 
                    ? ($transaction->product->admin_name ?? $transaction->product->name) 
                    : 'Produit supprimé',
                $transaction->quantity,
                number_format($transaction->price, 2) . ' €',
                number_format($transaction->total_value, 2) . ' €',
                $transaction->user->name ?? 'Système',
                $transaction->notes ?? '',
            ]);
        }
        
        fclose($handle);
        exit;
    }
}