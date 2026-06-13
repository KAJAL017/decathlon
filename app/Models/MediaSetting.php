<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaSetting extends Model
{
    protected $fillable = [
        'image_type',
        'max_width',
        'max_height',
        'quality',
        'format',
        'keep_aspect_ratio',
        'prevent_upscaling',
        'auto_optimize',
        'generate_thumbnail',
        'thumbnail_width',
        'thumbnail_height',
    ];

    protected $casts = [
        'keep_aspect_ratio' => 'boolean',
        'prevent_upscaling' => 'boolean',
        'auto_optimize' => 'boolean',
        'generate_thumbnail' => 'boolean',
        'max_width' => 'integer',
        'max_height' => 'integer',
        'quality' => 'integer',
        'thumbnail_width' => 'integer',
        'thumbnail_height' => 'integer',
    ];

    /**
     * Get settings for a specific type or default if not found.
     */
    public static function getType(string $type)
    {
        return static::where('image_type', $type)->first() ?? static::getDefaults($type);
    }

    /**
     * Get hardcoded defaults if DB record is missing.
     */
    public static function getDefaults(string $type)
    {
        $defaults = [
            'products' => ['max_width' => 1200, 'max_height' => 1200, 'format' => 'webp', 'quality' => 85],
            'categories' => ['max_width' => 800, 'max_height' => 800, 'format' => 'webp', 'quality' => 85],
            'collections' => ['max_width' => 1200, 'max_height' => 800, 'format' => 'webp', 'quality' => 85],
            'brands' => ['max_width' => 600, 'max_height' => 600, 'format' => 'webp', 'quality' => 85],
            'banners' => ['max_width' => 1920, 'max_height' => 700, 'format' => 'webp', 'quality' => 90],
            'blogs' => ['max_width' => 1200, 'max_height' => 630, 'format' => 'webp', 'quality' => 85],
            'gallery' => ['max_width' => 1600, 'max_height' => 1600, 'format' => 'webp', 'quality' => 85],
            'users' => ['max_width' => 400, 'max_height' => 400, 'format' => 'webp', 'quality' => 80],
            'logo' => ['max_width' => 500, 'max_height' => 200, 'format' => 'png', 'quality' => 90],
            'favicon' => ['max_width' => 64, 'max_height' => 64, 'format' => 'png', 'quality' => 100],
        ];

        $data = $defaults[$type] ?? [
            'max_width' => 1200,
            'max_height' => 1200,
            'format' => 'webp',
            'quality' => 85,
        ];

        return new static(array_merge([
            'image_type' => $type,
            'keep_aspect_ratio' => true,
            'prevent_upscaling' => true,
            'auto_optimize' => true,
            'generate_thumbnail' => true,
            'thumbnail_width' => 150,
            'thumbnail_height' => 150,
        ], $data));
    }
}
