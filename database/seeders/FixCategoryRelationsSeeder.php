<?php
// database/seeders/FixCategoryRelationsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class FixCategoryRelationsSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer la catégorie parente "Vins"
        $vins = Category::where('slug', 'vins')->first();

        if ($vins) {
            // Liste des sous-catégories à rattacher à "Vins"
            $subCategories = ['vins-rouges', 'vins-blancs', 'champagnes', 'spiritueux'];
            
            foreach ($subCategories as $slug) {
                $subCat = Category::where('slug', $slug)->first();
                if ($subCat) {
                    $subCat->parent_id = $vins->id;
                    $subCat->save();
                    $this->command->info("✓ {$subCat->name['fr']} rattaché à {$vins->name['fr']}");
                }
            }
        }

        // Vous pouvez faire de même pour d'autres catégories si nécessaire
        // Par exemple pour "Alimentation pour chiens" avec des sous-catégories
    }
}