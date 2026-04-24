<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function index()
    {
        $roles = \App\Models\Role::all();
        return view('admin.pages.admin-users.index', compact('roles'));
    }

    public function list(Request $request)
    {
        $query = User::with('role');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role (accept both 'role' and 'role_id')
        $roleFilter = $request->role_id ?? $request->role;
        if ($roleFilter) {
            $query->where('role_id', $roleFilter);
        }

        // Filter by status - only apply if status has a valid value
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/profiles'), $imageName);
            $data['profile_image'] = 'uploads/profiles/' . $imageName;
        }

        $user = User::create($data);

        // Log activity
        \App\Models\ActivityLog::log(
            'created',
            'admin_users',
            "Created new admin user: {$user->name}",
            ['user_id' => $user->id, 'email' => $user->email]
        );

        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully',
            'data' => $user->load('role')
        ]);
    }

    public function show($id)
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->is_active = $request->is_active ?? $user->is_active;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }
            
            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/profiles'), $imageName);
            $user->profile_image = 'uploads/profiles/' . $imageName;
        }
        
        $user->save();

        // Log activity
        \App\Models\ActivityLog::log(
            'updated',
            'admin_users',
            "Updated admin user: {$user->name}",
            ['user_id' => $user->id, 'email' => $user->email]
        );

        return response()->json([
            'success' => true,
            'message' => 'Admin user updated successfully',
            'data' => $user->load('role')
        ]);
    }

    public function destroy($id)
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Protect Super Admin from deletion
        if ($user->role && strtolower($user->role->name) === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => '🛡️ Super Admin cannot be deleted (Protected Account)'
            ], 403);
        }

        // Prevent deleting yourself
        if ($user->email === session('admin_email')) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete yourself'
            ], 403);
        }

        // Log activity before deletion
        \App\Models\ActivityLog::log(
            'deleted',
            'admin_users',
            "Deleted admin user: {$user->name}",
            ['user_id' => $user->id, 'email' => $user->email]
        );

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin user deleted successfully'
        ]);
    }


    public function toggleStatus($id)
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Protect Super Admin from status change
        if ($user->role && strtolower($user->role->name) === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => '🛡️ Super Admin status cannot be changed (Protected Account)'
            ], 403);
        }

        // Prevent deactivating yourself
        if ($user->email === session('admin_email')) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account'
            ], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        // Log activity
        \App\Models\ActivityLog::log(
            'status_changed',
            'admin_users',
            "Changed status of {$user->name} to " . ($user->is_active ? 'Active' : 'Inactive'),
            ['user_id' => $user->id, 'status' => $user->is_active]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $user->load('role')
        ]);
    }

}
