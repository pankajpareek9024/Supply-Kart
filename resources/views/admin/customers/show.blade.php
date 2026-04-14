@extends('admin.layouts.app')
@section('title', 'Customer Detail')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">{{ $customer->shop_name ?? $customer->owner_name }}</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Customer Detail</h1>
        <p class="page-subtitle">View profile and order history</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-3">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="admin-card text-center p-4">
            <div class="admin-avatar mx-auto mb-3" style="width:64px;height:64px;font-size:1.5rem;">
                {{ strtoupper(substr($customer->owner_name ?? $customer->shop_name ?? 'C', 0, 1)) }}
            </div>
            <h5 class="fw-700">{{ $customer->shop_name ?? '—' }}</h5>
            <p class="text-muted mb-3">{{ $customer->owner_name ?? '—' }}</p>
            <span class="status-badge {{ $customer->is_active ? 'active' : 'inactive' }} mb-3 d-inline-block">
                {{ $customer->is_active ? 'Active' : 'Inactive' }}
            </span>

            <hr>
            <div class="text-start">
                <div class="mb-2"><i class="bi bi-phone me-2 text-primary"></i><strong>Mobile:</strong> {{ $customer->mobile }}</div>
                @if($customer->email)
                <div class="mb-2"><i class="bi bi-envelope me-2 text-primary"></i><strong>Email:</strong> {{ $customer->email }}</div>
                @endif
                @if($customer->gst_number)
                <div class="mb-2"><i class="bi bi-file-text me-2 text-primary"></i><strong>GST:</strong> {{ $customer->gst_number }}</div>
                @endif
                @if($customer->address)
                <div class="mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i><strong>Address:</strong> {{ $customer->address }}</div>
                @endif
                <div class="mb-2"><i class="bi bi-calendar me-2 text-primary"></i><strong>Joined:</strong> {{ $customer->created_at->format('d M Y') }}</div>
            </div>

            <hr>
            <button class="btn toggle-status-btn btn-sm {{ $customer->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} w-100"
                data-url="{{ route('admin.customers.toggle-status', $customer) }}">
                {{ $customer->is_active ? 'Deactivate Customer' : 'Activate Customer' }}
            </button>
        </div>
    </div>

    {{-- Order History --}}
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="admin-card-title">Order History</h6>
                <span class="badge bg-light text-dark">{{ $orders->total() }} orders</span>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="fw-600 text-primary">{{ $order->order_number }}</td>
                                <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
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
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-action view">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders placed yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($orders->hasPages())
            <div class="admin-card-body border-top">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
