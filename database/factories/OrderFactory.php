<?php
// database/factories/OrderFactory.php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $type = $this->faker->randomElement(['client', 'supplier']);
        
        // Statuts possibles selon le type
        $statuses = [
            'client' => ['pending', 'processing', 'shipped', 'delivered', 'cancelled'],
            'supplier' => ['pending', 'processing', 'shipped', 'arrived', 'cancelled'],
        ];
        
        $status = $this->faker->randomElement($statuses[$type]);
        
        // Récupérer un utilisateur aléatoire ou en créer un
        $user = User::inRandomOrder()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        
        $totalAmount = $this->faker->randomFloat(2, 50, 2000);
        
        $data = [
            'user_id' => $user->id,
            'type' => $type,
            'date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'total_amount' => $totalAmount,
            'status' => $status,
            'payment_method' => $this->faker->randomElement(['card', 'cash', 'bank_transfer', null]),
            'payment_reference' => $this->faker->optional(0.5)->bothify('PAY-####-????'),
            'paid_at' => $this->faker->optional(0.3)->dateTimeBetween('-2 months', 'now'),
            'commission_total' => 0, // Sera calculé plus tard
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
        
        // Si c'est une commande fournisseur, ajouter un fournisseur
        if ($type === 'supplier') {
            $supplier = Supplier::inRandomOrder()->first();
            if (!$supplier) {
                $supplier = Supplier::factory()->create();
            }
            $data['supplier_id'] = $supplier->id;
        }
        
        return $data;
    }

    /**
     * Indiquer que la commande est pour un fournisseur.
     */
    public function supplier()
    {
        return $this->state(function (array $attributes) {
            $supplier = Supplier::inRandomOrder()->first() ?? Supplier::factory()->create();
            return [
                'type' => 'supplier',
                'supplier_id' => $supplier->id,
                'status' => $this->faker->randomElement(['pending', 'arrived', 'cancelled']),
            ];
        });
    }

    /**
     * Indiquer que la commande est pour un client.
     */
    public function client()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'client',
                'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            ];
        });
    }

    /**
     * Indiquer que la commande est terminée/arrivée.
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            $type = $attributes['type'] ?? $this->faker->randomElement(['client', 'supplier']);
            return [
                'status' => $type === 'client' ? 'delivered' : 'arrived',
                'paid_at' => now()->subDays($this->faker->numberBetween(1, 30)),
            ];
        });
    }
}