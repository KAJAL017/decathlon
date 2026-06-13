<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    protected $mediaService;

    public function __construct(\App\Services\MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        return view('admin.pages.banners.index');
    }

    public function list(Request $request)
    {
        $query = Banner::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', (bool)(int)$request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $banners = $query->orderBy('sort_order')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $banners->items(),
            'pagination' => [
                'total' => $banners->total(),
                'per_page' => $banners->perPage(),
                'current_page' => $banners->currentPage(),
                'last_page' => $banners->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_url' => 'required|string',
            'image_id' => 'nullable|string',
            'banner_link' => 'nullable|string|max:500',
            'sort_order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $banner = Banner::create($request->all());

        // Log activity
        \App\Models\ActivityLog::log(
            'created',
            'banners',
            "Created a new banner",
            ['banner_id' => $banner->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Banner created successfully',
            'data' => $banner
        ]);
    }

    public function show($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'image_url' => 'required|string',
            'image_id' => 'nullable|string',
            'banner_link' => 'nullable|string|max:500',
            'sort_order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Delete old image if changed
        if ($banner->image_url && $request->image_url !== $banner->getRawOriginal('image_url')) {
            $this->mediaService->delete($banner->getRawOriginal('image_url'));
        }

        $banner->update($request->all());

        // Log activity
        \App\Models\ActivityLog::log(
            'updated',
            'banners',
            "Updated banner #{$banner->id}",
            ['banner_id' => $banner->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Banner updated successfully',
            'data' => $banner
        ]);
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        // Log activity before deletion
        \App\Models\ActivityLog::log(
            'deleted',
            'banners',
            "Deleted banner #{$banner->id}",
            ['banner_id' => $banner->id]
        );

        if ($banner->image_url) {
            $this->mediaService->delete($banner->getRawOriginal('image_url'));
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        $banner->is_active = !$banner->is_active;
        $banner->save();

        // Log activity
        \App\Models\ActivityLog::log(
            'status_changed',
            'banners',
            "Changed status of banner #{$banner->id} to " . ($banner->is_active ? 'Active' : 'Inactive'),
            ['banner_id' => $banner->id, 'status' => $banner->is_active]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $banner
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:banners,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $action = $request->action;
        $ids = $request->ids;
        $count = 0;

        foreach ($ids as $id) {
            $banner = Banner::find($id);
            if (!$banner) continue;

            switch ($action) {
                case 'activate':
                    $banner->is_active = true;
                    $banner->save();
                    $count++;
                    break;
                case 'deactivate':
                    $banner->is_active = false;
                    $banner->save();
                    $count++;
                    break;
                case 'delete':
                    if ($banner->image_url) {
                        $this->mediaService->delete($banner->getRawOriginal('image_url'));
                    }
                    $banner->delete();
                    $count++;
                    break;
            }
        }

        // Log activity
        \App\Models\ActivityLog::log(
            'bulk_action',
            'banners',
            "Bulk {$action} performed on {$count} banners",
            ['action' => $action, 'count' => $count, 'ids' => $ids]
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully. {$count} banners affected."
        ]);
    }
}
