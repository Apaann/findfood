<?php

namespace App\Models\Concerns;

trait HasImageUrl
{
    /**
     * Kolom 'image' bisa berisi:
     * - path lokal di disk public (contoh: products/abc.jpg), atau
     * - URL lengkap dari situs lain (https://...).
     */
    public function getImageUrlAttribute(): ?string
    {
        $image = $this->image;

        if (! $image) {
            return null;
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return asset('storage/' . ltrim($image, '/'));
    }
}