<?php

namespace App\Http\Controllers;

use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UploadController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Handle general file upload with optimization
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|image',
            'folder' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'oldPath' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('file')
            ], 422);
        }

        try {
            // Attempt to determine type from folder if not provided
            $type = $request->input('type');
            $folder = $request->input('folder', 'general');
            
            if (!$type) {
                // Mapping some common folders to types
                $map = [
                    'products' => 'products',
                    'categories' => 'categories',
                    'collections' => 'collections',
                    'banners' => 'banners',
                    'brands' => 'brands',
                    'users' => 'users',
                ];
                $type = $map[$folder] ?? 'cms';
            }

            $result = $this->mediaService->upload(
                $request->file('file'),
                $type,
                $folder,
                $request->input('oldPath')
            );

            return response()->json($result);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first('file')
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete file from local storage
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filePath' => 'required_without:fileId|string',
            'fileId' => 'required_without:filePath|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File path or ID is required'
            ], 422);
        }

        $path = $request->filePath ?? $request->fileId;
        
        // Decode base64 if it's from the file manager
        if ($request->has('fileId') && !str_contains($path, '/') && base64_decode($path, true)) {
            $path = base64_decode($path);
        }

        if ($this->mediaService->delete($path)) {
            return response()->json(['success' => true, 'message' => 'File deleted']);
        }

        return response()->json(['success' => false, 'message' => 'File not found or already deleted'], 404);
    }

    public function usage()
    {
        $bytes = 0;
        $files = Storage::disk('public')->allFiles('uploads');
        foreach ($files as $file) {
            $bytes += Storage::disk('public')->size($file);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'bytes' => $bytes,
                'files' => count($files),
                'url_endpoint' => config('app.url')
            ]
        ]);
    }

    public function listFiles(Request $request)
    {
        $path = $request->get('path', 'uploads');
        $path = trim($path, '/');
        if (empty($path) || !str_starts_with($path, 'uploads')) {
            $path = 'uploads';
        }

        $directories = Storage::disk('public')->directories($path);
        $files = Storage::disk('public')->files($path);

        $items = [];
        foreach ($directories as $dir) {
            $items[] = [
                'type' => 'folder',
                'name' => basename($dir),
                'path' => '/' . $dir,
                'id' => base64_encode($dir)
            ];
        }

        foreach ($files as $file) {
            // Skip thumbnails in the general list to keep it clean
            if (str_contains($file, '_thumb.')) continue;

            $items[] = [
                'type' => 'file',
                'name' => basename($file),
                'path' => '/' . $file,
                'url'  => Storage::disk('public')->url($file),
                'size' => Storage::disk('public')->size($file),
                'updatedAt' => date('c', Storage::disk('public')->lastModified($file)),
                'id' => base64_encode($file)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function createFolder(Request $request)
    {
        $parent = trim($request->get('parentFolderPath', 'uploads'), '/');
        $name = trim($request->get('folderName'));
        
        if (empty($name)) {
            return response()->json(['success' => false, 'message' => 'Folder name is required'], 400);
        }

        $path = $parent . '/' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $name);
        Storage::disk('public')->makeDirectory($path);

        return response()->json(['success' => true, 'message' => 'Folder created']);
    }

    public function deleteFolder(Request $request)
    {
        $path = trim($request->get('folderPath', ''), '/');
        
        if (empty($path) || $path === 'uploads') {
            return response()->json(['success' => false, 'message' => 'Cannot delete root folder'], 400);
        }

        Storage::disk('public')->deleteDirectory($path);
        return response()->json(['success' => true, 'message' => 'Folder deleted']);
    }
}
