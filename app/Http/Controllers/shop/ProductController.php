<?php
// app/Http/Controllers/Shop/ProductController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'vendor', 'supplier'])
            ->where('is_active', true)
            ->where('quantity', '>', 0);

        // Filtre par catégorie (slug)
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                // Si c'est une catégorie principale, montrer aussi ses sous-catégories
                if ($category->children->isNotEmpty()) {
                    $categoryIds = $category->children->pluck('id')->push($category->id);
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'newest');
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
            default: // 'newest'
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        
        // Récupérer les catégories pour le filtre
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->whereHas('products', function($q) {
                $q->where('is_active', true)->where('quantity', '>', 0);
            })
            ->get();

        return view('shop.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // Vérifier que le produit est actif et en stock
        if (!$product->is_active || $product->quantity <= 0) {
            abort(404);
        }

        // Produits similaires
        $similarProducts = Product::with(['category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->limit(4)
            ->get();

        return view('shop.products.show', compact('product', 'similarProducts'));
    }
}