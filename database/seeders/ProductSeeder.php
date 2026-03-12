<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les fournisseurs
        $suppliers = Supplier::all();
        if ($suppliers->isEmpty()) {
            $supplier = Supplier::create([
                'name' => 'Fournisseur Premium',
                'email' => 'premium@example.com',
                'phone' => '0123456789',
                'address' => '123 Rue de Paris',
                'city' => 'Paris',
                'country' => 'France'
            ]);
            $suppliers = Supplier::all();
        }

        // Récupérer les catégories
        $categories = Category::all()->keyBy('slug');

        $products = [
            // Produits pour chiens
            [
                'name' => [
                    'fr' => 'Croquettes Royal Canin Maxi Adult',
                    'en' => 'Royal Canin Maxi Adult Kibble',
                    'pt' => 'Ração Royal Canin Maxi Adult',
                    'es' => 'Crocquetas Royal Canin Maxi Adult',
                    'de' => 'Royal Canin Maxi Adult Trockenfutter',
                ],
                'description' => [
                    'fr' => 'Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.',
                    'en' => 'Complete food for adult dogs of large breeds (26-44kg). Rich in proteins and vitamins.',
                    'pt' => 'Alimento completo para cães adultos de raças grandes (26-44kg). Rico em proteínas e vitaminas.',
                    'es' => 'Alimento completo para perros adultos de razas grandes (26-44kg). Rico en proteínas y vitaminas.',
                    'de' => 'Vollständiges Futter für erwachsene Hunde großer Rassen (26-44kg). Reich an Proteinen und Vitaminen.',
                ],
                'short_description' => [
                    'fr' => 'Croquettes premium pour grands chiens',
                    'en' => 'Premium kibble for large dogs',
                    'pt' => 'Ração premium para cães grandes',
                    'es' => 'Crocquetas premium para perros grandes',
                    'de' => 'Premium-Trockenfutter für große Hunde',
                ],
                'price' => 54.99,
                'quantity' => 45,
                'stock_threshold' => 10,
                'category_slug' => 'alimentation-chiens',
                'is_active' => true,
            ],
            
            // Gamelle
            [
                'name' => [
                    'fr' => 'Gamelle de chien',
                    'en' => 'Dog Bowl',
                    'pt' => 'Tigela para cachorro',
                    'es' => 'Cuenco para perro',
                    'de' => 'Hundenapf',
                ],
                'description' => [
                    'fr' => 'Gamelle robuste en acier inoxydable pour chien, antidérapante et facile à nettoyer.',
                    'en' => 'Sturdy stainless steel dog bowl, non-slip and easy to clean.',
                    'pt' => 'Tigela resistente em aço inoxidável para cães, antiderrapante e fácil de limpar.',
                    'es' => 'Cuenco resistente de acero inoxidable para perros, antideslizante y fácil de limpiar.',
                    'de' => 'Robuster Edelstahl-Hundenapf, rutschfest und leicht zu reinigen.',
                ],
                'short_description' => [
                    'fr' => 'Gamelle en acier inoxydable',
                    'en' => 'Stainless steel bowl',
                    'pt' => 'Tigela de aço inoxidável',
                    'es' => 'Cuenco de acero inoxidable',
                    'de' => 'Edelstahlnapf',
                ],
                'price' => 32.00,
                'quantity' => 7,
                'stock_threshold' => 3,
                'category_slug' => 'accessoires',
                'is_active' => true,
            ],
            
            // Ajoutez tous vos produits ici...
        ];

        $count = 0;
        foreach ($products as $productData) {
            if (isset($categories[$productData['category_slug']])) {
                $productData['category_id'] = $categories[$productData['category_slug']]->id;
                $productData['supplier_id'] = $suppliers->random()->id;
                
                // Convertir les tableaux en JSON
                $productData['name'] = json_encode($productData['name']);
                $productData['description'] = json_encode($productData['description']);
                $productData['short_description'] = json_encode($productData['short_description']);
                
                unset($productData['category_slug']);
                
                Product::create($productData);
                $count++;
            }
        }

        $this->command->info("✅ {$count} produits créés");
    }
}