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
    // Définir les constantes de statut (en codes, indépendants de la langue)
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_ARRIVED = 'arrived';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    // Liste des statuts disponibles
    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PAID,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED,
            self::STATUS_ARRIVED,
            self::STATUS_CANCELLED,
            self::STATUS_REFUNDED,
        ];
    }

    // Obtenir le libellé du statut pour la langue courante
    public function getStatusLabelAttribute()
    {
        $locale = app()->getLocale();
        
        $statusLabels = [
            'pending' => [
                'fr' => 'En attente de confirmation',
                'en' => 'Pending confirmation',
                'pt_BR' => 'Aguardando confirmação',
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

        return $statusLabels[$this->status][$locale] ?? $statusLabels[$this->status]['fr'] ?? $this->status;
    }

    // Couleurs associées aux statuts (indépendantes de la langue)
    public function getStatusColorAttribute()
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

    // Icônes associées aux statuts
    public function getStatusIconAttribute()
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
        'tracking_number',
        'carrier',
        'shipped_at',
        'delivered_at',
        'tracking_events',
        'payment_reference',
        'paid_at',
        'commission_total',
        'reference',
    ];

    protected $casts = [
        'date' => 'date',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'paid_at' => 'datetime',
        'tracking_events' => 'array',
        'total_amount' => 'decimal:2',
        'commission_total' => 'decimal:2',
    ];

    // Relations existantes
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price', 'vendor_id', 'status')
                    ->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'order_items')
                    ->distinct();
    }

    // ===== NOUVELLES RELATIONS FINANCIÈRES =====
    
    /**
     * Transactions financières liées à cette commande
     */
    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'order_id');
    }

    /**
     * Commissions générées par cette commande
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    // Méthodes de vérification
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isArrived(): bool
    {
        return $this->status === self::STATUS_ARRIVED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    // Mise à jour du statut avec historique
    public function updateStatusWithHistory($newStatus, $comment = null, $userId = null)
    {
        $oldStatus = $this->status;
        
        if ($oldStatus !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
            
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

    // Calculer le nombre de jours depuis la commande
    public function getDaysSinceOrderAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    // Vérifier si la commande est en retard
    public function isDelayed(): bool
    {
        if ($this->status === self::STATUS_SHIPPED) {
            return $this->days_since_order > 5;
        }
        return false;
    }

    // Générer une référence de commande
    public function getReferenceAttribute(): string
    {
        return $this->attributes['reference'] ?? 'CMD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Calculer le total (si nécessaire)
    public function getTotalAttribute()
    {
        return $this->total_amount;
    }

    public function canBeTracked()
    {
        return in_array($this->status, [
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED,
            self::STATUS_ARRIVED,
        ]);
    }

    /**
 * Générer les commissions après validation d'une commande client
 */
    public function generateCommissions()
    {
        if ($this->type !== 'client') {
            return false;
        }

        foreach ($this->items as $item) {
            // Vérifier si le produit appartient à un vendeur marketplace
            if ($item->vendor_id) {
                $vendor = Vendor::find($item->vendor_id);
                
                if ($vendor && $vendor->commission_rate > 0) {
                    // Calculer la commission (pourcentage du prix de vente)
                    $commissionAmount = ($item->price * $item->quantity) * ($vendor->commission_rate / 100);
                    
                    Commission::create([
                        'vendor_id' => $vendor->id,
                        'order_id' => $this->id,
                        'order_item_id' => $item->id,
                        'amount' => $commissionAmount,
                        'rate' => $vendor->commission_rate,
                        'status' => 'pending',
                    ]);
                }
            }
        }
        
        return true;
    }
}