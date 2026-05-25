<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }
        .page { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 24px; border-bottom: 2px solid #0082C3; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-icon { width: 48px; height: 48px; background: #0082C3; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .brand-icon svg { width: 28px; height: 28px; stroke: white; fill: none; }
        .brand-name { font-size: 22px; font-weight: 900; color: #0082C3; letter-spacing: -0.5px; }
        .brand-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .invoice-meta { text-align: right; }
        .invoice-number { font-size: 20px; font-weight: 800; color: #0082C3; }
        .invoice-type { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
        .invoice-date { font-size: 12px; color: #374151; margin-top: 6px; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 6px; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .addresses { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; }
        .address-box { background: #f9fafb; border-radius: 10px; padding: 16px; }
        .address-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6b7280; margin-bottom: 8px; }
        .address-name { font-size: 14px; font-weight: 700; color: #111827; }
        .address-detail { font-size: 12px; color: #6b7280; margin-top: 3px; line-height: 1.5; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .items-table thead tr { background: #0082C3; color: white; }
        .items-table thead th { padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .items-table thead th:last-child, .items-table thead th:nth-child(2), .items-table thead th:nth-child(3) { text-align: right; }
        .items-table tbody tr { border-bottom: 1px solid #f3f4f6; }
        .items-table tbody tr:hover { background: #f9fafb; }
        .items-table tbody td { padding: 10px 14px; font-size: 13px; }
        .items-table tbody td:nth-child(2), .items-table tbody td:nth-child(3), .items-table tbody td:last-child { text-align: right; }
        .totals { display: flex; justify-content: flex-end; margin-bottom: 32px; }
        .totals-box { width: 280px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
        .totals-row:last-child { border-bottom: none; font-size: 16px; font-weight: 800; color: #0082C3; padding-top: 10px; }
        .totals-label { color: #6b7280; }
        .notes-section { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
        .notes-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #0369a1; margin-bottom: 6px; }
        .notes-text { font-size: 12px; color: #0c4a6e; line-height: 1.6; }
        .footer { text-align: center; padding-top: 24px; border-top: 1px solid #e5e7eb; color: #9ca3af; font-size: 11px; }
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
            .page { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Print button --}}
    <div class="no-print" style="text-align:right;margin-bottom:20px">
        <button onclick="window.print()" style="background:#0082C3;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer">🖨 Print Invoice</button>
        <button onclick="window.close()" style="background:#6b7280;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;margin-left:8px">✕ Close</button>
    </div>

    {{-- Header --}}
    <div class="header">
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
            </div>
            <div>
                <div class="brand-name">DECATHLON</div>
                <div class="brand-sub">Sports & Outdoor Equipment</div>
            </div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            <div class="invoice-type">{{ ucwords(str_replace('_', ' ', $invoice->type)) }}</div>
            <div class="invoice-date">Date: {{ $invoice->invoice_date?->format('d M Y') }}</div>
            @if($invoice->due_date)
            <div class="invoice-date">Due: {{ $invoice->due_date->format('d M Y') }}</div>
            @endif
            <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
        </div>
    </div>

    {{-- Addresses --}}
    <div class="addresses">
        <div class="address-box">
            <div class="address-label">From</div>
            <div class="address-name">Decathlon India</div>
            <div class="address-detail">sports@decathlon.in<br>www.decathlon.in</div>
        </div>
        <div class="address-box">
            <div class="address-label">Bill To</div>
            <div class="address-name">{{ $invoice->customer_name }}</div>
            <div class="address-detail">
                {{ $invoice->customer_email }}<br>
                @if($invoice->customer_phone){{ $invoice->customer_phone }}<br>@endif
                @if($invoice->customer_gstin)GSTIN: {{ $invoice->customer_gstin }}<br>@endif
                @if($invoice->billing_address){{ $invoice->billing_address }}@endif
            </div>
        </div>
    </div>

    {{-- Items --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:50%">Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @if($invoice->items && count($invoice->items))
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item['name'] ?? $item['product_name'] ?? '—' }}</td>
                    <td>{{ $item['quantity'] ?? 1 }}</td>
                    <td>₹{{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 2) }}</td>
                    <td>₹{{ number_format(($item['unit_price'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:20px">No items</td></tr>
            @endif
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div class="totals-box">
            <div class="totals-row"><span class="totals-label">Subtotal</span><span>₹{{ number_format($invoice->subtotal, 2) }}</span></div>
            @if($invoice->discount_amount > 0)
            <div class="totals-row"><span class="totals-label">Discount</span><span style="color:#dc2626">-₹{{ number_format($invoice->discount_amount, 2) }}</span></div>
            @endif
            @if($invoice->tax_amount > 0)
            <div class="totals-row"><span class="totals-label">Tax (GST)</span><span>₹{{ number_format($invoice->tax_amount, 2) }}</span></div>
            @endif
            @if($invoice->shipping_amount > 0)
            <div class="totals-row"><span class="totals-label">Shipping</span><span>₹{{ number_format($invoice->shipping_amount, 2) }}</span></div>
            @endif
            <div class="totals-row"><span>Total</span><span>₹{{ number_format($invoice->total_amount, 2) }}</span></div>
            @if($invoice->paid_amount > 0)
            <div class="totals-row" style="font-size:13px;color:#047857"><span class="totals-label">Paid</span><span>₹{{ number_format($invoice->paid_amount, 2) }}</span></div>
            @endif
            @if($invoice->due_amount > 0)
            <div class="totals-row" style="font-size:13px;color:#dc2626"><span class="totals-label">Balance Due</span><span>₹{{ number_format($invoice->due_amount, 2) }}</span></div>
            @endif
        </div>
    </div>

    {{-- Notes --}}
    @if($invoice->notes)
    <div class="notes-section">
        <div class="notes-label">Notes</div>
        <div class="notes-text">{{ $invoice->notes }}</div>
    </div>
    @endif

    @if($invoice->terms)
    <div class="notes-section" style="background:#fefce8;border-color:#fde68a">
        <div class="notes-label" style="color:#92400e">Terms & Conditions</div>
        <div class="notes-text" style="color:#78350f">{{ $invoice->terms }}</div>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Thank you for your business! · Decathlon India · Generated on {{ now()->format('d M Y, h:i A') }}</p>
        @if($invoice->order_id)
        <p style="margin-top:4px">Order Reference: {{ $invoice->order?->order_number ?? 'N/A' }}</p>
        @endif
    </div>

</div>
</body>
</html>
