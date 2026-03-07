<?php
// app/Models/FinancialTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'type', // 'income', 'expense', 'commission', 'refund'
        'category', // 'sale', 'purchase', 'fee', 'commission', 'withdrawal'
        'amount',
        'tax',
        'total',
        'status', // 'pending', 'completed', 'cancelled', 'failed'
        'payment_method', // 'card', 'cash', 'bank_transfer', 'check', 'paypal', 'stripe'
        'description',
        'transaction_date',
        'user_id',
        'order_id',
        'vendor_id',
        'supplier_id',
        'payment_proof',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'transaction_date' => 'datetime',
        'metadata' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeByPeriod($query, $start, $end)
    {
        return $query->whereBetween('transaction_date', [$start, $end]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year);
    }

    // Accesseurs
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' €';
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2) . ' €';
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            'failed' => 'danger',
        ];
        
        $labels = [
            'pending' => 'En attente',
            'completed' => 'Complété',
            'cancelled' => 'Annulé',
            'failed' => 'Échoué',
        ];
        
        $color = $colors[$this->status] ?? 'secondary';
        $label = $labels[$this->status] ?? $this->status;
        
        return "<span class='badge bg-{$color}'>{$label}</span>";
    }

    public function getTypeBadgeAttribute()
    {
        if ($this->type === 'income') {
            return '<span class="badge bg-success"><i class="fas fa-arrow-up mr-1"></i> Revenu</span>';
        } else {
            return '<span class="badge bg-danger"><i class="fas fa-arrow-down mr-1"></i> Dépense</span>';
        }
    }
}