<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{

    use HasFactory;

    protected $fillable = [
        'product_id',
        'path',
        'is_primary'
    ];

    public function isPrimary(): bool
    {
        return $this->is_primary;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
