@extends('delivery.layouts.app')
@section('title', 'History')

@section('content')
<div class="mb-4">
    <h4 class="fw-bolder mb-0">Delivery History</h4>
    <p class="text-muted small">Your completed deliveries</p>
</div>

@if($orders->isEmpty())
<div class="text-center py-5">
    <i class="bi bi-clock-history text-muted fs-1 mb-2"></i>
    <p class="text-muted">No history found.</p>
</div>
@else
    @foreach($orders as $order)
    <div class="card border-0 mb-3 overflow-hidden opacity-75">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="fw-bold mb-1">#{{ $order->order_number }}</h6>
                    <div class="small text-muted">{{ $order->delivered_at ? $order->delivered_at->format('d M Y, h:i A') : $order->updated_at->format('d M Y') }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-success">₹{{ number_format($order->total_amount, 2) }}</div>
                    <span class="status-badge status-delivered">Delivered</span>
                </div>
            </div>
            <hr class="my-2 opacity-5">
            <div class="small text-muted"><i class="bi bi-person me-1"></i> {{ $order->customer->name ?? 'Guest' }}</div>
        </div>
    </div>
    @endforeach
    
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endif
@endsection
