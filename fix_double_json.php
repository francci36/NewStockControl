<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

echo "🔍 Analyse des données...\n";

// Nettoyer les produits
$products = Product::all();
$productCount = 0;

foreach ($products as $product) {
    $changed = false;
    
    // Nettoyer le nom
    if (is_string($product->name)) {
        $decoded = json_decode($product->name, true);
        if (is_array($decoded)) {
            // Vérifier si les valeurs sont elles-mêmes du JSON
            foreach ($decoded as $key => $value) {
                if (is_string($value) && str_starts_with(trim($value), '{')) {
                    $subDecoded = json_decode($value, true);
                    if (is_array($subDecoded)) {
                        $decoded[$key] = $subDecoded;
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                $product->name = $decoded;
            }
        }
    }
    
    // Nettoyer la description
    if (is_string($product->description)) {
        $decoded = json_decode($product->description, true);
        if (is_array($decoded)) {
            foreach ($decoded as $key => $value) {
                if (is_string($value) && str_starts_with(trim($value), '{')) {
                    $subDecoded = json_decode($value, true);
                    if (is_array($subDecoded)) {
                        $decoded[$key] = $subDecoded;
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                $product->description = $decoded;
            }
        }
    }
    
    // Nettoyer la short_description
    if (isset($product->short_description) && is_string($product->short_description)) {
        $decoded = json_decode($product->short_description, true);
        if (is_array($decoded)) {
            foreach ($decoded as $key => $value) {
                if (is_string($value) && str_starts_with(trim($value), '{')) {
                    $subDecoded = json_decode($value, true);
                    if (is_array($subDecoded)) {
                        $decoded[$key] = $subDecoded;
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                $product->short_description = $decoded;
            }
        }
    }
    
    if ($changed) {
        $product->save();
        $productCount++;
        echo "  ✓ Produit #{$product->id} corrigé\n";
    }
}

// Nettoyer les catégories
$categories = Category::all();
$categoryCount = 0;

foreach ($categories as $category) {
    $changed = false;
    
    if (is_string($category->name)) {
        $decoded = json_decode($category->name, true);
        if (is_array($decoded)) {
            foreach ($decoded as $key => $value) {
                if (is_string($value) && str_starts_with(trim($value), '{')) {
                    $subDecoded = json_decode($value, true);
                    if (is_array($subDecoded)) {
                        $decoded[$key] = $subDecoded;
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                $category->name = $decoded;
            }
        }
    }
    
    if (isset($category->description) && is_string($category->description)) {
        $decoded = json_decode($category->description, true);
        if (is_array($decoded)) {
            foreach ($decoded as $key => $value) {
                if (is_string($value) && str_starts_with(trim($value), '{')) {
                    $subDecoded = json_decode($value, true);
                    if (is_array($subDecoded)) {
                        $decoded[$key] = $subDecoded;
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                $category->description = $decoded;
            }
        }
    }
    
    if ($changed) {
        $category->save();
        $categoryCount++;
        echo "  ✓ Catégorie #{$category->id} corrigée\n";
    }
}

echo "\n✅ Récapitulatif :\n";
echo "   - {$productCount} produits corrigés\n";
echo "   - {$categoryCount} catégories corrigées\n";
echo "   - Total: " . ($productCount + $categoryCount) . " éléments corrigés\n";
