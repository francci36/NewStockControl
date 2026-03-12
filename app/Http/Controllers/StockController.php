<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // Synchroniser les stocks si nécessaire
        if (Stock::count() == 0) {
            $this->syncStocks();
        }

        // Construction de la requête de base AVEC jointure pour éviter les problèmes de tri
        $query = Stock::with(['product.supplier', 'product.vendor'])
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->select('stocks.*', 'products.name as product_name', 'products.sku', 'products.stock_threshold');
        
        // 🔍 RECHERCHE
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('products.sku', 'LIKE', '%' . $search . '%')
                  ->orWhere('products.description', 'LIKE', '%' . $search . '%');
            });
        }
        
        // 📊 FILTRE PAR STATUT - CORRIGÉ pour utiliser la jointure
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low':
                    $query->whereRaw('stocks.quantity <= COALESCE(products.stock_threshold, 5)')
                          ->where('stocks.quantity', '>', 0);
                    break;
                case 'out':
                    $query->where('stocks.quantity', '<=', 0);
                    break;
                case 'normal':
                    $query->whereRaw('stocks.quantity > COALESCE(products.stock_threshold, 5)');
                    break;
            }
        }
        
        // 🔄 GESTION DU TRI - SIMPLIFIÉE
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('products.name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('products.name', 'desc');
                break;
            case 'quantity_asc':
                $query->orderBy('stocks.quantity', 'asc');
                break;
            case 'quantity_desc':
                $query->orderBy('stocks.quantity', 'desc');
                break;
            default:
                // Tri par défaut
                $query->orderBy('products.name', 'asc');
                break;
        }
        
        // Pagination avec préservation des paramètres de requête
        $stocks = $query->paginate(20)->withQueryString();

        return view('stocks.index', compact('stocks'));
    }

    /**
     * Synchronise les produits avec la table stocks
     */
    private function syncStocks()
    {
        $products = Product::all();
        foreach ($products as $product) {
            Stock::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $product->quantity]
            );
        }
        
        Log::info('Stocks synchronisés', ['count' => $products->count()]);
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        // Mettre à jour le stock
        $stock->update($validated);
        
        // Mettre à jour le produit associé
        if ($stock->product) {
            $stock->product->quantity = $validated['quantity'];
            $stock->product->save();
            
            Log::info('Stock mis à jour', [
                'product_id' => $stock->product->id,
                'new_quantity' => $validated['quantity']
            ]);
        }

        // Redirection avec préservation des filtres
        return redirect()->route('stocks.index', request()->query())
            ->with('success', 'Stock mis à jour avec succès');
    }
    
    /**
     * Méthode pour la synchronisation AJAX
     */
    public function sync(Request $request)
    {
        try {
            $this->syncStocks();
            return response()->json([
                'success' => true,
                'message' => 'Stocks synchronisés avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur synchronisation stocks', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}