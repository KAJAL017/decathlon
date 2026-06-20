<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: #0082C3; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .order-box { background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .order-box h2 { margin: 0 0 15px; font-size: 18px; color: #333; }
        .order-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .order-item:last-child { border-bottom: none; }
        .total { font-weight: bold; font-size: 18px; color: #0082C3; }
        .btn { display: inline-block; background: #0082C3; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmed!</h1>
        </div>

        <div class="content">
            <p>Hi {{ $order->customer_name }},</p>
            <p>Thank you for your order. We've received your order and it's being processed.</p>

            <div class="order-box">
                <h2>Order #{{ $order->order_number }}</h2>

                @foreach($order->items as $item)
                <div class="order-item">
                    <div>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                            <br><small style="color: #666;">{{ $item->variant_name }}</small>
                        @endif
                        <br><small>Qty: {{ $item->quantity }}</small>
                    </div>
                    <div>₹{{ number_format($item->total_price, 2) }}</div>
                </div>
                @endforeach

                <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #ddd;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px; color: #22c55e;">
                        <span>Discount</span>
                        <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Shipping</span>
                        <span>{{ $order->shipping_amount > 0 ? '₹'.number_format($order->shipping_amount, 2) : 'Free' }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Tax</span>
                        <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 2px solid #ddd;">
                        <span class="total">Total</span>
                        <span class="total">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="order-box">
                <h2>Shipping Address</h2>
                <p style="margin: 0; line-height: 1.6;">
                    {{ $order->shipping_name }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_pincode }}<br>
                    {{ $order->shipping_country }}
                </p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('checkout.success', ['orderNumber' => $order->order_number, 'token' => $order->view_token]) }}" class="btn">View Order</a>
            </div>
        </div>

        <div class="footer">
            <p>If you have any questions, please contact our support team.</p>
            <p>&copy; {{ date('Y') }} Decathlon. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
