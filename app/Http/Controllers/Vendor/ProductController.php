<?php
// app/Http/Controllers/Vendor/ProductController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $categories = Category::where('is_active', true)->get();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'short_description' => 'nullable|string|max:500',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
        'stock_threshold' => 'nullable|integer|min:1',
    ];

    // Si nouvelle catégorie
    if ($request->category_id === 'new') {
        $rules['new_category'] = 'required|string|max:255|unique:categories,name';
    } else {
        $rules['category_id'] = 'required|exists:categories,id';
    }

    $request->validate($rules);

    try {
        DB::beginTransaction();

        $vendor = auth()->user()->vendor;
        
        // 🔴 Vérifions que le vendeur est bien récupéré
        if (!$vendor) {
            throw new \Exception('Vendeur non trouvé');
        }

        \Log::info('Vendor ID: ' . $vendor->id); // Devrait afficher 2

        // Gestion de la catégorie...

        // Préparation des données - VENDOR_ID DOIT ÊTRE PRÉSENT
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $categoryId,
            'vendor_id' => $vendor->id, // ← TRÈS IMPORTANT
            'stock_threshold' => $request->stock_threshold ?? 5,
            'is_active' => true,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);

        DB::commit();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produit ajouté avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur création produit vendeur: ' . $e->getMessage());
        
        return back()->with('error', 'Une erreur est survenue.')
            ->withInput();
    }
}

    public function edit(Product $product)
    {
        // Vérifier que le produit appartient au vendeur
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'stock_threshold' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product->update($data);

            return redirect()->route('vendor.products.index')
                ->with('success', 'Produit mis à jour.');

        } catch (\Exception $e) {
            Log::error('Erreur modification produit: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth()->user()->vendor->id) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produit supprimé.');
    }
}