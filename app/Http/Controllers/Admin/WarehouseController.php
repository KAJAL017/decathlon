<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('admin.pages.warehouses.index');
    }

    public function list(Request $request)
    {
        $q = Warehouse::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($query) use ($s) {
                $query->where('name', 'like', "%$s%")
                      ->orWhere('code', 'like', "%$s%")
                      ->orWhere('city', 'like', "%$s%")
                      ->orWhere('contact_name', 'like', "%$s%");
            });
        }

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $q->where('is_active', (bool)(int)$request->status);
        }

        if ($request->filled('city')) {
            $q->where('city', 'like', '%' . $request->city . '%');
        }

        $perPage = (int)($request->per_page ?? 15);
        $result  = $q->orderByDesc('is_default')->orderBy('name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $result->items(),
            'pagination' => [
                'total'       => $result->total(),
                'per_page'    => $result->perPage(),
                'current_page'=> $result->currentPage(),
                'last_page'   => $result->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'required|string|max:50|unique:warehouses,code',
            'type'            => 'required|in:main,regional,dropship,virtual',
            'contact_name'    => 'nullable|string|max:255',
            'contact_email'   => 'nullable|email|max:255',
            'contact_phone'   => 'nullable|string|max:30',
            'address_line1'   => 'nullable|string|max:255',
            'address_line2'   => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'pincode'         => 'nullable|string|max:20',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'is_active'       => 'boolean',
            'is_default'      => 'boolean',
            'accepts_returns' => 'boolean',
            'processing_days' => 'integer|min:0|max:30',
            'notes'           => 'nullable|string',
        ]);

        // Only one default warehouse
        if (!empty($validated['is_default'])) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        }

        $validated['created_by'] = session('admin_id');
        $warehouse = Warehouse::create($validated);

        ActivityLog::log('created', 'warehouses', $warehouse->id, null, $warehouse->toArray());

        return response()->json(['success' => true, 'message' => 'Warehouse created successfully', 'data' => $warehouse]);
    }

    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return response()->json(['success' => true, 'data' => $warehouse]);
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'required|string|max:50|unique:warehouses,code,' . $id,
            'type'            => 'required|in:main,regional,dropship,virtual',
            'contact_name'    => 'nullable|string|max:255',
            'contact_email'   => 'nullable|email|max:255',
            'contact_phone'   => 'nullable|string|max:30',
            'address_line1'   => 'nullable|string|max:255',
            'address_line2'   => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'pincode'         => 'nullable|string|max:20',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'is_active'       => 'boolean',
            'is_default'      => 'boolean',
            'accepts_returns' => 'boolean',
            'processing_days' => 'integer|min:0|max:30',
            'notes'           => 'nullable|string',
        ]);

        if (!empty($validated['is_default'])) {
            Warehouse::where('is_default', true)->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $old = $warehouse->toArray();
        $warehouse->update($validated);

        ActivityLog::log('updated', 'warehouses', $warehouse->id, $old, $warehouse->fresh()->toArray());

        return response()->json(['success' => true, 'message' => 'Warehouse updated successfully', 'data' => $warehouse->fresh()]);
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        if ($warehouse->is_default) {
            return response()->json(['success' => false, 'message' => 'Cannot delete the default warehouse'], 422);
        }

        ActivityLog::log('deleted', 'warehouses', $warehouse->id, $warehouse->toArray(), null);
        $warehouse->delete();

        return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update(['is_active' => !$warehouse->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data'    => ['is_active' => $warehouse->is_active],
        ]);
    }

    public function setDefault($id)
    {
        Warehouse::where('is_default', true)->update(['is_default' => false]);
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update(['is_default' => true]);

        return response()->json(['success' => true, 'message' => "{$warehouse->name} set as default warehouse"]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate(['action' => 'required|string', 'ids' => 'required|array']);
        $ids    = $request->ids;
        $action = $request->action;

        switch ($action) {
            case 'activate':
                Warehouse::whereIn('id', $ids)->update(['is_active' => true]);
                break;
            case 'deactivate':
                Warehouse::whereIn('id', $ids)->update(['is_active' => false]);
                break;
            case 'delete':
                Warehouse::whereIn('id', $ids)->where('is_default', false)->delete();
                break;
        }

        return response()->json(['success' => true, 'message' => 'Bulk action applied']);
    }
}
