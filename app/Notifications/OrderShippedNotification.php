<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderShippedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre commande a été expédiée')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Votre commande #' . $this->order->reference . ' a été expédiée.')
            ->line('Numéro de suivi : ' . $this->order->tracking_number)
            ->line('Transporteur : ' . $this->order->carrier)
            ->action('Suivre ma commande', url('/client/orders/' . $this->order->id))
            ->line('Merci de votre confiance !');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_reference' => $this->order->reference,
            'tracking_number' => $this->order->tracking_number,
            'carrier' => $this->order->carrier,
            'message' => 'Votre commande a été expédiée'
        ];
    }
}