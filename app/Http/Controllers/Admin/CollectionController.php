<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    public function index()
    {
        return view('admin.pages.collections.index');
    }

    public function list(Request $request)
    {
        $query = Collection::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $collections = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $collections->items(),
            'pagination' => [
                'total' => $collections->total(),
                'per_page' => $collections->perPage(),
                'current_page' => $collections->currentPage(),
                'last_page' => $collections->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:collections,slug',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'type' => 'required|in:manual,auto',
            'rules' => 'nullable|array',
            'visibility' => 'required|in:visible,hidden',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'status' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->except(['product_ids']);
            $data['created_by'] = auth()->id();
            
            if (empty($data['slug']) || trim($data['slug']) === '') {
                unset($data['slug']);
            }

            $collection = Collection::create($data);

            // Handle products for manual collections
            if ($collection->type === 'manual' && $request->has('product_ids')) {
                $collection->syncProducts($request->product_ids);
            }

            // Apply auto rules for auto collections
            if ($collection->type === 'auto') {
                $collection->applyAutoRules();
            }

            \App\Models\ActivityLog::log(
                'created',
                'collections',
                "Created collection: {$collection->name}",
                ['collection_id' => $collection->id]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Collection created successfully',
                'data' => $collection
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create collection: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $collection = Collection::with(['products', 'creator'])->find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $collection
        ]);
    }

    public function update(Request $request, $id)
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:collections,slug,' . $id,
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'type' => 'required|in:manual,auto',
            'rules' => 'nullable|array',
            'visibility' => 'required|in:visible,hidden',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'status' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->except(['product_ids']);
            
            if (empty($data['slug']) || trim($data['slug']) === '') {
                unset($data['slug']);
            }

            $collection->update($data);

            // Handle products for manual collections
            if ($collection->type === 'manual' && $request->has('product_ids')) {
                $collection->syncProducts($request->product_ids);
            }

            // Apply auto rules for auto collections
            if ($collection->type === 'auto') {
                $collection->applyAutoRules();
            }

            \App\Models\ActivityLog::log(
                'updated',
                'collections',
                "Updated collection: {$collection->name}",
                ['collection_id' => $collection->id]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Collection updated successfully',
                'data' => $collection
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update collection: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        }

        \App\Models\ActivityLog::log(
            'deleted',
            'collections',
            "Deleted collection: {$collection->name}",
            ['collection_id' => $collection->id]
        );

        $collection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collection deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        }

        $collection->status = !$collection->status;
        $collection->save();

        \App\Models\ActivityLog::log(
            'status_changed',
            'collections',
            "Changed status of {$collection->name} to " . ($collection->status ? 'active' : 'inactive'),
            ['collection_id' => $collection->id, 'status' => $collection->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $collection
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:collections,id'
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
            $collection = Collection::find($id);
            if (!$collection) continue;

            switch ($action) {
                case 'activate':
                    $collection->status = true;
                    $collection->save();
                    $count++;
                    break;
                case 'deactivate':
                    $collection->status = false;
                    $collection->save();
                    $count++;
                    break;
                case 'feature':
                    $collection->is_featured = true;
                    $collection->save();
                    $count++;
                    break;
                case 'unfeature':
                    $collection->is_featured = false;
                    $collection->save();
                    $count++;
                    break;
                case 'delete':
                    $collection->delete();
                    $count++;
                    break;
            }
        }

        \App\Models\ActivityLog::log(
            'bulk_action',
            'collections',
            "Bulk {$action} performed on {$count} collections",
            ['action' => $action, 'count' => $count, 'ids' => $ids]
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully. {$count} collections affected."
        ]);
    }
}
