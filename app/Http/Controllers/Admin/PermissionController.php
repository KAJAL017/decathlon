<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function index()
    {
        $modules = Permission::select('module')->distinct()->pluck('module');
        return view('admin.pages.permissions.index', compact('modules'));
    }

    public function list(Request $request)
    {
        $query = Permission::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }

        if ($request->has('module') && $request->module) {
            $query->where('module', $request->module);
        }

        $perPage = $request->get('per_page', 10);
        $permissions = $query->orderBy('module')->orderBy('display_name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $permissions->items(),
            'pagination' => [
                'total' => $permissions->total(),
                'per_page' => $permissions->perPage(),
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name|max:255',
            'display_name' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $permission = Permission::create([
            'name' => strtolower(str_replace(' ', '.', $request->name)),
            'display_name' => $request->display_name,
            'module' => $request->module,
            'description' => $request->description,
        ]);

        // Log activity
        \App\Models\ActivityLog::log(
            'created',
            'permissions',
            "Created new permission: {$permission->display_name}",
            ['permission_id' => $permission->id, 'module' => $permission->module]
        );

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => $permission
        ]);
    }

    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'display_name' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $permission->name = strtolower(str_replace(' ', '.', $request->name));
        $permission->display_name = $request->display_name;
        $permission->module = $request->module;
        $permission->description = $request->description;
        $permission->save();

        // Log activity
        \App\Models\ActivityLog::log(
            'updated',
            'permissions',
            "Updated permission: {$permission->display_name}",
            ['permission_id' => $permission->id, 'module' => $permission->module]
        );

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission
        ]);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete permission assigned to roles'
            ], 403);
        }

        // Log activity before deletion
        \App\Models\ActivityLog::log(
            'deleted',
            'permissions',
            "Deleted permission: {$permission->display_name}",
            ['permission_id' => $permission->id, 'module' => $permission->module]
        );

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }
}
