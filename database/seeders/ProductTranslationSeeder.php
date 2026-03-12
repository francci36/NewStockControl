<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $locales = ['en', 'pt', 'es', 'de'];
        $products = Product::all();
        
        $count = 0;
        
        foreach ($products as $product) {
            $names = json_decode($product->name, true);
            $descriptions = json_decode($product->description, true);
            $shortDescriptions = json_decode($product->short_description, true);
            
            foreach ($locales as $locale) {
                if (isset($names[$locale])) {
                    DB::table('product_translations')->updateOrInsert(
                        [
                            'product_id' => $product->id,
                            'locale' => $locale,
                        ],
                        [
                            'name' => $names[$locale] ?? $names['fr'],
                            'description' => $descriptions[$locale] ?? $descriptions['fr'] ?? null,
                            'short_description' => $shortDescriptions[$locale] ?? $shortDescriptions['fr'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                    $count++;
                }
            }
        }
        
        $this->command->info("✅ {$count} traductions de produits créées/mises à jour");
    }
}