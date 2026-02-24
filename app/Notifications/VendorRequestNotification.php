<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorRequestNotification extends Notification
{
    use Queueable;

    protected $vendor;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Stockage en DB et email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouvelle demande vendeur sur votre marketplace')
                    ->greeting('Bonjour ' . $notifiable->name . '!')
                    ->line('Un nouvel utilisateur souhaite devenir vendeur sur votre plateforme.')
                    ->line('**Boutique :** ' . $this->vendor->shop_name)
                    ->line('**Vendeur :** ' . $this->vendor->user->name)
                    ->line('**Email :** ' . $this->vendor->user->email)
                    ->line('**Téléphone :** ' . $this->vendor->phone)
                    ->action('Voir la demande', route('admin.vendors.show', $this->vendor->id))
                    ->line('Merci de traiter cette demande rapidement.');
    }

    /**
     * Get the array representation of the notification for database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'vendor_id' => $this->vendor->id,
            'shop_name' => $this->vendor->shop_name,
            'user_name' => $this->vendor->user->name,
            'user_email' => $this->vendor->user->email,
            'message' => "Nouvelle demande vendeur : {$this->vendor->shop_name} par {$this->vendor->user->name}",
            'action_url' => route('admin.vendors.show', $this->vendor->id),
            'icon' => 'fa-store',
            'color' => 'warning',
            'time' => now()->toDateTimeString()
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'vendor-request';
    }
}