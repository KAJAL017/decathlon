<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.pages.customers.index');
    }

    public function list(Request $request)
    {
        $q = Customer::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($query) use ($s) {
                $query->where('first_name', 'like', "%$s%")
                      ->orWhere('last_name', 'like', "%$s%")
                      ->orWhere('email', 'like', "%$s%")
                      ->orWhere('phone', 'like', "%$s%");
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
                'phone'      => $u->phone,
                'is_active'  => $u->is_active,
                'last_login' => $u->last_login_at?->diffForHumans() ?? 'Never',
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
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:customers,email',
            'phone'      => 'nullable|string|max:20',
            'password'   => 'required|string|min:8',
            'is_active'  => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $customer = Customer::create($validated);
        ActivityLog::log('created', 'customers', $customer->id, null, ['name' => $customer->name, 'email' => $customer->email]);

        return response()->json(['success' => true, 'message' => 'Customer created successfully', 'data' => $customer]);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:customers,email,' . $id,
            'phone'      => 'nullable|string|max:20',
            'password'   => 'nullable|string|min:8',
            'is_active'  => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $old = $customer->toArray();
        $customer->update($validated);
        ActivityLog::log('updated', 'customers', $customer->id, $old, $customer->fresh()->toArray());

        return response()->json(['success' => true, 'message' => 'Customer updated successfully', 'data' => $customer->fresh()]);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        ActivityLog::log('deleted', 'customers', $customer->id, ['name' => $customer->name, 'email' => $customer->email], null);
        $customer->delete();

        return response()->json(['success' => true, 'message' => 'Customer deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['is_active' => !$customer->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data'    => ['is_active' => $customer->is_active],
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate(['action' => 'required|string', 'ids' => 'required|array']);
        $ids    = $request->ids;
        $action = $request->action;

        switch ($action) {
            case 'activate':
                Customer::whereIn('id', $ids)->update(['is_active' => true]);
                break;
            case 'deactivate':
                Customer::whereIn('id', $ids)->update(['is_active' => false]);
                break;
            case 'delete':
                Customer::whereIn('id', $ids)->delete();
                break;
        }

        return response()->json(['success' => true, 'message' => 'Bulk action applied']);
    }
}
