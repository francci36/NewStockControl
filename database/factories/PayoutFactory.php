<?php
// database/factories/PayoutFactory.php

namespace Database\Factories;

use App\Models\Payout;
use App\Models\Vendor;
use App\Models\Commission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayoutFactory extends Factory
{
    protected $model = Payout::class;

    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 50, 1000);
        
        // Récupérer quelques commissions aléatoires
        $commissionIds = Commission::inRandomOrder()
            ->limit($this->faker->numberBetween(1, 5))
            ->pluck('id')
            ->toArray();
        
        return [
            'vendor_id' => Vendor::inRandomOrder()->first()?->id ?? Vendor::factory(),
            'reference' => 'PAYOUT-' . strtoupper(uniqid()),
            'amount' => $amount,
            'commission_ids' => $commissionIds,
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'failed']),
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'paypal', 'check']),
            'payment_details' => [
                'account' => $this->faker->bankAccountNumber(),
                'swift' => $this->faker->swiftBicNumber(),
            ],
            'processed_at' => $this->faker->optional(0.5)->dateTimeBetween('-1 month', 'now'),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}