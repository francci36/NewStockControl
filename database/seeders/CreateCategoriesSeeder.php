<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CreateCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Catégories principales
            [
                'name' => [
                    'fr' => 'Alimentation pour chiens',
                    'en' => 'Dog Food',
                    'pt' => 'Alimentação para cães',
                    'es' => 'Alimento para perros',
                    'de' => 'Hundefutter',
                ],
                'slug' => 'alimentation-chiens',
                'is_active' => true,
            ],
            [
                'name' => [ 
                    'fr' => 'Alimentation pour chats',
                    'en' => 'Cat Food',             
                    'pt' => 'Alimentação para gatos',
                    'es' => 'Alimento para gatos',
                    'de' => 'Katzenfutter',
                ],
                'slug' => 'alimentation-chats',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Accessoires',
                    'en' => 'Accessories',
                    'pt' => 'Acessórios',
                    'es' => 'Accesorios',
                    'de' => 'Zubehör',
                ],
                'slug' => 'accessoires',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Soins et santé',
                    'en' => 'Health Care',
                    'pt' => 'Cuidados e saúde',
                    'es' => 'Cuidados y salud',
                    'de' => 'Gesundheitspflege',
                ],
                'slug' => 'soins-sante',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Aquariophilie',
                    'en' => 'Aquariums',
                    'pt' => 'Aquariofilia',
                    'es' => 'Acuariofilia',
                    'de' => 'Aquaristik',
                ],
                'slug' => 'aquariophilie',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Vins',
                    'en' => 'Wines',
                    'pt' => 'Vinhos',
                    'es' => 'Vinos',
                    'de' => 'Weine',
                ],
                'slug' => 'vins',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Produit de cuisine',
                    'en' => 'Kitchen Products',
                    'pt' => 'Produtos de cozinha',
                    'es' => 'Productos de cocina',
                    'de' => 'Küchenprodukte',
                ],
                'slug' => 'produit-cuisine',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Jardin',
                    'en' => 'Garden',
                    'pt' => 'Jardim',
                    'es' => 'Jardín',
                    'de' => 'Garten',
                ],
                'slug' => 'jardin',
                'is_active' => true,
            ],
            
            // Sous-catégories pour Vins
            [
                'name' => [
                    'fr' => 'Vins rouges',
                    'en' => 'Red wines',
                    'pt' => 'Vinhos tintos',
                    'es' => 'Vinos tintos',
                    'de' => 'Rotweine',
                ],
                'slug' => 'vins-rouges',
                'parent_slug' => 'vins',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Vins blancs',
                    'en' => 'White wines',
                    'pt' => 'Vinhos brancos',
                    'es' => 'Vinos blancos',
                    'de' => 'Weißweine',
                ],
                'slug' => 'vins-blancs',
                'parent_slug' => 'vins',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Champagnes',
                    'en' => 'Champagnes',
                    'pt' => 'Champanhes',
                    'es' => 'Champagnes',
                    'de' => 'Champagner',
                ],
                'slug' => 'champagnes',
                'parent_slug' => 'vins',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $catData) {
            // Gérer la relation parent
            $parentId = null;
            if (isset($catData['parent_slug'])) {
                $parent = Category::where('slug', $catData['parent_slug'])->first();
                $parentId = $parent ? $parent->id : null;
                unset($catData['parent_slug']);
            }
            
            $catData['parent_id'] = $parentId;
            
            // Créer ou mettre à jour
            $category = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
            
            $this->command->info("✓ Catégorie: {$catData['name']['fr']}");
        }

        $this->command->info("✅ " . count($categories) . " catégories créées/mises à jour");
    }
}