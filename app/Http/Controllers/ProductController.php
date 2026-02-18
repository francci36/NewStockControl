<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Affiche la liste des produits.
     */
    public function index(Request $request)
    {
        $query = Product::with('supplier');
        
        // Filtre par recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        // Filtre par fournisseur
        if ($request->has('supplier') && !empty($request->supplier)) {
            $query->where('supplier_id', $request->supplier);
        }
        
        $products = $query->orderBy('name')->paginate(15);
        
        // Récupérer tous les fournisseurs pour le filtre
        $suppliers = Supplier::all();
        
        return view('products.index', compact('products', 'suppliers'));
    }

    /**
     * Affiche le formulaire de création de produit.
     */
    public function create($supplier_id = null)
    {
        // Si un fournisseur est spécifié, vérifier qu'il existe
        if ($supplier_id) {
            $supplier = Supplier::findOrFail($supplier_id);
            $suppliers = collect([$supplier]); // Pour la vue, on passe juste ce fournisseur
        } else {
            $suppliers = Supplier::all();
        }

        return view('products.create', compact('suppliers', 'supplier_id'));
    }

    /**
     * Enregistre un nouveau produit.
     */
    public function store(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock_threshold' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Parcourir chaque produit et l'enregistrer
            foreach ($validatedData['products'] as $productData) {
                $product = Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'] ?? null,
                    'price' => $productData['price'],
                    'supplier_id' => $validatedData['supplier_id'],
                    'quantity' => 0, // Initialisation à 0 par défaut
                    'stock_threshold' => $productData['stock_threshold'] ?? 5,
                ]);

                // Créer l'entrée correspondante dans la table stocks
                Stock::create([
                    'product_id' => $product->id,
                    'quantity' => 0,
                ]);
            }

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Produits ajoutés avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'ajout des produits : ', ['error' => $e->getMessage()]);
            
            return redirect()->route('products.index')
                ->with('error', 'Une erreur est survenue lors de l\'ajout des produits.');
        }
    }

    /**
     * Affiche le formulaire d'édition d'un produit.
     */
    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'suppliers'));
    }

    /**
     * Met à jour un produit.
     */
    public function update(Request $request, Product $product)
    {
        // Valider les données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock_threshold' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour le produit
            $product->update($validated);

            // Mettre à jour ou créer l'entrée dans la table stocks
            Stock::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $validated['quantity']]
            );

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Produit mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du produit : ', ['error' => $e->getMessage()]);
            
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du produit.')
                         ->withInput();
        }
    }

    /**
     * Supprime un produit.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Supprimer l'entrée dans la table stocks
            Stock::where('product_id', $product->id)->delete();

            // Supprimer le produit
            $product->delete();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Produit supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du produit : ', ['error' => $e->getMessage()]);
            
            return redirect()->route('products.index')
                ->with('error', 'Une erreur est survenue lors de la suppression du produit.');
        }
    }
}