<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Affiche la liste des produits.
     */
    public function index(Request $request)
    {
        $query = Product::with(['supplier', 'category']); // AJOUT de category
        
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
            $suppliers = collect([$supplier]);
        } else {
            $suppliers = Supplier::all();
        }
        
        // Récupérer toutes les catégories actives
        $categories = Category::where('is_active', true)->get();

        return view('products.create', compact('suppliers', 'supplier_id', 'categories'));
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
            'products.*.short_description' => 'nullable|string|max:255',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.category_id' => 'required|exists:categories,id',
            'products.*.stock_threshold' => 'nullable|integer|min:1',
            'products.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validatedData['products'] as $index => $productData) {
                // Gestion de l'image
                $imagePath = null;
                if ($request->hasFile("products.$index.image")) {
                    $imagePath = $request->file("products.$index.image")->store('products', 'public');
                }

                $product = Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'] ?? null,
                    'short_description' => $productData['short_description'] ?? null,
                    'price' => $productData['price'],
                    'supplier_id' => $validatedData['supplier_id'],
                    'category_id' => $productData['category_id'],
                    'quantity' => 0,
                    'stock_threshold' => $productData['stock_threshold'] ?? 5,
                    'image' => $imagePath,
                    'is_active' => true,
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'quantity' => 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produits ajoutés avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'ajout des produits : ' . $e->getMessage());
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Une erreur est survenue lors de l\'ajout des produits.');
        }
    }

    /**
     * Affiche le formulaire d'édition d'un produit.
     */
    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = Category::where('is_active', true)->get(); // AJOUT
        
        return view('products.edit', compact('product', 'suppliers', 'categories'));
    }

    /**
     * Met à jour un produit.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id', // AJOUT
            'stock_threshold' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'supplier_id' => $validated['supplier_id'],
                'category_id' => $validated['category_id'], // AJOUT
                'stock_threshold' => $validated['stock_threshold'] ?? 5,
            ];

            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $updateData['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($updateData);

            Stock::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $validated['quantity']]
            );

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour produit: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors de la mise à jour.')
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

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            Stock::where('product_id', $product->id)->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression produit: ' . $e->getMessage());
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Erreur lors de la suppression.');
        }
    }
}