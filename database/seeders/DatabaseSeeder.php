<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Début des seeders...');
        
        // Ordre important : d'abord les structures, puis les données
        $this->call([
            
            CategorySeeder::class,
            // D'abord les catégories (structure)
            CreateCategoriesSeeder::class,
            
            // Ensuite les produits (dépend des catégories)
            ProductSeeder::class,
            
            // Ensuite les traductions (dépend des produits et catégories)
            TranslationSeeder::class,
            
            // Ensuite les données financières
            FinancialDataSeeder::class,

            MissingTranslationsSeeder::class,
        ]);
        
        $this->command->info('Tous les seeders ont été exécutés avec succès !');
    }
}