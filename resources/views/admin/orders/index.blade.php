@extends('admin.layouts.app')
@section('title', 'Orders')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">Manage all customer orders</p>
    </div>
    <span class="badge bg-primary fs-6">{{ $orders->total() }} total</span>
</div>

<form method="GET" class="filters-bar">
    <input type="text" name="search" class="form-control" style="max-width:220px;" placeholder="Order number…" value="{{ request('search') }}">
    <select name="status" class="form-select" style="max-width:170px;">
        <option value="">All Status</option>
        @foreach(['pending','processing','packed', 'assigned', 'picked_up', 'out_for_delivery','delivered','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <select name="payment_status" class="form-select" style="max-width:160px;">
        <option value="">All Payments</option>
        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="paid"    {{ request('payment_status') === 'paid'    ? 'selected' : '' }}>Paid</option>
        <option value="failed"  {{ request('payment_status') === 'failed'  ? 'selected' : '' }}>Failed</option>
    </select>
    <input type="date" name="date_from" class="form-control" style="max-width:160px;" value="{{ request('date_from') }}">
    <input type="date" name="date_to"   class="form-control" style="max-width:160px;" value="{{ request('date_to') }}">
    <button class="btn btn-primary btn-sm">Filter</button>
    @if(request()->hasAny(['search','status','payment_status','date_from','date_to']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    @endif
</form>

@if(isset($deliveryBoySummary) && $deliveryBoySummary->count() > 0)
<div class="row g-3 mb-4">
    <div class="col-12">
        <h5 class="fw-bold mb-3"><i class="bi bi-bicycle me-2 text-primary"></i>Today's Delivery Summary</h5>
    </div>
    @foreach($deliveryBoySummary as $boy)
    <div class="col-md-4 col-sm-6">
        <div class="admin-card text-center p-3">
            <div class="fs-5 fw-bold text-dark">{{ $boy['name'] }}</div>
            <div class="d-flex justify-content-center gap-3 mt-2">
                <div class="text-muted small border-end pe-3">
                    <div class="fs-4 fw-bolder text-primary mb-1">{{ $boy['total_delivered'] }}</div>
                    Delivered
                </div>
                <div class="text-muted small ps-2">
                    <div class="fs-4 fw-bolder text-success mb-1">₹{{ number_format($boy['total_cash']) }}</div>
                    Cash Collected
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif


<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Delivery Boy</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-600 text-primary">{{ $order->order_number }}</td>
                        <td>
                            <div class="fw-600">{{ $order->customer->shop_name ?? $order->customer->owner_name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $order->customer->mobile ?? '' }}</small>
                        </td>
                        <td class="fw-600">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="status-badge {{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge {{ $order->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($order->deliveryBoy)
                                <span class="text-muted">{{ $order->deliveryBoy->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-action view" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-bag fs-1 d-block mb-2"></i>No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</small>
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
