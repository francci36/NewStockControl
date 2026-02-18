<?php
// app/Http/Controllers/Shop/ProductController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Affiche la liste des produits
     */
    public function index(Request $request)
    {
        $query = Product::with('supplier')
            ->where('quantity', '>', 0); // Seulement les produits en stock

        // Filtre par recherche
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par fournisseur
        if ($request->has('supplier') && !empty($request->supplier)) {
            $query->where('supplier_id', $request->supplier);
        }

        // Filtre par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
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
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        return view('shop.products.index', compact('products'));
    }

    /**
     * Affiche le détail d'un produit
     */
    public function show(Product $product)
    {
        // Vérifier que le produit est disponible
        if ($product->quantity <= 0) {
            return redirect()->route('shop.products.index')
                ->with('error', 'Ce produit n\'est pas disponible.');
        }

        // Produits similaires (même fournisseur)
        $relatedProducts = Product::where('supplier_id', $product->supplier_id)
            ->where('id', '!=', $product->id)
            ->where('quantity', '>', 0)
            ->take(4)
            ->get();

        return view('shop.products.show', compact('product', 'relatedProducts'));
    }
}