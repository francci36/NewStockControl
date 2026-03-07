<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
    // IMPORTANT: Ajouter 'vendor' dans les relations
    $query = Product::with(['supplier', 'category', 'vendor']);
    
    // Filtre par recherche (nom, description, SKU)
    if ($request->filled('search')) {
        $search = $request->search;
        $searchTerm = '%' . $search . '%';
        
        $query->where(function($q) use ($searchTerm, $search) {
            $q->where('name', 'like', $searchTerm)
              ->orWhere('description', 'like', $searchTerm)
              ->orWhere('short_description', 'like', $searchTerm)
              ->orWhere('sku', 'like', $searchTerm)
              ->orWhereHas('supplier', function($sq) use ($searchTerm) {
                  $sq->where('name', 'like', $searchTerm);
              })
              ->orWhereHas('vendor', function($vq) use ($searchTerm) {
                  $vq->where('shop_name', 'like', $searchTerm);
              })
              ->orWhereHas('category', function($cq) use ($searchTerm) {
                  $cq->where('name', 'like', $searchTerm);
              });
        });
        
        // Ajouter un score de pertinence pour le tri
        $query->selectRaw('products.*, 
            CASE 
                WHEN products.name LIKE ? THEN 10
                WHEN products.name LIKE ? THEN 5
                WHEN products.sku LIKE ? THEN 3
                WHEN products.description LIKE ? THEN 2
                WHEN products.short_description LIKE ? THEN 1
                ELSE 0
            END as relevance', 
            [
                $search . '%',        // Commence par le terme (score 10)
                '%' . $search . '%',  // Contient le terme dans le nom (score 5)
                '%' . $search . '%',  // Contient le terme dans le SKU (score 3)
                '%' . $search . '%',  // Contient le terme dans la description (score 2)
                '%' . $search . '%'   // Contient le terme dans la courte description (score 1)
            ]
        );
        
        // Trier par pertinence d'abord, puis par nom
        $query->orderBy('relevance', 'desc');
    }
    
    // Filtre par fournisseur OU vendeur (CORRIGÉ)
    if ($request->filled('supplier')) {
        $supplierValue = $request->supplier;
        
        if (strpos($supplierValue, 'supplier_') === 0) {
            // C'est un fournisseur classique
            $supplierId = str_replace('supplier_', '', $supplierValue);
            $query->where('supplier_id', $supplierId);
        } elseif (strpos($supplierValue, 'vendor_') === 0) {
            // C'est un vendeur marketplace
            $vendorId = str_replace('vendor_', '', $supplierValue);
            $query->where('vendor_id', $vendorId);
        } else {
            // Ancien format (pour compatibilité)
            $query->where('supplier_id', $supplierValue);
        }
    }
    
    // Filtre par catégorie
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    
    // Filtre par statut
    if ($request->filled('status')) {
        if ($request->status == 'active') {
            $query->where('is_active', true);
        } elseif ($request->status == 'inactive') {
            $query->where('is_active', false);
        }
    }
    
    // Filtre par stock
    if ($request->filled('stock')) {
        switch ($request->stock) {
            case 'low':
                // Stock bas : quantité <= seuil (ou <= 5 par défaut)
                $query->whereRaw('quantity <= COALESCE(stock_threshold, 5)');
                break;
            case 'out':
                // Rupture : quantité <= 0
                $query->where('quantity', '<=', 0);
                break;
            case 'available':
                // Disponible : quantité > 0
                $query->where('quantity', '>', 0);
                break;
        }
    }
    
    // Tri alphabétique secondaire (après la pertinence si recherche)
    $query->orderBy('name');
    
    $products = $query->paginate(15);
    
    // 🔥 TRANSFORMATION POUR L'AFFICHAGE ADMIN 🔥
    // Parcourir les produits et ajouter des propriétés pour l'affichage
    $products->getCollection()->transform(function ($product) {
        // Gestion du nom traduit (c'est un tableau JSON)
        if (is_array($product->name)) {
            // Pour l'admin, on prend le français ou la première langue disponible
            $product->admin_name = $product->name['fr'] ?? 
                                   $product->name[array_key_first($product->name)] ?? 
                                   'Sans nom';
        } else {
            $product->admin_name = $product->name;
        }
        
        // Gestion de la description si nécessaire
        if (isset($product->description) && is_array($product->description)) {
            $product->admin_description = $product->description['fr'] ?? 
                                          $product->description[array_key_first($product->description)] ?? 
                                          '';
        }
        
        // Gestion de la courte description si nécessaire
        if (isset($product->short_description) && is_array($product->short_description)) {
            $product->admin_short_description = $product->short_description['fr'] ?? 
                                                $product->short_description[array_key_first($product->short_description)] ?? 
                                                '';
        }
        
        // Gestion de la catégorie (si elle a aussi des traductions)
        if ($product->category && isset($product->category->name) && is_array($product->category->name)) {
            $product->category->admin_name = $product->category->name['fr'] ?? 
                                             $product->category->name[array_key_first($product->category->name)] ?? 
                                             'Sans catégorie';
        }
        
        return $product;
    });
    
    // Récupérer tous les fournisseurs pour le filtre
    $suppliers = Supplier::orderBy('name')->get();
    
    // Récupérer tous les vendeurs actifs pour le filtre
    $vendors = \App\Models\Vendor::where('status', 'active')->orderBy('shop_name')->get();
    
    // Récupérer toutes les catégories pour le filtre
    $categories = Category::where('is_active', true)->orderBy('name')->get();
    
    // Si c'est une requête AJAX, retourner seulement la partie tableau
    if ($request->ajax()) {
        return view('products._table', compact('products'))->render();
    }
    
    return view('products.index', compact('products', 'suppliers', 'vendors', 'categories'));
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
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('products.create', compact('suppliers', 'supplier_id', 'categories'));
    }

    /**
     * Enregistre un nouveau produit.
     */
    public function store(Request $request)
{
    // Log des données reçues
    Log::info('=== DÉBUT STORE PRODUIT ===');
    Log::info('Données brutes:', $request->all());
    
    try {
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

        Log::info('Validation passée', $validatedData);

        DB::beginTransaction();

        foreach ($validatedData['products'] as $index => $productData) {
            Log::info('Création produit ' . $index, $productData);
            
            // Gestion de l'image
            $imagePath = null;
            if ($request->hasFile("products.$index.image")) {
                $imagePath = $request->file("products.$index.image")->store('products', 'public');
                Log::info('Image sauvegardée: ' . $imagePath);
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

            Log::info('Produit créé avec ID: ' . $product->id);

            // Générer le SKU
            $category = Category::find($productData['category_id']);
            $categoryPrefix = $category ? strtoupper(substr($category->name, 0, 3)) : 'GEN';
            $product->sku = $categoryPrefix . '-' . str_pad($product->id, 6, '0', STR_PAD_LEFT);
            $product->save();
            
            Log::info('SKU généré: ' . $product->sku);

            Stock::create([
                'product_id' => $product->id,
                'quantity' => 0,
            ]);
            
            Log::info('Stock créé pour produit ' . $product->id);
        }

        DB::commit();
        Log::info('Transaction COMMIT réussie');
        
        return redirect()->route('admin.products.index')
            ->with('success', count($validatedData['products']) . ' produit(s) ajouté(s) avec succès.');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Erreur de validation:', $e->errors());
        throw $e;
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('ERREUR lors de la création:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('admin.products.index')
            ->with('error', 'Erreur : ' . $e->getMessage());
    }
}
    /**
     * Affiche le formulaire d'édition d'un produit.
     */
    public function edit(Product $product)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
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
            'category_id' => 'required|exists:categories,id',
            'stock_threshold' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
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
                'category_id' => $validated['category_id'],
                'stock_threshold' => $validated['stock_threshold'] ?? 5,
                'is_active' => $request->has('is_active'),
            ];

            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $updateData['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($updateData);

            // Mettre à jour ou créer le stock
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

            // Supprimer l'image si elle existe
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Supprimer les stocks associés
            Stock::where('product_id', $product->id)->delete();
            
            // Supprimer le produit
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