<?php
// app/Models/OrderStatusHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    /**
     * Spécifiez le nom de la table explicitement
     * car Laravel cherche "order_status_histories" par défaut
     */
    protected $table = 'order_status_history';

    protected $fillable = [
        'order_id',
        'status',
        'comment',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec la commande
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relation avec l'utilisateur qui a effectué le changement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accesseur pour le libellé du statut (multilingue)
     */
    public function getStatusLabelAttribute(): string
    {
        $statusLabels = [
            'pending' => [
                'fr' => 'En attente',
                'en' => 'Pending',
                'pt_BR' => 'Pendente',
            ],
            'paid' => [
                'fr' => 'Payée',
                'en' => 'Paid',
                'pt_BR' => 'Pago',
            ],
            'processing' => [
                'fr' => 'En traitement',
                'en' => 'Processing',
                'pt_BR' => 'Em processamento',
            ],
            'shipped' => [
                'fr' => 'Expédiée',
                'en' => 'Shipped',
                'pt_BR' => 'Enviado',
            ],
            'delivered' => [
                'fr' => 'Livrée',
                'en' => 'Delivered',
                'pt_BR' => 'Entregue',
            ],
            'arrived' => [
                'fr' => 'Arrivée',
                'en' => 'Arrived',
                'pt_BR' => 'Chegou',
            ],
            'cancelled' => [
                'fr' => 'Annulée',
                'en' => 'Cancelled',
                'pt_BR' => 'Cancelado',
            ],
            'refunded' => [
                'fr' => 'Remboursée',
                'en' => 'Refunded',
                'pt_BR' => 'Reembolsado',
            ],
        ];

        $locale = app()->getLocale();
        
        return $statusLabels[$this->status][$locale] ?? $statusLabels[$this->status]['fr'] ?? $this->status;
    }

    /**
     * Accesseur pour la couleur du statut
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'arrived' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Accesseur pour l'icône du statut
     */
    public function getStatusIconAttribute(): string
    {
        $icons = [
            'pending' => 'fa-clock',
            'paid' => 'fa-credit-card',
            'processing' => 'fa-cog',
            'shipped' => 'fa-truck',
            'delivered' => 'fa-check-circle',
            'arrived' => 'fa-check-circle',
            'cancelled' => 'fa-times-circle',
            'refunded' => 'fa-undo',
        ];

        return $icons[$this->status] ?? 'fa-info-circle';
    }

    /**
     * Accesseur pour la date formatée
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}