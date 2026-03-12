<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => [
                    'fr' => 'Alimentation pour chiens',
                    'en' => 'Dog Food',
                    'pt' => 'Alimentação para cães',
                    'es' => 'Alimento para perros',
                    'de' => 'Hundefutter',
                ],
                'slug' => 'alimentation-chiens',
                'description' => [
                    'fr' => 'Tous les aliments pour chiens',
                    'en' => 'All dog food products',
                    'pt' => 'Todos os alimentos para cães',
                    'es' => 'Todos los alimentos para perros',
                    'de' => 'Alle Hundefutterprodukte',
                ],
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
                'description' => [
                    'fr' => 'Tous les aliments pour chats',
                    'en' => 'All cat food products',
                    'pt' => 'Todos os alimentos para gatos',
                    'es' => 'Todos los alimentos para gatos',
                    'de' => 'Alle Katzenfutterprodukte',
                ],
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
                    'fr' => 'Vins',
                    'en' => 'Wines',
                    'pt' => 'Vinhos',
                    'es' => 'Vinos',
                    'de' => 'Weine',
                ],
                'slug' => 'vins',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Ajouter des sous-catégories
        $vins = Category::where('slug', 'vins')->first();
        if ($vins) {
            $subCategories = [
                [
                    'name' => [
                        'fr' => 'Vins rouges',
                        'en' => 'Red wines',
                        'pt' => 'Vinhos tintos',
                        'es' => 'Vinos tintos',
                        'de' => 'Rotweine',
                    ],
                    'slug' => 'vins-rouges',
                    'parent_id' => $vins->id,
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
                    'parent_id' => $vins->id,
                    'is_active' => true,
                ],
            ];

            foreach ($subCategories as $subData) {
                Category::updateOrCreate(
                    ['slug' => $subData['slug']],
                    $subData
                );
            }
        }

        $this->command->info('Catégories créées avec succès !');
    }
}