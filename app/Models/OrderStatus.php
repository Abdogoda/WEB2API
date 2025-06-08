<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatus extends Model
{

    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'user_id'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function byUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
