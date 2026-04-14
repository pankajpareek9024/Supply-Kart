@extends('admin.layouts.app')
@section('title', 'Payments')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Payment Records</h1>
        <p class="page-subtitle">Track all payment transactions</p>
    </div>
</div>

<form method="GET" class="filters-bar">
    <input type="text" name="search" class="form-control" style="max-width:200px;" placeholder="Order number…" value="{{ request('search') }}">
    <select name="payment_status" class="form-select" style="max-width:160px;">
        <option value="">All Payments</option>
        <option value="paid"    {{ request('payment_status') === 'paid'    ? 'selected' : '' }}>Paid</option>
        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="failed"  {{ request('payment_status') === 'failed'  ? 'selected' : '' }}>Failed</option>
    </select>
    <input type="date" name="date_from" class="form-control" style="max-width:170px;" value="{{ request('date_from') }}">
    <input type="date" name="date_to"   class="form-control" style="max-width:170px;" value="{{ request('date_to') }}">
    <button class="btn btn-primary btn-sm">Filter</button>
    @if(request()->hasAny(['search','payment_status','date_from','date_to']))
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    @endif
</form>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td class="fw-600 text-primary">{{ $payment->order_number }}</td>
                        <td>
                            <div class="fw-600">{{ $payment->customer->shop_name ?? $payment->customer->owner_name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $payment->customer->mobile ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark text-uppercase">{{ $payment->payment_method }}</span>
                        </td>
                        <td class="fw-600">₹{{ number_format($payment->total_amount, 2) }}</td>
                        <td>
                            <span class="status-badge {{ $payment->payment_status === 'paid' ? 'success' : ($payment->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge {{ $payment->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $payment->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $payment->id) }}" class="btn-action view" title="View Order">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-credit-card fs-1 d-block mb-2"></i>No payment records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payments->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $payments->firstItem() }}–{{ $payments->lastItem() }} of {{ $payments->total() }}</small>
        {{ $payments->links() }}
    </div>
    @endif
</div>

@endsection
