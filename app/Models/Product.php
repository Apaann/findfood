<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasImageUrl;

    protected $fillable = [
        'restaurant_id', 'name', 'category', 'price', 'is_best_seller', 'description', 'image',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_best_seller' => 'boolean',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}