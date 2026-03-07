<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Début de l\'import des traductions...');
        $this->command->info('=================================');
        
        // Appeler les seeders de traductions
        $this->call(CategoryTranslationSeeder::class);
        $this->call(ProductTranslationSeeder::class);
        
        $this->command->info('=================================');
        $this->command->info('Toutes les traductions ont été importées avec succès !');
    }
}