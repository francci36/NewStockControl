<?php
// database/factories/FinancialTransactionFactory.php

namespace Database\Factories;

use App\Models\FinancialTransaction;
use App\Models\User;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialTransactionFactory extends Factory
{
    protected $model = FinancialTransaction::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['income', 'expense', 'commission', 'refund']);
        $amount = $this->faker->randomFloat(2, 10, 1000);
        $tax = $amount * 0.2; // TVA 20%
        
        return [
            'reference' => 'TRX-' . strtoupper(uniqid()),
            'type' => $type,
            'category' => $this->faker->randomElement(['sale', 'purchase', 'fee', 'commission', 'withdrawal']),
            'amount' => $amount,
            'tax' => $tax,
            'total' => $amount + $tax,
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled', 'failed']),
            'payment_method' => $this->faker->randomElement(['card', 'cash', 'bank_transfer', 'paypal']),
            'description' => $this->faker->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_id' => Order::inRandomOrder()->first()?->id ?? null,
            'vendor_id' => Vendor::inRandomOrder()->first()?->id ?? null,
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? null,
            'metadata' => null,
        ];
    }
}