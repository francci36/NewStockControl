<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VendorWebhookService
{
    /**
     * Envoyer une notification au vendeur
     */
    public function notifyVendor(Vendor $vendor, string $event, array $data)
    {
        $webhook = $vendor->webhooks()->where('is_active', true)->first();
        
        if (!$webhook) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Webhook-Signature' => $this->generateSignature($data, $webhook->secret),
                'X-Webhook-Event' => $event
            ])->post($webhook->url, [
                'event' => $event,
                'data' => $data,
                'timestamp' => now()->toIso8601String()
            ]);

            if ($response->failed()) {
                Log::warning("Webhook failed for vendor {$vendor->id}: " . $response->body());
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error("Webhook error for vendor {$vendor->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Générer la signature pour le webhook
     */
    protected function generateSignature(array $data, ?string $secret): string
    {
        $payload = json_encode($data) . $secret;
        return hash_hmac('sha256', $payload, $secret ?? config('services.vendor.webhook_secret'));
    }

    /**
     * Vérifier la signature d'un webhook entrant
     */
    public function verifySignature(string $payload, string $signature, ?string $secret = null): bool
    {
        $secret = $secret ?? config('services.vendor.webhook_secret');
        $computed = hash_hmac('sha256', $payload, $secret);
        return hash_equals($computed, $signature);
    }
}