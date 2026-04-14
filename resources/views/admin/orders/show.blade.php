@extends('admin.layouts.app')
@section('title', 'Order #' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Order #{{ $order->order_number }}</h1>
        <p class="page-subtitle">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-outline-primary">
            <i class="bi bi-download me-1"></i>Invoice PDF
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- Order Items --}}
    <div class="col-lg-8">
        <div class="admin-card mb-3">
            <div class="admin-card-header">
                <h6 class="admin-card-title">Order Items</h6>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}" class="img-thumb">
                                        @else
                                            <div class="img-thumb-placeholder"><i class="bi bi-box"></i></div>
                                        @endif
                                        <div class="fw-600">{{ $item->product->name ?? $item->product_name ?? 'Product Deleted' }}</div>
                                    </div>
                                </td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-600">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-600">Delivery Charge</td>
                                <td class="fw-600">₹{{ number_format($order->delivery_charge, 2) }}</td>
                            </tr>
                            <tr class="table-active">
                                <td colspan="3" class="text-end fw-700 fs-6">Total Amount</td>
                                <td class="fw-700 fs-6 text-primary">₹{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="admin-card mb-3">
            <div class="admin-card-header"><h6 class="admin-card-title"><i class="bi bi-geo-alt me-2"></i>Shipping Address</h6></div>
            <div class="admin-card-body">
                <p class="mb-0">{{ $order->shipping_address }}</p>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Customer Info --}}
        <div class="admin-card mb-3">
            <div class="admin-card-header"><h6 class="admin-card-title">Customer</h6></div>
            <div class="admin-card-body">
                <div class="fw-600 mb-1">{{ $order->customer->shop_name ?? $order->customer->owner_name }}</div>
                <div class="text-muted mb-1"><i class="bi bi-phone me-1"></i>{{ $order->customer->mobile }}</div>
                @if($order->customer->address)
                <div class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $order->customer->address }}</div>
                @endif
            </div>
        </div>

        {{-- Order Status --}}
        <div class="admin-card mb-3">
            <div class="admin-card-header"><h6 class="admin-card-title">Order Status</h6></div>
            <div class="admin-card-body">
                <p>Current: <span class="status-badge {{ $order->status_badge }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></p>
                <select id="orderStatus" class="form-select mb-2">
                    @foreach(['pending','processing','packed','out_for_delivery','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                </select>
                <button id="btnUpdateStatus" class="btn btn-primary w-100"
                    data-url="{{ route('admin.orders.update-status', $order) }}">
                    Update Status
                </button>
                <div class="mt-3">
                    <strong>Payment:</strong>
                    <span class="status-badge {{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                    <span class="ms-2"><strong>Method:</strong> {{ strtoupper($order->payment_method) }}</span>
                    @if($order->delivered_at)
                        <br><span class="text-muted small">Delivered at: {{ $order->delivered_at->format('d M Y, h:i A') }}</span>
                    @endif
                    @if($order->deliveryBoy)
                        <br><span class="text-muted small">Delivered by: {{ $order->deliveryBoy->name }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Assign Delivery Boy --}}
        <div class="admin-card mb-3">
            <div class="admin-card-header"><h6 class="admin-card-title">Delivery Boy</h6></div>
            <div class="admin-card-body">
                @if($order->deliveryBoy)
                    <div class="mb-2"><i class="bi bi-person me-1"></i><strong>{{ $order->deliveryBoy->name }}</strong></div>
                    <div class="text-muted"><i class="bi bi-phone me-1"></i>{{ $order->deliveryBoy->phone }}</div>
                    <hr>
                @endif
                <select id="deliveryBoySelect" class="form-select mb-2">
                    <option value="">Select delivery boy</option>
                    @foreach($deliveryBoys as $boy)
                        <option value="{{ $boy->id }}" {{ optional($order->deliveryBoy)->id === $boy->id ? 'selected' : '' }}>{{ $boy->name }} — {{ $boy->phone }}</option>
                    @endforeach
                </select>
                <button id="btnAssignDelivery" class="btn btn-outline-primary w-100"
                    data-url="{{ route('admin.orders.assign-delivery-boy', $order) }}">
                    <i class="bi bi-bicycle me-1"></i>Assign
                </button>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Update order status
$('#btnUpdateStatus').on('click', function () {
    const url    = $(this).data('url');
    const status = $('#orderStatus').val();

    $.post(url, { status: status }, function (res) {
        if (res.success) toastr.success(res.message);
        else toastr.error(res.message);
    }).fail(() => toastr.error('Failed to update status.'));
});

// Assign delivery boy
$('#btnAssignDelivery').on('click', function () {
    const url    = $(this).data('url');
    const boyId  = $('#deliveryBoySelect').val();
    if (!boyId) { toastr.warning('Please select a delivery boy.'); return; }

    $.post(url, { delivery_boy_id: boyId }, function (res) {
        if (res.success) { toastr.success(res.message); setTimeout(() => location.reload(), 1000); }
        else toastr.error(res.message);
    }).fail(() => toastr.error('Failed to assign.'));
});
</script>
@endpush
