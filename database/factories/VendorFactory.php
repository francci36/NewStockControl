<?php
// database/factories/VendorFactory.php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        // Créer un utilisateur associé avec le rôle vendor
        $user = User::factory()->create([
            'role' => 'vendor',
        ]);
        
        $shopName = $this->faker->company();
        
        return [
            'user_id' => $user->id,
            'shop_name' => $shopName,
            'slug' => Str::slug($shopName . '-' . uniqid()),
            'description' => $this->faker->paragraph(),
            'logo' => null,
            'banner' => null,
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'phone' => $this->faker->phoneNumber(),
            'business_number' => $this->faker->bothify('??#########'), // SIRET / TVA
            'bank_account' => $this->faker->iban('FR'), // IBAN français
            'website' => $this->faker->url(),
            'commission_rate' => $this->faker->randomFloat(2, 5, 20),
            'status' => $this->faker->randomElement(['pending', 'active', 'suspended', 'rejected']),
            'rejection_reason' => null,
            'approved_at' => $this->faker->optional(0.7)->dateTimeBetween('-6 months', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indiquer que le vendeur est en attente.
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'approved_at' => null,
                'rejection_reason' => null,
            ];
        });
    }

    /**
     * Indiquer que le vendeur est actif.
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'active',
                'approved_at' => now()->subDays($this->faker->numberBetween(1, 30)),
                'rejection_reason' => null,
            ];
        });
    }

    /**
     * Indiquer que le vendeur est suspendu.
     */
    public function suspended()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'suspended',
                'approved_at' => $this->faker->optional(0.5)->dateTimeBetween('-3 months', 'now'),
            ];
        });
    }

    /**
     * Indiquer que le vendeur est rejeté.
     */
    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'rejection_reason' => $this->faker->sentence(),
                'approved_at' => null,
            ];
        });
    }

    /**
     * Indiquer que le vendeur a une commission spécifique.
     */
    public function withCommission(float $rate)
    {
        return $this->state(function (array $attributes) use ($rate) {
            return [
                'commission_rate' => $rate,
            ];
        });
    }
}