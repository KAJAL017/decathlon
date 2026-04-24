<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeValueController extends Controller
{
    public function index()
    {
        return view('admin.pages.attribute-values.index');
    }

    public function getAttributes()
    {
        $attributes = Attribute::where('status', true)
            ->ordered()
            ->get(['id', 'name', 'slug', 'type']);

        return response()->json([
            'success' => true,
            'data' => $attributes
        ]);
    }

    public function list(Request $request)
    {
        $query = AttributeValue::with('attribute');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('value', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by attribute
        if ($request->filled('attribute_id')) {
            $query->where('attribute_id', $request->attribute_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $values = $query->ordered()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $values->items(),
            'pagination' => [
                'total' => $values->total(),
                'per_page' => $values->perPage(),
                'current_page' => $values->currentPage(),
                'last_page' => $values->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'color_code' => 'nullable|string|max:20',
            'image_url' => 'nullable|string',
            'image_id' => 'nullable|string',
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
            $maxSortOrder = AttributeValue::where('attribute_id', $data['attribute_id'])->max('sort_order');
            $data['sort_order'] = $maxSortOrder ? $maxSortOrder + 1 : 1;
        }
        
        if (empty($data['slug']) || trim($data['slug']) === '') {
            unset($data['slug']);
        }

        $attributeValue = AttributeValue::create($data);

        \App\Models\ActivityLog::log(
            'created',
            'attribute_values',
            "Created attribute value: {$attributeValue->value}",
            ['attribute_value_id' => $attributeValue->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute value created successfully',
            'data' => $attributeValue->load('attribute')
        ]);
    }

    public function show($id)
    {
        $value = AttributeValue::with('attribute')->find($id);

        if (!$value) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $value
        ]);
    }

    public function update(Request $request, $id)
    {
        $value = AttributeValue::find($id);

        if (!$value) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'color_code' => 'nullable|string|max:20',
            'image_url' => 'nullable|string',
            'image_id' => 'nullable|string',
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

        $value->update($data);

        \App\Models\ActivityLog::log(
            'updated',
            'attribute_values',
            "Updated attribute value: {$value->value}",
            ['attribute_value_id' => $value->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute value updated successfully',
            'data' => $value->load('attribute')
        ]);
    }

    public function destroy($id)
    {
        $value = AttributeValue::find($id);

        if (!$value) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        \App\Models\ActivityLog::log(
            'deleted',
            'attribute_values',
            "Deleted attribute value: {$value->value}",
            ['attribute_value_id' => $value->id]
        );

        $value->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute value deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $value = AttributeValue::find($id);

        if (!$value) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute value not found'
            ], 404);
        }

        $value->status = !$value->status;
        $value->save();

        \App\Models\ActivityLog::log(
            'status_changed',
            'attribute_values',
            "Changed status of {$value->value} to " . ($value->status ? 'Active' : 'Inactive'),
            ['attribute_value_id' => $value->id, 'status' => $value->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $value->load('attribute')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:attribute_values,id'
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
            $value = AttributeValue::find($id);
            if (!$value) continue;

            switch ($action) {
                case 'activate':
                    $value->status = true;
                    $value->save();
                    $count++;
                    break;
                case 'deactivate':
                    $value->status = false;
                    $value->save();
                    $count++;
                    break;
                case 'delete':
                    $value->delete();
                    $count++;
                    break;
            }
        }

        \App\Models\ActivityLog::log(
            'bulk_action',
            'attribute_values',
            "Bulk {$action} performed on {$count} attribute values",
            ['action' => $action, 'count' => $count, 'ids' => $ids]
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully. {$count} attribute values affected."
        ]);
    }
}
