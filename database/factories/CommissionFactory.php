<?php
// database/factories/CommissionFactory.php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    protected $model = Commission::class;

    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 5, 200);
        $rate = $this->faker->randomFloat(2, 5, 20);
        
        return [
            'vendor_id' => Vendor::inRandomOrder()->first()?->id ?? Vendor::factory(),
            'order_id' => Order::inRandomOrder()->first()?->id ?? Order::factory(),
            'order_item_id' => OrderItem::inRandomOrder()->first()?->id ?? OrderItem::factory(),
            'amount' => $amount,
            'rate' => $rate,
            'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled']),
            'paid_at' => $this->faker->optional(0.3)->dateTimeBetween('-2 months', 'now'),
            'payment_reference' => $this->faker->optional(0.3)->bothify('PAY-####-????'),
        ];
    }
}