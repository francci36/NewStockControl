<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Events\OrderShipped;
use App\Events\OrderDelivered;

class OrderStatusService
{
    protected $vendorWebhookService;

    public function __construct(VendorWebhookService $vendorWebhookService)
    {
        $this->vendorWebhookService = $vendorWebhookService;
    }

    /**
     * Marquer les produits d'un vendeur comme expédiés
     */
    public function markVendorProductAsShipped(
        Order $order, 
        Vendor $vendor, 
        string $productSku, 
        int $quantity,
        string $trackingNumber,
        string $carrier
    ) {
        DB::transaction(function () use ($order, $vendor, $productSku, $quantity, $trackingNumber, $carrier) {
            $product = Product::where('sku', $productSku)->firstOrFail();
            
            $orderItem = $order->items()
                ->where('product_id', $product->id)
                ->where('vendor_id', $vendor->id)
                ->firstOrFail();

            $orderItem->update([
                'shipped_at' => now(),
                'tracking_number' => $trackingNumber,
                'carrier' => $carrier,
                'status' => 'shipped'
            ]);

            // Vérifier si tous les produits du vendeur sont expédiés
            $remaining = $order->items()
                ->where('vendor_id', $vendor->id)
                ->whereNull('shipped_at')
                ->count();

            if ($remaining === 0) {
                event(new VendorOrderCompleted($order, $vendor));
            }

            $this->syncGlobalStatus($order);
        });
    }

    /**
     * Marquer tous les produits d'un vendeur comme expédiés
     */
    public function markVendorOrderAsShipped(
        Order $order,
        Vendor $vendor,
        string $trackingNumber,
        string $carrier
    ) {
        DB::transaction(function () use ($order, $vendor, $trackingNumber, $carrier) {
            $order->items()
                ->where('vendor_id', $vendor->id)
                ->update([
                    'shipped_at' => now(),
                    'tracking_number' => $trackingNumber,
                    'carrier' => $carrier,
                    'status' => 'shipped'
                ]);

            event(new VendorOrderCompleted($order, $vendor));
            $this->syncGlobalStatus($order);
        });
    }

    /**
     * Synchroniser le statut global de la commande
     */
    public function syncGlobalStatus(Order $order)
    {
        $items = $order->items;
        
        if ($items->every(fn($item) => $item->status === 'delivered')) {
            $order->update(['status' => 'delivered']);
        } elseif ($items->contains(fn($item) => $item->status === 'shipped')) {
            $order->update(['status' => 'partially_shipped']);
        } elseif ($items->contains(fn($item) => $item->status === 'cancelled')) {
            $order->update(['status' => 'partially_cancelled']);
        }
    }

    /**
     * Vérifier si une commande peut être expédiée
     */
    public function canShip(Order $order, Vendor $vendor): bool
    {
        $pendingItems = $order->items()
            ->where('vendor_id', $vendor->id)
            ->whereNull('shipped_at')
            ->count();

        return $pendingItems > 0;
    }
}