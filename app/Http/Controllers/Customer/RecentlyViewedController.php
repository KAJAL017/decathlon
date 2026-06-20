<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\RecentlyViewedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecentlyViewedController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $recentlyViewed = RecentlyViewedProduct::where('customer_id', $customer->id)
            ->with([
                'product.featuredImage',
                'product.brand',
                'product.variants',
                'product.images',
            ])
            ->latest('viewed_at')
            ->get()
            ->pluck('product')
            ->unique('id')
            ->values();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'recentlyViewed' => $recentlyViewed]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.recently-viewed' : 'customer.desktop.recently-viewed', compact('recentlyViewed'));
    }

    public function track(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        RecentlyViewedProduct::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'product_id' => $request->product_id,
            ],
            ['viewed_at' => now()]
        );

        return response()->json(['success' => true]);
    }
}
