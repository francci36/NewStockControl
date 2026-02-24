<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'supplier_id',
        'client_id',
        'type',
        'status',
        'date',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_zip',
        'shipping_country',
        'shipping_phone',
        'payment_method',
        'total_amount',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Statuts possibles
    const STATUS_PENDING = 'en attente';
    const STATUS_PROCESSING = 'en traitement';
    const STATUS_SHIPPED = 'expédiée';
    const STATUS_DELIVERED = 'livrée';
    const STATUS_ARRIVED = 'arrivé';
    const STATUS_CANCELLED = 'annulé';

    // Statuts pour les commandes clients (suivi)
    const TRACKING_STATUSES = [
        'en attente' => 'En attente de confirmation',
        'en traitement' => 'Commande en cours de préparation',
        'expédiée' => 'Commande expédiée',
        'livrée' => 'Commande livrée',
        'arrivé' => 'Commande arrivée',
        'annulé' => 'Commande annulée'
    ];

    // Couleurs associées aux statuts
    const STATUS_COLORS = [
        'en attente' => 'warning',
        'en traitement' => 'info',
        'expédiée' => 'primary',
        'livrée' => 'success',
        'arrivé' => 'success',
        'annulé' => 'danger'
    ];

    // Relation avec les produits
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    // Relation avec l'utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le fournisseur
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relation avec le client (pour les commandes clients)
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relation avec les items de commande
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relation avec le paiement
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Relation avec les transactions
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Relation avec l'historique des statuts (NOUVEAU)
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    // Vérifier si la commande est en attente
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Vérifier si la commande est arrivée
    public function isArrived()
    {
        return $this->status === self::STATUS_ARRIVED;
    }

    // Vérifier si la commande est annulée
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // Vérifier si la commande est expédiée
    public function isShipped()
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    // Vérifier si la commande est livrée
    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    // Vérifier si la commande est en traitement
    public function isProcessing()
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    // Obtenir le libellé du statut
    public function getStatusLabelAttribute()
    {
        return self::TRACKING_STATUSES[$this->status] ?? $this->status;
    }

    // Obtenir la couleur du statut
    public function getStatusColorAttribute()
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    // Obtenir l'icône du statut
    public function getStatusIconAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'fa-clock';
            case self::STATUS_PROCESSING:
                return 'fa-cog';
            case self::STATUS_SHIPPED:
                return 'fa-truck';
            case self::STATUS_DELIVERED:
            case self::STATUS_ARRIVED:
                return 'fa-check-circle';
            case self::STATUS_CANCELLED:
                return 'fa-times-circle';
            default:
                return 'fa-info-circle';
        }
    }

    // Méthode pour mettre à jour le statut avec historique (NOUVEAU)
    public function updateStatusWithHistory($newStatus, $comment = null, $userId = null)
    {
        $oldStatus = $this->status;
        
        if ($oldStatus !== $newStatus) {
            // Mettre à jour le statut
            $this->status = $newStatus;
            $this->save();
            
            // Créer l'historique si la table existe
            if (class_exists(OrderStatusHistory::class)) {
                $this->statusHistory()->create([
                    'status' => $newStatus,
                    'comment' => $comment,
                    'user_id' => $userId ?? auth()->id(),
                ]);
            }
        }
        
        return $this;
    }

    // Calculer le nombre de jours depuis la commande (NOUVEAU)
    public function getDaysSinceOrderAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    // Vérifier si la commande est en retard (NOUVEAU)
    public function isDelayed()
    {
        if ($this->status === self::STATUS_SHIPPED) {
            return $this->days_since_order > 5; // Plus de 5 jours en statut expédié
        }
        return false;
    }

    // Dans app/Models/Order.php

public function items()
{
    return $this->hasMany(OrderItem::class);
}



public function getReferenceAttribute()
{
    return $this->attributes['reference'] ?? 'CMD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
}
}