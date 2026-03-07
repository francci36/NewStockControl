<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            'Alimentation pour chiens' => [
                'fr' => 'Alimentation pour chiens',
                'en' => 'Dog Food',
                'pt_BR' => 'Alimentação para cães',
                'es' => 'Alimento para perros',
                'de' => 'Hundefutter',
                'it' => 'Cibo per cani',
            ],
            'Alimentation pour chats' => [
                'fr' => 'Alimentation pour chats',
                'en' => 'Cat Food',
                'pt_BR' => 'Alimentação para gatos',
                'es' => 'Alimento para gatos',
                'de' => 'Katzenfutter',
                'it' => 'Cibo per gatti',
            ],
            'Accessoires' => [
                'fr' => 'Accessoires',
                'en' => 'Accessories',
                'pt_BR' => 'Acessórios',
                'es' => 'Accesorios',
                'de' => 'Zubehör',
                'it' => 'Accessori',
            ],
            'Soins et santé' => [
                'fr' => 'Soins et santé',
                'en' => 'Health Care',
                'pt_BR' => 'Cuidados e saúde',
                'es' => 'Cuidados y salud',
                'de' => 'Gesundheitspflege',
                'it' => 'Cura e salute',
            ],
            'Aquariophilie' => [
                'fr' => 'Aquariophilie',
                'en' => 'Aquariums',
                'pt_BR' => 'Aquariofilia',
                'es' => 'Acuariofilia',
                'de' => 'Aquaristik',
                'it' => 'Acquariofilia',
            ],
            'Vins' => [
                'fr' => 'Vins',
                'en' => 'Wines',
                'pt_BR' => 'Vinhos',
                'es' => 'Vinos',
                'de' => 'Weine',
                'it' => 'Vini',
            ],
            'Produit de cuisine' => [
                'fr' => 'Produit de cuisine',
                'en' => 'Kitchen Products',
                'pt_BR' => 'Produtos de cozinha',
                'es' => 'Productos de cocina',
                'de' => 'Küchenprodukte',
                'it' => 'Prodotti da cucina',
            ],
            'Jardin' => [
                'fr' => 'Jardin',
                'en' => 'Garden',
                'pt_BR' => 'Jardim',
                'es' => 'Jardín',
                'de' => 'Garten',
                'it' => 'Giardino',
            ],
        ];

        $successCount = 0;

        foreach ($translations as $frName => $langs) {
            $category = Category::where('name', $frName)->first();
            
            if ($category) {
                foreach ($langs as $locale => $translatedName) {
                    DB::table('category_translations')->updateOrInsert(
                        [
                            'category_id' => $category->id,
                            'locale' => $locale,
                        ],
                        [
                            'name' => $translatedName,
                            'description' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                $successCount++;
                $this->command->info("✓ Traductions ajoutées pour catégorie: {$frName}");
            } else {
                $this->command->warn("⚠ Catégorie non trouvée: {$frName}");
            }
        }
        
        $this->command->info("=================================");
        $this->command->info("RÉSUMÉ: {$successCount} catégories traduites avec succès");
        $this->command->info("=================================");
    }
}