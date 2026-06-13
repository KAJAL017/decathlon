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

        $originalPath = $path;

        // If it's already a full URL, we can't easily find a thumbnail
        if (str_starts_with($path, 'http')) {
            $baseUrl = Storage::disk('public')->url('');
            // Normalize slashes
            $normalizedBase = rtrim($baseUrl, '/');
            if (str_starts_with($path, $normalizedBase)) {
                $path = ltrim(substr($path, strlen($normalizedBase)), '/');
            } else {
                return $path; // External URL
            }
        }

        $pathInfo = pathinfo($path);
        
        // Ensure extension exists
        if (!isset($pathInfo['extension'])) return $originalPath;

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

        return str_starts_with($originalPath, 'http') ? $originalPath : Storage::disk('public')->url($path);
    }
}
