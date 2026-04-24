<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function index()
    {
        return view('admin.pages.attributes.index');
    }

    public function list(Request $request)
    {
        $query = Attribute::with('group')->withCount('values');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
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

        // Pagination
        $perPage = $request->get('per_page', 10);
        $attributes = $query->ordered()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $attributes->items(),
            'pagination' => [
                'total' => $attributes->total(),
                'per_page' => $attributes->perPage(),
                'current_page' => $attributes->currentPage(),
                'last_page' => $attributes->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'nullable|exists:attribute_groups,id',
            'name' => 'required|string|max:255|unique:attributes,name',
            'slug' => 'nullable|string|unique:attributes,slug',
            'type' => 'required|in:select,multiselect,color,text,number,boolean',
            'display_type' => 'required|in:dropdown,radio,checkbox,color_swatch',
            'is_variant' => 'boolean',
            'is_filterable' => 'boolean',
            'is_required' => 'boolean',
            'is_searchable' => 'boolean',
            'unit' => 'nullable|string|max:50',
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
        
        // Auto-increment sort_order if not provided
        if (!isset($data['sort_order']) || $data['sort_order'] === null || $data['sort_order'] === '') {
            $maxSortOrder = Attribute::max('sort_order');
            $data['sort_order'] = $maxSortOrder ? $maxSortOrder + 1 : 1;
        }
        
        if (empty($data['slug']) || trim($data['slug']) === '') {
            unset($data['slug']);
        }

        $attribute = Attribute::create($data);

        \App\Models\ActivityLog::log(
            'created',
            'attributes',
            "Created attribute: {$attribute->name}",
            ['attribute_id' => $attribute->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute created successfully',
            'data' => $attribute->load('group')->loadCount('values')
        ]);
    }

    public function show($id)
    {
        $attribute = Attribute::with('group')->withCount('values')->find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attribute
        ]);
    }

    public function update(Request $request, $id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'group_id' => 'nullable|exists:attribute_groups,id',
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
            'slug' => 'nullable|string|unique:attributes,slug,' . $id,
            'type' => 'required|in:select,multiselect,color,text,number,boolean',
            'display_type' => 'required|in:dropdown,radio,checkbox,color_swatch',
            'is_variant' => 'boolean',
            'is_filterable' => 'boolean',
            'is_required' => 'boolean',
            'is_searchable' => 'boolean',
            'unit' => 'nullable|string|max:50',
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
        
        if (empty($data['slug']) || trim($data['slug']) === '') {
            unset($data['slug']);
        }

        $attribute->update($data);

        \App\Models\ActivityLog::log(
            'updated',
            'attributes',
            "Updated attribute: {$attribute->name}",
            ['attribute_id' => $attribute->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute updated successfully',
            'data' => $attribute->load('group')->loadCount('values')
        ]);
    }

    public function destroy($id)
    {
        $attribute = Attribute::with('values')->find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        \App\Models\ActivityLog::log(
            'deleted',
            'attributes',
            "Deleted attribute: {$attribute->name}",
            ['attribute_id' => $attribute->id]
        );

        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        $attribute->status = !$attribute->status;
        $attribute->save();

        \App\Models\ActivityLog::log(
            'status_changed',
            'attributes',
            "Changed status of {$attribute->name} to " . ($attribute->status ? 'Active' : 'Inactive'),
            ['attribute_id' => $attribute->id, 'status' => $attribute->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $attribute->load('group')->loadCount('values')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:attributes,id'
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
            $attribute = Attribute::find($id);
            if (!$attribute) continue;

            switch ($action) {
                case 'activate':
                    $attribute->status = true;
                    $attribute->save();
                    $count++;
                    break;
                case 'deactivate':
                    $attribute->status = false;
                    $attribute->save();
                    $count++;
                    break;
                case 'delete':
                    $attribute->delete();
                    $count++;
                    break;
            }
        }

        \App\Models\ActivityLog::log(
            'bulk_action',
            'attributes',
            "Bulk {$action} performed on {$count} attributes",
            ['action' => $action, 'count' => $count, 'ids' => $ids]
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully. {$count} attributes affected."
        ]);
    }
}
