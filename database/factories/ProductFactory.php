<?php
// database/factories/ProductFactory.php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        // Récupérer un fournisseur aléatoire ou en créer un
        $supplier = Supplier::inRandomOrder()->first();
        if (!$supplier) {
            $supplier = Supplier::factory()->create();
        }
        
        // Récupérer une catégorie aléatoire ou en créer une
        $category = Category::inRandomOrder()->first();
        if (!$category) {
            $category = Category::factory()->create();
        }
        
        // Parfois un vendeur marketplace (30% de chance)
        $vendor = null;
        if ($this->faker->boolean(30)) {
            $vendor = Vendor::inRandomOrder()->first();
            if (!$vendor) {
                $vendor = Vendor::factory()->create();
            }
        }

        $name = $this->faker->unique()->words(3, true);
        
        return [
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'short_description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'quantity' => $this->faker->numberBetween(0, 100),
            'supplier_id' => $supplier->id,
            'vendor_id' => $vendor?->id,
            'category_id' => $category->id,
            'image' => null,
            'stock_threshold' => $this->faker->numberBetween(3, 15),
            'is_active' => $this->faker->boolean(85),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indiquer que le produit est en stock critique.
     */
    public function criticalStock()
    {
        return $this->state(function (array $attributes) {
            return [
                'quantity' => $this->faker->numberBetween(0, 5),
                'stock_threshold' => $this->faker->numberBetween(6, 10),
            ];
        });
    }

    /**
     * Indiquer que le produit est d'un vendeur marketplace.
     */
    public function marketplace()
    {
        return $this->state(function (array $attributes) {
            $vendor = Vendor::inRandomOrder()->first() ?? Vendor::factory()->create();
            return [
                'vendor_id' => $vendor->id,
            ];
        });
    }
}