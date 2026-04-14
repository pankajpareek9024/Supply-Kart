@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- ── Page Header ──────────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ auth('admin')->user()->name ?? 'Administrator' }}! Here's what's happening today.</p>
    </div>
    <div class="text-muted" style="font-size:.85rem;">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d M Y') }}
    </div>
</div>

{{-- ── Stat Cards ───────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.orders.index') }}" class="stat-card">
            <div class="stat-icon primary"><i class="bi bi-bag-check"></i></div>
            <div>
                <div class="stat-value">{{ number_format($totalOrders) }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.customers.index') }}" class="stat-card">
            <div class="stat-icon success"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ number_format($totalCustomers) }}</div>
                <div class="stat-label">Total Customers</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.products.index') }}" class="stat-card">
            <div class="stat-icon warning"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-value">{{ number_format($totalProducts) }}</div>
                <div class="stat-label">Total Products</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.payments.index') }}" class="stat-card">
            <div class="stat-icon info"><i class="bi bi-currency-rupee"></i></div>
            <div>
                <div class="stat-value">₹{{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </a>
    </div>
</div>

{{-- ── Charts Row ────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h6 class="admin-card-title"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Daily Sales (Last 7 Days)</h6>
            </div>
            <div class="admin-card-body">
                <canvas id="dailySalesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h6 class="admin-card-title"><i class="bi bi-pie-chart me-2 text-primary"></i>Order Status</h6>
            </div>
            <div class="admin-card-body">
                <canvas id="orderStatusChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="admin-card-title"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Monthly Revenue (Last 12 Months)</h6>
            </div>
            <div class="admin-card-body">
                <canvas id="monthlyRevenueChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Recent Orders ─────────────────────────────────────────────── --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h6 class="admin-card-title"><i class="bi bi-clock-history me-2"></i>Recent Orders</h6>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
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
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><span class="fw-600 text-primary">{{ $order->order_number }}</span></td>
                        <td>
                            <div class="fw-600">{{ $order->customer?->shop_name ?? $order->customer?->owner_name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $order->customer?->mobile ?? '' }}</small>
                        </td>
                        <td><span class="fw-600">₹{{ number_format($order->total_amount, 2) }}</span></td>
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
                        <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-action view">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No orders yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const chartDefaults = {
    responsive: true,
    plugins: { legend: { display: false } },
};

// -- Daily Sales Bar Chart --
const dailyLabels  = @json($dailySales->pluck('date'));
const dailyTotals  = @json($dailySales->pluck('total'));

new Chart(document.getElementById('dailySalesChart'), {
    type: 'bar',
    data: {
        labels  : dailyLabels,
        datasets: [{
            label          : 'Sales (₹)',
            data           : dailyTotals,
            backgroundColor: 'rgba(79,70,229,.25)',
            borderColor    : '#4f46e5',
            borderWidth    : 2,
            borderRadius   : 6,
        }]
    },
    options: {
        ...chartDefaults,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ₹' + Number(ctx.raw).toLocaleString('en-IN') } } },
        scales : { y: { ticks: { callback: v => '₹' + v.toLocaleString('en-IN') } } }
    }
});

// -- Order Status Doughnut --
const statusLabels = @json($orderStatusCounts->keys());
const statusCounts = @json($orderStatusCounts->values());
const statusColors = ['#f59e0b','#3b82f6','#6366f1','#8b5cf6','#10b981','#ef4444'];

new Chart(document.getElementById('orderStatusChart'), {
    type: 'doughnut',
    data: {
        labels  : statusLabels.map(l => l.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())),
        datasets: [{ data: statusCounts, backgroundColor: statusColors, borderWidth: 2 }]
    },
    options: {
        responsive: true,
        cutout    : '65%',
        plugins   : { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
    }
});

// -- Monthly Revenue Line Chart --
const monthlyData = @json($monthlyRevenue);
const monthLabels = monthlyData.map(d => {
    const months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return months[d.month] + ' ' + String(d.year).slice(2);
});
const monthTotals = monthlyData.map(d => d.total);

new Chart(document.getElementById('monthlyRevenueChart'), {
    type: 'line',
    data: {
        labels  : monthLabels,
        datasets: [{
            label          : 'Revenue (₹)',
            data           : monthTotals,
            borderColor    : '#10b981',
            backgroundColor: 'rgba(16,185,129,.1)',
            borderWidth    : 2.5,
            pointRadius    : 4,
            tension        : 0.4,
            fill           : true,
        }]
    },
    options: {
        ...chartDefaults,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ₹' + Number(ctx.raw).toLocaleString('en-IN') } } },
        scales : { y: { ticks: { callback: v => '₹' + v.toLocaleString('en-IN') } } }
    }
});
</script>
@endpush
