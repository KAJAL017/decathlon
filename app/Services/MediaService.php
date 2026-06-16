<?php

namespace App\Services;

use App\Models\MediaSetting;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaService
{
    protected $manager;
    protected $driverAvailable = false;

    public function __construct()
    {
        try {
            if (extension_loaded('gd')) {
                $this->manager = new ImageManager(new Driver());
                $this->driverAvailable = true;
            } elseif (extension_loaded('imagick')) {
                $this->manager = new ImageManager(new \Intervention\Image\Drivers\Imagick\Driver());
                $this->driverAvailable = true;
            }
        } catch (\Exception $e) {
            \Log::warning('MediaService: Failed to initialize image driver. Falling back to raw storage. Error: ' . $e->getMessage());
        }
    }

    /**
     * Process and store an uploaded image based on type settings.
     *
     * @param UploadedFile $file
     * @param string $type The image type (product, category, etc.)
     * @param string|null $customFolder Custom subfolder within uploads/
     * @param string|null $oldPath Optional old path to delete
     * @return array [url, filePath, fileId]
     */
    public function upload(UploadedFile $file, string $type, ?string $customFolder = null, ?string $oldPath = null)
    {
        $settings = MediaSetting::getType($type);
        $globalSettings = Setting::group('media');

        // 1. Validation (Type & Extension)
        $allowedExts = explode(',', $globalSettings['allowed_extensions'] ?? 'jpg,jpeg,png,webp');

        Validator::make(['file' => $file], [
            'file' => 'image|mimes:' . implode(',', $allowedExts)
        ])->validate();

        // 2. Generate Filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName);
        if ($globalSettings['unique_filenames'] ?? true) {
            $safeName .= '_' . time() . '_' . Str::random(5);
        }
        
        $extension = $settings->format === 'original' ? $file->getClientOriginalExtension() : $settings->format;
        // If driver is not available, we MUST use original extension if we can't convert
        if (!$this->driverAvailable) {
            $extension = $file->getClientOriginalExtension();
        }
        $filename = $safeName . '.' . $extension;

        // 3. Prepare Path
        $folder = $customFolder ?: $type;
        $path = 'uploads/' . trim($folder, '/');
        $fullPath = $path . '/' . $filename;

        // 4. Image Processing (Only if driver is available)
        $requiresProcessing = $settings->auto_optimize || $settings->format !== 'original' || ($globalSettings['strip_metadata'] ?? true);

        if ($requiresProcessing && !$this->driverAvailable) {
            throw new \RuntimeException('Image optimization is unavailable because no PHP image driver is enabled.');
        }

        if ($requiresProcessing) {
            try {
                $image = $this->manager->read($file);

                if ($settings->auto_optimize) {
                    $image->scaleDown($settings->max_width, $settings->max_height);
                }

                $encoded = $this->encodeImageToTargetSize(
                    $image,
                    $settings->format,
                    $settings->quality,
                    (int) ($globalSettings['max_upload_size'] ?? 0)
                );

                Storage::disk('public')->put($fullPath, (string) $encoded);
            } catch (\Exception $e) {
                \Log::error("MediaService: Image processing failed. Error: " . $e->getMessage());
                throw $e;
            }
        } else {
            Storage::disk('public')->putFileAs($path, $file, $filename);
        }

        // 5. Thumbnail Generation (Only if driver is available)
        if ($this->driverAvailable && $settings->generate_thumbnail) {
            $this->generateThumbnail($file, $path, $safeName, $settings);
        }

        // 6. Delete Old Image if requested
        if ($oldPath && ($globalSettings['auto_delete_old'] ?? true)) {
            $this->delete($oldPath);
        }

        return [
            'success' => true,
            'url' => Storage::disk('public')->url($fullPath),
            'filePath' => $fullPath,
            'fileId' => $fullPath
        ];
    }

    /**
     * Encode image to specific format and quality
     */
    protected function encodeImage($image, string $format, int $quality)
    {
        switch (strtolower($format)) {
            case 'webp':
                return $image->toWebp($quality);
            case 'jpg':
            case 'jpeg':
                return $image->toJpeg($quality);
            case 'png':
                return $image->toPng();
            default:
                return $image->encode();
        }
    }

    protected function encodeImageToTargetSize($image, string $format, int $quality, int $targetKb)
    {
        $encoded = $this->encodeImage($image, $format, $quality);

        if ($targetKb <= 0 || strtolower($format) === 'png') {
            return $encoded;
        }

        $targetBytes = $targetKb * 1024;
        $currentQuality = min(100, max(10, $quality));

        while (strlen((string) $encoded) > $targetBytes && $currentQuality > 35) {
            $currentQuality -= 10;
            $encoded = $this->encodeImage($image, $format, $currentQuality);
        }

        return $encoded;
    }

    /**
     * Generate thumbnail based on settings
     */
    protected function generateThumbnail($file, string $path, string $safeName, $settings)
    {
        try {
            $extension = $settings->format === 'original' ? $file->getClientOriginalExtension() : $settings->format;
            $thumbName = $safeName . '_thumb.' . $extension;
            $thumbPath = $path . '/' . $thumbName;

            $image = $this->manager->read($file);
            
            // Maintain aspect ratio if cover is not desired, but usually thumbnails are cropped to square
            // Requirement says "Maintain aspect ratio", so let's use scale() or resize() with ratio
            $image->scaleDown($settings->thumbnail_width ?? 150, $settings->thumbnail_height ?? 150);
            
            $encoded = $this->encodeImage($image, $settings->format, $settings->quality);

            Storage::disk('public')->put($thumbPath, (string) $encoded);
            
            return $thumbPath;
        } catch (\Exception $e) {
            \Log::error("Thumbnail generation failed for {$safeName}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete an image and its thumbnail
     */
    public function delete(?string $path)
    {
        if (!$path) return;

        // Remove domain if full URL is passed
        if (str_starts_with($path, 'http')) {
            $path = parse_url($path, PHP_URL_PATH);
            if (str_starts_with($path, '/storage/')) {
                $path = substr($path, 9);
            }
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            
            // Try to delete thumbnail
            $pathInfo = pathinfo($path);
            $thumbPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
            if (Storage::disk('public')->exists($thumbPath)) {
                Storage::disk('public')->delete($thumbPath);
            }
            
            return true;
        }

        return false;
    }
}
