<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.pages.products.index');
    }

    public function list(Request $request)
    {
        $query = Product::with(['brand', 'category', 'featuredImage'])
            ->withCount('variants');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('sku_prefix', 'like', "%{$search}%");
            });
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $products = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'sku_prefix' => 'nullable|string|max:50',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'product_type' => 'required|in:simple,variable,digital,service',
            'status' => 'required|in:draft,active,inactive',
            'availability_status' => 'nullable|in:in_stock,out_of_stock,pre_order,backorder',
            'available_date' => 'nullable|date|after:today',
            'published_at' => 'nullable|date',
            'unpublished_at' => 'nullable|date|after:published_at',
            'visibility' => 'nullable|in:visible,hidden,catalog_only,search_only',
            'is_digital' => 'boolean',
            'download_url' => 'nullable|string|max:500',
            'download_limit' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_best_seller' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'videos' => 'nullable|array',
            'videos.*.title' => 'nullable|string|max:255',
            'videos.*.provider' => 'required_with:videos|in:youtube,vimeo,direct',
            'videos.*.video_url' => 'required_with:videos|string|max:500',
            'videos.*.video_id' => 'nullable|string|max:100',
            'videos.*.thumbnail_url' => 'nullable|string|max:500',
            'videos.*.is_featured' => 'nullable|boolean',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            
            if (empty($data['slug']) || trim($data['slug']) === '') {
                unset($data['slug']);
            }

            // Remove videos and faqs from main data
            $videos = $data['videos'] ?? [];
            $faqs = $data['faqs'] ?? [];
            unset($data['videos'], $data['faqs']);

            $product = Product::create($data);

            // Handle categories (multiple)
            if ($request->has('category_ids') && is_array($request->category_ids)) {
                $product->categories()->sync($request->category_ids);
            }

            // Handle tags
            if ($request->has('tags') && is_array($request->tags)) {
                $product->syncTags($request->tags);
            }

            // Handle videos
            if (!empty($videos)) {
                foreach ($videos as $index => $videoData) {
                    $product->videos()->create([
                        'title' => $videoData['title'] ?? null,
                        'provider' => $videoData['provider'],
                        'video_url' => $videoData['video_url'],
                        'video_id' => $videoData['video_id'] ?? null,
                        'thumbnail_url' => $videoData['thumbnail_url'] ?? null,
                        'is_featured' => $videoData['is_featured'] ?? false,
                        'sort_order' => $index,
                        'status' => true,
                    ]);
                }
            }

            // Handle FAQs
            if (!empty($faqs)) {
                foreach ($faqs as $index => $faqData) {
                    $product->faqs()->create([
                        'question' => $faqData['question'],
                        'answer' => $faqData['answer'],
                        'sort_order' => $index,
                        'status' => true,
                    ]);
                }
            }

            \App\Models\ActivityLog::log(
                'created',
                'products',
                "Created product: {$product->name}",
                ['product_id' => $product->id]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load(['brand', 'category', 'categories', 'videos', 'faqs'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $product = Product::with([
            'brand',
            'category',
            'categories',
            'variants.variantAttributes.attribute',
            'variants.variantAttributes.attributeValue',
            'images',
            'attributeValues.attribute',
            'attributeValues.attributeValue',
            'tags',
            'videos' => function($query) {
                $query->where('status', true)->orderBy('sort_order');
            },
            'faqs' => function($query) {
                $query->where('status', true)->orderBy('sort_order');
            }
        ])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $id,
            'sku_prefix' => 'nullable|string|max:50',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'product_type' => 'required|in:simple,variable,digital,service',
            'status' => 'required|in:draft,active,inactive',
            'availability_status' => 'nullable|in:in_stock,out_of_stock,pre_order,backorder',
            'available_date' => 'nullable|date|after:today',
            'published_at' => 'nullable|date',
            'unpublished_at' => 'nullable|date|after:published_at',
            'visibility' => 'nullable|in:visible,hidden,catalog_only,search_only',
            'is_digital' => 'boolean',
            'download_url' => 'nullable|string|max:500',
            'download_limit' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_best_seller' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'videos' => 'nullable|array',
            'videos.*.title' => 'nullable|string|max:255',
            'videos.*.provider' => 'required_with:videos|in:youtube,vimeo,direct',
            'videos.*.video_url' => 'required_with:videos|string|max:500',
            'videos.*.video_id' => 'nullable|string|max:100',
            'videos.*.thumbnail_url' => 'nullable|string|max:500',
            'videos.*.is_featured' => 'nullable|boolean',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            if (empty($data['slug']) || trim($data['slug']) === '') {
                unset($data['slug']);
            }

            // Remove videos and faqs from main data
            $videos = $data['videos'] ?? [];
            $faqs = $data['faqs'] ?? [];
            unset($data['videos'], $data['faqs']);

            $product->update($data);

            // Handle categories (multiple)
            if ($request->has('category_ids') && is_array($request->category_ids)) {
                $product->categories()->sync($request->category_ids);
            }

            // Handle tags
            if ($request->has('tags') && is_array($request->tags)) {
                $product->syncTags($request->tags);
            }

            // Handle videos - delete old and create new
            $product->videos()->delete();
            if (!empty($videos)) {
                foreach ($videos as $index => $videoData) {
                    $product->videos()->create([
                        'title' => $videoData['title'] ?? null,
                        'provider' => $videoData['provider'],
                        'video_url' => $videoData['video_url'],
                        'video_id' => $videoData['video_id'] ?? null,
                        'thumbnail_url' => $videoData['thumbnail_url'] ?? null,
                        'is_featured' => $videoData['is_featured'] ?? false,
                        'sort_order' => $index,
                        'status' => true,
                    ]);
                }
            }

            // Handle FAQs - delete old and create new
            $product->faqs()->delete();
            if (!empty($faqs)) {
                foreach ($faqs as $index => $faqData) {
                    $product->faqs()->create([
                        'question' => $faqData['question'],
                        'answer' => $faqData['answer'],
                        'sort_order' => $index,
                        'status' => true,
                    ]);
                }
            }

            \App\Models\ActivityLog::log(
                'updated',
                'products',
                "Updated product: {$product->name}",
                ['product_id' => $product->id]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load(['brand', 'category', 'categories', 'videos', 'faqs'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::with(['variants', 'images'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        \App\Models\ActivityLog::log(
            'deleted',
            'products',
            "Deleted product: {$product->name}",
            ['product_id' => $product->id]
        );

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Toggle between active and inactive
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();

        \App\Models\ActivityLog::log(
            'status_changed',
            'products',
            "Changed status of {$product->name} to {$product->status}",
            ['product_id' => $product->id, 'status' => $product->status]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $product
        ]);
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:products,id'
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
            $product = Product::find($id);
            if (!$product) continue;

            switch ($action) {
                case 'activate':
                    $product->status = 'active';
                    $product->save();
                    $count++;
                    break;
                case 'deactivate':
                    $product->status = 'inactive';
                    $product->save();
                    $count++;
                    break;
                case 'feature':
                    $product->is_featured = true;
                    $product->save();
                    $count++;
                    break;
                case 'unfeature':
                    $product->is_featured = false;
                    $product->save();
                    $count++;
                    break;
                case 'delete':
                    $product->delete();
                    $count++;
                    break;
            }
        }

        \App\Models\ActivityLog::log(
            'bulk_action',
            'products',
            "Bulk {$action} performed on {$count} products",
            ['action' => $action, 'count' => $count, 'ids' => $ids]
        );

        return response()->json([
            'success' => true,
            'message' => "Bulk action completed successfully. {$count} products affected."
        ]);
    }

    // Get variant attributes for product
    public function getVariantAttributes()
    {
        $attributes = Attribute::where('is_variant', true)
            ->where('status', true)
            ->with(['values' => function($query) {
                $query->where('status', true)->orderBy('sort_order');
            }])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attributes
        ]);
    }

    // Related Products Management
    public function getRelatedProducts($id, $type)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $relatedProducts = $product->relatedProducts()
            ->wherePivot('type', $type)
            ->with(['brand', 'featuredImage'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $relatedProducts
        ]);
    }

    public function syncRelatedProducts(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:related,upsell,cross_sell',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $type = $request->type;
            $productIds = $request->product_ids;

            // Remove existing relations of this type
            $product->relatedProducts()->wherePivot('type', $type)->detach();

            // Add new relations with sort order
            $syncData = [];
            foreach ($productIds as $index => $relatedId) {
                $syncData[$relatedId] = [
                    'type' => $type,
                    'sort_order' => $index
                ];
            }

            $product->relatedProducts()->attach($syncData);

            \App\Models\ActivityLog::log(
                'updated',
                'products',
                "Updated {$type} products for: {$product->name}",
                ['product_id' => $product->id, 'type' => $type, 'count' => count($productIds)]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Related products updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update related products: ' . $e->getMessage()
            ], 500);
        }
    }

    // Product Duplication
    public function duplicate($id)
    {
        $product = Product::with([
            'variants',
            'images',
            'videos',
            'faqs',
            'attributeValues',
            'tags',
            'categories'
        ])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Create new product
            $newProduct = $product->replicate();
            $newProduct->name = $product->name . ' (Copy)';
            $newProduct->slug = null; // Will auto-generate
            $newProduct->status = 'draft';
            $newProduct->created_by = auth()->id();
            $newProduct->save();

            // Duplicate images
            foreach ($product->images as $image) {
                $newImage = $image->replicate();
                $newImage->product_id = $newProduct->id;
                $newImage->save();
            }

            // Duplicate videos
            foreach ($product->videos as $video) {
                $newVideo = $video->replicate();
                $newVideo->product_id = $newProduct->id;
                $newVideo->save();
            }

            // Duplicate FAQs
            foreach ($product->faqs as $faq) {
                $newFaq = $faq->replicate();
                $newFaq->product_id = $newProduct->id;
                $newFaq->save();
            }

            // Duplicate variants
            foreach ($product->variants as $variant) {
                $newVariant = $variant->replicate();
                $newVariant->product_id = $newProduct->id;
                $newVariant->sku = $variant->sku . '-COPY';
                $newVariant->save();

                // Duplicate variant attributes
                foreach ($variant->variantAttributes as $variantAttr) {
                    $newVariantAttr = $variantAttr->replicate();
                    $newVariantAttr->variant_id = $newVariant->id;
                    $newVariantAttr->save();
                }
            }

            // Duplicate product attributes
            foreach ($product->attributeValues as $attrValue) {
                $newAttrValue = $attrValue->replicate();
                $newAttrValue->product_id = $newProduct->id;
                $newAttrValue->save();
            }

            // Sync tags
            $tagIds = $product->tags->pluck('id')->toArray();
            $newProduct->tags()->sync($tagIds);

            // Sync categories
            $categoryIds = $product->categories->pluck('id')->toArray();
            $newProduct->categories()->sync($categoryIds);

            \App\Models\ActivityLog::log(
                'duplicated',
                'products',
                "Duplicated product: {$product->name} to {$newProduct->name}",
                ['original_id' => $product->id, 'new_id' => $newProduct->id]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product duplicated successfully',
                'data' => $newProduct->load(['brand', 'category', 'featuredImage'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Product Import/Export
    public function exportProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:csv,excel',
            'status' => 'nullable|in:active,inactive,draft',
            'product_type' => 'nullable|in:simple,variable,digital,service',
            'brand_ids' => 'nullable|array',
            'brand_ids.*' => 'exists:brands,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'fields' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $service = new \App\Services\ProductImportExportService();
            $job = $service->exportProducts(
                session('current_store_id'), // For multi-store support
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Export started successfully',
                'data' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'format' => 'required|in:csv,excel',
            'update_existing' => 'boolean',
            'update_by_sku' => 'boolean',
            'field_mapping' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store uploaded file
            $file = $request->file('file');
            $fileName = 'import_' . time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('imports', $fileName);

            $service = new \App\Services\ProductImportExportService();
            $job = $service->importProducts(
                $filePath,
                session('current_store_id'), // For multi-store support
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Import started successfully',
                'data' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getImportTemplate()
    {
        try {
            $service = new \App\Services\ProductImportExportService();
            $template = $service->getExportTemplate();

            return response($template, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="product_import_template.csv"'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate template: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getImportExportJobs(Request $request)
    {
        $query = \App\Models\ProductImportExportJob::with('user')
            ->where('store_id', session('current_store_id'))
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $jobs->items(),
            'pagination' => [
                'total' => $jobs->total(),
                'per_page' => $jobs->perPage(),
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
            ]
        ]);
    }

    public function downloadExportFile($jobId)
    {
        $job = \App\Models\ProductImportExportJob::where('id', $jobId)
            ->where('store_id', session('current_store_id'))
            ->where('type', 'export')
            ->where('status', 'completed')
            ->first();

        if (!$job || !$job->file_path) {
            return response()->json([
                'success' => false,
                'message' => 'Export file not found'
            ], 404);
        }

        return Storage::download($job->file_path, $job->file_name);
    }
}
