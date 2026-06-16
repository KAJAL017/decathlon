<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasMediaThumbnails
{
    /**
     * Get the thumbnail URL for a given image path.
     *
     * @param string|null $path The relative path to the original image.
     * @return string|null
     */
    public function getThumbnailUrl(?string $path)
    {
        if (!$path) return null;

        // Reject external URLs — only local paths allowed
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        // Strip leading slash and /storage/ prefix if present
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        $pathInfo = pathinfo($path);

        // Ensure extension exists
        if (!isset($pathInfo['extension'])) {
            return Storage::disk('public')->url($path);
        }

        $thumbPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];

        if (Storage::disk('public')->exists($thumbPath)) {
            return Storage::disk('public')->url($thumbPath);
        }

        // Try WebP fallback if original wasn't WebP
        if (strtolower($pathInfo['extension']) !== 'webp') {
            $webpThumb = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.webp';
            if (Storage::disk('public')->exists($webpThumb)) {
                return Storage::disk('public')->url($webpThumb);
            }
        }

        return Storage::disk('public')->url($path);
    }
}
