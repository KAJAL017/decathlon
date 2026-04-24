<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'description',
        'provider',
        'video_url',
        'video_id',
        'thumbnail_url',
        'duration',
        'sort_order',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'duration' => 'integer',
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    // Accessors
    public function getEmbedUrlAttribute()
    {
        if ($this->provider === 'youtube') {
            return "https://www.youtube.com/embed/{$this->video_id}";
        } elseif ($this->provider === 'vimeo') {
            return "https://player.vimeo.com/video/{$this->video_id}";
        }
        
        return $this->video_url;
    }

    public function getThumbnailAttribute()
    {
        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }

        if ($this->provider === 'youtube') {
            return "https://img.youtube.com/vi/{$this->video_id}/maxresdefault.jpg";
        } elseif ($this->provider === 'vimeo') {
            // Vimeo thumbnails need API call, return placeholder
            return "https://via.placeholder.com/640x360?text=Vimeo+Video";
        }

        return null;
    }

    // Helper Methods
    public static function extractVideoId($url, $provider)
    {
        if ($provider === 'youtube') {
            // Extract YouTube video ID from various URL formats
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches);
            return $matches[1] ?? null;
        } elseif ($provider === 'vimeo') {
            // Extract Vimeo video ID
            preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/', $url, $matches);
            return $matches[3] ?? null;
        }

        return null;
    }
}
