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
    public function index()
    {
        $transactions = Transaction::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::with('supplier')
            ->orderBy('name')
            ->get();
            
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'numeric|min:0',
            'type' => 'required|in:entry,exit',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->product_id as $index => $productId) {
                $product = Product::findOrFail($productId);
                
                // Vérification du stock pour les sorties
                if ($request->type === 'exit') {
                    $totalQuantity = $request->quantity[$index];
                    
                    if ($product->quantity < $totalQuantity) {
                        throw new \Exception("Stock insuffisant pour {$product->name}. Stock: {$product->quantity}, demandé: {$totalQuantity}");
                    }
                }

                // Création de la transaction
                $transaction = Transaction::create([
                    'product_id' => $productId,
                    'quantity' => $request->quantity[$index],
                    'price' => $request->price[$index],
                    'type' => $request->type,
                    'user_id' => auth()->id(), // Enregistrer qui a fait la transaction
                ]);

                // Mise à jour du stock du produit
                if ($request->type === 'entry') {
                    $product->quantity += $request->quantity[$index];
                    Log::info('Entrée de stock manuelle', [
                        'product' => $product->name,
                        'quantite' => $request->quantity[$index],
                        'user' => auth()->user()->name
                    ]);
                } else {
                    $product->quantity -= $request->quantity[$index];
                    Log::info('Sortie de stock manuelle', [
                        'product' => $product->name,
                        'quantite' => $request->quantity[$index],
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
}