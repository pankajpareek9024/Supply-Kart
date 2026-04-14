@extends('admin.layouts.app')
@section('title', 'Admin Accounts')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Admin Accounts</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Admin Accounts</h1>
        <p class="page-subtitle">Manage all administrator accounts</p>
    </div>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i>Add Admin
    </a>
</div>

{{-- Search --}}
<form method="GET" class="filters-bar mb-3">
    <input type="text" name="search" class="form-control" style="max-width:300px;"
        placeholder="Search by name or email…" value="{{ request('search') }}">
    <button class="btn btn-primary btn-sm">Search</button>
    @if(request('search'))
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    @endif
</form>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Administrator</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}</td>

                        {{-- Avatar + Name --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($admin->avatar)
                                    <img src="{{ Storage::url($admin->avatar) }}"
                                         class="rounded-circle"
                                         style="width:38px;height:38px;object-fit:cover;border:2px solid var(--border);"
                                         alt="{{ $admin->name }}">
                                @else
                                    <div class="admin-avatar" style="width:38px;height:38px;font-size:.78rem;">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-600">
                                        {{ $admin->name }}
                                        @if($admin->id === auth('admin')->id())
                                            <span class="badge ms-1" style="background:var(--primary-light);color:var(--primary);font-size:.65rem;">You</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $admin->role === 'super_admin' ? 'Super Admin' : 'Editor' }}</small>
                                </div>
                            </div>
                        </td>

                        <td>{{ $admin->email }}</td>

                        <td>
                            @if($admin->role === 'super_admin')
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-600" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.02em;">Super Admin</span>
                            @else
                                <span class="badge bg-info bg-opacity-10 text-info fw-600" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.02em;">Editor</span>
                            @endif
                        </td>

                        {{-- Status badge + toggle --}}
                        <td>
                            <span class="status-badge {{ $admin->is_active ? 'active' : 'inactive' }}">
                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td class="text-muted">{{ $admin->created_at ? $admin->created_at->format('d M Y') : 'N/A' }}</td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                {{-- Edit --}}
                                <a href="{{ route('admin.admins.edit', $admin) }}"
                                   class="btn-action edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Toggle Status (disabled for self) --}}
                                @if($admin->id !== auth('admin')->id())
                                    <button class="btn toggle-status-btn p-0"
                                        data-url="{{ route('admin.admins.toggle-status', $admin) }}"
                                        title="{{ $admin->is_active ? 'Deactivate' : 'Activate' }}">
                                        @if($admin->is_active)
                                            <i class="bi bi-toggle-on text-success fs-5"></i>
                                        @else
                                            <i class="bi bi-toggle-off text-muted fs-5"></i>
                                        @endif
                                    </button>
                                @else
                                    {{-- Self: show greyed-out toggle --}}
                                    <span class="d-inline-flex align-items-center px-1" title="Cannot deactivate yourself">
                                        <i class="bi bi-toggle-on text-success fs-5 opacity-25"></i>
                                    </span>
                                @endif

                                {{-- Delete (disabled for self) --}}
                                @if($admin->id !== auth('admin')->id())
                                    <button class="btn-action delete delete-btn"
                                        data-url="{{ route('admin.admins.destroy', $admin) }}"
                                        data-name="{{ $admin->name }}"
                                        title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @else
                                    <span class="btn-action delete opacity-25" title="Cannot delete yourself" style="cursor:not-allowed;">
                                        <i class="bi bi-trash"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-lock fs-1 d-block mb-2"></i>
                            No admin accounts found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($admins->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
            Showing {{ $admins->firstItem() }}–{{ $admins->lastItem() }} of {{ $admins->total() }} admins
        </small>
        {{ $admins->links() }}
    </div>
    @endif
</div>

{{-- Info banner --}}
<div class="mt-3 p-3 rounded d-flex gap-2 align-items-start"
     style="background:#eff6ff;border:1px solid #bfdbfe;font-size:.82rem;color:#1e40af;">
    <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
    <div>
        <strong>Security Note:</strong>
        You cannot delete or deactivate your own account.
        Admin accounts marked as <em>Inactive</em> are blocked from logging in.
    </div>
</div>

@endsection
