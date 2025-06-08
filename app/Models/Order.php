<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{

    protected $fillable = [
        'user_id',
        'total_amount',
        'order_number',
        'shipping_address',
        'shipping_country',
        'shipping_city',
        'shipping_state',
        'shipping_phone',
        'notes'
    ];

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(OrderStatus::class)->orderByDesc('created_at');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
