<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'short_description',
        'price',
        'quantity',
        'supplier_id',
        'category_id',
        'vendor_id',
        'image',
        'stock_threshold',
        'is_active',
    ];

    protected $attributes = [
        'stock_threshold' => 5,
        'quantity' => 0,
        'is_active' => true,
    ];

    // ✅ AJOUT IMPORTANT : caster les JSON en tableaux
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'short_description' => 'array',
    ];

    // Relations
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    // Relation avec les traductions
    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }
    
    // Scope pour charger les traductions
    public function scopeWithTranslation($query, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        return $query->with(['translations' => function($q) use ($locale) {
            $q->where('locale', $locale);
        }]);
    }
    
    // ✅ ACCESSEUR ROBUSTE POUR LE NOM TRADUIT (pour le front office)
    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        $shortLocale = str_contains($locale, '_') ? explode('_', $locale)[0] : $locale;
        
        // Essayer les traductions d'abord
        if ($this->relationLoaded('translations')) {
            // Essayer locale complète (de_DE)
            $translation = $this->translations->firstWhere('locale', $locale);
            
            // Essayer locale courte (de)
            if (!$translation) {
                $translation = $this->translations->firstWhere('locale', $shortLocale);
            }
            
            if ($translation && $translation->name) {
                return $translation->name;
            }
        }
        
        // Utiliser le JSON casté
        $names = $this->name;
        if (is_array($names)) {
            return $names[$locale] ?? $names[$shortLocale] ?? $names['fr'] ?? array_values($names)[0] ?? 'Produit';
        }
        
        return $names;
    }
    
    public function getTranslatedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $shortLocale = str_contains($locale, '_') ? explode('_', $locale)[0] : $locale;
        
        if ($this->relationLoaded('translations')) {
            $translation = $this->translations->firstWhere('locale', $locale);
            if (!$translation) {
                $translation = $this->translations->firstWhere('locale', $shortLocale);
            }
            if ($translation && $translation->description) {
                return $translation->description;
            }
        }
        
        $descriptions = $this->description;
        if (is_array($descriptions)) {
            return $descriptions[$locale] ?? $descriptions[$shortLocale] ?? $descriptions['fr'] ?? '';
        }
        
        return $descriptions;
    }
    
    public function getTranslatedShortDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $shortLocale = str_contains($locale, '_') ? explode('_', $locale)[0] : $locale;
        
        if ($this->relationLoaded('translations')) {
            $translation = $this->translations->firstWhere('locale', $locale);
            if (!$translation) {
                $translation = $this->translations->firstWhere('locale', $shortLocale);
            }
            if ($translation && $translation->short_description) {
                return $translation->short_description;
            }
        }
        
        $shortDescriptions = $this->short_description;
        if (is_array($shortDescriptions)) {
            return $shortDescriptions[$locale] ?? $shortDescriptions[$shortLocale] ?? $shortDescriptions['fr'] ?? '';
        }
        
        return $shortDescriptions;
    }

    // ✅ NOUVEAU : ACCESSEUR POUR L'ADMIN (version française)
    public function getAdminNameAttribute()
    {
        if (is_array($this->name)) {
            // Priorité au français, sinon première langue disponible
            return $this->name['fr'] ?? 
                   $this->name[array_key_first($this->name)] ?? 
                   'Sans nom';
        }
        return $this->name ?? 'Sans nom';
    }

    public function getAdminDescriptionAttribute()
    {
        if (isset($this->description) && is_array($this->description)) {
            return $this->description['fr'] ?? 
                   $this->description[array_key_first($this->description)] ?? 
                   '';
        }
        return $this->description ?? '';
    }

    public function getAdminShortDescriptionAttribute()
    {
        if (isset($this->short_description) && is_array($this->short_description)) {
            return $this->short_description['fr'] ?? 
                   $this->short_description[array_key_first($this->short_description)] ?? 
                   '';
        }
        return $this->short_description ?? '';
    }

    // Méthodes utilitaires
    public function isLowStock()
    {
        return $this->quantity <= $this->stock_threshold;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}