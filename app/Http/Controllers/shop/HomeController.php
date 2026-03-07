<?php
// app/Http/Controllers/Shop/HomeController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer UNIQUEMENT les catégories principales (sans parent)
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
            ->get();

        // Nouveautés (8 produits les plus récents)
        $newProducts = Product::with(['category', 'supplier', 'vendor'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // Produits en vedette
        $featuredProducts = Product::with(['category', 'supplier', 'vendor'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('shop.home', compact('categories', 'newProducts', 'featuredProducts'));
    }
}