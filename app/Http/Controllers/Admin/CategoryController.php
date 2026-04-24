<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('sort_order')->get();
        return view('admin.pages.categories.index', compact('categories'));
    }

    public function list(Request $request)
    {
        $query = Category::with('parent');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by parent category
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate($perPage);

        // Add dynamic products_count to each category
        $categories->getCollection()->transform(function ($category) {
            $category->products_count = $category->products_count ?? 0;
            return $category;
        });

        return response()->json([
            'success' => true,
            'data' => $categories->items(),
            'pagination' => [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image_url' => 'nullable|string',
            'image_id' => 'nullable|string',
            'banner_url' => 'nullable|string',
            'banner_id' => 'nullable|string',
            'icon_url' => 'nullable|string',
            'icon_id' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_menu' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Auto-generate slug if not provided or empty
        if (empty($data['slug']) || trim($data['slug']) === '') {
            unset($data['slug']);
        }

        $category = Category::create($data);

        // Log activity
        \App\Models\ActivityLog::log(
            'created',
            'categories',
            "Created category: {$category->name}",
            ['category_id' => $category->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category->load('parent')
        ]);
    }

    public function show($id)
    {
        $category = Category::with('parent')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image_url' => 'nullable|string',
            'image_id' => 'nullable|string',
            'banner_url' => 'nullable|string',
            'banner_id' => 'nullable|string',
            'icon_url' => 'nullable|string',
            'icon_id' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_menu' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Remove empty slug to let model regenerate it if name changed
        if (empty($data['slug']) || trim($data['slug']) === '') {
            unset($data['slug']);
        }

        $category->update($data);

        // Log activity
        \App\Models\ActivityLog::log(
            'updated',
            'categories',
            "Updated category: {$category->name}",
            ['category_id' => $category->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category->load('parent')
        ]);
    }

    public function destroy($id)
    {
        $category = Category::with('children')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with subcategories. Please delete or move subcategories first.'
            ], 403);
        }

        // Check if category has products (when Product model exists)
        // Uncomment when Product model is created:
        // if ($category->products()->count() > 0) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Cannot delete category with products. Please move products to another category first.'
        //     ], 403);
        // }

        // Log activity before deletion
        \App\Models\ActivityLog::log(
            'deleted',
            'categories',
            "Deleted category: {$category->name}",
            ['category_id' => $category->id]
        );

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->is_active = !$category->is_active;
        $category->save();

        // Log activity
        \App\Models\ActivityLog::log(
            'status_changed',
            'categories',
            "Changed status of {$category->name} to " . ($category->is_active ? 'Active' : 'Inactive'),
            ['category_id' => $category->id, 'status' => $category->is_active]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $category->load('parent')
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:categories,id'
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
        $skipped = 0;

        foreach ($ids as $id) {
            $category = Category::find($id);
            if (!$category) continue;

            switch ($action) {
                case 'activate':
                    $category->is_active = true;
                    $category->save();
                    $count++;
                    break;
                case 'deactivate':
                    $category->is_active = false;
                    $category->save();
                    $count++;
                    break;
                case 'feature':
                    $category->is_featured = true;
                    $category->save();
                    $count++;
                    break;
                case 'unfeature':
                    $category->is_featured = false;
                    $category->save();
                    $count++;
                    break;
                case 'delete':
                    // Check if has children
                    if ($category->children()->count() > 0) {
                        $skipped++;
                        continue 2;
                    }
                    // Check if has products (when Product model exists)
                    // Uncomment when Product model is created:
                    // if ($category->products()->count() > 0) {
                    //     $skipped++;
                    //     continue 2;
                    // }
                    $category->delete();
                    $count++;
                    break;
            }
        }

        // Log activity
        \App\Models\ActivityLog::log(
            'bulk_action',
            'categories',
            "Bulk {$action} performed on {$count} categories" . ($skipped > 0 ? ", {$skipped} skipped" : ""),
            ['action' => $action, 'count' => $count, 'skipped' => $skipped, 'ids' => $ids]
        );

        $message = "Bulk action completed successfully. {$count} categories affected.";
        if ($skipped > 0) {
            $message .= " {$skipped} categories skipped (have subcategories or products).";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}

