<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Ajout des traductions manquantes...');

        // Traductions pour l'allemand (de)
        $germanTranslations = [
            // Filtres et recherche
            ['locale' => 'de', 'key' => 'price', 'value' => 'Preis'],
            ['locale' => 'de', 'key' => 'min', 'value' => 'Min'],
            ['locale' => 'de', 'key' => 'max', 'value' => 'Max'],
            ['locale' => 'de', 'key' => 'all', 'value' => 'Alle'],
            ['locale' => 'de', 'key' => 'filter_search', 'value' => 'Filtern & Suchen'],
            ['locale' => 'de', 'key' => 'filter_by_category', 'value' => 'Kategorie'],
            ['locale' => 'de', 'key' => 'filter_by_vendor', 'value' => 'Verkauft von'],
            ['locale' => 'de', 'key' => 'filter_by_stock', 'value' => 'Bestand'],
            ['locale' => 'de', 'key' => 'in_stock_only', 'value' => 'Nur auf Lager'],
            ['locale' => 'de', 'key' => 'sort_by', 'value' => 'Sortieren'],
            ['locale' => 'de', 'key' => 'apply_filters', 'value' => 'Filter anwenden'],
            ['locale' => 'de', 'key' => 'reset_filters', 'value' => 'Filter zurücksetzen'],
            
            // Pagination
            ['locale' => 'de', 'key' => 'showing', 'value' => 'Zeige'],
            ['locale' => 'de', 'key' => 'to', 'value' => 'bis'],
            ['locale' => 'de', 'key' => 'of', 'value' => 'von'],
            ['locale' => 'de', 'key' => 'results', 'value' => 'Ergebnissen'],
            
            // Fournisseurs/vendeurs
            ['locale' => 'de', 'key' => 'sold_by', 'value' => 'Verkauft von'],
            ['locale' => 'de', 'key' => 'supplier', 'value' => 'Lieferant'],
        ];

        // Traductions pour le français (fr)
        $frenchTranslations = [
            ['locale' => 'fr', 'key' => 'price', 'value' => 'Prix'],
            ['locale' => 'fr', 'key' => 'min', 'value' => 'Min'],
            ['locale' => 'fr', 'key' => 'max', 'value' => 'Max'],
            ['locale' => 'fr', 'key' => 'all', 'value' => 'Tous'],
            ['locale' => 'fr', 'key' => 'filter_search', 'value' => 'Filtrer & Rechercher'],
            ['locale' => 'fr', 'key' => 'filter_by_category', 'value' => 'Catégorie'],
            ['locale' => 'fr', 'key' => 'filter_by_vendor', 'value' => 'Vendu par'],
            ['locale' => 'fr', 'key' => 'filter_by_stock', 'value' => 'Stock'],
            ['locale' => 'fr', 'key' => 'in_stock_only', 'value' => 'En stock uniquement'],
            ['locale' => 'fr', 'key' => 'sort_by', 'value' => 'Trier par'],
            ['locale' => 'fr', 'key' => 'apply_filters', 'value' => 'Appliquer les filtres'],
            ['locale' => 'fr', 'key' => 'reset_filters', 'value' => 'Réinitialiser les filtres'],
            
            ['locale' => 'fr', 'key' => 'showing', 'value' => 'Affichage de'],
            ['locale' => 'fr', 'key' => 'to', 'value' => 'à'],
            ['locale' => 'fr', 'key' => 'of', 'value' => 'sur'],
            ['locale' => 'fr', 'key' => 'results', 'value' => 'résultats'],
        ];

        // Traductions pour l'anglais (en)
        $englishTranslations = [
            ['locale' => 'en', 'key' => 'price', 'value' => 'Price'],
            ['locale' => 'en', 'key' => 'min', 'value' => 'Min'],
            ['locale' => 'en', 'key' => 'max', 'value' => 'Max'],
            ['locale' => 'en', 'key' => 'all', 'value' => 'All'],
            ['locale' => 'en', 'key' => 'filter_search', 'value' => 'Filter & Search'],
            ['locale' => 'en', 'key' => 'filter_by_category', 'value' => 'Category'],
            ['locale' => 'en', 'key' => 'filter_by_vendor', 'value' => 'Sold by'],
            ['locale' => 'en', 'key' => 'filter_by_stock', 'value' => 'Stock'],
            ['locale' => 'en', 'key' => 'in_stock_only', 'value' => 'In stock only'],
            ['locale' => 'en', 'key' => 'sort_by', 'value' => 'Sort by'],
            ['locale' => 'en', 'key' => 'apply_filters', 'value' => 'Apply filters'],
            ['locale' => 'en', 'key' => 'reset_filters', 'value' => 'Reset filters'],
            
            ['locale' => 'en', 'key' => 'showing', 'value' => 'Showing'],
            ['locale' => 'en', 'key' => 'to', 'value' => 'to'],
            ['locale' => 'en', 'key' => 'of', 'value' => 'of'],
            ['locale' => 'en', 'key' => 'results', 'value' => 'results'],
        ];

        // Traductions pour l'espagnol (es)
        $spanishTranslations = [
            ['locale' => 'es', 'key' => 'price', 'value' => 'Precio'],
            ['locale' => 'es', 'key' => 'min', 'value' => 'Mín'],
            ['locale' => 'es', 'key' => 'max', 'value' => 'Máx'],
            ['locale' => 'es', 'key' => 'all', 'value' => 'Todos'],
            ['locale' => 'es', 'key' => 'showing', 'value' => 'Mostrando'],
            ['locale' => 'es', 'key' => 'to', 'value' => 'a'],
            ['locale' => 'es', 'key' => 'of', 'value' => 'de'],
            ['locale' => 'es', 'key' => 'results', 'value' => 'resultados'],
        ];

        // Traductions pour le portugais (pt)
        $portugueseTranslations = [
            ['locale' => 'pt', 'key' => 'price', 'value' => 'Preço'],
            ['locale' => 'pt', 'key' => 'min', 'value' => 'Mín'],
            ['locale' => 'pt', 'key' => 'max', 'value' => 'Máx'],
            ['locale' => 'pt', 'key' => 'all', 'value' => 'Todos'],
            ['locale' => 'pt', 'key' => 'showing', 'value' => 'Mostrando'],
            ['locale' => 'pt', 'key' => 'to', 'value' => 'até'],
            ['locale' => 'pt', 'key' => 'of', 'value' => 'de'],
            ['locale' => 'pt', 'key' => 'results', 'value' => 'resultados'],
        ];

        // Fusionner toutes les traductions
        $allTranslations = array_merge(
            $germanTranslations,
            $frenchTranslations,
            $englishTranslations,
            $spanishTranslations,
            $portugueseTranslations
        );

        // Compter les insertions
        $count = 0;

        foreach ($allTranslations as $translation) {
            // Vérifier si la traduction existe déjà
            $exists = DB::table('translations')
                ->where('locale', $translation['locale'])
                ->where('key', $translation['key'])
                ->exists();

            if (!$exists) {
                DB::table('translations')->insert([
                    'locale' => $translation['locale'],
                    'key' => $translation['key'],
                    'value' => $translation['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
            }
        }

        $this->command->info("✅ {$count} nouvelles traductions ajoutées avec succès !");
        
        // Afficher un résumé par langue
        $this->command->info("\nRésumé par langue :");
        $locales = ['de', 'fr', 'en', 'es', 'pt'];
        foreach ($locales as $locale) {
            $total = DB::table('translations')->where('locale', $locale)->count();
            $this->command->line("   {$locale}: {$total} traductions");
        }
    }
}