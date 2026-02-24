<?php
// app/Http/Controllers/Shop/HomeController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil de la boutique
     */
    public function index()
    {
        // Récupérer TOUTES les catégories avec le nombre de produits actifs et en stock
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true)
                  ->where('quantity', '>', 0); // Seulement les produits en stock et actifs
        }])
        // Ne pas filtrer les catégories avec where('is_active', true) si la colonne n'existe pas
        ->get();

        // Nouveautés (8 produits les plus récents)
        $newProducts = Product::with(['category', 'supplier'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // Produits en vedette (aléatoire pour l'exemple)
        $featuredProducts = Product::with(['category', 'supplier'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // POUR DÉBOGUER - Ajoutez ceci temporairement
        // Décommentez pour voir dans les logs ce qui se passe
        /*
        \Log::info('Nombre de catégories: ' . $categories->count());
        foreach($categories as $cat) {
            \Log::info('Catégorie: ' . $cat->name . ' - Produits: ' . $cat->products_count);
        }
        */

        return view('shop.home', compact(
            'categories',
            'newProducts',
            'featuredProducts'
        ));
    }
}