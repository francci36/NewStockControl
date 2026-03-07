<?php
// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les fournisseurs existants
        $suppliers = Supplier::all();
        if ($suppliers->isEmpty()) {
            $this->command->warn('⚠ Aucun fournisseur trouvé. Création de fournisseurs par défaut...');
            $supplier1 = Supplier::create([
                'name' => 'Fournisseur Premium',
                'email' => 'premium@example.com',
                'phone' => '0123456789',
                'address' => '123 Rue de Paris',
                'city' => 'Paris',
                'country' => 'France'
            ]);
            $supplier2 = Supplier::create([
                'name' => 'Fournisseur Standard',
                'email' => 'standard@example.com',
                'phone' => '0123456788',
                'address' => '456 Rue de Lyon',
                'city' => 'Lyon',
                'country' => 'France'
            ]);
            $suppliers = Supplier::all();
        }

        // Récupérer les catégories existantes
        $categories = Category::all()->keyBy('name');
        
        if ($categories->isEmpty()) {
            $this->command->error('❌ Aucune catégorie trouvée !');
            return;
        }

        // Produits à créer (au format JSON pour les colonnes traduisibles)
        $products = [
            'Croquettes Royal Canin Maxi Adult' => [
                'name' => json_encode([
                    'fr' => 'Croquettes Royal Canin Maxi Adult',
                    'en' => 'Royal Canin Maxi Adult Kibble',
                    'pt_BR' => 'Ração Royal Canin Maxi Adult',
                    'es' => 'Crocquetas Royal Canin Maxi Adult',
                    'de' => 'Royal Canin Maxi Adult Trockenfutter',
                    'it' => 'Royal Canin Maxi Adult Crocchette',
                ]),
                'description' => json_encode([
                    'fr' => 'Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.',
                    'en' => 'Complete food for adult dogs of large breeds (26-44kg). Rich in proteins and vitamins.',
                    'pt_BR' => 'Alimento completo para cães adultos de raças grandes (26-44kg). Rico em proteínas e vitaminas.',
                    'es' => 'Alimento completo para perros adultos de razas grandes (26-44kg). Rico en proteínas y vitaminas.',
                    'de' => 'Vollständiges Futter für erwachsene Hunde großer Rassen (26-44kg). Reich an Proteinen und Vitaminen.',
                    'it' => 'Alimento completo per cani adulti di razze grandi (26-44kg). Ricco di proteine e vitamine.',
                ]),
                'short_description' => json_encode([
                    'fr' => 'Croquettes premium pour grands chiens',
                    'en' => 'Premium kibble for large dogs',
                    'pt_BR' => 'Ração premium para cães grandes',
                    'es' => 'Crocquetas premium para perros grandes',
                    'de' => 'Premium-Trockenfutter für große Hunde',
                    'it' => 'Crocchette premium per cani grandi',
                ]),
                'price' => 54.99,
                'quantity' => 45,
                'stock_threshold' => 10,
                'category_name' => 'Alimentation pour chiens'
            ],
            'Croquettes Purina Pro Plan Sensitive' => [
                'name' => json_encode([
                    'fr' => 'Croquettes Purina Pro Plan Sensitive',
                    'en' => 'Purina Pro Plan Sensitive Kibble',
                    'pt_BR' => 'Ração Purina Pro Plan Sensitive',
                    'es' => 'Crocquetas Purina Pro Plan Sensitive',
                    'de' => 'Purina Pro Plan Sensitive Trockenfutter',
                    'it' => 'Purina Pro Plan Sensitive Crocchette',
                ]),
                'description' => json_encode([
                    'fr' => 'Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.',
                    'en' => 'For dogs with sensitive skin. Formula with salmon and rice, easy to digest.',
                    'pt_BR' => 'Para cães com pele sensível. Fórmula com salmão e arroz, fácil de digerir.',
                    'es' => 'Para perros con piel sensible. Fórmula con salmón y arroz, fácil de digerir.',
                    'de' => 'Für Hunde mit empfindlicher Haut. Formel mit Lachs und Reis, leicht verdaulich.',
                    'it' => 'Per cani con pelle sensibile. Formula con salmone e riso, facile da digerire.',
                ]),
                'short_description' => json_encode([
                    'fr' => 'Croquettes pour peaux sensibles',
                    'en' => 'Kibble for sensitive skin',
                    'pt_BR' => 'Ração para pele sensível',
                    'es' => 'Crocquetas para piel sensible',
                    'de' => 'Trockenfutter für empfindliche Haut',
                    'it' => 'Crocchette per pelle sensibile',
                ]),
                'price' => 47.50,
                'quantity' => 32,
                'stock_threshold' => 8,
                'category_name' => 'Alimentation pour chiens'
            ],
            // ... Ajoutez tous les autres produits de la même manière
        ];

        // Version simplifiée pour les produits avec un seul nom (par défaut)
        $simpleProducts = [
            'Gamelle anti-glouton' => [
                'name' => json_encode(['fr' => 'Gamelle anti-glouton']),
                'description' => json_encode(['fr' => 'Gamelle avec labyrinthe pour ralentir la prise de nourriture.']),
                'short_description' => json_encode(['fr' => 'Gamelle pour chien/chats']),
                'price' => 24.99,
                'quantity' => 28,
                'stock_threshold' => 5,
                'category_name' => 'Accessoires'
            ],
            'Nourriture pour poissons' => [
                'name' => json_encode(['fr' => 'Nourriture pour poissons']),
                'description' => json_encode(['fr' => 'Alimentation équilibrée pour poissons d\'aquarium']),
                'short_description' => json_encode(['fr' => 'Nourriture aquarium']),
                'price' => 8.99,
                'quantity' => 50,
                'stock_threshold' => 10,
                'category_name' => 'Aquariophilie'
            ],
            'Vin de Baune' => [
                'name' => json_encode(['fr' => 'Vin de Baune']),
                'description' => json_encode(['fr' => 'Vin rouge de caractère de Bourgogne']),
                'short_description' => json_encode(['fr' => 'Vin rouge']),
                'price' => 28.00,
                'quantity' => 15,
                'stock_threshold' => 3,
                'category_name' => 'Vins'
            ],
            'Aquarium 100L' => [
                'name' => json_encode(['fr' => 'Aquarium 100L']),
                'description' => json_encode(['fr' => 'Aquarium complet pour poissons']),
                'short_description' => json_encode(['fr' => 'Aquarium 100L']),
                'price' => 129.99,
                'quantity' => 8,
                'stock_threshold' => 2,
                'category_name' => 'Aquariophilie'
            ],
            'Croquettes Royal Canin Sterilised' => [
                'name' => json_encode(['fr' => 'Croquettes Royal Canin Sterilised']),
                'description' => json_encode(['fr' => 'Pour chats stérilisés. Aide à maintenir un poids santé.']),
                'short_description' => json_encode(['fr' => 'Croquettes pour chats stérilisés']),
                'price' => 39.99,
                'quantity' => 38,
                'stock_threshold' => 8,
                'category_name' => 'Alimentation pour chats'
            ],
            'Pâtée Whiskas en gelée' => [
                'name' => json_encode(['fr' => 'Pâtée Whiskas en gelée']),
                'description' => json_encode(['fr' => 'Lot de 12 sachets fraîcheur, variété de saveurs.']),
                'short_description' => json_encode(['fr' => 'Pâtée pour chats en sachets']),
                'price' => 15.90,
                'quantity' => 75,
                'stock_threshold' => 15,
                'category_name' => 'Alimentation pour chats'
            ],
            'Antiparasitaire Frontline Combo' => [
                'name' => json_encode(['fr' => 'Antiparasitaire Frontline Combo']),
                'description' => json_encode(['fr' => 'Pipettes anti-puces et tiques pour chats, 3 pipettes.']),
                'short_description' => json_encode(['fr' => 'Antiparasitaire pour chats']),
                'price' => 29.90,
                'quantity' => 25,
                'stock_threshold' => 5,
                'category_name' => 'Soins et santé'
            ],
            'Litière agglomérante Catsan' => [
                'name' => json_encode(['fr' => 'Litière agglomérante Catsan']),
                'description' => json_encode(['fr' => 'Litière minérale ultra-absorbante, 10 litres.']),
                'short_description' => json_encode(['fr' => 'Litière pour chats']),
                'price' => 18.50,
                'quantity' => 42,
                'stock_threshold' => 10,
                'category_name' => 'Accessoires'
            ],
            'Bourgogne Rouge 2024 - "Les Champs d\'Argent"' => [
                'name' => json_encode(['fr' => 'Bourgogne Rouge 2024 - "Les Champs d\'Argent"']),
                'description' => json_encode(['fr' => 'Cuvée très spécifique, ce Bourgogne est issu de vieilles vignes plantées en 1952.']),
                'short_description' => json_encode(['fr' => 'Vin rouge de Bourgogne']),
                'price' => 18.50,
                'quantity' => 20,
                'stock_threshold' => 5,
                'category_name' => 'Vins'
            ],
            'Bourgogne Passetoutgrain 2022' => [
                'name' => json_encode(['fr' => 'Bourgogne Passetoutgrain 2022']),
                'description' => json_encode(['fr' => 'Le célèbre Bourgogne Passetoutgrains, assemblage de Pinot Noir et Gamay.']),
                'short_description' => json_encode(['fr' => 'Vin rouge de Bourgogne']),
                'price' => 10.80,
                'quantity' => 18,
                'stock_threshold' => 4,
                'category_name' => 'Vins'
            ],
            'Couteaux Bourguignons 2021' => [
                'name' => json_encode(['fr' => 'Couteaux Bourguignons 2021']),
                'description' => json_encode(['fr' => 'Coteaux Bourguignons 2021 réalisés par le domaine Remoriquet.']),
                'short_description' => json_encode(['fr' => 'Vin rouge de Bourgogne']),
                'price' => 8.70,
                'quantity' => 22,
                'stock_threshold' => 5,
                'category_name' => 'Vins'
            ],
            'Hautes côtes de nuit' => [
                'name' => json_encode(['fr' => 'Hautes côtes de nuit']),
                'description' => json_encode(['fr' => 'Vin rouge domaine du baccara aux tannins soyeux.']),
                'short_description' => json_encode(['fr' => 'Vin rouge de Bourgogne']),
                'price' => 18.00,
                'quantity' => 16,
                'stock_threshold' => 4,
                'category_name' => 'Vins'
            ],
            'Mercurey 2022 Rouge - "Les Chavances"' => [
                'name' => json_encode(['fr' => 'Mercurey 2022 Rouge - "Les Chavances"']),
                'description' => json_encode(['fr' => 'Vin rouge de Mercurey aux arômes de fruits rouges.']),
                'short_description' => json_encode(['fr' => 'Vin rouge 75cl']),
                'price' => 28.00,
                'quantity' => 12,
                'stock_threshold' => 3,
                'category_name' => 'Vins'
            ],
        ];

        // Fusionner les deux tableaux
        $allProducts = array_merge($products, $simpleProducts);

        $count = 0;
        foreach ($allProducts as $key => $productData) {
            if (isset($categories[$productData['category_name']])) {
                $category = $categories[$productData['category_name']];
                $productData['category_id'] = $category->id;
                $productData['supplier_id'] = $suppliers->random()->id;
                $productData['is_active'] = true;
                
                unset($productData['category_name']);
                
                Product::create($productData);
                $count++;
                $this->command->info("✓ Produit créé: " . json_decode($productData['name'], true)['fr']);
            } else {
                $this->command->warn("⚠ Catégorie non trouvée: {$productData['category_name']} pour le produit " . json_decode($productData['name'], true)['fr']);
            }
        }

        $this->command->info("=================================");
        $this->command->info("RÉSUMÉ: {$count} produits créés avec succès");
        $this->command->info("=================================");
    }
}