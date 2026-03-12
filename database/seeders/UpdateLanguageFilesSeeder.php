<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UpdateLanguageFilesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Mise à jour des fichiers de langue...');

        // Traductions à ajouter pour chaque langue
        $newTranslations = [
            'de' => [
                'price' => 'Preis',
                'min' => 'Min',
                'max' => 'Max',
                'all' => 'Alle',
                'showing' => 'Zeige',
                'to' => 'bis',
                'of' => 'von',
                'results' => 'Ergebnissen',
                'filter_search' => 'Filtern & Suchen',
                'filter_by_category' => 'Kategorie',
                'filter_by_vendor' => 'Verkauft von',
                'filter_by_stock' => 'Bestand',
                'in_stock_only' => 'Nur auf Lager',
                'sort_by' => 'Sortieren',
                'apply_filters' => 'Filter anwenden',
                'reset_filters' => 'Filter zurücksetzen',
                'sold_by' => 'Verkauft von',
                'supplier' => 'Lieferant',
            ],
            'fr' => [
                'price' => 'Prix',
                'min' => 'Min',
                'max' => 'Max',
                'all' => 'Tous',
                'showing' => 'Affichage de',
                'to' => 'à',
                'of' => 'sur',
                'results' => 'résultats',
                'filter_search' => 'Filtrer & Rechercher',
                'filter_by_category' => 'Catégorie',
                'filter_by_vendor' => 'Vendu par',
                'filter_by_stock' => 'Stock',
                'in_stock_only' => 'En stock uniquement',
                'sort_by' => 'Trier par',
                'apply_filters' => 'Appliquer les filtres',
                'reset_filters' => 'Réinitialiser les filtres',
            ],
            'en' => [
                'price' => 'Price',
                'min' => 'Min',
                'max' => 'Max',
                'all' => 'All',
                'showing' => 'Showing',
                'to' => 'to',
                'of' => 'of',
                'results' => 'results',
                'filter_search' => 'Filter & Search',
                'filter_by_category' => 'Category',
                'filter_by_vendor' => 'Sold by',
                'filter_by_stock' => 'Stock',
                'in_stock_only' => 'In stock only',
                'sort_by' => 'Sort by',
                'apply_filters' => 'Apply filters',
                'reset_filters' => 'Reset filters',
            ],
            'es' => [
                'price' => 'Precio',
                'min' => 'Mín',
                'max' => 'Máx',
                'all' => 'Todos',
                'showing' => 'Mostrando',
                'to' => 'a',
                'of' => 'de',
                'results' => 'resultados',
            ],
            'pt_BR' => [
            'price' => 'Preço',
            'min' => 'Mín',
            'max' => 'Máx',
            'all' => 'Todos',
            'showing' => 'Mostrando',
            'to' => 'até',
            'of' => 'de',
            'results' => 'resultados',
            'filter_search' => 'Filtrar & Buscar',
            'filter_by_category' => 'Categoria',
            'filter_by_vendor' => 'Vendido por',
            'filter_by_stock' => 'Estoque',
            'in_stock_only' => 'Apenas em estoque',
            'sort_by' => 'Ordenar por',
            'apply_filters' => 'Aplicar filtros',
            'reset_filters' => 'Redefinir filtros',
            ],
        ];

        $totalAdded = 0;

        foreach ($newTranslations as $locale => $translations) {
            $filePath = resource_path("lang/{$locale}/messages.php");
            
            if (!File::exists($filePath)) {
                $this->command->warn("⚠ Fichier non trouvé: {$filePath}");
                continue;
            }

            // Lire le fichier existant
            $currentContent = File::get($filePath);
            
            // Extraire le tableau PHP
            $pattern = '/return\s*(\[.*?\])\s*;/s';
            preg_match($pattern, $currentContent, $matches);
            
            if (!isset($matches[1])) {
                $this->command->warn("⚠ Format de fichier non reconnu: {$filePath}");
                continue;
            }

            // Évaluer le tableau existant
            $currentArray = eval("return {$matches[1]};");
            
            // Ajouter les nouvelles traductions
            $added = 0;
            foreach ($translations as $key => $value) {
                if (!isset($currentArray[$key])) {
                    $currentArray[$key] = $value;
                    $added++;
                    $totalAdded++;
                }
            }

            if ($added > 0) {
                // Reconstruire le contenu du fichier
                $newContent = "<?php\n\nreturn " . $this->arrayToPhpString($currentArray) . ";\n";
                
                // Sauvegarder
                File::put($filePath, $newContent);
                $this->command->info("✓ {$locale}: {$added} nouvelles traductions ajoutées");
            } else {
                $this->command->line("- {$locale}: aucune nouvelle traduction");
            }
        }

        $this->command->info("=================================");
        $this->command->info("✅ TOTAL: {$totalAdded} traductions ajoutées");
        $this->command->info("=================================");
    }

    private function arrayToPhpString(array $array, int $indent = 1): string
    {
        $output = "[\n";
        
        foreach ($array as $key => $value) {
            $output .= str_repeat('    ', $indent);
            
            if (is_string($key)) {
                $output .= "'{$key}' => ";
            }
            
            if (is_array($value)) {
                $output .= $this->arrayToPhpString($value, $indent + 1);
            } elseif (is_string($value)) {
                // Échapper les apostrophes
                $value = str_replace("'", "\\'", $value);
                $output .= "'{$value}'";
            } elseif (is_bool($value)) {
                $output .= $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $output .= 'null';
            } else {
                $output .= $value;
            }
            
            $output .= ",\n";
        }
        
        $output .= str_repeat('    ', $indent - 1) . "]";
        
        return $output;
    }
}