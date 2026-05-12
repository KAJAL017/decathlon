<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        $tags = ProductTag::ordered()->get();
        return view('admin.pages.tags.index', compact('tags'));
    }

    public function list(Request $request)
    {
        $query = ProductTag::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get stats
        $stats = [
            'total' => ProductTag::count(),
            'active' => ProductTag::where('status', true)->count(),
            'inactive' => ProductTag::where('status', false)->count(),
        ];

        // Pagination
        $perPage = $request->get('per_page', 10);
        $tags = $query->ordered()->paginate($perPage);

        return response()->json([
            'success' => true,
            'tags' => $tags,
            'stats' => $stats
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_tags,name',
            'slug' => 'nullable|string|unique:product_tags,slug',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Auto-generate slug if not provided or empty
        if (empty($data['slug']) || trim($data['slug']) === '') {
            unset($data['slug']);
        }

        $tag = ProductTag::create($data);

        // Log activity
        \App\Models\ActivityLog::log(
            'created',
            'tags',
            "Created tag: {$tag->name}",
            ['tag_id' => $tag->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'data' => $tag
        ]);
    }

    public function show($id)
    {
        $tag = ProductTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tag' => $tag
        ]);
    }

    public function update(Request $request, $id)
    {
        $tag = ProductTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_tags,name,' . $id,
            'slug' => 'nullable|string|unique:product_tags,slug,' . $id,
            'description' => 'nullable|string',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Remove empty slug to let model regenerate it if name changed
        if (empty($data['slug']) || trim($data['slug']) === '') {
            unset($data['slug']);
        }

        $tag->update($data);

        // Log activity
        \App\Models\ActivityLog::log(
            'updated',
            'tags',
            "Updated tag: {$tag->name}",
            ['tag_id' => $tag->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag
        ]);
    }

    public function destroy($id)
    {
        $tag = ProductTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found'
            ], 404);
        }

        // Check if tag has products
        if ($tag->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete tag with products. Please remove products from this tag first.'
            ], 403);
        }

        // Log activity before deletion
        \App\Models\ActivityLog::log(
            'deleted',
            'tags',
            "Deleted tag: {$tag->name}",
            ['tag_id' => $tag->id]
        );

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $tag = ProductTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->status = !$tag->status;
        $tag->save();

        // Log activity
        \App\Models\ActivityLog::log(
            'status_changed',
            'tags',
            "Changed status of {$tag->name} to " . ($tag->status ? 'Active' : 'Inactive'),
            ['tag_id' => $tag->id, 'status' => $tag->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $tag
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:product_tags,id'
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
        $skipped = 0;

        foreach ($ids as $id) {
            $tag = ProductTag::find($id);
            if (!$tag) continue;

            switch ($action) {
                case 'activate':
                    $tag->status = true;
                    $tag->save();
                    $count++;
                    break;
                case 'deactivate':
                    $tag->status = false;
                    $tag->save();
                    $count++;
                    break;
                case 'delete':
                    // Check if has products
                    if ($tag->products()->count() > 0) {
                        $skipped++;
                        continue 2;
                    }
                    $tag->delete();
                    $count++;
                    break;
            }
        }

        // Log activity
        \App\Models\ActivityLog::log(
            'bulk_action',
            'tags',
            "Bulk {$action} performed on {$count} tags" . ($skipped > 0 ? ", {$skipped} skipped" : ""),
            ['action' => $action, 'count' => $count, 'skipped' => $skipped, 'ids' => $ids]
        );

        $message = "Bulk action completed successfully. {$count} tags affected.";
        if ($skipped > 0) {
            $message .= " {$skipped} tags skipped (have products).";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
