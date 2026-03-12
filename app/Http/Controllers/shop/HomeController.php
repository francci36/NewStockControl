<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Catégories principales
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->withCount(['products' => function($q) {
                    $q->where('is_active', true)
                      ->where('quantity', '>', 0);
                }]);
            }])
            ->withCount(['products' => function($query) {
                $query->where('is_active', true)
                      ->where('quantity', '>', 0);
            }])
            ->get()
            ->map(function($category) {
                // S'assurer que le nom est accessible
                $category->translated_name = is_array($category->name) 
                    ? ($category->name[app()->getLocale()] ?? $category->name['fr'] ?? 'Catégorie')
                    : $category->name;
                return $category;
            });

        // Nouveautés avec traduction
        $newProducts = Product::with(['category', 'supplier', 'vendor'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get()
            ->map(function($product) {
                $product->translated_name = $product->translated_name; // Utilise l'accesseur
                return $product;
            });

        // Produits vedettes avec traduction
        $featuredProducts = Product::with(['category', 'supplier', 'vendor'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get()
            ->map(function($product) {
                $product->translated_name = $product->translated_name;
                return $product;
            });

        return view('shop.home', compact('categories', 'newProducts', 'featuredProducts'));
    }
}