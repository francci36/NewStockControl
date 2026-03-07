<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Début des seeders de traduction...');
        
        $this->call(CategoryTranslationSeeder::class);
        $this->call(ProductTranslationSeeder::class);
        
        $this->command->info('Tous les seeders ont été exécutés avec succès !');
        
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}