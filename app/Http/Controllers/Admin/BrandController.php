<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin.pages.brands.index');
    }

    public function list(Request $request)
    {
        $query = Brand::withCount('products');

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
        $brands = $query->ordered()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $brands->items(),
            'pagination' => [
                'total' => $brands->total(),
                'per_page' => $brands->perPage(),
                'current_page' => $brands->currentPage(),
                'last_page' => $brands->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'nullable|string|unique:brands,slug',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'logo_id' => 'nullable|string',
            'website' => 'nullable|url',
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

        $brand = Brand::create($data);

        \App\Models\ActivityLog::log(
            'created',
            'brands',
            "Created brand: {$brand->name}",
            ['brand_id' => $brand->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully',
            'data' => $brand->loadCount('products')
        ]);
    }

    public function show($id)
    {
        $brand = Brand::withCount('products')->find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $brand
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'slug' => 'nullable|string|unique:brands,slug,' . $id,
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'logo_id' => 'nullable|string',
            'website' => 'nullable|url',
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

        $brand->update($data);

        \App\Models\ActivityLog::log(
            'updated',
            'brands',
            "Updated brand: {$brand->name}",
            ['brand_id' => $brand->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully',
            'data' => $brand->loadCount('products')
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::with('products')->find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        // Check if brand has products
        if ($brand->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete brand with existing products. Please reassign or delete products first.'
            ], 422);
        }

        \App\Models\ActivityLog::log(
            'deleted',
            'brands',
            "Deleted brand: {$brand->name}",
            ['brand_id' => $brand->id]
        );

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        $brand->status = !$brand->status;
        $brand->save();

        \App\Models\ActivityLog::log(
            'status_changed',
            'brands',
            "Changed status of {$brand->name} to " . ($brand->status ? 'Active' : 'Inactive'),
            ['brand_id' => $brand->id, 'status' => $brand->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $brand->loadCount('products')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:brands,id'
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
            $brand = Brand::find($id);
            if (!$brand) continue;

            switch ($action) {
                case 'activate':
                    $brand->status = true;
                    $brand->save();
                    $count++;
                    break;
                case 'deactivate':
                    $brand->status = false;
                    $brand->save();
                    $count++;
                    break;
                case 'delete':
                    if ($brand->products()->count() === 0) {
                        $brand->delete();
                        $count++;
                    }
                    break;
            }
        }

        \App\Models\ActivityLog::log(
            'bulk_action',
            'brands',
            "Bulk {$action} performed on {$count} brands",
            ['action' => $action, 'count' => $count, 'ids' => $ids]
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully. {$count} brands affected."
        ]);
    }
}
