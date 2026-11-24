<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasImageUrl;

    protected $fillable = [
        'name', 'type', 'address', 'lat', 'lng', 'phone', 'rating', 'image', 'google_place_id',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'rating' => 'float',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('name');
    }

    public function getMapsUrlAttribute(): string
    {
        $url = "https://www.google.com/maps/search/?api=1&query={$this->lat},{$this->lng}";

        return $this->google_place_id
            ? $url . '&query_place_id=' . $this->google_place_id
            : $url;
    }

    public function getDirectionsUrlAttribute(): string
    {
        return "https://www.google.com/maps/dir/?api=1&destination={$this->lat},{$this->lng}";
    }
}