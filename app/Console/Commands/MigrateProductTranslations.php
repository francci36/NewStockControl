<?php
// app/Console/Commands/MigrateProductTranslations.php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTranslation;
use App\Models\CategoryTranslation;
use Illuminate\Console\Command;

class MigrateProductTranslations extends Command
{
    protected $signature = 'migrate:translations';
    protected $description = 'Migrate existing products to translations tables';

    public function handle()
    {
        $this->info('Migrating products...');
        $products = Product::all();
        $locales = ['fr', 'en', 'de', 'es', 'pt'];
        
        foreach ($products as $product) {
            $name = $product->name;
            $description = $product->description;
            $shortDescription = $product->short_description;
            
            // Si le nom est du JSON, le décoder
            $decodedName = json_decode($name, true);
            $decodedDesc = json_decode($description, true);
            $decodedShort = json_decode($shortDescription, true);
            
            foreach ($locales as $locale) {
                ProductTranslation::updateOrCreate(
                    ['product_id' => $product->id, 'locale' => $locale],
                    [
                        'name' => (json_last_error() === JSON_ERROR_NONE && is_array($decodedName)) 
                            ? ($decodedName[$locale] ?? $decodedName['fr'] ?? $name) 
                            : $name,
                        'description' => (json_last_error() === JSON_ERROR_NONE && is_array($decodedDesc)) 
                            ? ($decodedDesc[$locale] ?? $decodedDesc['fr'] ?? $description) 
                            : $description,
                        'short_description' => (json_last_error() === JSON_ERROR_NONE && is_array($decodedShort)) 
                            ? ($decodedShort[$locale] ?? $decodedShort['fr'] ?? $shortDescription) 
                            : $shortDescription,
                    ]
                );
            }
            $this->info("Product #{$product->id} migrated");
        }
        
        $this->info('Migrating categories...');
        $categories = Category::all();
        
        foreach ($categories as $category) {
            foreach ($locales as $locale) {
                CategoryTranslation::updateOrCreate(
                    ['category_id' => $category->id, 'locale' => $locale],
                    [
                        'name' => $category->name,
                        'description' => $category->description,
                    ]
                );
            }
            $this->info("Category #{$category->id} migrated");
        }
        
        $this->info('Migration completed!');
    }
}