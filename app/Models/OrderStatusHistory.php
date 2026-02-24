<?php
// app/Models/OrderStatusHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'order_status_history';
    
    protected $fillable = [
        'order_id',
        'status',
        'comment',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
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
     * Accesseur pour la date formatée
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Accesseur pour le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return Order::TRACKING_STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Accesseur pour la couleur du statut
     */
    public function getStatusColorAttribute(): string
    {
        return Order::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    /**
     * Accesseur pour l'icône du statut
     */
    public function getStatusIconAttribute(): string
    {
        switch ($this->status) {
            case Order::STATUS_PENDING:
                return 'fa-clock';
            case Order::STATUS_PROCESSING:
                return 'fa-cog';
            case Order::STATUS_SHIPPED:
                return 'fa-truck';
            case Order::STATUS_DELIVERED:
            case Order::STATUS_ARRIVED:
                return 'fa-check-circle';
            case Order::STATUS_CANCELLED:
                return 'fa-times-circle';
            default:
                return 'fa-info-circle';
        }
    }
}