<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\ProductReview;
use App\Models\Promotion;
use App\Models\Coupon;
use App\Models\EmailCampaign;
use App\Models\StockMovement;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.pages.dashboard');
    }

    public function stats()
    {
        try {
            // ── Catalog ──────────────────────────────────────────
            $totalProducts    = Product::count();
            $activeProducts   = Product::where('status', 'active')->count();
            $totalCategories  = Category::count();
            $totalBrands      = Brand::count();
            $totalCollections = Collection::count();
            $totalTags        = \App\Models\ProductTag::count();
            $totalAttributes  = \App\Models\Attribute::count();
            $totalCustomers   = User::whereNull('role_id')->orWhereDoesntHave('role', fn($r) => $r->whereIn('name', ['admin','super_admin','manager','staff']))->count();

            // ── Reviews ──────────────────────────────────────────
            $totalReviews   = ProductReview::count();
            $pendingReviews = ProductReview::where('status', 'pending')->count();
            $avgRating      = round(ProductReview::where('status', 'approved')->avg('rating') ?? 0, 1);

            // ── Marketing ────────────────────────────────────────
            $now = now();
            $activePromotions = Promotion::where('is_active', true)
                ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
                ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
                ->count();

            $activeCoupons = Coupon::where('is_active', true)
                ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
                ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now))
                ->count();

            $totalCampaigns = EmailCampaign::count();
            $sentCampaigns  = EmailCampaign::where('status', 'sent')->count();

            // ── Inventory ────────────────────────────────────────
            $hasManageStock  = Schema::hasColumn('products', 'manage_stock');
            $hasStockQty     = Schema::hasColumn('products', 'stock_quantity');
            $hasLowThreshold = Schema::hasColumn('products', 'low_stock_threshold');

            $lowStockCount   = 0;
            $outOfStockCount = 0;
            $stockValue      = 0;

            if ($hasStockQty) {
                $stockValue = ProductVariant::whereNotNull('price')->where('price', '>', 0)
                    ->sum(DB::raw('COALESCE(stock_quantity, 0) * price'));

                if ($hasManageStock && $hasLowThreshold) {
                    $lowStockCount   = Product::where('manage_stock', true)->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0)->count();
                    $outOfStockCount = Product::where('manage_stock', true)->where('stock_quantity', '<=', 0)->count();
                } else {
                    $lowStockCount   = Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5)->count();
                    $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();
                }
            }

            $totalWarehouses  = Schema::hasTable('warehouses') ? Warehouse::count() : 0;
            $activeWarehouses = Schema::hasTable('warehouses') ? Warehouse::where('is_active', true)->count() : 0;

            // ── Users ────────────────────────────────────────────
            $totalAdmins  = User::count();
            $activeAdmins = User::where('is_active', true)->count();

            // ── Activity ─────────────────────────────────────────
            $todayLogs = ActivityLog::whereDate('created_at', today())->count();
            $recentActivity = ActivityLog::with('user')
                ->latest()
                ->limit(8)
                ->get()
                ->map(fn($l) => [
                    'id'          => $l->id,
                    'action'      => $l->action,
                    'module'      => $l->module,
                    'description' => $l->description,
                    'user'        => $l->user?->name ?? 'System',
                    'time'        => $l->created_at?->diffForHumans(),
                    'created_at'  => $l->created_at?->format('d M, H:i'),
                ]);

            // ── Products by category (top 6) ─────────────────────
            $productsByCategory = Category::select('id', 'name')
                ->selectRaw('(SELECT COUNT(*) FROM products WHERE products.category_id = categories.id AND products.deleted_at IS NULL) as cat_products_count')
                ->having('cat_products_count', '>', 0)
                ->orderByDesc('cat_products_count')
                ->limit(6)
                ->get()
                ->map(fn($c) => ['name' => $c->name, 'count' => $c->cat_products_count]);

            // ── Recent reviews ───────────────────────────────────
            $recentReviews = ProductReview::with('product:id,name')
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($r) => [
                    'reviewer_name' => $r->reviewer_name,
                    'product'       => $r->product?->name ?? '—',
                    'rating'        => $r->rating,
                    'status'        => $r->status,
                    'time'          => $r->created_at?->diffForHumans(),
                ]);

            // ── Stock movements (last 7 days) ────────────────────
            $stockMovements = [];
            if (Schema::hasTable('stock_movements')) {
                $stockMovements = StockMovement::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('count(*) as count')
                    )
                    ->where('created_at', '>=', now()->subDays(6))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(fn($r) => ['date' => $r->date, 'count' => $r->count]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    // Catalog
                    'total_products'    => $totalProducts,
                    'active_products'   => $activeProducts,
                    'total_categories'  => $totalCategories,
                    'total_brands'      => $totalBrands,
                    'total_collections' => $totalCollections,
                    'total_tags'        => $totalTags,
                    'total_attributes'  => $totalAttributes,
                    'total_customers'   => $totalCustomers,
                    // Reviews
                    'total_reviews'     => $totalReviews,
                    'pending_reviews'   => $pendingReviews,
                    'avg_rating'        => $avgRating,
                    // Marketing
                    'active_promotions' => $activePromotions,
                    'active_coupons'    => $activeCoupons,
                    'total_campaigns'   => $totalCampaigns,
                    'sent_campaigns'    => $sentCampaigns,
                    // Inventory
                    'low_stock_count'   => $lowStockCount,
                    'out_of_stock_count'=> $outOfStockCount,
                    'stock_value'       => round($stockValue, 2),
                    'total_warehouses'  => $totalWarehouses,
                    'active_warehouses' => $activeWarehouses,
                    // Users
                    'total_admins'      => $totalAdmins,
                    'active_admins'     => $activeAdmins,
                    // Activity
                    'today_logs'        => $todayLogs,
                    'recent_activity'   => $recentActivity,
                    // Charts
                    'products_by_category' => $productsByCategory,
                    'recent_reviews'       => $recentReviews,
                    'stock_movements'      => $stockMovements,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
