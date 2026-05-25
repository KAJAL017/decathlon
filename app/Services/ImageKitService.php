<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Setting;

class ImageKitService
{
    protected $publicKey;
    protected $privateKey;
    protected $urlEndpoint;

    public function __construct()
    {
        // DB first, then .env fallback
        $this->publicKey   = Setting::get('imagekit_public_key')   ?: config('imagekit.public_key');
        $this->privateKey  = Setting::get('imagekit_private_key')  ?: config('imagekit.private_key');
        $this->urlEndpoint = Setting::get('imagekit_url_endpoint') ?: config('imagekit.url_endpoint');
    }

    /**
     * Generate authentication parameters for client-side upload
     */
    public function getAuthenticationParameters()
    {
        $token = Str::random(40);
        // Use 10 minutes instead of 1 hour to avoid timezone issues
        $expire = time() + 600; // 10 minutes from now
        
        // Debug: Log the current time and expire time
        \Log::info('ImageKit Auth Debug', [
            'current_time' => time(),
            'current_datetime' => date('Y-m-d H:i:s'),
            'expire_time' => $expire,
            'expire_datetime' => date('Y-m-d H:i:s', $expire),
            'difference_seconds' => $expire - time()
        ]);
        
        $signature = hash_hmac(
            'sha1',
            $token . $expire,
            $this->privateKey
        );

        return [
            'token' => $token,
            'expire' => $expire,
            'signature' => $signature,
            'publicKey' => $this->publicKey,
            'urlEndpoint' => $this->urlEndpoint,
        ];
    }

    /**
     * Upload image to ImageKit
     */
    public function upload($file, $fileName = null, $folder = 'categories')
    {
        if (!$fileName) {
            $fileName = time() . '_' . Str::random(10);
        }

        $response = Http::withBasicAuth($this->privateKey, '')
            ->attach('file', file_get_contents($file), $fileName)
            ->post('https://upload.imagekit.io/api/v1/files/upload', [
                'fileName' => $fileName,
                'folder' => $folder,
                'useUniqueFileName' => 'true', // Must be string 'true', not boolean
                'tags' => 'category,ecommerce',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('ImageKit upload failed: ' . $response->body());
    }

    /**
     * Delete image from ImageKit
     */
    public function delete($fileId)
    {
        $response = Http::withBasicAuth($this->privateKey, '')
            ->delete("https://api.imagekit.io/v1/files/{$fileId}");

        return $response->successful();
    }

    /**
     * Get optimized image URL with transformations
     */
    public function getUrl($filePath, $transformations = [])
    {
        $url = rtrim($this->urlEndpoint, '/') . '/' . ltrim($filePath, '/');
        
        if (!empty($transformations)) {
            $params = [];
            
            if (isset($transformations['width'])) {
                $params[] = 'w-' . $transformations['width'];
            }
            if (isset($transformations['height'])) {
                $params[] = 'h-' . $transformations['height'];
            }
            if (isset($transformations['quality'])) {
                $params[] = 'q-' . $transformations['quality'];
            }
            if (isset($transformations['format'])) {
                $params[] = 'f-' . $transformations['format'];
            }
            
            // Add compression
            $params[] = 'c-maintain_ratio';
            
            if (!empty($params)) {
                $url .= '?tr=' . implode(',', $params);
            }
        }
        
        return $url;
    }

    /**
     * Get responsive image URLs (multiple sizes for all devices)
     */
    public function getResponsiveUrls($filePath, $preset = 'category_image')
    {
        // Define responsive breakpoints for all devices
        $sizes = [
            // Mobile devices
            'mobile_small' => ['width' => 320, 'quality' => 80],   // Small phones
            'mobile' => ['width' => 480, 'quality' => 80],         // Standard phones
            'mobile_large' => ['width' => 640, 'quality' => 85],   // Large phones
            
            // Tablets
            'tablet' => ['width' => 768, 'quality' => 85],         // Portrait tablets
            'tablet_large' => ['width' => 1024, 'quality' => 85],  // Landscape tablets
            
            // Desktop
            'desktop' => ['width' => 1280, 'quality' => 90],       // Standard desktop
            'desktop_large' => ['width' => 1920, 'quality' => 90], // Large desktop
            
            // Special sizes
            'thumbnail' => ['width' => 150, 'height' => 150, 'quality' => 80],
            'card' => ['width' => 400, 'height' => 400, 'quality' => 85],
            'hero' => ['width' => 2560, 'quality' => 90],          // 4K displays
        ];

        $responsiveUrls = [
            'original' => $this->getUrl($filePath),
        ];

        // Generate URLs for each size
        foreach ($sizes as $sizeName => $transformations) {
            // WebP format for modern browsers
            $responsiveUrls[$sizeName . '_webp'] = $this->getUrl($filePath, array_merge($transformations, ['format' => 'webp']));
            
            // JPEG format for fallback
            $responsiveUrls[$sizeName . '_jpg'] = $this->getUrl($filePath, array_merge($transformations, ['format' => 'jpg']));
        }

        return $responsiveUrls;
    }

    /**
     * Generate srcset string for responsive images
     */
    public function generateSrcset($filePath, $format = 'webp')
    {
        $widths = [320, 480, 640, 768, 1024, 1280, 1920, 2560];
        $srcset = [];

        foreach ($widths as $width) {
            $url = $this->getUrl($filePath, [
                'width' => $width,
                'format' => $format,
                'quality' => $width > 1280 ? 90 : 85
            ]);
            $srcset[] = "{$url} {$width}w";
        }

        return implode(', ', $srcset);
    }
}
