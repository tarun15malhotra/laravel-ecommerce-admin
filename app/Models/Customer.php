<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'password',
        'is_active',
        'email_verified',
        'email_verified_at',
        'total_spent',
        'order_count',
        'customer_group',
        'preferences',
        'last_order_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'email_verified' => 'boolean',
        'email_verified_at' => 'datetime',
        'total_spent' => 'decimal:2',
        'order_count' => 'integer',
        'preferences' => 'array',
        'last_order_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function updateOrderStats(): void
    {
        $stats = $this->orders()
            ->where('status', '!=', 'cancelled')
            ->selectRaw('COUNT(*) as count, SUM(total_amount) as total, MAX(created_at) as last_order')
            ->first();
        
        $this->update([
            'order_count' => $stats->count ?? 0,
            'total_spent' => $stats->total ?? 0,
            'last_order_at' => $stats->last_order,
        ]);
    }

    public function getCustomerTypeAttribute(): string
    {
        if ($this->total_spent > 10000) {
            return 'VIP';
        } elseif ($this->total_spent > 5000) {
            return 'Gold';
        } elseif ($this->total_spent > 1000) {
            return 'Silver';
        }
        return 'Regular';
    }
}
