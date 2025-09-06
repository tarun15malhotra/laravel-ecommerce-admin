<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'usage_limit',
        'usage_count',
        'usage_limit_per_customer',
        'is_active',
        'valid_from',
        'valid_until',
        'applicable_categories',
        'applicable_products',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'usage_limit_per_customer' => 'integer',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        
        $now = now();
        
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }
        
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }
        
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }
        
        return true;
    }

    public function canBeUsedByCustomer($customerId): bool
    {
        if (!$this->usage_limit_per_customer) {
            return true;
        }
        
        $customerUsageCount = $this->orders()
            ->where('customer_id', $customerId)
            ->where('status', '!=', 'cancelled')
            ->count();
        
        return $customerUsageCount < $this->usage_limit_per_customer;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if ($this->minimum_order_amount && $orderAmount < $this->minimum_order_amount) {
            return 0;
        }
        
        if ($this->discount_type === 'percentage') {
            return min($orderAmount * ($this->discount_value / 100), $orderAmount);
        }
        
        return min($this->discount_value, $orderAmount);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
