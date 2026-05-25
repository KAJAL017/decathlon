<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ShiprocketService
{
    protected string $baseUrl = 'https://apiv2.shiprocket.in/v1/external';
    protected string $email;
    protected string $password;

    public function __construct()
    {
        $this->email    = Setting::get('shiprocket_email', '');
        $this->password = Setting::get('shiprocket_password', '');
        if ($this->password) {
            try { $this->password = decrypt($this->password); } catch (\Exception $e) {}
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->email) && !empty($this->password);
    }

    // ── Get/refresh token ─────────────────────────────────────────
    public function getToken(): string
    {
        $cached = Cache::get('shiprocket_token');
        if ($cached) return $cached;

        $res = Http::post("{$this->baseUrl}/auth/login", [
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        if (!$res->successful() || !isset($res->json()['token'])) {
            throw new \Exception('Shiprocket login failed: ' . ($res->json()['message'] ?? 'Unknown error'));
        }

        $token = $res->json()['token'];
        Cache::put('shiprocket_token', $token, now()->addHours(23));
        return $token;
    }

    protected function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withToken($this->getToken())
            ->baseUrl($this->baseUrl);
    }

    // ── Get available couriers with rates ────────────────────────
    public function getAvailableCouriers(string $pickupPincode, string $deliveryPincode, float $weight, float $cod = 0): array
    {
        $res = $this->http()->get('/courier/serviceability/', [
            'pickup_postcode'   => $pickupPincode,
            'delivery_postcode' => $deliveryPincode,
            'weight'            => $weight,
            'cod'               => $cod > 0 ? 1 : 0,
        ]);

        if (!$res->successful()) {
            throw new \Exception('Failed to fetch couriers: ' . $res->body());
        }

        $data     = $res->json();
        $couriers = $data['data']['available_courier_companies'] ?? [];

        return array_map(fn($c) => [
            'id'               => $c['courier_company_id'] ?? null,
            'name'             => $c['courier_name'] ?? '—',
            'rate'             => (float)($c['rate'] ?? 0),
            'cod_charges'      => (float)($c['cod_charges'] ?? 0),
            'estimated_days'   => $c['estimated_delivery_days'] ?? $c['etd'] ?? '—',
            'etd'              => $c['etd'] ?? null,
            'min_weight'       => $c['min_weight'] ?? null,
            'is_surface'       => $c['is_surface'] ?? false,
            'is_hyperlocal'    => $c['is_hyperlocal'] ?? false,
            'logo'             => $c['courier_company_logo'] ?? null,
            'rating'           => $c['rating'] ?? null,
            'delivery_performance' => $c['delivery_performance'] ?? null,
        ], $couriers);
    }

    // ── Create order with specific courier ────────────────────────
    public function createOrderWithCourier(\App\Models\Order $order, ?int $courierId = null): array
    {
        return $this->createOrder($order, $courierId);
    }

    // ── Create order in Shiprocket ────────────────────────────────
    public function createOrder(\App\Models\Order $order, ?int $courierId = null): array
    {
        $items = $order->items->map(fn($i) => [
            'name'          => $i->product_name,
            'sku'           => $i->product_sku ?: 'SKU-' . $i->id,
            'units'         => $i->quantity,
            'selling_price' => (float)$i->unit_price,
            'discount'      => (float)($i->discount_amount ?? 0),
            'tax'           => '',
            'hsn'           => '',
        ])->toArray();

        $payload = [
            'order_id'              => $order->order_number,
            'order_date'            => $order->created_at->format('Y-m-d H:i'),
            'pickup_location'       => Setting::get('shiprocket_pickup_location', 'Primary'),
            'channel_id'            => '',
            'comment'               => $order->customer_note ?? '',
            'billing_customer_name' => $order->billing_name ?? $order->customer_name,
            'billing_last_name'     => '',
            'billing_address'       => $order->billing_address ?? $order->shipping_address,
            'billing_address_2'     => '',
            'billing_city'          => $order->billing_city ?? $order->shipping_city,
            'billing_pincode'       => $order->billing_pincode ?? $order->shipping_pincode,
            'billing_state'         => $order->billing_state ?? $order->shipping_state,
            'billing_country'       => 'India',
            'billing_email'         => $order->customer_email,
            'billing_phone'         => $order->customer_phone ?? '',
            'shipping_is_billing'   => true,
            'shipping_customer_name'=> $order->shipping_name,
            'shipping_last_name'    => '',
            'shipping_address'      => $order->shipping_address,
            'shipping_address_2'    => '',
            'shipping_city'         => $order->shipping_city,
            'shipping_pincode'      => $order->shipping_pincode,
            'shipping_country'      => 'India',
            'shipping_state'        => $order->shipping_state,
            'shipping_email'        => $order->customer_email,
            'shipping_phone'        => $order->customer_phone ?? '',
            'order_items'           => $items,
            'payment_method'        => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
            'shipping_charges'      => (float)$order->shipping_amount,
            'giftwrap_charges'      => 0,
            'transaction_charges'   => 0,
            'total_discount'        => (float)$order->discount_amount,
            'sub_total'             => (float)$order->subtotal,
            'length'                => 10,
            'breadth'               => 10,
            'height'                => 10,
            'weight'                => (float)Setting::get('shiprocket_default_weight', '0.5'),
        ];

        $res = $this->http()->post('/orders/create/adhoc', $payload);

        if (!$res->successful()) {
            $body = $res->json();
            throw new \Exception('Shiprocket order creation failed: ' . ($body['message'] ?? json_encode($body)));
        }

        return $res->json();
    }

    // ── Get order details from Shiprocket ─────────────────────────
    public function getOrder(string $shiprocketOrderId): array
    {
        $res = $this->http()->get("/orders/show/{$shiprocketOrderId}");
        if (!$res->successful()) throw new \Exception('Failed to fetch order: ' . $res->body());
        return $res->json()['data'] ?? $res->json();
    }

    // ── Get order status + AWB + tracking from Shiprocket ─────────
    public function getOrderStatus(string $shiprocketOrderId): array
    {
        $res = $this->http()->get("/orders/show/{$shiprocketOrderId}");
        if (!$res->successful()) throw new \Exception('Failed to fetch order status: ' . $res->body());

        $data     = $res->json()['data'] ?? $res->json();
        $shipment = $data['shipments'] ?? [];

        // Flatten useful fields
        return [
            'status'        => $data['status'] ?? ($shipment['status'] ?? '—'),
            'awb_code'      => $shipment['awb'] ?? ($data['awb_code'] ?? null),
            'courier_name'  => $shipment['courier'] ?? ($data['courier_name'] ?? null),
            'shipped_at'    => $data['shipped_at'] ?? null,
            'delivered_at'  => $data['delivered_at'] ?? null,
            'etd'           => $shipment['etd'] ?? null,
            'raw'           => $data,
        ];
    }

    // ── Generate AWB (assign courier) ─────────────────────────────
    public function generateAWB(int $shipmentId): array
    {
        $res = $this->http()->post('/courier/assign/awb', [
            'shipment_id' => $shipmentId,
        ]);
        if (!$res->successful()) throw new \Exception('AWB generation failed: ' . $res->body());
        return $res->json();
    }

    // ── Schedule pickup ───────────────────────────────────────────
    public function schedulePickup(int $shipmentId): array
    {
        $res = $this->http()->post('/courier/generate/pickup', [
            'shipment_id' => [$shipmentId],
        ]);
        if (!$res->successful()) throw new \Exception('Pickup scheduling failed: ' . $res->body());
        return $res->json();
    }

    // ── Track shipment ────────────────────────────────────────────
    public function trackShipment(string $awb): array
    {
        $res = $this->http()->get("/courier/track/awb/{$awb}");
        if (!$res->successful()) throw new \Exception('Tracking failed: ' . $res->body());
        return $res->json();
    }

    // ── Cancel order ──────────────────────────────────────────────
    public function cancelOrder(array $orderIds): array
    {
        $res = $this->http()->post('/orders/cancel', ['ids' => $orderIds]);
        if (!$res->successful()) throw new \Exception('Cancel failed: ' . $res->body());
        return $res->json();
    }
}
