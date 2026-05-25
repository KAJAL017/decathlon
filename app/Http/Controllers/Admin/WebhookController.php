<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    // All available webhook events
    public const EVENTS = [
        'order.created'       => 'Order Created',
        'order.updated'       => 'Order Updated',
        'order.cancelled'     => 'Order Cancelled',
        'order.shipped'       => 'Order Shipped',
        'order.delivered'     => 'Order Delivered',
        'payment.success'     => 'Payment Success',
        'payment.failed'      => 'Payment Failed',
        'payment.refunded'    => 'Payment Refunded',
        'product.created'     => 'Product Created',
        'product.updated'     => 'Product Updated',
        'product.deleted'     => 'Product Deleted',
        'stock.low'           => 'Stock Low Alert',
        'stock.out'           => 'Stock Out Alert',
        'customer.registered' => 'Customer Registered',
        'review.submitted'    => 'Review Submitted',
        'coupon.used'         => 'Coupon Used',
    ];

    public function list(Request $request)
    {
        $q = Webhook::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($query) use ($s) {
                $query->where('name', 'like', "%$s%")
                      ->orWhere('url', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $q->where('is_active', (bool)(int)$request->status);
        }

        if ($request->filled('event')) {
            $q->whereJsonContains('events', $request->event);
        }

        $perPage = (int)($request->per_page ?? 20);
        $result  = $q->orderByDesc('created_at')->paginate($perPage);

        $stats = [
            'total'   => Webhook::count(),
            'active'  => Webhook::where('is_active', true)->count(),
            'failed'  => Webhook::where('last_status', 'failed')->count(),
            'events'  => self::EVENTS,
        ];

        return response()->json([
            'success'    => true,
            'data'       => $result->items(),
            'pagination' => [
                'total'        => $result->total(),
                'per_page'     => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
            ],
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'url'         => 'required|url|max:500',
            'events'      => 'required|array|min:1',
            'events.*'    => 'string',
            'secret'      => 'nullable|string|max:100',
            'method'      => 'in:POST,GET,PUT',
            'headers'     => 'nullable|array',
            'is_active'   => 'boolean',
            'timeout'     => 'integer|min:1|max:60',
            'retry_count' => 'integer|min:0|max:5',
        ]);

        $validated['created_by'] = session('admin_id');
        $webhook = Webhook::create($validated);

        ActivityLog::log('created', 'webhooks', $webhook->id, null, ['name' => $webhook->name, 'url' => $webhook->url]);

        return response()->json(['success' => true, 'message' => 'Webhook created', 'data' => $webhook]);
    }

    public function show($id)
    {
        $webhook = Webhook::findOrFail($id);
        return response()->json(['success' => true, 'data' => $webhook]);
    }

    public function update(Request $request, $id)
    {
        $webhook = Webhook::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'url'         => 'required|url|max:500',
            'events'      => 'required|array|min:1',
            'events.*'    => 'string',
            'secret'      => 'nullable|string|max:100',
            'method'      => 'in:POST,GET,PUT',
            'headers'     => 'nullable|array',
            'is_active'   => 'boolean',
            'timeout'     => 'integer|min:1|max:60',
            'retry_count' => 'integer|min:0|max:5',
        ]);

        $old = $webhook->toArray();
        $webhook->update($validated);
        ActivityLog::log('updated', 'webhooks', $webhook->id, $old, $webhook->fresh()->toArray());

        return response()->json(['success' => true, 'message' => 'Webhook updated', 'data' => $webhook->fresh()]);
    }

    public function destroy($id)
    {
        $webhook = Webhook::findOrFail($id);
        ActivityLog::log('deleted', 'webhooks', $webhook->id, ['name' => $webhook->name], null);
        $webhook->delete();

        return response()->json(['success' => true, 'message' => 'Webhook deleted']);
    }

    public function toggleStatus($id)
    {
        $webhook = Webhook::findOrFail($id);
        $webhook->update(['is_active' => !$webhook->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data'    => ['is_active' => $webhook->is_active],
        ]);
    }

    public function test($id)
    {
        $webhook = Webhook::findOrFail($id);

        $payload = [
            'event'     => 'webhook.test',
            'timestamp' => now()->toISOString(),
            'source'    => 'decathlon-admin',
            'data'      => [
                'message'    => 'This is a test webhook from Decathlon Admin',
                'webhook_id' => $webhook->id,
                'webhook'    => $webhook->name,
            ],
        ];

        $success = $webhook->dispatch($payload);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Test ping sent successfully!' : 'Test ping failed: ' . $webhook->last_response,
            'status'  => $webhook->last_status,
            'response'=> $webhook->last_response,
        ]);
    }

    public function getEvents()
    {
        return response()->json(['success' => true, 'data' => self::EVENTS]);
    }
}
