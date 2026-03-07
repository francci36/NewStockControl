<?php
// database/factories/SupplierFactory.php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'website' => $this->faker->url(),
            'tax_id' => $this->faker->bothify('??#########'),
            'payment_terms' => $this->faker->randomElement(['immediate', '15_days', '30_days', '45_days', '60_days']),
            'is_preferred' => $this->faker->boolean(20),
            'is_active' => $this->faker->boolean(90),
            'notes' => $this->faker->optional(0.5)->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now(),
        ];
    }
}