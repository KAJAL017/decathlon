<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeGroupController extends Controller
{
    public function index()
    {
        return view('admin.pages.attribute-groups.index');
    }

    public function getAttributes()
    {
        $attributes = \App\Models\Attribute::where('status', true)
            ->ordered()
            ->get(['id', 'name', 'slug', 'type', 'attribute_group_id']);

        return response()->json([
            'success' => true,
            'data' => $attributes
        ]);
    }

    public function list(Request $request)
    {
        $query = AttributeGroup::withCount('attributes');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $groups = $query->ordered()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $groups->items(),
            'pagination' => [
                'total' => $groups->total(),
                'per_page' => $groups->perPage(),
                'current_page' => $groups->currentPage(),
                'last_page' => $groups->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:attribute_groups,name',
            'slug' => 'nullable|string|unique:attribute_groups,slug',
            'description' => 'nullable|string|max:500',
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

        $group = AttributeGroup::create($data);

        \App\Models\ActivityLog::log(
            'created',
            'attribute_groups',
            "Created attribute group: {$group->name}",
            ['group_id' => $group->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute group created successfully',
            'data' => $group->loadCount('attributes')
        ]);
    }

    public function show($id)
    {
        $group = AttributeGroup::withCount('attributes')->find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute group not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $group
        ]);
    }

    public function update(Request $request, $id)
    {
        $group = AttributeGroup::find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute group not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:attribute_groups,name,' . $id,
            'slug' => 'nullable|string|unique:attribute_groups,slug,' . $id,
            'description' => 'nullable|string|max:500',
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

        $group->update($data);

        \App\Models\ActivityLog::log(
            'updated',
            'attribute_groups',
            "Updated attribute group: {$group->name}",
            ['group_id' => $group->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute group updated successfully',
            'data' => $group->loadCount('attributes')
        ]);
    }

    public function destroy($id)
    {
        $group = AttributeGroup::with('attributes')->find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute group not found'
            ], 404);
        }

        // Check if group has attributes
        if ($group->attributes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete group with existing attributes. Please reassign or delete attributes first.'
            ], 422);
        }

        \App\Models\ActivityLog::log(
            'deleted',
            'attribute_groups',
            "Deleted attribute group: {$group->name}",
            ['group_id' => $group->id]
        );

        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute group deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $group = AttributeGroup::find($id);

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute group not found'
            ], 404);
        }

        $group->status = !$group->status;
        $group->save();

        \App\Models\ActivityLog::log(
            'status_changed',
            'attribute_groups',
            "Changed status of {$group->name} to " . ($group->status ? 'Active' : 'Inactive'),
            ['group_id' => $group->id, 'status' => $group->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $group->loadCount('attributes')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:attribute_groups,id'
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
            $group = AttributeGroup::find($id);
            if (!$group) continue;

            switch ($action) {
                case 'activate':
                    $group->status = true;
                    $group->save();
                    $count++;
                    break;
                case 'deactivate':
                    $group->status = false;
                    $group->save();
                    $count++;
                    break;
                case 'delete':
                    if ($group->attributes()->count() === 0) {
                        $group->delete();
                        $count++;
                    }
                    break;
            }
        }

        \App\Models\ActivityLog::log(
            'bulk_action',
            'attribute_groups',
            "Bulk {$action} performed on {$count} attribute groups",
            ['action' => $action, 'count' => $count, 'ids' => $ids]
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully. {$count} groups affected."
        ]);
    }
}
