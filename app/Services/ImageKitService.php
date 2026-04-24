<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImageKitService
{
    protected $publicKey;
    protected $privateKey;
    protected $urlEndpoint;

    public function __construct()
    {
        $this->publicKey = config('imagekit.public_key');
        $this->privateKey = config('imagekit.private_key');
        $this->urlEndpoint = config('imagekit.url_endpoint');
    }

    /**
     * Generate authentication parameters for client-side upload
     */
    public function getAuthenticationParameters()
    {
        $token = Str::random(40);
        $expire = time() + 3600; // 1 hour
        
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
     * Get responsive image URLs (multiple sizes)
     */
    public function getResponsiveUrls($filePath, $preset = 'category_image')
    {
        $presetConfig = config("imagekit.presets.{$preset}", []);
        
        return [
            'original' => $this->getUrl($filePath),
            'large' => $this->getUrl($filePath, array_merge($presetConfig, ['width' => $presetConfig['width'] ?? 1200])),
            'medium' => $this->getUrl($filePath, array_merge($presetConfig, ['width' => ($presetConfig['width'] ?? 1200) / 2])),
            'small' => $this->getUrl($filePath, array_merge($presetConfig, ['width' => ($presetConfig['width'] ?? 1200) / 4])),
            'thumbnail' => $this->getUrl($filePath, config('imagekit.presets.thumbnail')),
            'webp' => $this->getUrl($filePath, array_merge($presetConfig, ['format' => 'webp'])),
        ];
    }
}
