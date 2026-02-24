<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product;
use App\Services\VendorWebhookService;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class VendorWebhookController extends Controller
{
    protected $webhookService;
    protected $orderStatusService;

    public function __construct(
        VendorWebhookService $webhookService,
        OrderStatusService $orderStatusService
    ) {
        $this->webhookService = $webhookService;
        $this->orderStatusService = $orderStatusService;
    }

    /**
     * Webhook pour les mises à jour d'expédition des vendeurs
     */
    public function handleShipping(Request $request)
    {
        try {
            // Validation des données d'entrée
            $data = $request->validate([
                'vendor_id' => 'required|integer|exists:vendors,id',
                'order_id' => 'required|integer|exists:orders,id',
                'tracking_number' => 'required|string|max:255',
                'carrier' => ['required', 'string', Rule::in(['colissimo', 'chronopost', 'dhl', 'mondial_relay', 'other'])],
                'products' => 'sometimes|array|min:1',
                'products.*.product_id' => 'required_with:products|integer|exists:products,id',
                'products.*.quantity' => 'required_with:products|integer|min:1',
                'shipped_at' => 'sometimes|date',
                'estimated_delivery' => 'sometimes|date|after:shipped_at',
                'metadata' => 'sometimes|array'
            ]);

            // Trouver la commande par ID
            $order = Order::findOrFail($data['order_id']);
            
            // Vérification du vendeur
            $vendor = Vendor::find($data['vendor_id']);
            
            if (!$vendor) {
                return response()->json([
                    'error' => 'Vendor not found',
                    'message' => "Vendor with ID {$data['vendor_id']} not found"
                ], 404);
            }

            // Traitement des produits expédiés
            $this->processShippedProducts($order, $vendor, $data);

            // Mise à jour des informations de suivi de la commande
            $this->updateOrderTracking($order, $data);

            // Déclenchement des événements
            $this->dispatchOrderEvents($order, $vendor, 'shipped');

            return response()->json([
                'success' => true,
                'message' => 'Shipping status updated successfully',
                'order' => [
                    'id' => $order->id,
                    'reference' => $order->reference,
                    'status' => $order->status,
                    'tracking_number' => $order->tracking_number,
                    'carrier' => $order->carrier,
                    'shipped_at' => $order->shipped_at?->toIso8601String()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order not found',
                'message' => "Order with ID '{$request->order_id}' not found"
            ], 404);
        } catch (\Exception $e) {
            Log::error('Webhook shipping error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

   /**
 * Webhook pour les mises à jour de livraison
 */
public function handleDelivery(Request $request)
{
    try {
        // Validation des données d'entrée
        $data = $request->validate([
            'tracking_number' => 'required|string|max:255',
            'delivered_at' => 'sometimes|date',
            'signature' => 'sometimes|string|max:255',
            'proof_of_delivery' => 'sometimes|string|max:255'
        ]);

        // Recherche de la commande par numéro de suivi
        $order = Order::where('tracking_number', $data['tracking_number'])->first();
        
        if (!$order) {
            return response()->json([
                'error' => 'Order not found',
                'message' => "Order with tracking number '{$data['tracking_number']}' not found"
            ], 404);
        }

        // Convertir la date ISO 8601 en format MySQL
        $deliveredAt = $data['delivered_at'] ?? now();
        if (is_string($deliveredAt) && str_contains($deliveredAt, 'T')) {
            $deliveredAt = str_replace('Z', '', $deliveredAt);
            $deliveredAt = str_replace('T', ' ', $deliveredAt);
        }

        // Mise à jour des items de la commande
        $order->items()->update([
            'delivered_at' => $deliveredAt,
            'status' => 'delivered'
        ]);

        // Mise à jour des événements de suivi
        $trackingEvents = $order->tracking_events ?? [];
        $trackingEvents[] = [
            'event' => 'delivered',
            'delivered_at' => $deliveredAt,
            'signature' => $data['signature'] ?? null,
            'proof_of_delivery' => $data['proof_of_delivery'] ?? null
        ];

        // Mise à jour de la commande
        $order->update([
            'status' => 'delivered',
            'delivered_at' => $deliveredAt,
            'tracking_events' => $trackingEvents
        ]);

        // Déclenchement des événements
        $this->dispatchOrderEvents($order, null, 'delivered');

        return response()->json([
            'success' => true,
            'message' => 'Delivery status updated successfully',
            'order' => [
                'id' => $order->id,
                'reference' => $order->reference,
                'status' => $order->status,
                'delivered_at' => $order->delivered_at?->toIso8601String()
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'details' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Webhook delivery error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        
        return response()->json([
            'error' => 'Internal server error',
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Traite les produits expédiés
     */
    private function processShippedProducts($order, $vendor, $data)
    {
        if (!isset($data['products'])) {
            // Tous les produits du vendeur sont expédiés
            $order->items()
                ->where('vendor_id', $vendor->id)
                ->update([
                    'shipped_at' => $data['shipped_at'] ?? now(),
                    'tracking_number' => $data['tracking_number'],
                    'carrier' => $data['carrier'],
                    'status' => 'shipped'
                ]);
            return;
        }

        // Mise à jour individuelle des produits
        foreach ($data['products'] as $productData) {
            $orderItem = $order->items()
                ->where('product_id', $productData['product_id'])
                ->first();

            if (!$orderItem) {
                Log::warning("Product ID {$productData['product_id']} not found in order {$order->id}");
                continue;
            }

            // Vérification que le produit appartient bien au vendeur (si la colonne existe)
            if (isset($orderItem->vendor_id) && $orderItem->vendor_id != $vendor->id) {
                Log::warning("Product ID {$productData['product_id']} does not belong to vendor {$vendor->id}");
                continue;
            }

            $orderItem->update([
                'shipped_at' => $data['shipped_at'] ?? now(),
                'tracking_number' => $data['tracking_number'],
                'carrier' => $data['carrier'],
                'status' => 'shipped',
                'quantity' => $productData['quantity'] ?? $orderItem->quantity
            ]);
        }
    }

    /**
     * Met à jour les informations de suivi de la commande
     */
private function updateOrderTracking($order, $data)
{
    // Convertir la date ISO 8601 en format MySQL
    $shippedAt = $data['shipped_at'] ?? now();
    if (is_string($shippedAt) && str_contains($shippedAt, 'T')) {
        $shippedAt = str_replace('Z', '', $shippedAt);
        $shippedAt = str_replace('T', ' ', $shippedAt);
    }

    $trackingEvents = $order->tracking_events ?? [];
    $trackingEvents[] = [
        'event' => 'shipped',
        'carrier' => $data['carrier'],
        'tracking_number' => $data['tracking_number'],
        'shipped_at' => $shippedAt,
        'estimated_delivery' => $data['estimated_delivery'] ?? null,
        'metadata' => $data['metadata'] ?? []
    ];

    // Mettre à jour les champs
    $order->tracking_number = $data['tracking_number'];
    $order->carrier = $data['carrier'];
    $order->shipped_at = $shippedAt;
    $order->tracking_events = $trackingEvents;
    $order->status = 'shipped';
    $order->save();
}

    /**
     * Déclenche les événements liés à la commande
     */
    private function dispatchOrderEvents($order, $vendor = null, $eventType)
    {
        try {
            switch ($eventType) {
                case 'shipped':
                    event(new \App\Events\OrderShipped($order, $vendor));
                    break;
                case 'delivered':
                    event(new \App\Events\OrderDelivered($order));
                    break;
            }
        } catch (\Exception $e) {
            Log::warning("Could not dispatch Order{$eventType} event: " . $e->getMessage());
        }
    }
}