@extends('delivery.layouts.app')
@section('title', 'Active Tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bolder mb-0">Active Tasks</h4>
    <span class="badge bg-primary rounded-pill">{{ $orders->count() }}</span>
</div>

@if($orders->isEmpty())
<div class="text-center py-5">
    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
        <i class="bi bi-patch-check text-muted fs-1"></i>
    </div>
    <h5 class="fw-bold">All caught up!</h5>
    <p class="text-muted">No pending deliveries at the moment.</p>
</div>
@else
    @foreach($orders as $order)
    <div class="card border-0 mb-3 overflow-hidden">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="fw-bold mb-1">#{{ $order->order_number }}</h6>
                    <span class="status-badge status-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-dark">₹{{ number_format($order->total_amount, 2) }}</div>
                    <small class="text-muted">{{ strtoupper($order->payment_method) }}</small>
                </div>
            </div>
            <div class="mb-3">
                <div class="small fw-bold text-dark">{{ $order->customer->name ?? 'Guest' }}</div>
                <div class="small text-muted text-truncate"><i class="bi bi-geo-alt me-1"></i> {{ $order->shipping_address }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('delivery.orders.show', $order->id) }}" class="btn btn-primary btn-sm flex-grow-1">Order Details</a>
                <a href="tel:{{ $order->customer->mobile ?? '' }}" class="btn btn-outline-primary btn-sm rounded-circle"><i class="bi bi-telephone"></i></a>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
