<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, \App\Traits\HasMediaThumbnails;

    protected $fillable = [
        'image_url',
        'image_id',
        'banner_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['thumbnail_url'];

    public function getThumbnailUrlAttribute()
    {
        return $this->getThumbnailUrl($this->getRawOriginal('image_url'));
    }

    /**
     * Accessor for full image URL.
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;

        // Reject external URLs
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return null;
        }

        $path = $this->normalizeImagePath($value);

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }

    /**
     * Mutator to store only relative path.
     */
    public function setImageUrlAttribute($value)
    {
        $this->attributes['image_url'] = $this->normalizeImagePath($value);
    }

    protected function normalizeImagePath($value)
    {
        if (!$value) return $value;

        $path = str_starts_with($value, 'http') ? parse_url($value, PHP_URL_PATH) : $value;
        $path = str_replace('\\', '/', $path);

        if (str_contains($path, '/storage/')) {
            return ltrim(substr($path, strpos($path, '/storage/') + 9), '/');
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            return substr($path, 8);
        }

        return $path;
    }

    /**
     * Scope a query to only include active banners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
