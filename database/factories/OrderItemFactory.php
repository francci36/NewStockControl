<?php
// database/factories/OrderItemFactory.php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        // Récupérer une commande aléatoire ou en créer une
        $order = Order::inRandomOrder()->first();
        if (!$order) {
            $order = Order::factory()->create();
        }
        
        // Récupérer un produit aléatoire ou en créer un
        $product = Product::inRandomOrder()->first();
        if (!$product) {
            $product = Product::factory()->create();
        }
        
        $quantity = $this->faker->numberBetween(1, 5);
        
        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
            'vendor_id' => $product->vendor_id,
            'status' => 'pending',
            'created_at' => $order->created_at,
            'updated_at' => now(),
        ];
    }

    /**
     * Associer à une commande spécifique.
     */
    public function forOrder(Order $order)
    {
        return $this->state(function (array $attributes) use ($order) {
            return [
                'order_id' => $order->id,
            ];
        });
    }

    /**
     * Associer à un produit spécifique.
     */
    public function forProduct(Product $product)
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'product_id' => $product->id,
                'price' => $product->price,
                'vendor_id' => $product->vendor_id,
            ];
        });
    }
}