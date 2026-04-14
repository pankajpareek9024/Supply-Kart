@extends('delivery.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bolder mb-0">Hello, {{ auth('delivery_boy')->user()->name }}!</h4>
        <p class="text-muted small mb-0">{{ date('l, d F Y') }}</p>
    </div>
    <form action="{{ route('delivery.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-light btn-sm rounded-circle"><i class="bi bi-box-arrow-right"></i></button>
    </form>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="card p-3 text-center border-0" style="background: #eef2ff;">
            <div class="text-primary fw-bold fs-4">{{ $stats['pending'] }}</div>
            <div class="text-muted" style="font-size: 0.65rem;">PENDING</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card p-3 text-center border-0" style="background: #ecfdf5;">
            <div class="text-success fw-bold fs-4">{{ $stats['delivered'] }}</div>
            <div class="text-muted" style="font-size: 0.65rem;">DELIVERED</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card p-3 text-center border-0" style="background: #fffbeb;">
            <div class="text-warning fw-bold fs-4">{{ $stats['today_earnings'] }}</div>
            <div class="text-muted" style="font-size: 0.65rem;">TODAY</div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Active Tasks</h6>
    <a href="{{ route('delivery.orders.index') }}" class="text-decoration-none small">View All</a>
</div>

@if($recentOrders->isEmpty())
<div class="card p-5 text-center">
    <i class="bi bi-emoji-smile text-muted fs-1 mb-2"></i>
    <p class="text-muted mb-0">No active tasks assigned yet.</p>
</div>
@else
    @foreach($recentOrders as $order)
    <div class="card border-0 mb-3 overflow-hidden">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-bold mb-1">#{{ $order->order_number }}</h6>
                    <span class="status-badge status-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-dark">₹{{ number_format($order->total_amount, 2) }}</div>
                    <small class="text-muted">{{ $order->payment_method === 'cod' ? 'CASH' : 'PREPAID' }}</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-geo-alt text-danger"></i>
                <div class="small text-muted text-truncate">{{ $order->shipping_address }}</div>
            </div>
            <div class="d-grid">
                <a href="{{ route('delivery.orders.show', $order->id) }}" class="btn btn-primary btn-sm">Manage Order</a>
            </div>
        </div>
    </div>
    @endforeach
@endif

@endsection
