<?php

namespace App\Http\Controllers;

use App\Services\ImageKitService;
use Illuminate\Http\Request;

class ImageKitController extends Controller
{
    protected $imageKit;

    public function __construct(ImageKitService $imageKit)
    {
        $this->imageKit = $imageKit;
    }

    /**
     * Get authentication parameters for client-side upload
     */
    public function auth()
    {
        try {
            $params = $this->imageKit->getAuthenticationParameters();
            
            return response()->json($params);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate authentication parameters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload image (server-side)
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB max
            'folder' => 'nullable|string',
            'type' => 'nullable|in:image,banner,icon',
        ]);

        try {
            $file = $request->file('file');
            $folder = $request->input('folder', 'categories');
            $type = $request->input('type', 'image');
            
            // Upload to ImageKit
            $result = $this->imageKit->upload($file, null, $folder);
            
            // Get optimized URLs based on type
            $preset = 'category_' . $type;
            $urls = $this->imageKit->getResponsiveUrls($result['filePath'], $preset);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'fileId' => $result['fileId'],
                    'filePath' => $result['filePath'],
                    'url' => $result['url'],
                    'name' => $result['name'],
                    'size' => $result['size'],
                    'responsiveUrls' => $urls,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete image
     */
    public function delete(Request $request)
    {
        $request->validate([
            'fileId' => 'required|string',
        ]);

        try {
            $deleted = $this->imageKit->delete($request->fileId);
            
            return response()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Image deleted successfully' : 'Failed to delete image'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
