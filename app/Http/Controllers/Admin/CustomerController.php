<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.pages.customers.index');
    }

    public function list(Request $request)
    {
        // Customers = users with role_id = null OR role_id pointing to 'customer' role
        // For now, treat all non-admin users as customers
        $q = User::query()->whereDoesntHave('role', function ($r) {
            $r->whereIn('name', ['admin', 'super_admin', 'manager', 'staff']);
        })->orWhereNull('role_id');

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($query) use ($s) {
                $query->where('name', 'like', "%$s%")
                      ->orWhere('email', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $q->where('is_active', (bool)(int)$request->status);
        }

        $perPage = (int)($request->per_page ?? 15);
        $result  = $q->orderByDesc('created_at')->paginate($perPage);

        $items = $result->map(function ($u) {
            return [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'is_active'  => $u->is_active,
                'last_login' => $u->last_login?->diffForHumans(),
                'created_at' => $u->created_at?->format('d M Y'),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $items,
            'pagination' => [
                'total'        => $result->total(),
                'per_page'     => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'is_active'=> 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id']  = null;

        $user = User::create($validated);
        ActivityLog::log('created', 'customers', $user->id, null, ['name' => $user->name, 'email' => $user->email]);

        return response()->json(['success' => true, 'message' => 'Customer created successfully', 'data' => $user]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $id,
            'password'  => 'nullable|string|min:8',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $old = $user->toArray();
        $user->update($validated);
        ActivityLog::log('updated', 'customers', $user->id, $old, $user->fresh()->toArray());

        return response()->json(['success' => true, 'message' => 'Customer updated successfully', 'data' => $user->fresh()]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        ActivityLog::log('deleted', 'customers', $user->id, ['name' => $user->name, 'email' => $user->email], null);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Customer deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data'    => ['is_active' => $user->is_active],
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate(['action' => 'required|string', 'ids' => 'required|array']);
        $ids    = $request->ids;
        $action = $request->action;

        switch ($action) {
            case 'activate':
                User::whereIn('id', $ids)->update(['is_active' => true]);
                break;
            case 'deactivate':
                User::whereIn('id', $ids)->update(['is_active' => false]);
                break;
            case 'delete':
                User::whereIn('id', $ids)->delete();
                break;
        }

        return response()->json(['success' => true, 'message' => 'Bulk action applied']);
    }
}
