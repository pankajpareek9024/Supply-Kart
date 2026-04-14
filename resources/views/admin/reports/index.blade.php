@extends('admin.layouts.app')
@section('title', 'Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Sales Reports</h1>
        <p class="page-subtitle">Analyze revenue and orders over time</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.export-csv', request()->query()) }}" class="btn btn-outline-success">
            <i class="bi bi-filetype-csv me-1"></i>Export CSV
        </a>
        <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" class="btn btn-outline-danger">
            <i class="bi bi-filetype-pdf me-1"></i>Export PDF
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="filters-bar mb-4">
    <input type="date" name="date_from" class="form-control" style="max-width:180px;" value="{{ $dateFrom->format('Y-m-d') }}">
    <input type="date" name="date_to"   class="form-control" style="max-width:180px;" value="{{ $dateTo->format('Y-m-d') }}">
    <button class="btn btn-primary btn-sm">Generate</button>
</form>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="bi bi-bag-check"></i></div>
            <div>
                <div class="stat-value">{{ number_format($summary['total_orders']) }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon success"><i class="bi bi-currency-rupee"></i></div>
            <div>
                <div class="stat-value">₹{{ number_format($summary['total_revenue'], 0) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon info"><i class="bi bi-calculator"></i></div>
            <div>
                <div class="stat-value">₹{{ number_format($summary['avg_order'], 0) }}</div>
                <div class="stat-label">Avg Order Value</div>
            </div>
        </div>
    </div>
</div>

{{-- Revenue Chart --}}
<div class="admin-card mb-4">
    <div class="admin-card-header">
        <h6 class="admin-card-title">Revenue Trend ({{ $dateFrom->format('d M') }} – {{ $dateTo->format('d M Y') }})</h6>
    </div>
    <div class="admin-card-body">
        <canvas id="reportChart" height="80"></canvas>
    </div>
</div>

{{-- Sales Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h6 class="admin-card-title">Daily Breakdown</h6>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr><th>Date</th><th>Orders</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    @forelse($salesData as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                        <td>{{ $row->orders }}</td>
                        <td class="fw-600">₹{{ number_format($row->revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">No data for selected period.</td>
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
const labels   = @json($salesData->pluck('date'));
const revenues = @json($salesData->pluck('revenue'));
const orders   = @json($salesData->pluck('orders'));

new Chart(document.getElementById('reportChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label           : 'Revenue (₹)',
                data            : revenues,
                borderColor     : '#4f46e5',
                backgroundColor : 'rgba(79,70,229,.1)',
                borderWidth     : 2.5,
                tension         : 0.4,
                fill            : true,
                yAxisID         : 'y',
            },
            {
                label      : 'Orders',
                data       : orders,
                borderColor: '#10b981',
                borderWidth: 2,
                tension    : 0.4,
                fill       : false,
                yAxisID    : 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top' },
            tooltip: { callbacks: { label: ctx => ctx.dataset.yAxisID === 'y' ? ' ₹' + Number(ctx.raw).toLocaleString('en-IN') : ' ' + ctx.raw + ' orders' } }
        },
        scales: {
            y:  { position: 'left',  ticks: { callback: v => '₹' + v.toLocaleString('en-IN') } },
            y1: { position: 'right', grid: { drawOnChartArea: false } }
        }
    }
});
</script>
@endpush
