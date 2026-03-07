<?php
// app/Models/Payout.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'reference',
        'amount',
        'commission_ids', // JSON des commissions incluses
        'status', // 'pending', 'processing', 'completed', 'failed'
        'payment_method',
        'payment_details',
        'processed_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_ids' => 'array',
        'payment_details' => 'array',
        'processed_at' => 'datetime',
    ];

    // Relations
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function commissions()
    {
        return Commission::whereIn('id', $this->commission_ids ?? []);
    }

    // Accesseurs
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' €';
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
        ];
        
        $labels = [
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'completed' => 'Effectué',
            'failed' => 'Échoué',
        ];
        
        $color = $colors[$this->status] ?? 'secondary';
        $label = $labels[$this->status] ?? $this->status;
        
        return "<span class='badge bg-{$color}'>{$label}</span>";
    }
}