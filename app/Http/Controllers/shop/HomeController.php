<?php
// app/Http/Controllers/Shop/HomeController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil de la boutique
     */
    public function index()
    {
        // Produits en vedette (les plus récents avec stock > 0)
        $featuredProducts = Product::with('supplier')
            ->where('quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        // Nouveaux produits
        $newProducts = Product::with('supplier')
            ->where('quantity', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        return view('shop.home', compact(
            'featuredProducts',
            'newProducts'
        ));
    }
}