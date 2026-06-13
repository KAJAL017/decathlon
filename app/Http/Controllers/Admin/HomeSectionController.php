<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeSectionController extends Controller
{
    public function index()
    {
        $data = [
            'categories' => \App\Models\Category::active()->orderBy('name')->get(['id', 'name']),
            'brands' => \App\Models\Brand::active()->orderBy('name')->get(['id', 'name']),
            'banners' => \App\Models\Banner::active()->orderBy('sort_order')->get(['id', 'image_url']),
            'promotions' => \App\Models\Promotion::active()->orderBy('name')->get(['id', 'name']),
            // For products, we only get featured/recent ones for the list to keep it performant, 
            // but in a real large app we'd use a search-based selector.
            'products' => \App\Models\Product::active()->orderBy('name')->limit(100)->get(['id', 'name']),
        ];
        return view('admin.pages.home-sections.index', $data);
    }

    public function list(Request $request)
    {
        $query = HomeSection::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', (bool)(int)$request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 50);
        $sections = $query->orderBy('sort_order')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $sections->items(),
            'pagination' => [
                'total' => $sections->total(),
                'per_page' => $sections->perPage(),
                'current_page' => $sections->currentPage(),
                'last_page' => $sections->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $section = HomeSection::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully',
            'data' => $section
        ]);
    }

    public function show($id)
    {
        $section = HomeSection::find($id);

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $section
        ]);
    }

    public function update(Request $request, $id)
    {
        $section = HomeSection::find($id);

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $section->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully',
            'data' => $section
        ]);
    }

    public function destroy($id)
    {
        $section = HomeSection::find($id);

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ], 404);
        }

        $section->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully'
        ]);
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:home_sections,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->ids as $index => $id) {
            HomeSection::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reordered successfully'
        ]);
    }
}
