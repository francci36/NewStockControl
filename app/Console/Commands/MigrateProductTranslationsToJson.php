<?php
// app/Console/Commands/MigrateProductTranslationsToJson.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use DB;

class MigrateProductTranslationsToJson extends Command
{
    protected $signature = 'translations:migrate-products';
    protected $description = 'Migrate product translations from table to JSON columns';

    public function handle()
    {
        $this->info('🔍 Migration des traductions produits vers JSON...');
        
        // Récupérer toutes les traductions
        $translations = DB::table('product_translations')->get();
        $grouped = $translations->groupBy('product_id');
        
        $this->info("📊 {$translations->count()} traductions trouvées pour {$grouped->count()} produits");
        
        $bar = $this->output->createProgressBar($grouped->count());
        $bar->start();
        
        $count = 0;
        $errors = 0;
        
        foreach ($grouped as $productId => $items) {
            try {
                $product = Product::find($productId);
                if (!$product) {
                    $this->warn("\n⚠️  Produit #{$productId} non trouvé, ignoré");
                    $errors++;
                    $bar->advance();
                    continue;
                }
                
                // Préparer les tableaux de traductions
                $names = [];
                $descriptions = [];
                $shortDescriptions = [];
                
                foreach ($items as $item) {
                    // Normaliser la locale (pt_BR -> pt, en -> en, etc.)
                    $locale = $item->locale;
                    if (str_contains($locale, '_')) {
                        $locale = explode('_', $locale)[0];
                    }
                    
                    $names[$locale] = $item->name;
                    
                    if ($item->description) {
                        $descriptions[$locale] = $item->description;
                    }
                    
                    if ($item->short_description) {
                        $shortDescriptions[$locale] = $item->short_description;
                    }
                }
                
                // Récupérer les traductions existantes
                $currentName = is_string($product->name) ? json_decode($product->name, true) : ($product->name ?? []);
                $currentDesc = is_string($product->description) ? json_decode($product->description, true) : ($product->description ?? []);
                $currentShortDesc = is_string($product->short_description) ? json_decode($product->short_description, true) : ($product->short_description ?? []);
                
                if (!is_array($currentName)) $currentName = [];
                if (!is_array($currentDesc)) $currentDesc = [];
                if (!is_array($currentShortDesc)) $currentShortDesc = [];
                
                // Fusionner avec priorité aux nouvelles traductions
                $product->name = array_merge($currentName, $names);
                
                if (!empty($descriptions)) {
                    $product->description = array_merge($currentDesc, $descriptions);
                }
                
                if (!empty($shortDescriptions)) {
                    $product->short_description = array_merge($currentShortDesc, $shortDescriptions);
                }
                
                $product->save();
                $count++;
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->error("\n❌ Erreur produit #{$productId}: " . $e->getMessage());
                $errors++;
                $bar->advance();
            }
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info("✅ {$count} produits migrés avec succès !");
        
        if ($errors > 0) {
            $this->warn("⚠️  {$errors} erreurs rencontrées");
        }
        
        $this->newLine();
        
        // Afficher quelques exemples pour vérification
        $this->info("📝 Exemples de produits migrés :");
        $sampleIds = [5, 6, 27]; // Produits à vérifier
        foreach ($sampleIds as $id) {
            $product = Product::find($id);
            if ($product) {
                $this->line("Produit #{$id}:");
                $this->line("  FR: " . ($product->getTranslation('name', 'fr') ?? 'N/A'));
                $this->line("  EN: " . ($product->getTranslation('name', 'en') ?? 'N/A'));
                $this->line("  ES: " . ($product->getTranslation('name', 'es') ?? 'N/A'));
                $this->line("  DE: " . ($product->getTranslation('name', 'de') ?? 'N/A'));
                $this->line("  PT: " . ($product->getTranslation('name', 'pt') ?? 'N/A'));
                $this->line("  IT: " . ($product->getTranslation('name', 'it') ?? 'N/A'));
            }
        }
        
        $this->newLine();
        $this->warn("📌 Après vérification, supprime la table product_translations avec:");
        $this->warn("   php artisan make:migration drop_product_translations_table");
    }
}