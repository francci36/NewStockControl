<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    // Retirez 'quantity' du $fillable
    const TYPE_SUPPLIER = 'supplier'; // Commande d'approvisionnement
    const TYPE_CLIENT = 'client'; // Commande client (boutique)

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
        // 'quantity' n'est PAS dans fillable
    ];

    

    protected $casts = [
        'date' => 'date',
    ];

    const STATUS_PENDING = 'en attente';
    const STATUS_ARRIVED = 'arrivé';
    const STATUS_CANCELLED = 'annulé';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le client (utilisateur qui a acheté)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}