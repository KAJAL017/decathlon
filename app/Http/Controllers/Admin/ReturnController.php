<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Return_;
use App\Models\Order;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        return view('admin.pages.returns.index');
    }

    public function list(Request $request)
    {
        try {
            $query = Return_::with('order:id,order_number,customer_name,total_amount');

            if ($request->search) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('return_number', 'like', "%$s%")
                      ->orWhereHas('order', fn($oq) => $oq->where('order_number', 'like', "%$s%")
                          ->orWhere('customer_name', 'like', "%$s%"));
                });
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->type) {
                $query->where('type', $request->type);
            }

            $returns = $query->orderByDesc('created_at')->paginate(20);

            return response()->json([
                'success' => true,
                'data'    => $returns->items(),
                'meta'    => [
                    'total'        => $returns->total(),
                    'current_page' => $returns->currentPage(),
                    'last_page'    => $returns->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason'   => 'required|string',
            'type'     => 'required|in:return,exchange,refund_only',
        ]);

        try {
            $return = Return_::create([
                'return_number' => Return_::generateReturnNumber(),
                'order_id'      => $request->order_id,
                'status'        => 'requested',
                'type'          => $request->type,
                'reason'        => $request->reason,
                'description'   => $request->description,
                'refund_amount' => $request->refund_amount ?? 0,
                'refund_method' => $request->refund_method,
                'admin_note'    => $request->admin_note,
                'items'         => $request->items ?? [],
                'handled_by'    => session('admin_id'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Return request created',
                'data'    => $return->load('order'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $return = Return_::with(['order.items'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $return]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $return = Return_::findOrFail($id);

            $data = $request->only([
                'status', 'refund_amount', 'refund_method', 'refund_reference',
                'admin_note', 'items',
            ]);

            if (isset($data['status']) && $data['status'] === 'refunded' && !$return->refunded_at) {
                $data['refunded_at'] = now();
                $data['handled_by']  = session('admin_id');

                // Update order payment status if fully refunded
                $order = $return->order;
                if ($order) {
                    $order->update(['payment_status' => 'refunded', 'status' => 'refunded']);
                }
            }

            $return->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Return updated',
                'data'    => $return->fresh()->load('order'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Return_::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Return deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function stats()
    {
        try {
            return response()->json(['success' => true, 'data' => [
                'total'     => Return_::count(),
                'requested' => Return_::where('status', 'requested')->count(),
                'approved'  => Return_::where('status', 'approved')->count(),
                'refunded'  => Return_::where('status', 'refunded')->count(),
                'rejected'  => Return_::where('status', 'rejected')->count(),
                'total_refunded' => Return_::where('status', 'refunded')->sum('refund_amount'),
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
