<?php

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
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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

    public function webhooks()
{
    return $this->hasMany(VendorWebhook::class);
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
}