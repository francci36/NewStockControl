<?php
// app/Http/Controllers/Vendor/ProductController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        $products = $vendor->products()->with('category')->paginate(15);
        
        return view('vendor.products.index', compact('products'));
    }

        public function create()
    {
        // Récupérer uniquement les catégories principales (sans parent)
        $mainCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('vendor.products.create', compact('mainCategories'));
    }

    public function store(Request $request)
    {
        // Validation avec les nouveaux champs de traduction
        $rules = [
            'name_fr' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'short_description_fr' => 'nullable|string|max:500',
            'short_description_en' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'stock_threshold' => 'nullable|integer|min:1',
        ];

        // Gestion de la catégorie (nouvelle ou existante)
        if ($request->category_id === 'new') {
            $rules['new_category_fr'] = 'required|string|max:255';
            $rules['new_category_en'] = 'nullable|string|max:255';
        } else {
            $rules['category_id'] = 'required|exists:categories,id';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $vendor = auth()->user()->vendor;
            
            if (!$vendor) {
                throw new \Exception('Vendeur non trouvé');
            }

            // ✅ GESTION DE LA CATÉGORIE
            $categoryId = null;
            
            if ($request->category_id === 'new') {
                // Créer une nouvelle catégorie avec traductions
                $slug = Str::slug($request->new_category_fr);
                
                // Vérifier l'unicité du slug
                $originalSlug = $slug;
                $counter = 1;
                while (Category::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $category = Category::create([
                    'name' => [
                        'fr' => $request->new_category_fr,
                        'en' => $request->new_category_en ?? $request->new_category_fr,
                    ],
                    'slug' => $slug,
                    'is_active' => true
                ]);
                
                $categoryId = $category->id;
                Log::info('Nouvelle catégorie créée: ' . $request->new_category_fr . ' (ID: ' . $categoryId . ')');
            } else {
                $categoryId = $request->category_id;
                Log::info('Catégorie existante sélectionnée: ID ' . $categoryId);
            }

            // ✅ PRÉPARATION DES DONNÉES AVEC TRADUCTIONS
            $data = [
                'name' => [
                    'fr' => $request->name_fr,
                    'en' => $request->name_en ?? $request->name_fr,
                ],
                'description' => [
                    'fr' => $request->description_fr,
                    'en' => $request->description_en,
                ],
                'short_description' => [
                    'fr' => $request->short_description_fr,
                    'en' => $request->short_description_en,
                ],
                'price' => $request->price,
                'quantity' => $request->quantity,
                'category_id' => $categoryId,
                'vendor_id' => $vendor->id,
                'stock_threshold' => $request->stock_threshold ?? 5,
                'is_active' => true,
            ];

            // Gestion de l'image
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
                Log::info('Image sauvegardée: ' . $path);
            }

            // Création du produit
            $product = Product::create($data);
            Log::info('Produit créé avec ID: ' . $product->id);

            // ✅ CRÉATION DU STOCK
            Stock::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
            Log::info('Stock créé pour le produit');

            // ✅ GÉNÉRATION DU SKU
            $category = Category::find($categoryId);
            $categoryName = is_array($category->name) ? ($category->name['fr'] ?? '') : $category->name;
            $categoryPrefix = $category ? strtoupper(substr($categoryName, 0, 3)) : 'VEN';
            $product->sku = $categoryPrefix . '-' . str_pad($product->id, 6, '0', STR_PAD_LEFT);
            $product->save();
            Log::info('SKU généré: ' . $product->sku);

            DB::commit();

            return redirect()->route('vendor.products.index')
                ->with('success', 'Produit "' . ($request->name_fr) . '" ajouté avec succès. SKU: ' . $product->sku);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création produit vendeur: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        // Vérifier que le produit appartient bien au vendeur connecté
        $vendor = auth()->user()->vendor;
        if ($product->vendor_id != $vendor->id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce produit.');
        }

        // Récupérer les catégories actives
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        // Validation avec les champs de traduction
        $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'short_description_fr' => 'nullable|string|max:500',
            'short_description_en' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'stock_threshold' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // ✅ PRÉPARATION DES DONNÉES AVEC TRADUCTIONS
            $data = [
                'name' => [
                    'fr' => $request->name_fr,
                    'en' => $request->name_en ?? $request->name_fr,
                ],
                'description' => [
                    'fr' => $request->description_fr,
                    'en' => $request->description_en,
                ],
                'short_description' => [
                    'fr' => $request->short_description_fr,
                    'en' => $request->short_description_en,
                ],
                'price' => $request->price,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'stock_threshold' => $request->stock_threshold ?? 5,
                'is_active' => $request->has('is_active'),
            ];

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product->update($data);

            // Mettre à jour le stock
            Stock::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $request->quantity]
            );

            DB::commit();

            return redirect()->route('vendor.products.index')
                ->with('success', 'Produit "' . $request->name_fr . '" mis à jour.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur modification produit: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue.')
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // Supprimer l'image si elle existe
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Supprimer le stock associé
            Stock::where('product_id', $product->id)->delete();
            
            // Supprimer le produit
            $product->delete();

            DB::commit();

            return redirect()->route('vendor.products.index')
                ->with('success', 'Produit supprimé.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression produit: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}