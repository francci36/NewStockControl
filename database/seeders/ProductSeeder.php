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
        // Créer des catégories
        $categories = [
            ['name' => 'Alimentation pour chiens', 'slug' => 'alimentation-chiens', 'description' => 'Croquettes, pâtées et friandises pour chiens'],
            ['name' => 'Alimentation pour chats', 'slug' => 'alimentation-chats', 'description' => 'Croquettes, pâtées et friandises pour chats'],
            ['name' => 'Accessoires', 'slug' => 'accessoires', 'description' => 'Litières, gamelles, jouets'],
            ['name' => 'Soins et santé', 'slug' => 'soins-sante', 'description' => 'Produits vétérinaires, antiparasitaires'],
            ['name' => 'Aquariophilie', 'slug' => 'aquariophilie', 'description' => 'Aquariums, poissons, accessoires'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Récupérer les fournisseurs existants
        $suppliers = Supplier::all();

        // Produits pour chiens
        $dogFoods = [
            [
                'name' => 'Croquettes Royal Canin Maxi Adult',
                'description' => 'Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.',
                'short_description' => 'Croquettes pour chiens adultes de grande taille',
                'price' => 54.99,
                'quantity' => 45,
                'stock_threshold' => 10,
                'image' => 'royal-canin-maxi.jpg',
                'category' => 'alimentation-chiens'
            ],
            [
                'name' => 'Croquettes Purina Pro Plan Sensitive',
                'description' => 'Pour chiens à peau sensible. Formule avec saumon et riz, facile à digérer.',
                'short_description' => 'Croquettes pour chiens sensibles',
                'price' => 47.50,
                'quantity' => 32,
                'stock_threshold' => 8,
                'image' => 'purina-sensitive.jpg',
                'category' => 'alimentation-chiens'
            ],
            [
                'name' => 'Friandises Pedigree Dentastix',
                'description' => 'Bâtonnets dentaires pour chiens, aide à réduire la formation de tartre.',
                'short_description' => 'Bâtonnets dentaires pour chiens',
                'price' => 12.90,
                'quantity' => 120,
                'stock_threshold' => 20,
                'image' => 'dentastix.jpg',
                'category' => 'alimentation-chiens'
            ],
        ];

        // Produits pour chats
        $catFoods = [
            [
                'name' => 'Croquettes Royal Canin Sterilised',
                'description' => 'Pour chats stérilisés. Aide à maintenir un poids santé.',
                'short_description' => 'Croquettes pour chats stérilisés',
                'price' => 39.99,
                'quantity' => 38,
                'stock_threshold' => 8,
                'image' => 'royal-canin-sterilised.jpg',
                'category' => 'alimentation-chats'
            ],
            [
                'name' => 'Pâtée Whiskas en gelée',
                'description' => 'Lot de 12 sachets fraîcheur, variété de saveurs.',
                'short_description' => 'Pâtée pour chats en sachets',
                'price' => 15.90,
                'quantity' => 75,
                'stock_threshold' => 15,
                'image' => 'whiskas.jpg',
                'category' => 'alimentation-chats'
            ],
            [
                'name' => 'Litière agglomérante Catsan',
                'description' => 'Litière minérale ultra-absorbante, 10 litres.',
                'short_description' => 'Litière pour chats',
                'price' => 18.50,
                'quantity' => 42,
                'stock_threshold' => 10,
                'image' => 'catsan.jpg',
                'category' => 'accessoires'
            ],
        ];

        // Accessoires
        $accessories = [
            [
                'name' => 'Gamelle anti-glouton',
                'description' => 'Gamelle avec labyrinthe pour ralentir la prise de nourriture.',
                'short_description' => 'Gamelle pour chien/chats',
                'price' => 24.99,
                'quantity' => 28,
                'stock_threshold' => 5,
                'image' => 'gamelle-anti-glouton.jpg',
                'category' => 'accessoires'
            ],
            [
                'name' => 'Arbre à chat 3 étages',
                'description' => 'Arbre à chat avec griffoirs, cachettes et jouets suspendus.',
                'short_description' => 'Arbre à chat complet',
                'price' => 89.90,
                'quantity' => 12,
                'stock_threshold' => 3,
                'image' => 'arbre-chat.jpg',
                'category' => 'accessoires'
            ],
        ];

        // Produits de soins
        $health = [
            [
                'name' => 'Antiparasitaire Frontline Combo',
                'description' => 'Pipettes anti-puces et tiques pour chats, 3 pipettes.',
                'short_description' => 'Antiparasitaire pour chats',
                'price' => 29.90,
                'quantity' => 25,
                'stock_threshold' => 5,
                'image' => 'frontline.jpg',
                'category' => 'soins-sante'
            ],
            [
                'name' => 'Vermifuge Milbemax pour chiens',
                'description' => 'Comprimés vermifuges pour chiens de 5-25kg.',
                'short_description' => 'Vermifuge pour chiens',
                'price' => 18.90,
                'quantity' => 30,
                'stock_threshold' => 6,
                'image' => 'milbemax.jpg',
                'category' => 'soins-sante'
            ],
        ];

        // Combiner tous les produits
        $allProducts = array_merge($dogFoods, $catFoods, $accessories, $health);

        // Assigner un fournisseur aléatoire à chaque produit
        foreach ($allProducts as $productData) {
            $category = Category::where('slug', $productData['category'])->first();
            
            if ($category) {
                unset($productData['category']);
                $productData['category_id'] = $category->id;
                $productData['supplier_id'] = $suppliers->random()->id;
                
                Product::create($productData);
            }
        }

        $this->command->info('Produits créés avec succès !');
    }
}