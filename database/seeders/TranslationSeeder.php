<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Import des traductions...');
        
        // Traductions pour les messages flash et interface
        $translations = [
            // Messages généraux
            ['fr', 'best_seller', 'Meilleure vente'],
            ['en', 'best_seller', 'Best seller'],
            ['pt', 'best_seller', 'Mais vendido'],
            ['es', 'best_seller', 'Más vendido'],
            ['de', 'best_seller', 'Bestseller'],
            
            ['fr', 'units', 'unités'],
            ['en', 'units', 'units'],
            ['pt', 'units', 'unidades'],
            ['es', 'units', 'unidades'],
            ['de', 'units', 'Einheiten'],
            
            ['fr', 'free_shipping_over', 'Livraison gratuite dès :amount €'],
            ['en', 'free_shipping_over', 'Free shipping over €:amount'],
            ['pt', 'free_shipping_over', 'Frete grátis acima de €:amount'],
            ['es', 'free_shipping_over', 'Envío gratis a partir de :amount €'],
            ['de', 'free_shipping_over', 'Kostenloser Versand ab :amount €'],
        ];
        
        // Si vous utilisez une table 'translations'
        if (Schema::hasTable('translations')) {
            foreach ($translations as [$locale, $key, $value]) {
                DB::table('translations')->updateOrInsert(
                    ['locale' => $locale, 'key' => $key],
                    ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
                );
            }
            $this->command->info('✅ Traductions insérées dans la table translations');
        }
        
        // Appeler les autres seeders de traduction
        $this->call(ProductTranslationSeeder::class);
        
        $this->command->info('✅ Toutes les traductions ont été importées');
    }
}