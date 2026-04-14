<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice #{{ $order->order_number }}</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size:12px; color:#1e293b; background:#fff; }
    .invoice-wrap { padding:30px; }
    .header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:30px; }
    .brand-name { font-size:22px; font-weight:700; color:#4f46e5; }
    .brand-tag  { font-size:10px; color:#64748b; }
    .inv-title  { font-size:18px; font-weight:700; text-align:right; }
    .inv-info   { font-size:11px; color:#64748b; text-align:right; margin-top:4px; }
    .divider    { border:none; border-top:2px solid #4f46e5; margin:20px 0; }
    .two-col    { display:flex; gap:30px; margin-bottom:20px; }
    .col-block  { flex:1; }
    .col-label  { font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:6px; letter-spacing:.05em; }
    .col-val    { font-size:12px; color:#1e293b; line-height:1.6; }
    table       { width:100%; border-collapse:collapse; margin-bottom:20px; }
    th          { background:#f1f5f9; padding:8px 10px; font-size:10px; text-transform:uppercase; color:#64748b; text-align:left; }
    td          { padding:8px 10px; border-bottom:1px solid #e2e8f0; }
    tr:last-child td { border-bottom:none; }
    .totals     { width:250px; margin-left:auto; }
    .totals td  { padding:6px 10px; }
    .totals .grand td { background:#4f46e5; color:#fff; font-weight:700; border-radius:4px; }
    .badge      { display:inline-block; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
    .badge-success { background:#d1fae5; color:#065f46; }
    .badge-warning { background:#fef3c7; color:#92400e; }
    .badge-danger  { background:#fee2e2; color:#991b1b; }
    .footer     { margin-top:30px; padding-top:20px; border-top:1px solid #e2e8f0; text-align:center; color:#94a3b8; font-size:10px; }
</style>
</head>
<body>
<div class="invoice-wrap">
    <div class="header">
        <div>
            <div class="brand-name">⬡ SupplyKart</div>
            <div class="brand-tag">B2B Wholesale Platform</div>
        </div>
        <div>
            <div class="inv-title">TAX INVOICE</div>
            <div class="inv-info"># {{ $order->order_number }}</div>
            <div class="inv-info">Date: {{ $order->created_at->format('d M Y') }}</div>
        </div>
    </div>

    <hr class="divider">

    <div class="two-col">
        <div class="col-block">
            <div class="col-label">Bill To</div>
            <div class="col-val">
                <strong>{{ $order->customer->shop_name ?? $order->customer->owner_name }}</strong><br>
                @if($order->customer->owner_name && $order->customer->shop_name)
                    {{ $order->customer->owner_name }}<br>
                @endif
                {{ $order->customer->mobile }}<br>
                @if($order->customer->gst_number)
                    GST: {{ $order->customer->gst_number }}
                @endif
            </div>
        </div>
        <div class="col-block">
            <div class="col-label">Ship To</div>
            <div class="col-val">{{ $order->shipping_address }}</div>
        </div>
        <div class="col-block">
            <div class="col-label">Order Info</div>
            <div class="col-val">
                <strong>Method:</strong> {{ strtoupper($order->payment_method) }}<br>
                <strong>Payment:</strong>
                <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }}">
                    {{ ucfirst($order->payment_status) }}
                </span><br>
                <strong>Status:</strong> {{ ucfirst(str_replace('_',' ',$order->status)) }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->product->name ?? 'Product' }}</td>
                <td>₹{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td>₹{{ number_format($order->total_amount - $order->delivery_charge, 2) }}</td>
        </tr>
        <tr>
            <td>Delivery Charge</td>
            <td>{{ $order->delivery_charge > 0 ? '₹'.number_format($order->delivery_charge,2) : 'FREE' }}</td>
        </tr>
        <tr class="grand">
            <td><strong>Grand Total</strong></td>
            <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
        </tr>
    </table>

    @if($order->delivery_boy_name)
    <p style="font-size:11px;color:#64748b;">
        <strong>Delivery Agent:</strong> {{ $order->delivery_boy_name }} — {{ $order->delivery_boy_phone }}
    </p>
    @endif

    <div class="footer">
        <p>Thank you for your business! | support@supplykart.com</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</div>
</body>
</html>
