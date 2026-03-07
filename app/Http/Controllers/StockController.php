<?php

namespace App\Http\Controllers;

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

        // Construction de la requête
        $query = Stock::with(['product', 'product.supplier']);
        
        // 🔍 RECHERCHE
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        // 📊 FILTRE PAR STATUT
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low':
                    $query->whereHas('product', function($q) {
                        $q->whereRaw('quantity <= COALESCE(stock_threshold, 5)')
                          ->where('quantity', '>', 0);
                    });
                    break;
                case 'out':
                    $query->whereHas('product', function($q) {
                        $q->where('quantity', '<=', 0);
                    });
                    break;
                case 'normal':
                    $query->whereHas('product', function($q) {
                        $q->whereRaw('quantity > COALESCE(stock_threshold, 5)');
                    });
                    break;
            }
        }
        
        // 🔄 GESTION DU TRI (CORRIGÉ)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->join('products', 'stocks.product_id', '=', 'products.id')
                          ->select('stocks.*')
                          ->orderBy('products.name', 'asc');
                    break;
                case 'name_desc':
                    $query->join('products', 'stocks.product_id', '=', 'products.id')
                          ->select('stocks.*')
                          ->orderBy('products.name', 'desc');
                    break;
                case 'quantity_asc':
                    $query->orderBy('quantity', 'asc');
                    break;
                case 'quantity_desc':
                    $query->orderBy('quantity', 'desc');
                    break;
                default:
                    // Tri par défaut : nom ascendant
                    $query->join('products', 'stocks.product_id', '=', 'products.id')
                          ->select('stocks.*')
                          ->orderBy('products.name', 'asc');
                    break;
            }
        } else {
            // Tri par défaut si aucun tri spécifié
            $query->join('products', 'stocks.product_id', '=', 'products.id')
                  ->select('stocks.*')
                  ->orderBy('products.name', 'asc');
        }
        
        // Pagination
        $stocks = $query->paginate(20);

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
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $stock->update($validated);
        
        if ($stock->product) {
            $stock->product->quantity = $validated['quantity'];
            $stock->product->save();
        }

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stock mis à jour avec succès');
    }
}