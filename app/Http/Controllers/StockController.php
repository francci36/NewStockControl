<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        // Vérifier si la table stocks est vide
        if (Stock::count() == 0) {
            Log::warning('Table stocks vide - tentative de synchronisation');
            $this->syncStocks();
        }

        // Récupérer les stocks avec pagination
        $stocks = Stock::with('product')->paginate(20);

        // Log pour vérifier les données
        Log::info('Stocks récupérés : ', [
            'count' => $stocks->count(),
            'total' => $stocks->total()
        ]);

        return view('stocks.index', compact('stocks'));
    }

    /**
     * Synchronise les produits avec la table stocks
     */
    private function syncStocks()
    {
        $products = Product::all();
        $count = 0;
        
        foreach ($products as $product) {
            Stock::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $product->quantity]
            );
            $count++;
        }
        
        Log::info("Synchronisation terminée: $count stocks créés/mis à jour");
    }

    /**
     * Route de synchronisation manuelle
     */
    public function sync()
    {
        try {
            $this->syncStocks();
            return response()->json([
                'success' => true,
                'message' => 'Stocks synchronisés avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $stock->update($validated);
        
        // Mettre à jour aussi le produit correspondant
        if ($stock->product) {
            $stock->product->quantity = $validated['quantity'];
            $stock->product->save();
            Log::info('Stock et produit mis à jour', [
                'stock_id' => $stock->id,
                'product_id' => $stock->product_id,
                'quantity' => $validated['quantity']
            ]);
        }

        return redirect()->route('stocks.index')->with('success', 'Stock mis à jour');
    }
}