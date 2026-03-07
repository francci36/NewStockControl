<?php
// app/Http/Controllers/Webhook/DeliveryController.php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    /**
     * Webhook universel pour tous les transporteurs
     */
    public function handle(Request $request, $provider)
    {
        // 1. Log pour débogage
        Log::info("Webhook reçu de {$provider}", $request->all());
        
        // 2. Sauvegarder les données brutes
        $webhook = DeliveryWebhook::create([
            'provider' => $provider,
            'tracking_number' => $this->extractTrackingNumber($request, $provider),
            'event' => $this->extractEvent($request, $provider),
            'payload' => $request->all(),
            'raw_data' => $request->getContent(),
            'status' => 'received'
        ]);
        
        // 3. Trouver la commande correspondante
        $order = Order::where('tracking_number', $webhook->tracking_number)->first();
        
        if (!$order) {
            Log::warning("Commande non trouvée pour tracking: {$webhook->tracking_number}");
            $webhook->update(['status' => 'order_not_found']);
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        $webhook->update(['order_id' => $order->id]);
        
        // 4. Traiter selon l'événement
        DB::beginTransaction();
        try {
            $newStatus = $this->mapEventToStatus($webhook->event, $provider);
            
            if ($newStatus && $newStatus != $order->status) {
                $comment = "Mise à jour automatique via {$provider}: {$webhook->event}";
                
                $order->updateStatusWithHistory(
                    $newStatus,
                    $comment,
                    null // Système, pas d'utilisateur
                );
                
                // Si livré, enregistrer la date
                if ($newStatus == 'delivered') {
                    $order->delivered_at = now();
                    $order->save();
                }
                
                Log::info("Commande #{$order->id} mise à jour vers {$newStatus}");
            }
            
            // 5. Sauvegarder les événements de tracking
            $trackingEvents = $this->extractTrackingHistory($request, $provider);
            if ($trackingEvents) {
                $order->tracking_events = $trackingEvents;
                $order->save();
            }
            
            $webhook->update([
                'status' => 'processed',
                'processed_at' => now()
            ]);
            
            DB::commit();
            
            // 6. Notifier le client (optionnel)
            if ($newStatus == 'delivered') {
                // event(new OrderDelivered($order));
            }
            
            return response()->json(['status' => 'processed']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur traitement webhook: " . $e->getMessage());
            $webhook->update(['status' => 'error', 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing error'], 500);
        }
    }
    
    /**
     * Extraire le numéro de suivi selon le transporteur
     */
    private function extractTrackingNumber(Request $request, $provider)
    {
        switch ($provider) {
            case 'colissimo':
                return $request->input('trackingNumber') ?? 
                       $request->input('parcelNumber') ??
                       $request->input('data.parcel.number');
                       
            case 'ups':
                return $request->input('tracking_number') ??
                       $request->input('package.trackingNumber');
                       
            case 'dhl':
                return $request->input('shipment.trackingNumber') ??
                       $request->input('trackingNumber');
                       
            default:
                // Recherche dans tous les champs possibles
                return $request->input('tracking_number') ??
                       $request->input('trackingNumber') ??
                       $request->input('tracking') ??
                       $request->input('parcel_number');
        }
    }
    
    /**
     * Extraire l'événement selon le transporteur
     */
    private function extractEvent(Request $request, $provider)
    {
        $statusMap = [
            'colissimo' => [
                'delivered' => ['LIVRE', 'DELIVERED', 'REMIS'],
                'in_transit' => ['EN_TRANSIT', 'IN_TRANSIT', 'EN_COURS'],
                'exception' => ['ERREUR', 'EXCEPTION', 'PROBLEME'],
            ],
            'ups' => [
                'delivered' => ['D', 'DELIVERED'],
                'in_transit' => ['I', 'IN_TRANSIT'],
                'exception' => ['X', 'EXCEPTION'],
            ]
        ];
        
        // Logique d'extraction selon le provider
        return $request->input('event') ??
               $request->input('status') ??
               $request->input('data.status') ??
               'unknown';
    }
    
    /**
     * Convertir l'événement en statut de commande
     */
    private function mapEventToStatus($event, $provider)
    {
        $event = strtolower($event);
        
        if (str_contains($event, 'delivered') || 
            str_contains($event, 'livre') || 
            str_contains($event, 'remis')) {
            return 'delivered';
        }
        
        if (str_contains($event, 'in_transit') || 
            str_contains($event, 'en_cours') || 
            str_contains($event, 'en transit')) {
            return 'shipped';
        }
        
        if (str_contains($event, 'exception') || 
            str_contains($event, 'error') || 
            str_contains($event, 'probleme')) {
            return 'cancelled'; // ou un statut spécial 'delivery_exception'
        }
        
        return null;
    }
    
    /**
     * Extraire l'historique de suivi
     */
    private function extractTrackingHistory(Request $request, $provider)
    {
        switch ($provider) {
            case 'colissimo':
                return $request->input('history') ?? 
                       $request->input('trackingEvents');
            case 'ups':
                return $request->input('package.activity');
            default:
                return $request->input('history') ?? 
                       $request->input('tracking_events') ??
                       $request->input('events');
        }
    }
}