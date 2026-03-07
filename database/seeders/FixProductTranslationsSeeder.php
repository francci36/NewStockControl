<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\DB;

class FixProductTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Début de l\'ajout des traductions...');
        
        $locales = ['en', 'pt_BR', 'es', 'de', 'it'];
        $products = Product::all();
        
        $this->command->info('Produits trouvés : ' . $products->count());
        
        $count = 0;
        
        foreach ($products as $product) {
            $this->command->info("Traitement du produit #{$product->id}...");
            
            // Décoder le JSON du nom
            $names = json_decode($product->name, true);
            $descriptions = json_decode($product->description, true);
            $shortDescriptions = json_decode($product->short_description, true);
            
            if (!is_array($names)) {
                $this->command->warn("  ⚠ Erreur JSON pour le produit #{$product->id}");
                continue;
            }
            
            foreach ($locales as $locale) {
                // Vérifier si la traduction existe déjà
                $exists = ProductTranslation::where('product_id', $product->id)
                    ->where('locale', $locale)
                    ->exists();
                
                if (!$exists) {
                    // Prendre la valeur du JSON ou utiliser le français par défaut
                    $name = $names[$locale] ?? $names['fr'] ?? $product->name;
                    $description = $descriptions[$locale] ?? $descriptions['fr'] ?? null;
                    $shortDescription = $shortDescriptions[$locale] ?? $shortDescriptions['fr'] ?? null;
                    
                    ProductTranslation::create([
                        'product_id' => $product->id,
                        'locale' => $locale,
                        'name' => $name,
                        'description' => $description,
                        'short_description' => $shortDescription,
                    ]);
                    
                    $count++;
                    $this->command->info("  ✓ Traduction {$locale} ajoutée");
                } else {
                    $this->command->line("  - Traduction {$locale} déjà existante");
                }
            }
        }
        
        $this->command->info('=================================');
        $this->command->info("RÉSUMÉ : {$count} nouvelles traductions ajoutées");
        $this->command->info('=================================');
    }
}