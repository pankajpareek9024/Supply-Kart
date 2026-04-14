@extends('admin.layouts.app')
@section('title', 'Customers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Customers</h1>
        <p class="page-subtitle">Manage registered B2B customers</p>
    </div>
    <span class="badge bg-primary fs-6">{{ $customers->total() }} total</span>
</div>

<form method="GET" class="filters-bar">
    <input type="text" name="search" class="form-control" style="max-width:280px;" placeholder="Search by shop, name, mobile…" value="{{ request('search') }}">
    <select name="status" class="form-select" style="max-width:150px;">
        <option value="">All Status</option>
        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    <button class="btn btn-primary btn-sm">Filter</button>
    @if(request()->hasAny(['search','status']))
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    @endif
</form>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Shop / Customer</th>
                        <th>Mobile</th>
                        <th>GST Number</th>
                        <th>Orders</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="admin-avatar" style="width:36px;height:36px;font-size:.75rem;">
                                    {{ strtoupper(substr($customer->owner_name ?? $customer->shop_name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-600">{{ $customer->shop_name ?? '—' }}</div>
                                    <small class="text-muted">{{ $customer->owner_name ?? '—' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $customer->mobile }}</td>
                        <td>{{ $customer->gst_number ?? '—' }}</td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-600">{{ $customer->orders_count }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $customer->is_active ? 'active' : 'inactive' }}">
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $customer->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn-action view" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn toggle-status-btn p-0"
                                    data-url="{{ route('admin.customers.toggle-status', $customer) }}"
                                    title="{{ $customer->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($customer->is_active)
                                        <i class="bi bi-toggle-on text-success fs-5"></i>
                                    @else
                                        <i class="bi bi-toggle-off text-muted fs-5"></i>
                                    @endif
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>No customers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ $customers->total() }}</small>
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection
