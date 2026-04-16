@extends('admin.layouts.app')
@section('title', 'Delivery Boy Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.delivery-boys.index') }}">Delivery Boys</a></li>
    <li class="breadcrumb-item active">View Details</li>
@endsection

@section('content')

<div class="row g-3 mb-4">
    <!-- Profile Info -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-body text-center py-5">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $deliveryBoy->name }}</h4>
                <p class="text-muted"><i class="bi bi-telephone ms-1"></i> {{ $deliveryBoy->phone }}</p>

                <div class="mt-4">
                    <span class="badge {{ $deliveryBoy->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $deliveryBoy->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="admin-card-body border-top">
                <p class="text-muted small fw-bold mb-2">EMAIL ADDRESS</p>
                <p>{{ $deliveryBoy->email }}</p>

                <p class="text-muted small fw-bold mb-2">REGISTERED ON</p>
                <p>{{ $deliveryBoy->created_at->format('d M Y') }}</p>

                <p class="text-muted small fw-bold mb-2">ADDRESS</p>
                <p>{{ $deliveryBoy->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="col-lg-8">
        <div class="row g-3">
            <div class="col-sm-6">
                <div class="admin-card">
                    <div class="admin-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted mb-0">Today's Orders</h6>
                            <i class="bi bi-box text-primary fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-primary">{{ $stats['today_orders'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="admin-card">
                    <div class="admin-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted mb-0">Today's Cash Collection</h6>
                            <i class="bi bi-cash-stack text-success fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-success">₹{{ number_format($stats['today_collection'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="admin-card">
                    <div class="admin-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted mb-0">Total Orders</h6>
                            <i class="bi bi-boxes text-info fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-info">{{ $stats['total_orders'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="admin-card">
                    <div class="admin-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted mb-0">Total Cash Collection</h6>
                            <i class="bi bi-currency-rupee text-warning fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-warning">₹{{ number_format($stats['total_collection'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders assigned to this delivery boy -->
        <div class="admin-card mt-3">
            <div class="admin-card-header">
                <h6 class="admin-card-title">Recent Orders</h6>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->order_number }}</a></td>
                                    <td>{{ $order->customer->shop_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No orders assigned yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
