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
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.pages.reports.index');
    }

    // ── Helper: date range from request ──────────────────────────
    private function dateRange(Request $request): array
    {
        $days = (int)($request->days ?? 30);
        $from = $request->date_from ? \Carbon\Carbon::parse($request->date_from)->startOfDay() : now()->subDays($days)->startOfDay();
        $to   = $request->date_to   ? \Carbon\Carbon::parse($request->date_to)->endOfDay()   : now()->endOfDay();
        return [$from, $to];
    }

    public function overview(Request $request)
    {
        try {
            [$from, $to] = $this->dateRange($request);

            $totalProducts    = Product::count();
            $totalCollections = Collection::count();
            $totalReviews     = ProductReview::count();
            $newReviews       = ProductReview::whereBetween('created_at', [$from, $to])->count();
            $avgRating        = round(ProductReview::where('status', 'approved')->avg('rating') ?? 0, 1);

            // Stock
            $stockValue = $lowStockCount = $outOfStockCount = 0;
            $hasManageStock  = Schema::hasColumn('products', 'manage_stock');
            $hasStockQty     = Schema::hasColumn('products', 'stock_quantity');
            $hasLowThreshold = Schema::hasColumn('products', 'low_stock_threshold');

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

            // Marketing
            $now = now();
            $activePromotions = Promotion::where('is_active', true)
                ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
                ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
                ->count();

            $activeCoupons = Coupon::where('is_active', true)
                ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
                ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now))
                ->count();

            // Top rated
            $topRated = Product::with('brand')
                ->where('reviews_count', '>', 0)
                ->orderByDesc('average_rating')->orderByDesc('reviews_count')
                ->limit(5)->get(['id', 'name', 'sku_prefix', 'average_rating', 'reviews_count', 'brand_id'])
                ->map(fn($p) => [
                    'id' => $p->id, 'name' => $p->name, 'sku_prefix' => $p->sku_prefix,
                    'brand' => $p->brand?->name ?? '—',
                    'avg_rating' => round($p->average_rating, 1), 'reviews_count' => $p->reviews_count,
                ]);

            // Recent reviews
            $recentReviews = ProductReview::with('product')->latest()->limit(5)
                ->get(['id', 'product_id', 'reviewer_name', 'rating', 'status', 'created_at'])
                ->map(fn($r) => [
                    'id' => $r->id, 'reviewer_name' => $r->reviewer_name,
                    'product' => $r->product?->name ?? '—', 'rating' => $r->rating,
                    'status' => $r->status, 'created_at' => $r->created_at?->diffForHumans(),
                ]);

            // Review trend (daily for period)
            $reviewTrend = ProductReview::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('date')->orderBy('date')->get()
                ->map(fn($r) => ['date' => $r->date, 'count' => $r->count]);

            return response()->json(['success' => true, 'data' => [
                'total_products'    => $totalProducts,
                'total_collections' => $totalCollections,
                'total_reviews'     => $totalReviews,
                'new_reviews'       => $newReviews,
                'avg_rating'        => $avgRating,
                'stock_value'       => round($stockValue, 2),
                'low_stock_count'   => $lowStockCount,
                'out_of_stock_count'=> $outOfStockCount,
                'active_promotions' => $activePromotions,
                'active_coupons'    => $activeCoupons,
                'top_rated'         => $topRated,
                'recent_reviews'    => $recentReviews,
                'review_trend'      => $reviewTrend,
                'period'            => ['from' => $from->format('d M Y'), 'to' => $to->format('d M Y')],
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function products(Request $request)
    {
        try {
            $byCategory = Category::select('id', 'name')
                ->selectRaw('(SELECT COUNT(*) FROM products WHERE products.category_id = categories.id AND products.deleted_at IS NULL) as cat_products_count')
                ->having('cat_products_count', '>', 0)
                ->orderByDesc('cat_products_count')->limit(10)->get()
                ->map(fn($c) => ['name' => $c->name, 'count' => $c->cat_products_count]);

            $byBrand = Brand::withCount('products')->having('products_count', '>', 0)
                ->orderByDesc('products_count')->limit(10)->get(['id', 'name'])
                ->map(fn($b) => ['name' => $b->name, 'count' => $b->products_count]);

            $byType = Product::select('product_type', DB::raw('count(*) as count'))
                ->groupBy('product_type')->orderByDesc('count')->get()
                ->map(fn($r) => ['type' => $r->product_type ?? 'unknown', 'count' => $r->count]);

            // Status breakdown
            $byStatus = Product::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')->get()->pluck('count', 'status');

            // Flags
            $flags = [
                'featured'    => Product::where('is_featured', true)->count(),
                'new'         => Product::where('is_new', true)->count(),
                'best_seller' => Product::where('is_best_seller', true)->count(),
            ];

            // Stock distribution
            $hasManageStock  = Schema::hasColumn('products', 'manage_stock');
            $hasStockQty     = Schema::hasColumn('products', 'stock_quantity');
            $hasLowThreshold = Schema::hasColumn('products', 'low_stock_threshold');
            $stockDistribution = ['in_stock' => 0, 'low_stock' => 0, 'out_of_stock' => 0];

            if ($hasStockQty && $hasManageStock && $hasLowThreshold) {
                $stockDistribution['out_of_stock'] = Product::where('manage_stock', true)->where('stock_quantity', '<=', 0)->count();
                $stockDistribution['low_stock']    = Product::where('manage_stock', true)->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0)->count();
                $stockDistribution['in_stock']     = Product::where('manage_stock', true)->whereColumn('stock_quantity', '>', 'low_stock_threshold')->count();
            } elseif ($hasStockQty) {
                $stockDistribution['out_of_stock'] = Product::where('stock_quantity', '<=', 0)->count();
                $stockDistribution['low_stock']    = Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5)->count();
                $stockDistribution['in_stock']     = Product::where('stock_quantity', '>', 5)->count();
            } else {
                $stockDistribution['in_stock'] = Product::count();
            }

            return response()->json(['success' => true, 'data' => [
                'by_category'        => $byCategory,
                'by_brand'           => $byBrand,
                'by_type'            => $byType,
                'by_status'          => $byStatus,
                'flags'              => $flags,
                'stock_distribution' => $stockDistribution,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function inventory(Request $request)
    {
        try {
            $movementsByType = StockMovement::select('type', DB::raw('count(*) as count'), DB::raw('SUM(ABS(quantity)) as total_qty'))
                ->groupBy('type')->orderByDesc('count')->get()
                ->map(fn($r) => ['type' => $r->type, 'label' => ucfirst($r->type), 'count' => $r->count, 'total_qty' => $r->total_qty]);

            // Recent movements
            $recentMovements = StockMovement::with('product:id,name')
                ->latest()->limit(15)->get()
                ->map(fn($m) => [
                    'type'       => $m->type,
                    'product'    => $m->product?->name ?? '—',
                    'quantity'   => $m->quantity,
                    'note'       => $m->note,
                    'created_at' => $m->created_at?->diffForHumans(),
                ]);

            $hasManageStock  = Schema::hasColumn('products', 'manage_stock');
            $hasStockQty     = Schema::hasColumn('products', 'stock_quantity');
            $hasLowThreshold = Schema::hasColumn('products', 'low_stock_threshold');

            $lowStockProducts = collect();
            $outOfStockProducts = collect();

            if ($hasStockQty) {
                if ($hasManageStock && $hasLowThreshold) {
                    $lowStockProducts = Product::with('brand')
                        ->where('manage_stock', true)->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0)
                        ->orderBy('stock_quantity')->limit(20)
                        ->get(['id', 'name', 'sku_prefix', 'stock_quantity', 'low_stock_threshold', 'brand_id'])
                        ->map(fn($p) => [
                            'id' => $p->id, 'name' => $p->name, 'sku_prefix' => $p->sku_prefix,
                            'brand' => $p->brand?->name ?? '—', 'stock_quantity' => $p->stock_quantity,
                            'threshold' => $p->low_stock_threshold,
                            'urgency' => $p->stock_quantity <= 2 ? 'critical' : 'warning',
                        ]);

                    $outOfStockProducts = Product::with('brand')
                        ->where('manage_stock', true)->where('stock_quantity', '<=', 0)
                        ->orderBy('name')->limit(20)
                        ->get(['id', 'name', 'sku_prefix', 'stock_quantity', 'brand_id'])
                        ->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'sku_prefix' => $p->sku_prefix, 'brand' => $p->brand?->name ?? '—']);
                } else {
                    $lowStockProducts = Product::with('brand')
                        ->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5)
                        ->orderBy('stock_quantity')->limit(20)
                        ->get(['id', 'name', 'sku_prefix', 'stock_quantity', 'brand_id'])
                        ->map(fn($p) => [
                            'id' => $p->id, 'name' => $p->name, 'sku_prefix' => $p->sku_prefix,
                            'brand' => $p->brand?->name ?? '—', 'stock_quantity' => $p->stock_quantity,
                            'threshold' => 5, 'urgency' => $p->stock_quantity <= 2 ? 'critical' : 'warning',
                        ]);

                    $outOfStockProducts = Product::with('brand')
                        ->where('stock_quantity', '<=', 0)->orderBy('name')->limit(20)
                        ->get(['id', 'name', 'sku_prefix', 'stock_quantity', 'brand_id'])
                        ->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'sku_prefix' => $p->sku_prefix, 'brand' => $p->brand?->name ?? '—']);
                }
            }

            // Warehouse stock summary
            $warehouseSummary = [];
            if (Schema::hasTable('warehouses')) {
                $warehouseSummary = Warehouse::where('is_active', true)
                    ->get(['id', 'name', 'code', 'city'])
                    ->map(fn($w) => ['name' => $w->name, 'code' => $w->code, 'city' => $w->city]);
            }

            return response()->json(['success' => true, 'data' => [
                'movements_by_type'  => $movementsByType,
                'recent_movements'   => $recentMovements,
                'low_stock_products' => $lowStockProducts,
                'out_of_stock'       => $outOfStockProducts,
                'warehouse_summary'  => $warehouseSummary,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function reviews(Request $request)
    {
        try {
            [$from, $to] = $this->dateRange($request);

            $total    = ProductReview::count();
            $avgRating = round(ProductReview::where('status', 'approved')->avg('rating') ?? 0, 1);

            // Rating distribution
            $ratingDist = [];
            for ($i = 5; $i >= 1; $i--) {
                $count = ProductReview::where('rating', $i)->count();
                $ratingDist[] = ['rating' => $i, 'count' => $count, 'percent' => $total > 0 ? round(($count / $total) * 100, 1) : 0];
            }

            // Status breakdown
            $byStatus = ProductReview::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')->get()->pluck('count', 'status');

            // Source breakdown (website vs admin)
            $bySource = ProductReview::select('source', DB::raw('count(*) as count'))
                ->groupBy('source')->get()->pluck('count', 'source');

            // Verified vs unverified
            $verified   = ProductReview::where('verified_purchase', true)->count();
            $unverified = $total - $verified;

            // Top reviewed products
            $topReviewed = Product::orderByDesc('reviews_count')->where('reviews_count', '>', 0)
                ->limit(10)->get(['id', 'name', 'sku_prefix', 'average_rating', 'reviews_count'])
                ->map(fn($p) => [
                    'id' => $p->id, 'name' => $p->name, 'sku_prefix' => $p->sku_prefix,
                    'avg_rating' => round($p->average_rating, 1), 'reviews_count' => $p->reviews_count,
                ]);

            // Recent reviews
            $recentReviews = ProductReview::with('product')->latest()->limit(15)->get()
                ->map(fn($r) => [
                    'id' => $r->id, 'reviewer_name' => $r->reviewer_name,
                    'product' => $r->product?->name ?? '—', 'rating' => $r->rating,
                    'title' => $r->title, 'status' => $r->status,
                    'verified_purchase' => $r->verified_purchase,
                    'source' => $r->source,
                    'created_at' => $r->created_at?->diffForHumans(),
                ]);

            // Review trend
            $reviewTrend = ProductReview::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('date')->orderBy('date')->get()
                ->map(fn($r) => ['date' => $r->date, 'count' => $r->count]);

            return response()->json(['success' => true, 'data' => [
                'total'          => $total,
                'avg_rating'     => $avgRating,
                'rating_dist'    => $ratingDist,
                'by_status'      => $byStatus,
                'by_source'      => $bySource,
                'verified'       => $verified,
                'unverified'     => $unverified,
                'top_reviewed'   => $topReviewed,
                'recent_reviews' => $recentReviews,
                'review_trend'   => $reviewTrend,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function marketing(Request $request)
    {
        try {
            // Promotions
            $promotionsByType = Promotion::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')->orderByDesc('count')->get()
                ->map(fn($r) => ['type' => $r->type, 'count' => $r->count]);

            $now = now();
            $promotionsByStatus = [
                'active'    => Promotion::where('is_active', true)->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))->count(),
                'scheduled' => Promotion::where('is_active', true)->where('starts_at', '>', $now)->count(),
                'expired'   => Promotion::where('is_active', true)->where('ends_at', '<', $now)->count(),
                'inactive'  => Promotion::where('is_active', false)->count(),
            ];

            // Coupons
            $couponsUsage = Coupon::select('code', 'name', 'used_count', 'usage_limit', 'is_active', 'discount_type', 'discount_value')
                ->orderByDesc('used_count')->limit(10)->get()
                ->map(fn($c) => [
                    'code' => $c->code, 'name' => $c->name,
                    'used_count' => $c->used_count ?? 0, 'usage_limit' => $c->usage_limit,
                    'percent' => $c->usage_limit > 0 ? round((($c->used_count ?? 0) / $c->usage_limit) * 100, 1) : 0,
                    'is_active' => $c->is_active,
                    'discount_type' => $c->discount_type, 'discount_value' => $c->discount_value,
                ]);

            // Email campaigns
            $campaignsByStatus = EmailCampaign::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')->get()->pluck('count', 'status');

            $totalCampaigns = EmailCampaign::count();
            $totalSent      = EmailCampaign::where('status', 'sent')->sum('sent_count');
            $totalOpened    = EmailCampaign::where('status', 'sent')->sum('opened_count');
            $totalClicked   = EmailCampaign::where('status', 'sent')->sum('clicked_count');
            $openRate       = $totalSent > 0 ? round(($totalOpened / $totalSent) * 100, 1) : 0;
            $clickRate      = $totalSent > 0 ? round(($totalClicked / $totalSent) * 100, 1) : 0;

            // Top campaigns by open rate
            $topCampaigns = EmailCampaign::where('status', 'sent')
                ->where('sent_count', '>', 0)
                ->orderByDesc('opened_count')
                ->limit(5)->get(['name', 'sent_count', 'opened_count', 'clicked_count', 'sent_at'])
                ->map(fn($c) => [
                    'name'       => $c->name,
                    'sent'       => $c->sent_count,
                    'opened'     => $c->opened_count,
                    'clicked'    => $c->clicked_count,
                    'open_rate'  => $c->sent_count > 0 ? round(($c->opened_count / $c->sent_count) * 100, 1) : 0,
                    'click_rate' => $c->sent_count > 0 ? round(($c->clicked_count / $c->sent_count) * 100, 1) : 0,
                    'sent_at'    => $c->sent_at?->format('d M Y'),
                ]);

            return response()->json(['success' => true, 'data' => [
                'promotions_by_type'   => $promotionsByType,
                'promotions_by_status' => $promotionsByStatus,
                'coupons_usage'        => $couponsUsage,
                'campaigns_by_status'  => $campaignsByStatus,
                'total_campaigns'      => $totalCampaigns,
                'total_sent'           => $totalSent,
                'total_opened'         => $totalOpened,
                'total_clicked'        => $totalClicked,
                'open_rate'            => $openRate,
                'click_rate'           => $clickRate,
                'top_campaigns'        => $topCampaigns,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function customers(Request $request)
    {
        try {
            $totalUsers  = \App\Models\User::count();
            $activeUsers = \App\Models\User::where('is_active', true)->count();
            $totalRoles  = \App\Models\Role::count();

            $usersByRole = \App\Models\Role::withCount('users')->get()
                ->map(fn($r) => ['role' => $r->display_name ?? $r->name, 'count' => $r->users_count]);

            $users = \App\Models\User::with('role')->latest()->limit(20)->get()
                ->map(fn($u) => [
                    'name'       => $u->name,
                    'email'      => $u->email,
                    'role'       => $u->role?->display_name ?? '—',
                    'is_active'  => $u->is_active,
                    'last_login' => $u->last_login?->diffForHumans() ?? 'Never',
                    'created_at' => $u->created_at?->format('d M Y'),
                ]);

            return response()->json(['success' => true, 'data' => [
                'total_users'  => $totalUsers,
                'active_users' => $activeUsers,
                'total_roles'  => $totalRoles,
                'users_by_role'=> $usersByRole,
                'users'        => $users,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function catalog(Request $request)
    {
        try {
            $byCategory = Category::select('id', 'name')
                ->selectRaw('(SELECT COUNT(*) FROM products WHERE products.category_id = categories.id AND products.deleted_at IS NULL) as cat_products_count')
                ->having('cat_products_count', '>', 0)
                ->orderByDesc('cat_products_count')->limit(10)->get()
                ->map(fn($c) => ['name' => $c->name, 'count' => $c->cat_products_count]);

            $byBrand = Brand::withCount('products')->having('products_count', '>', 0)
                ->orderByDesc('products_count')->limit(10)->get(['id', 'name'])
                ->map(fn($b) => ['name' => $b->name, 'count' => $b->products_count]);

            $collections = Collection::withCount('products')
                ->orderByDesc('products_count')->limit(10)
                ->get(['id', 'name', 'type', 'is_active'])
                ->map(fn($c) => ['name' => $c->name, 'type' => $c->type, 'products_count' => $c->products_count, 'is_active' => $c->is_active]);

            return response()->json(['success' => true, 'data' => [
                'total_products'    => Product::count(),
                'total_categories'  => Category::count(),
                'total_brands'      => Brand::count(),
                'total_collections' => Collection::count(),
                'total_tags'        => \App\Models\ProductTag::count(),
                'total_attributes'  => \App\Models\Attribute::count(),
                'by_category'       => $byCategory,
                'by_brand'          => $byBrand,
                'collections'       => $collections,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function activity(Request $request)
    {
        try {
            [$from, $to] = $this->dateRange($request);
            $byAction = \App\Models\ActivityLog::select('action', DB::raw('count(*) as count'))->groupBy('action')->orderByDesc('count')->get()->map(fn($r) => ['action' => $r->action, 'count' => $r->count]);
            $byModule = \App\Models\ActivityLog::select('module', DB::raw('count(*) as count'))->whereNotNull('module')->groupBy('module')->orderByDesc('count')->limit(10)->get()->map(fn($r) => ['module' => $r->module, 'count' => $r->count]);
            $trend    = \App\Models\ActivityLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))->whereBetween('created_at', [$from, $to])->groupBy('date')->orderBy('date')->get()->map(fn($r) => ['date' => $r->date, 'count' => $r->count]);
            $recent   = \App\Models\ActivityLog::with('user:id,name')->latest()->limit(20)->get()->map(fn($l) => ['action' => $l->action, 'module' => $l->module, 'description' => $l->description, 'user' => $l->user?->name ?? 'System', 'ip' => $l->ip_address, 'created_at' => $l->created_at?->diffForHumans()]);

            return response()->json(['success' => true, 'data' => [
                'total'     => \App\Models\ActivityLog::count(),
                'today'     => \App\Models\ActivityLog::whereDate('created_at', today())->count(),
                'created'   => \App\Models\ActivityLog::where('action', 'created')->count(),
                'deleted'   => \App\Models\ActivityLog::where('action', 'deleted')->count(),
                'by_action' => $byAction,
                'by_module' => $byModule,
                'trend'     => $trend,
                'recent'    => $recent,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
