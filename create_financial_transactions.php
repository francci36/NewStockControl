<?php
// create_financial_transactions.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FinancialTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

echo "🚀 CRÉATION DES TRANSACTIONS FINANCIÈRES À PARTIR DES COMMANDES\n";
echo "============================================================\n\n";

// Récupérer toutes les commandes
$orders = Order::all();
echo "📊 Commandes trouvées : " . $orders->count() . "\n";
echo "   - Clients : " . $orders->where('type', 'client')->count() . "\n";
echo "   - Fournisseurs : " . $orders->where('type', 'supplier')->count() . "\n\n";

$count = 0;
$totalIncome = 0;
$totalExpense = 0;

// Commencer une transaction
DB::beginTransaction();

try {
    foreach ($orders as $order) {
        // Vérifier si une transaction existe déjà pour cette commande
        $exists = FinancialTransaction::where('order_id', $order->id)->exists();
        
        if (!$exists) {
            // Déterminer le type
            $type = $order->type === 'client' ? 'income' : 'expense';
            $category = $order->type === 'client' ? 'sale' : 'purchase';
            $prefix = $order->type === 'client' ? 'SALE' : 'PURCHASE';
            
            // Calculer la TVA (20%)
            $ht = $order->total_amount / 1.2;
            $tva = $order->total_amount - $ht;
            
            // Nom du fournisseur/client pour la description
            if ($order->type === 'client') {
                $nom = $order->shipping_name ?? 'Client';
            } else {
                $nom = $order->supplier->admin_name ?? $order->supplier->name ?? 'Fournisseur';
            }
            
            // Créer la transaction
            $transaction = FinancialTransaction::create([
                'reference' => $prefix . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                'type' => $type,
                'category' => $category,
                'amount' => round($ht, 2),
                'tax' => round($tva, 2),
                'total' => $order->total_amount,
                'status' => $order->status === 'cancelled' ? 'cancelled' : 'completed',
                'payment_method' => $order->payment_method ?? ($order->type === 'client' ? 'card' : 'bank_transfer'),
                'description' => ($order->type === 'client' ? "Vente client #{$order->id} - " : "Achat fournisseur #{$order->id} - ") . $nom,
                'transaction_date' => $order->created_at,
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'supplier_id' => $order->supplier_id,
            ]);
            
            $count++;
            
            if ($type === 'income') {
                $totalIncome += $order->total_amount;
                $emoji = '💰';
            } else {
                $totalExpense += $order->total_amount;
                $emoji = '💸';
            }
            
            echo "{$emoji} Créée : {$transaction->reference} - " . number_format($transaction->total, 2) . " €\n";
        } else {
            echo "⏭️  Transaction existe déjà pour commande #{$order->id}\n";
        }
    }
    
    DB::commit();
    
    echo "\n✅ SUCCÈS !\n";
    echo "==========\n";
    echo "Transactions créées : {$count}\n";
    echo "💰 Total revenus (ventes) : " . number_format($totalIncome, 2) . " €\n";
    echo "💸 Total dépenses (achats) : " . number_format($totalExpense, 2) . " €\n";
    echo "📈 Bénéfice : " . number_format($totalIncome - $totalExpense, 2) . " €\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERREUR : " . $e->getMessage() . "\n";
}