<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;

class HomeController extends Controller
{
    protected $homeService;
    protected $searchService;
    protected $productService;

    public function __construct(
        \App\Services\HomeService $homeService,
        \App\Services\SearchService $searchService,
        \App\Services\ProductService $productService
    ) {
        $this->homeService = $homeService;
        $this->searchService = $searchService;
        $this->productService = $productService;
    }

    /**
     * Display the home page
     */
    public function index()
    {
        $sections = $this->homeService->getHomepageData();
        return view('pages.home', compact('sections'));
    }

    /**
     * Display the dynamic shop/category page
     */
    public function shop(Request $request)
    {
        $products = $this->productService->getShopProducts($request);
        return view('pages.shop', compact('products'));
    }

    /**
     * Display the dynamic shop by category page
     */
    public function categories(Request $request)
    {
        return view('pages.categories');
    }

    /**
     * Display the dynamic cart page with recommendations
     */
    public function cart(Request $request)
    {
        $boughtTogether = Product::active()->inRandomOrder()->limit(10)->with(['brand', 'featuredImage', 'variants'])->get();
        $under99 = Product::active()->whereHas('variants', function($q) {
            $q->where('price', '<=', 99);
        })->limit(10)->with(['brand', 'featuredImage', 'variants'])->get();
        $mightLike = Product::active()->featured()->limit(10)->with(['brand', 'featuredImage', 'variants'])->get();

        return view('pages.cart', compact('boughtTogether', 'under99', 'mightLike'));
    }

    /**
     * Display the dynamic product details page
     */
    public function product($slug)
    {
        $product = $this->productService->getProductBySlug($slug);

        if (!$product) {
            abort(404);
        }

        // Get related products from the same category
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['brand', 'featuredImage', 'variants'])
            ->limit(10)
            ->get();

        // Frequently bought together
        $boughtTogether = $product->crossSellProducts()->active()
            ->with(['brand', 'featuredImage', 'variants'])
            ->limit(10)
            ->get();
        
        if ($boughtTogether->isEmpty()) {
            $boughtTogether = Product::active()
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->with(['brand', 'featuredImage', 'variants'])
                ->limit(10)
                ->get();
        }

        return view('pages.product', compact('product', 'relatedProducts', 'boughtTogether'));
    }

    /**
     * Return product details for Quick View as JSON
     */
    public function quickView(Request $request, $slug)
    {
        $product = $this->searchService->getProductDetail($slug);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Return search suggestions (products + categories) as JSON
     */
    public function searchSuggestions(Request $request)
    {
        $q = $request->get('q', '');
        $results = $this->searchService->getSuggestions($q);

        return response()->json($results);
    }
}

