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
            
            // ImageKit SDK expects these exact keys
            return response()->json([
                'token' => $params['token'],
                'expire' => $params['expire'],
                'signature' => $params['signature']
            ]);
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

    // ── File Manager ──────────────────────────────────────────────
    public function listFiles(\Illuminate\Http\Request $request)
    {
        try {
            $path  = $request->get('path', '/');
            $skip  = (int)$request->get('skip', 0);
            $limit = (int)$request->get('limit', 50);

            $files   = $this->imageKit->listFiles($path, $skip, $limit);
            $folders = $this->imageKit->listFolders($path);

            return response()->json([
                'success' => true,
                'data'    => [
                    'folders' => $folders,
                    'files'   => $files,
                    'path'    => $path,
                    'skip'    => $skip,
                    'limit'   => $limit,
                    'has_more'=> count($files) === $limit,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function createFolder(\Illuminate\Http\Request $request)
    {
        $request->validate(['folder_name' => 'required|string|max:100', 'parent_path' => 'nullable|string']);
        try {
            $result = $this->imageKit->createFolder(
                $request->folder_name,
                $request->parent_path ?? '/'
            );
            return response()->json(['success' => true, 'message' => 'Folder created', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteFolder(\Illuminate\Http\Request $request)
    {
        $request->validate(['folder_path' => 'required|string']);
        try {
            $this->imageKit->deleteFolder($request->folder_path);
            return response()->json(['success' => true, 'message' => 'Folder deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function uploadToFolder(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'file'   => 'required|image|max:10240',
            'folder' => 'nullable|string',
        ]);
        try {
            $folder = $request->input('folder', '/');
            $result = $this->imageKit->upload($request->file('file'), null, $folder);
            return response()->json(['success' => true, 'message' => 'Uploaded', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function usage()
    {
        try {
            $data = $this->imageKit->getUsage();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
