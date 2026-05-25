<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('admin.pages.invoices.index');
    }

    public function list(Request $request)
    {
        try {
            $query = Invoice::query();

            if ($request->search) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('invoice_number', 'like', "%$s%")
                      ->orWhere('customer_name', 'like', "%$s%")
                      ->orWhere('customer_email', 'like', "%$s%");
                });
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->type) {
                $query->where('type', $request->type);
            }

            if ($request->date_from) {
                $query->whereDate('invoice_date', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('invoice_date', '<=', $request->date_to);
            }

            $invoices = $query->orderByDesc('created_at')->paginate(20);

            return response()->json([
                'success' => true,
                'data'    => $invoices->items(),
                'meta'    => [
                    'total'        => $invoices->total(),
                    'current_page' => $invoices->currentPage(),
                    'last_page'    => $invoices->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'invoice_date'   => 'required|date',
            'items'          => 'required|array|min:1',
        ]);

        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
            }

            $taxAmount      = (float)($request->tax_amount ?? 0);
            $shippingAmount = (float)($request->shipping_amount ?? 0);
            $discountAmount = (float)($request->discount_amount ?? 0);
            $total          = $subtotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount     = (float)($request->paid_amount ?? 0);

            $invoice = Invoice::create([
                'invoice_number'  => Invoice::generateInvoiceNumber(),
                'order_id'        => $request->order_id,
                'type'            => $request->type ?? 'sale',
                'status'          => $request->status ?? 'draft',
                'customer_name'   => $request->customer_name,
                'customer_email'  => $request->customer_email,
                'customer_phone'  => $request->customer_phone,
                'customer_gstin'  => $request->customer_gstin,
                'billing_address' => $request->billing_address,
                'shipping_address'=> $request->shipping_address,
                'subtotal'        => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount'      => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount'    => $total,
                'paid_amount'     => $paidAmount,
                'due_amount'      => $total - $paidAmount,
                'currency'        => 'INR',
                'invoice_date'    => $request->invoice_date,
                'due_date'        => $request->due_date,
                'notes'           => $request->notes,
                'terms'           => $request->terms,
                'items'           => $request->items,
                'created_by'      => session('admin_id'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invoice created',
                'data'    => $invoice,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::with('order')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $invoice]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            $data = $request->only([
                'status', 'paid_amount', 'notes', 'terms', 'due_date',
                'customer_gstin', 'billing_address',
            ]);

            if (isset($data['status'])) {
                if ($data['status'] === 'sent' && !$invoice->sent_at) {
                    $data['sent_at'] = now();
                }
                if ($data['status'] === 'paid') {
                    $data['paid_at']     = now();
                    $data['paid_amount'] = $invoice->total_amount;
                    $data['due_amount']  = 0;
                }
            }

            if (isset($data['paid_amount'])) {
                $data['due_amount'] = $invoice->total_amount - (float)$data['paid_amount'];
            }

            $invoice->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated',
                'data'    => $invoice->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Invoice::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Invoice deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function generateFromOrder($orderId)
    {
        try {
            $order = Order::with('items')->findOrFail($orderId);

            // Check if invoice already exists
            if ($order->invoice) {
                return response()->json(['success' => false, 'message' => 'Invoice already exists for this order'], 422);
            }

            $items = $order->items->map(fn($item) => [
                'name'         => $item->product_name,
                'sku'          => $item->product_sku,
                'quantity'     => $item->quantity,
                'unit_price'   => $item->unit_price,
                'discount'     => $item->discount_amount,
                'tax'          => $item->tax_amount,
                'total'        => $item->total_price,
            ])->toArray();

            $invoice = Invoice::create([
                'invoice_number'  => Invoice::generateInvoiceNumber(),
                'order_id'        => $order->id,
                'type'            => 'sale',
                'status'          => 'draft',
                'customer_name'   => $order->customer_name,
                'customer_email'  => $order->customer_email,
                'customer_phone'  => $order->customer_phone,
                'billing_address' => $order->billing_address ?? $order->shipping_address,
                'shipping_address'=> $order->shipping_address,
                'subtotal'        => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'tax_amount'      => $order->tax_amount,
                'shipping_amount' => $order->shipping_amount,
                'total_amount'    => $order->total_amount,
                'paid_amount'     => $order->payment_status === 'paid' ? $order->total_amount : 0,
                'due_amount'      => $order->payment_status === 'paid' ? 0 : $order->total_amount,
                'invoice_date'    => now()->toDateString(),
                'due_date'        => now()->addDays(7)->toDateString(),
                'items'           => $items,
                'created_by'      => session('admin_id'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invoice generated from order',
                'data'    => $invoice,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function stats()
    {
        try {
            return response()->json(['success' => true, 'data' => [
                'total'       => Invoice::count(),
                'draft'       => Invoice::where('status', 'draft')->count(),
                'sent'        => Invoice::where('status', 'sent')->count(),
                'paid'        => Invoice::where('status', 'paid')->count(),
                'overdue'     => Invoice::where('status', 'sent')->whereDate('due_date', '<', today())->count(),
                'total_value' => Invoice::sum('total_amount'),
                'paid_value'  => Invoice::where('status', 'paid')->sum('total_amount'),
                'due_value'   => Invoice::whereIn('status', ['sent', 'overdue'])->sum('due_amount'),
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Print-friendly invoice view
    public function print($id)
    {
        try {
            $invoice = Invoice::with('order')->findOrFail($id);
            return view('admin.pages.invoices.print', compact('invoice'));
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
