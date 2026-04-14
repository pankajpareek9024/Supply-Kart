<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sales Report</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size:11px; color:#1e293b; }
    .header { display:flex; justify-content:space-between; padding:20px; border-bottom:2px solid #4f46e5; }
    .brand { font-size:18px; font-weight:700; color:#4f46e5; }
    .report-title { font-size:14px; font-weight:700; text-align:right; }
    .period { font-size:10px; color:#64748b; text-align:right; }
    table { width:100%; border-collapse:collapse; margin:20px 0; }
    th { background:#4f46e5; color:#fff; padding:7px 10px; text-align:left; font-size:10px; }
    td { padding:6px 10px; border-bottom:1px solid #e2e8f0; font-size:10px; }
    tr:nth-child(even) td { background:#f8fafc; }
    .total-row td { background:#1e293b; color:#fff; font-weight:700; }
    .footer { text-align:center; font-size:9px; color:#94a3b8; padding:20px; border-top:1px solid #e2e8f0; }
    .summary { display:flex; gap:20px; padding:15px 20px; background:#f8fafc; }
    .summary-item { text-align:center; }
    .summary-value { font-size:16px; font-weight:700; color:#4f46e5; }
    .summary-label { font-size:9px; color:#64748b; }
</style>
</head>
<body>
<div class="header">
    <div>
        <div class="brand">⬡ SupplyKart</div>
        <div style="font-size:9px;color:#64748b;">B2B Wholesale Platform</div>
    </div>
    <div>
        <div class="report-title">SALES REPORT</div>
        <div class="period">{{ $dateFrom->format('d M Y') }} — {{ $dateTo->format('d M Y') }}</div>
        <div class="period">Generated: {{ now()->format('d M Y, h:i A') }}</div>
    </div>
</div>

<div class="summary">
    <div class="summary-item">
        <div class="summary-value">{{ $orders->count() }}</div>
        <div class="summary-label">Total Orders</div>
    </div>
    <div class="summary-item">
        <div class="summary-value">₹{{ number_format($totalRevenue, 2) }}</div>
        <div class="summary-label">Total Revenue</div>
    </div>
    <div class="summary-item">
        <div class="summary-value">{{ $orders->where('payment_status', 'paid')->count() }}</div>
        <div class="summary-label">Paid Orders</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Order Number</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $i => $order)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->customer->shop_name ?? $order->customer->owner_name ?? 'N/A' }}</td>
            <td>{{ $order->created_at->format('d M Y') }}</td>
            <td>₹{{ number_format($order->total_amount, 2) }}</td>
            <td>{{ ucfirst($order->payment_status) }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$order->status)) }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4">TOTAL</td>
            <td>₹{{ number_format($totalRevenue, 2) }}</td>
            <td colspan="2">{{ $orders->count() }} orders</td>
        </tr>
    </tbody>
</table>

<div class="footer">
    Confidential — SupplyKart Admin Panel | Generated on {{ now()->format('d M Y') }}
</div>
</body>
</html>
