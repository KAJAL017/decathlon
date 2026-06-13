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
        return $this->getThumbnailUrl($this->image_url);
    }

    /**
     * Accessor for full image URL.
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;
        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }

    /**
     * Mutator to store only relative path.
     */
    public function setImageUrlAttribute($value)
    {
        if ($value && str_starts_with($value, 'http')) {
            $path = parse_url($value, PHP_URL_PATH);
            if (str_starts_with($path, '/storage/')) {
                $this->attributes['image_url'] = substr($path, 9); // Remove '/storage/'
            } else {
                $this->attributes['image_url'] = $path;
            }
        } else {
            $this->attributes['image_url'] = $value;
        }
    }

    /**
     * Scope a query to only include active banners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
