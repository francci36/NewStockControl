<?php
// database/seeders/FinancialDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialTransaction;
use App\Models\Commission;
use App\Models\Payout;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Supplier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class FinancialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier et créer des données de base si nécessaire
        $this->ensureBaseData();

        // Créer 50 transactions financières
        FinancialTransaction::factory(50)->create();
        
        // Créer 30 commissions
        Commission::factory(30)->create();
        
        // Créer 10 paiements
        Payout::factory(10)->create();
        
        $this->command->info('✅ Données financières de test créées avec succès !');
        $this->command->info('📊 Transactions: 50');
        $this->command->info('💰 Commissions: 30');
        $this->command->info('💸 Paiements: 10');
    }

    private function ensureBaseData(): void
    {
        // Créer des utilisateurs si nécessaire
        if (User::count() < 5) {
            User::factory(10)->create();
            $this->command->info('👤 Utilisateurs de test créés');
        }

        // Créer des fournisseurs si nécessaire
        if (Supplier::count() < 3) {
            Supplier::factory(5)->create();
            $this->command->info('🏢 Fournisseurs de test créés');
        }

        // Créer des vendeurs si nécessaire
        if (Vendor::count() < 3) {
            Vendor::factory(5)->create();
            $this->command->info('🛒 Vendeurs de test créés');
        }

        // Créer des produits si nécessaire
        if (Product::count() < 10) {
            Product::factory(20)->create();
            $this->command->info('📦 Produits de test créés');
        }

        // Créer des commandes si nécessaire
        if (Order::count() < 5) {
            Order::factory(10)->create();
            $this->command->info('📋 Commandes de test créées');
        }

        // Créer des items de commande si nécessaire
        if (OrderItem::count() < 10) {
            Order::all()->each(function ($order) {
                OrderItem::factory(rand(1, 3))->create([
                    'order_id' => $order->id,
                ]);
            });
            $this->command->info('🧾 Items de commande créés');
        }
    }
}