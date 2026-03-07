<?php
// app/Models/Vendor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'slug',
        'description',
        'logo',
        'banner',
        'address',
        'city',              
        'postal_code',       
        'country',           
        'phone',
        'business_number',
        'bank_account',
        'commission_rate',
        'status',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'commission_rate' => 'decimal:2',
    ];

    /**
     * Relations existantes
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function webhooks()
    {
        return $this->hasMany(VendorWebhook::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relation avec les commandes via order_items
     */
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            OrderItem::class,
            'vendor_id',      // Clé étrangère sur order_items
            'id',             // Clé locale sur orders
            'id',             // Clé locale sur vendors
            'order_id'        // Clé étrangère sur order_items pour lier à orders
        )->distinct();
    }

    // ===== NOUVELLES RELATIONS FINANCIÈRES =====
    
    /**
     * Commissions du vendeur
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Paiements reçus par le vendeur
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * Transactions financières liées au vendeur
     */
    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Accesseurs
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-shop.png');
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getFormattedCommissionAttribute()
    {
        return $this->commission_rate . '%';
    }

    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }

    /**
     * Statistiques des commandes
     */
    public function getOrdersCountAttribute()
    {
        return $this->orderItems()
            ->distinct('order_id')
            ->count('order_id');
    }

    public function getPendingOrdersCountAttribute()
    {
        return $this->orders()
            ->where('status', 'pending')
            ->count();
    }

    public function getTotalRevenueAttribute()
    {
        return $this->orderItems()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->sum('orders.total_amount');
    }

    public function getMonthlyRevenueAttribute()
    {
        return $this->orderItems()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->sum('orders.total_amount');
    }

    /**
     * Statistiques financières
     */
    public function getTotalCommissionsAttribute()
    {
        return $this->commissions()->sum('amount');
    }

    public function getPendingCommissionsAttribute()
    {
        return $this->commissions()->where('status', 'pending')->sum('amount');
    }

    public function getPaidCommissionsAttribute()
    {
        return $this->commissions()->where('status', 'paid')->sum('amount');
    }

    public function getTotalPayoutsAttribute()
    {
        return $this->payouts()->sum('amount');
    }

    /**
     * Alternative plus simple : Compter via une sous-requête
     */
    public function getOrdersCountViaItemsAttribute()
    {
        return $this->orderItems()
            ->select('order_id')
            ->distinct()
            ->get()
            ->count();
    }

    /**
     * Génère un slug unique à partir du nom de la boutique
     */
    public static function generateUniqueSlug($shopName)
    {
        $slug = \Str::slug($shopName);
        $count = 1;
        $originalSlug = $slug;
        
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }

    /**
     * Accesseur pour l'affichage dans l'admin - nom de la boutique
     */
    public function getAdminShopNameAttribute()
    {
        if (isset($this->shop_name) && is_array($this->shop_name)) {
            return $this->shop_name['fr'] ?? 
                $this->shop_name[array_key_first($this->shop_name)] ?? 
                'Boutique sans nom';
        }
        return $this->shop_name ?? 'Boutique sans nom';
    }

    /**
     * Accesseur pour l'affichage dans l'admin - description
     */
    public function getAdminDescriptionAttribute()
    {
        if (isset($this->description) && is_array($this->description)) {
            return $this->description['fr'] ?? 
                $this->description[array_key_first($this->description)] ?? 
                '';
        }
        return $this->description ?? '';
    }

    /**
     * Badge de statut pour l'affichage
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'active' => 'success',
            'suspended' => 'danger',
            'rejected' => 'secondary',
        ];
        
        $labels = [
            'pending' => 'En attente',
            'active' => 'Actif',
            'suspended' => 'Suspendu',
            'rejected' => 'Rejeté',
        ];
        
        $color = $colors[$this->status] ?? 'secondary';
        $label = $labels[$this->status] ?? $this->status;
        
        return "<span class='badge bg-{$color}'>{$label}</span>";
    }
}