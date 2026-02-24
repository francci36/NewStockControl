<?php
// app/Http/Controllers/Shop/ProductController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['supplier', 'category', 'vendor'])
            ->where('quantity', '>', 0) // Seulement les produits en stock
            ->where('is_active', true); // Seulement les produits actifs
            

        // 🔍 FILTRE PAR RECHERCHE
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('short_description', 'like', '%' . $search . '%');
            });
        }

        // 📁 FILTRE PAR CATÉGORIE
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 🏭 FILTRE PAR FOURNISSEUR OU VENDEUR
        if ($request->has('vendor') && !empty($request->vendor)) {
            $query->where('vendor_id', $request->vendor);
        }

        if ($request->has('supplier') && !empty($request->supplier)) {
            $query->where('supplier_id', $request->supplier);
        }

        // 💰 FILTRE PAR PRIX
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // 📊 TRI
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // 'latest'
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        // Récupérer les catégories pour le filtre
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        // Récupérer les vendeurs pour le filtre (optionnel)
        $vendors = \App\Models\Vendor::where('status', 'active')->get();

        return view('shop.products.index', compact('products', 'categories', 'vendors'));
    }

    public function show(Product $product)
    {
        // Vérifier que le produit est disponible
        if ($product->quantity <= 0 || !$product->is_active) {
            return redirect()->route('shop.products.index')
                ->with('error', 'Ce produit n\'est pas disponible.');
        }

        // Charger les relations
        $product->load(['supplier', 'category', 'vendor']);

        // Produits similaires (même catégorie ou même vendeur)
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->where(function($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('vendor_id', $product->vendor_id);
            })
            ->take(4)
            ->get();

        return view('shop.products.show', compact('product', 'relatedProducts'));
    }
}