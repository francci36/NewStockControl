<?php
// database/seeders/SubCategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer la catégorie parente "Vins"
        $vins = Category::where('slug', 'vins')->first();

        if ($vins) {
            $subCategories = [
                [
                    'parent_id' => $vins->id,
                    'name' => ['fr' => 'Vins rouges', 'en' => 'Red wines'],
                    'slug' => 'vins-rouges',
                    'is_active' => true,
                ],
                [
                    'parent_id' => $vins->id,
                    'name' => ['fr' => 'Vins blancs', 'en' => 'White wines'],
                    'slug' => 'vins-blancs',
                    'is_active' => true,
                ],
                [
                    'parent_id' => $vins->id,
                    'name' => ['fr' => 'Champagnes', 'en' => 'Champagnes'],
                    'slug' => 'champagnes',
                    'is_active' => true,
                ],
                [
                    'parent_id' => $vins->id,
                    'name' => ['fr' => 'Spiritueux', 'en' => 'Spirits'],
                    'slug' => 'spiritueux',
                    'is_active' => true,
                ],
            ];

            foreach ($subCategories as $category) {
                Category::updateOrCreate(
                    ['slug' => $category['slug']],
                    $category
                );
            }
        }
    }
}