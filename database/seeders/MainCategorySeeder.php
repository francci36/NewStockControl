<?php
// database/seeders/MainCategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class MainCategorySeeder extends Seeder
{
    public function run(): void
    {
        $mainCategories = [
            [
                'name' => ['fr' => 'Vins', 'en' => 'Wines'],
                'slug' => 'vins',
                'is_active' => true,
            ],
            [
                'name' => ['fr' => 'Alimentation pour chiens', 'en' => 'Dog Food'],
                'slug' => 'alimentation-chiens',
                'is_active' => true,
            ],
            [
                'name' => ['fr' => 'Alimentation pour chats', 'en' => 'Cat Food'],
                'slug' => 'alimentation-chats',
                'is_active' => true,
            ],
            [
                'name' => ['fr' => 'Accessoires', 'en' => 'Accessories'],
                'slug' => 'accessoires',
                'is_active' => true,
            ],
            [
                'name' => ['fr' => 'Soins et santé', 'en' => 'Health Care'],
                'slug' => 'soins-sante',
                'is_active' => true,
            ],
            [
                'name' => ['fr' => 'Aquariophilie', 'en' => 'Aquariums'],
                'slug' => 'aquariophilie',
                'is_active' => true,
            ],
            [
                'name' => ['fr' => 'Jardin', 'en' => 'Garden'],
                'slug' => 'jardin',
                'is_active' => true,
            ],
        ];

        foreach ($mainCategories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}