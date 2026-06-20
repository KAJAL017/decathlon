<?php

namespace App\Http\Controllers;

use App\Services\WishlistService;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $wishlist = $this->wishlistService->getWishlist();
        $isGuest = !Auth()->guard('customer')->check();
        return view('pages.wishlist', compact('wishlist', 'isGuest'));
    }

    public function guestProducts(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|max:50',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $products = Product::whereIn('id', $request->product_ids)
            ->where('status', 'active')
            ->with('brand', 'variants', 'images')
            ->get()
            ->map(function ($product) {
                $featuredImage = $product->featuredImage ?? $product->images->first();
                $imageUrl = $featuredImage?->image_url ?? asset('images/placeholder-product.svg');
                $variant = $product->variants->first();
                $price = $variant?->price ?? 0;
                $comparePrice = $variant?->compare_price;
                $discount = 0;
                if ($comparePrice && $comparePrice > $price) {
                    $discount = round((($comparePrice - $price) / $comparePrice) * 100);
                }
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'brand' => $product->brand?->name ?? 'DECATHLON',
                    'image_url' => $imageUrl,
                    'price' => $price,
                    'compare_price' => $comparePrice,
                    'discount' => $discount,
                    'is_new' => $product->is_new,
                    'average_rating' => $product->average_rating ?? 4.5,
                    'has_variants' => $product->variants->count() > 1,
                    'first_variant_id' => $variant?->id,
                    'stock_quantity' => $product->stock_quantity,
                    'manage_stock' => $product->manage_stock,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        try {
            $result = $this->wishlistService->toggle($request->product_id);
            $productIds = $this->wishlistService->getCustomerProductIds();

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'status' => $result['status'],
                    'count' => count($productIds),
                    'product_ids' => $productIds
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        try {
            $result = $this->wishlistService->add($request->product_id);
            $productIds = $this->wishlistService->getCustomerProductIds();

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'status' => $result['status'],
                    'count' => count($productIds),
                    'product_ids' => $productIds
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        try {
            $result = $this->wishlistService->remove($request->product_id);
            $productIds = $this->wishlistService->getCustomerProductIds();

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'status' => $result['status'],
                    'count' => count($productIds),
                    'product_ids' => $productIds
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function check(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array'
        ]);

        $customerIds = $this->wishlistService->getCustomerProductIds();

        return response()->json([
            'success' => true,
            'data' => [
                'product_ids' => $customerIds
            ]
        ]);
    }

    public function count()
    {
        $count = $this->wishlistService->getCount();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $count
            ]
        ]);
    }

    public function syncGuestWishlist(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|max:50',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        try {
            $added = 0;
            foreach ($request->product_ids as $productId) {
                $result = $this->wishlistService->add($productId);
                if ($result['status'] === 'added') {
                    $added++;
                }
            }

            $productIds = $this->wishlistService->getCustomerProductIds();

            return response()->json([
                'success' => true,
                'message' => $added . ' items synced to your wishlist.',
                'data' => [
                    'count' => count($productIds),
                    'product_ids' => $productIds,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
