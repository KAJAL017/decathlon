<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $summary = $this->getSummaryData();
        return view('admin.pages.stock.index', compact('summary'));
    }

    public function list(Request $request)
    {
        $tab = $request->get('tab', 'overview');

        if ($tab === 'movements') {
            return $this->listMovements($request);
        }

        return $this->listOverview($request);
    }

    private function listOverview(Request $request)
    {
        $q = Product::query()->select([
            'id', 'name', 'sku_prefix as sku', 'stock_quantity', 'low_stock_threshold',
            'manage_stock', 'updated_at',
        ]);

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('sku_prefix', 'like', "%$s%"));
        }

        if ($request->filled('low_stock')) {
            $q->where('manage_stock', true)
              ->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
        }

        if ($request->filled('stock_status')) {
            match($request->stock_status) {
                'out'  => $q->where('stock_quantity', '<=', 0),
                'low'  => $q->where('stock_quantity', '>', 0)->whereColumn('stock_quantity', '<=', 'low_stock_threshold'),
                'in'   => $q->whereColumn('stock_quantity', '>', 'low_stock_threshold'),
                default => null,
            };
        }

        $perPage = (int) $request->get('per_page', 20);
        $items   = $q->orderBy('name')->paginate($perPage);

        $items->getCollection()->transform(function ($p) {
            $p->stock_status = $this->getStockStatus($p);
            return $p;
        });

        return response()->json([
            'success'    => true,
            'data'       => $items->items(),
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
            ],
            'summary' => $this->getSummaryData(),
        ]);
    }

    private function listMovements(Request $request)
    {
        $q = StockMovement::with(['product:id,name,sku_prefix', 'creator:id,name']);

        if ($request->filled('search')) {
            $s = $request->search;
            $q->whereHas('product', fn($q) => $q->where('name', 'like', "%$s%")->orWhere('sku_prefix', 'like', "%$s%"));
        }

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $q->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $q->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = (int) $request->get('per_page', 20);
        $items   = $q->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success'    => true,
            'data'       => $items->items(),
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'product_id'     => 'required|integer|exists:products,id',
            'variant_id'     => 'nullable|integer|exists:product_variants,id',
            'type'           => 'required|in:purchase,sale,return,adjustment,transfer,damage,expired',
            'quantity'       => 'required|integer|min:1',
            'direction'      => 'required|in:add,remove',
            'notes'          => 'nullable|string|max:500',
            'cost_per_unit'  => 'nullable|numeric|min:0',
            'reference_type' => 'nullable|string|max:100',
            'reference_id'   => 'nullable|integer',
            'location'       => 'nullable|string|max:100',
        ]);

        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);

            // Determine actual quantity (positive = add, negative = remove)
            $qty = (int) $request->quantity;
            if ($request->direction === 'remove') {
                $qty = -$qty;
            }

            // Get current stock
            $currentStock = 0;
            if ($request->variant_id) {
                $variant = ProductVariant::find($request->variant_id);
                if ($variant && Schema::hasColumn('product_variants', 'stock_quantity')) {
                    $currentStock = (int) $variant->stock_quantity;
                }
            } else {
                if (Schema::hasColumn('products', 'stock_quantity')) {
                    $currentStock = (int) $product->stock_quantity;
                }
            }

            $newStock = $currentStock + $qty;

            // Create movement record
            $movement = StockMovement::create([
                'product_id'     => $request->product_id,
                'variant_id'     => $request->variant_id,
                'type'           => $request->type,
                'quantity'       => $qty,
                'quantity_before' => $currentStock,
                'quantity_after'  => $newStock,
                'reference_type' => $request->reference_type,
                'reference_id'   => $request->reference_id,
                'notes'          => $request->notes,
                'cost_per_unit'  => $request->cost_per_unit,
                'location'       => $request->location ?? 'Main Warehouse',
                'created_by'     => session('admin_id'),
            ]);

            // Update stock quantity
            if ($request->variant_id) {
                $variant = ProductVariant::find($request->variant_id);
                if ($variant && Schema::hasColumn('product_variants', 'stock_quantity')) {
                    $variant->stock_quantity = max(0, $newStock);
                    $variant->save();
                }
            } else {
                if (Schema::hasColumn('products', 'stock_quantity')) {
                    $product->stock_quantity = max(0, $newStock);
                    if (Schema::hasColumn('products', 'manage_stock')) {
                        $product->manage_stock = true;
                    }
                    $product->save();
                }
            }

            $sign = $qty > 0 ? '+' : '';
            \App\Models\ActivityLog::log('created', 'stock_movements',
                "Stock {$request->type}: {$product->name} ({$sign}{$qty})",
                ['product_id' => $product->id, 'movement_id' => $movement->id]
            );

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Stock movement recorded successfully',
                'data'     => $movement->load('product:id,name,sku_prefix'),
                'new_stock' => max(0, $newStock),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to record movement: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $m = StockMovement::with(['product:id,name,sku_prefix', 'variant', 'creator:id,name'])->find($id);
        if (!$m) return response()->json(['success' => false, 'message' => 'Not found'], 404);
        return response()->json(['success' => true, 'data' => $m]);
    }

    public function adjustStock(Request $request)
    {
        $v = Validator::make($request->all(), [
            'product_id'    => 'required|integer|exists:products,id',
            'new_quantity'  => 'required|integer|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $currentStock = Schema::hasColumn('products', 'stock_quantity') ? (int) $product->stock_quantity : 0;
            $newStock = (int) $request->new_quantity;
            $diff = $newStock - $currentStock;

            StockMovement::create([
                'product_id'      => $request->product_id,
                'type'            => 'adjustment',
                'quantity'        => $diff,
                'quantity_before' => $currentStock,
                'quantity_after'  => $newStock,
                'notes'           => $request->notes ?? 'Manual stock adjustment',
                'location'        => $request->location ?? 'Main Warehouse',
                'created_by'      => session('admin_id'),
            ]);

            if (Schema::hasColumn('products', 'stock_quantity')) {
                $product->stock_quantity = $newStock;
                $product->save();
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Stock adjusted successfully',
                'new_stock' => $newStock,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Adjustment failed: ' . $e->getMessage()], 500);
        }
    }

    public function getLowStock()
    {
        $products = Product::select(['id', 'name', 'sku_prefix', 'stock_quantity', 'low_stock_threshold'])
            ->where('manage_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->get()
            ->map(fn($p) => array_merge($p->toArray(), ['stock_status' => $this->getStockStatus($p)]));

        return response()->json(['success' => true, 'data' => $products, 'count' => $products->count()]);
    }

    public function getStockHistory($productId)
    {
        $product = Product::find($productId);
        if (!$product) return response()->json(['success' => false, 'message' => 'Product not found'], 404);

        $movements = StockMovement::with(['creator:id,name'])
            ->where('product_id', $productId)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json([
            'success'  => true,
            'product'  => $product->only(['id', 'name', 'sku_prefix', 'stock_quantity']),
            'data'     => $movements,
        ]);
    }

    private function getSummaryData(): array
    {
        $hasStock = Schema::hasColumn('products', 'stock_quantity');
        $hasManage = Schema::hasColumn('products', 'manage_stock');
        $hasThreshold = Schema::hasColumn('products', 'low_stock_threshold');

        if (!$hasStock) {
            return ['total' => 0, 'in_stock' => 0, 'low_stock' => 0, 'out_of_stock' => 0, 'total_value' => 0];
        }

        $total = Product::count();
        $outOfStock = Product::where('stock_quantity', '<=', 0)->count();
        $lowStock = 0;
        if ($hasThreshold && $hasManage) {
            $lowStock = Product::where('manage_stock', true)
                ->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                ->count();
        }
        $inStock = $total - $outOfStock - $lowStock;

        // Total stock value (cost * quantity)
        $totalValue = 0;
        try {
            if (Schema::hasColumn('products', 'cost_price')) {
                $totalValue = Product::where('stock_quantity', '>', 0)
                    ->whereNotNull('cost_price')
                    ->selectRaw('SUM(stock_quantity * cost_price) as total')
                    ->value('total') ?? 0;
            }
        } catch (\Exception $e) {}

        return [
            'total'       => $total,
            'in_stock'    => max(0, $inStock),
            'low_stock'   => $lowStock,
            'out_of_stock' => $outOfStock,
            'total_value' => round($totalValue, 2),
        ];
    }

    private function getStockStatus(Product $product): string
    {
        if ($product->stock_quantity <= 0) return 'out';
        if (isset($product->low_stock_threshold) && $product->stock_quantity <= $product->low_stock_threshold) return 'low';
        return 'in';
    }
}
