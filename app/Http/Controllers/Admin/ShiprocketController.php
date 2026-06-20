<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;

class ShiprocketController extends Controller
{
    // ── Sync order status from Shiprocket ────────────────────────
    public function syncStatus($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $sr    = new ShiprocketService();

            if (!$sr->isConfigured()) {
                return response()->json(['success' => false, 'message' => 'Shiprocket not configured.'], 422);
            }

            // Extract Shiprocket order ID from admin_note
            preg_match('/Shiprocket Order ID: (\d+)/', $order->admin_note ?? '', $m);
            $srOrderId = $m[1] ?? null;

            preg_match('/Shipment ID: (\d+)/', $order->admin_note ?? '', $m2);
            $srShipmentId = $m2[1] ?? null;

            if (!$srOrderId) {
                return response()->json(['success' => false, 'message' => 'This order has not been pushed to Shiprocket yet.'], 422);
            }

            $data = $sr->getOrderStatus($srOrderId);

            // Map Shiprocket status → local status
            $srStatus  = strtolower($data['status'] ?? '');
            $awb       = $data['awb_code'] ?? $data['awb'] ?? null;
            $carrier   = $data['courier_name'] ?? null;
            $shippedAt = $data['shipped_at'] ?? null;
            $deliveredAt = $data['delivered_at'] ?? null;

            $localStatus = $order->status;
            if (str_contains($srStatus, 'delivered'))          $localStatus = 'delivered';
            elseif (str_contains($srStatus, 'shipped') || str_contains($srStatus, 'in transit') || str_contains($srStatus, 'out for delivery')) $localStatus = 'shipped';
            elseif (str_contains($srStatus, 'pickup') || str_contains($srStatus, 'processing')) $localStatus = 'processing';
            elseif (str_contains($srStatus, 'cancel'))         $localStatus = 'cancelled';

            $update = ['status' => $localStatus];
            if ($awb)         $update['tracking_number']  = $awb;
            if ($carrier)     $update['shipping_carrier'] = $carrier;
            if ($shippedAt)   $update['shipped_at']       = $shippedAt;
            if ($deliveredAt) $update['delivered_at']     = $deliveredAt;

            $order->update($update);

            \App\Models\ActivityLog::log('updated', 'shiprocket', "Synced status for {$order->order_number}: {$srStatus}", [
                'order_id' => $order->id,
                'sr_status' => $srStatus,
                'awb' => $awb,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status synced from Shiprocket',
                'data'    => [
                    'sr_status'    => $data['status'] ?? '—',
                    'local_status' => $localStatus,
                    'awb'          => $awb,
                    'carrier'      => $carrier,
                    'shipped_at'   => $shippedAt,
                    'delivered_at' => $deliveredAt,
                    'sr_order_id'  => $srOrderId,
                    'shipment_id'  => $srShipmentId,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Get available couriers for an order ───────────────────────
    public function getCouriers($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $sr    = new ShiprocketService();

            if (!$sr->isConfigured()) {
                return response()->json(['success' => false, 'message' => 'Shiprocket not configured. Go to Integrations → Shipping.'], 422);
            }

            $pickupPincode   = Setting::get('shiprocket_pickup_pincode',
                                Setting::get('store_zip',
                                Setting::get('store_pincode', '110001')));
            $deliveryPincode = $order->shipping_pincode ?? '400001';
            $weight          = (float) Setting::get('shiprocket_default_weight', '0.5');
            $cod             = $order->payment_method === 'cod' ? (float) $order->total_amount : 0;

            $couriers = $sr->getAvailableCouriers($pickupPincode, $deliveryPincode, $weight, $cod);

            // Sort by rate ascending
            usort($couriers, fn($a, $b) => $a['rate'] <=> $b['rate']);

            return response()->json([
                'success' => true,
                'data'    => [
                    'couriers'         => $couriers,
                    'order_number'     => $order->order_number,
                    'delivery_pincode' => $deliveryPincode,
                    'pickup_pincode'   => $pickupPincode,
                    'weight'           => $weight,
                    'is_cod'           => $cod > 0,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Create order in Shiprocket ─────
    public function createOrder($orderId)
    {
        try {
            $order = Order::with('items')->findOrFail($orderId);
            $sr    = new ShiprocketService();

            if (!$sr->isConfigured()) {
                return response()->json(['success' => false, 'message' => 'Shiprocket not configured. Go to Integrations → Shipping.'], 422);
            }

            $result = $sr->createOrder($order);

            // Save Shiprocket order ID and shipment ID to order
            $srOrderId    = $result['order_id']    ?? null;
            $srShipmentId = $result['shipment_id'] ?? null;
            $srStatus     = $result['status']      ?? 'NEW';

            // Don't overwrite existing SR note — append only if new
            $existingNote = $order->admin_note ?? '';
            $srNote       = "Shiprocket Order ID: {$srOrderId} | Shipment ID: {$srShipmentId} | Status: {$srStatus}";

            if (!str_contains($existingNote, 'Shiprocket Order ID:')) {
                $newNote = $existingNote ? $existingNote . "\n" . $srNote : $srNote;
            } else {
                // Update existing SR line
                $newNote = preg_replace('/Shiprocket Order ID:.*$/m', $srNote, $existingNote);
            }

            $order->update([
                'admin_note' => $newNote,
                'status'     => in_array($srStatus, ['CANCELED', 'CANCELLATION REQUESTED']) ? $order->status : 'processing',
            ]);

            \App\Models\ActivityLog::log('created', 'shiprocket', "Created Shiprocket order for {$order->order_number}", [
                'order_id'    => $order->id,
                'sr_order_id' => $srOrderId,
            ]);

            return response()->json([
                'success'     => true,
                'message'     => 'Order created in Shiprocket! Status: ' . $srStatus,
                'data'        => [
                    'sr_order_id'    => $srOrderId,
                    'sr_shipment_id' => $srShipmentId,
                    'sr_status'      => $srStatus,
                    'status'         => $result['status'] ?? null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Track shipment ────────────────────────────────────────────
    public function track(Request $request)
    {
        $request->validate(['awb' => 'required|string']);
        try {
            $sr     = new ShiprocketService();
            $result = $sr->trackShipment($request->awb);
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Cancel order ──────────────────────────────────────────────
    public function cancelOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $sr    = new ShiprocketService();

            // Extract Shiprocket order ID from admin_note
            preg_match('/Shiprocket Order ID: (\d+)/', $order->admin_note ?? '', $m);
            $srOrderId = $m[1] ?? null;

            if (!$srOrderId) {
                return response()->json(['success' => false, 'message' => 'No Shiprocket order found for this order.'], 422);
            }

            \DB::beginTransaction();

            // Restore stock when cancelling
            if ($order->status !== 'cancelled') {
                $order->load('items.variant');
                foreach ($order->items as $item) {
                    if ($item->variant && $item->variant->manage_stock) {
                        $item->variant->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            $result = $sr->cancelOrder([$srOrderId]);
            $order->update(['status' => 'cancelled']);

            \DB::commit();

            return response()->json(['success' => true, 'message' => 'Order cancelled in Shiprocket', 'data' => $result]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
