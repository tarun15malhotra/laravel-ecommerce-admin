<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'cost_price',
        'sale_price',
        'stock_quantity',
        'low_stock_threshold',
        'track_quantity',
        'in_stock',
        'is_featured',
        'is_active',
        'weight',
        'dimensions',
        'images',
        'attributes',
        'views',
        'sales_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'track_quantity' => 'boolean',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
        'attributes' => 'array',
        'views' => 'integer',
        'sales_count' => 'integer',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->sale_price) {
            return null;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function isLowStock(): bool
    {
        return $this->track_quantity && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function decrementStock(int $quantity): void
    {
        if ($this->track_quantity) {
            $this->decrement('stock_quantity', $quantity);
            
            if ($this->stock_quantity <= 0) {
                $this->update(['in_stock' => false]);
            }
        }
    }

    public function incrementStock(int $quantity): void
    {
        if ($this->track_quantity) {
            $this->increment('stock_quantity', $quantity);
            $this->update(['in_stock' => true]);
        }
    }
}
