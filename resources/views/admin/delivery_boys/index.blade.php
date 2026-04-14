@extends('admin.layouts.app')
@section('title', 'Delivery Boys')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Delivery Boys</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Delivery Boys</h1>
        <p class="page-subtitle">Manage delivery agents</p>
    </div>
    <button class="btn btn-primary" id="btnAddDeliveryBoy">
        <i class="bi bi-plus-lg me-1"></i>Add Delivery Boy
    </button>
</div>

<form method="GET" class="filters-bar">
    <input type="text" name="search" class="form-control" style="max-width:280px;" placeholder="Search name or phone…" value="{{ request('search') }}">
    <button class="btn btn-primary btn-sm">Search</button>
    @if(request('search'))
        <a href="{{ route('admin.delivery-boys.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    @endif
</form>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Deliveries</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryBoys as $boy)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($deliveryBoys->currentPage() - 1) * $deliveryBoys->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="admin-avatar" style="width:36px;height:36px;font-size:.75rem;background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                                    {{ strtoupper(substr($boy->name, 0, 1)) }}
                                </div>
                                <span class="fw-600">{{ $boy->name }}</span>
                            </div>
                        </td>
                        <td>{{ $boy->phone }}</td>
                        <td class="text-muted">{{ \Str::limit($boy->address, 40) ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark">{{ $boy->orders_count }}</span></td>
                        <td>
                            <span class="status-badge {{ $boy->is_active ? 'active' : 'inactive' }}">
                                {{ $boy->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <button class="btn-action edit edit-db-btn"
                                    data-url="{{ route('admin.delivery-boys.edit', $boy) }}"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action delete delete-btn"
                                    data-url="{{ route('admin.delivery-boys.destroy', $boy) }}"
                                    data-name="{{ $boy->name }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn toggle-status-btn p-0"
                                    data-url="{{ route('admin.delivery-boys.toggle-status', $boy) }}">
                                    @if($boy->is_active)
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-bicycle fs-1 d-block mb-2"></i>No delivery boys added yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($deliveryBoys->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $deliveryBoys->firstItem() }}–{{ $deliveryBoys->lastItem() }} of {{ $deliveryBoys->total() }}</small>
        {{ $deliveryBoys->links() }}
    </div>
    @endif
</div>

{{-- Add / Edit Modal --}}
<div class="modal fade" id="deliveryBoyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dbModalLabel">Add Delivery Boy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deliveryBoyForm">
                @csrf
                <input type="hidden" id="deliveryBoyId" name="_boy_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="dbName" class="form-control" placeholder="e.g. Ravi Kumar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="dbEmail" class="form-control" placeholder="ravi@supplykart.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="dbPhone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" id="passwordLabel">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="dbPassword" class="form-control" placeholder="••••••••">
                        <small class="text-muted" id="passwordHelp">Required for new delivery boys.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Address</label>
                        <textarea name="address" id="dbAddress" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="dbStatus" value="1" checked>
                        <label class="form-check-label" for="dbStatus">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Delivery Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const modal = new bootstrap.Modal($('#deliveryBoyModal'));
    const form = $('#deliveryBoyForm');

    $('#btnAddDeliveryBoy').click(function() {
        form[0].reset();
        $('#deliveryBoyId').val('');
        $('#dbModalLabel').text('Add Delivery Partner');
        $('#dbPassword').attr('required', true);
        $('#passwordHelp').text('Required for new delivery partners.');
        $('#passwordLabel').find('.text-danger').show();
        modal.show();
    });

    $(document).on('click', '.edit-db-btn', function() {
        const url = $(this).data('url');
        $.get(url, function(res) {
            if (res.success) {
                $('#deliveryBoyId').val(res.boy.id);
                $('#dbName').val(res.boy.name);
                $('#dbEmail').val(res.boy.email);
                $('#dbPhone').val(res.boy.phone);
                $('#dbAddress').val(res.boy.address);
                $('#dbStatus').prop('checked', res.boy.is_active);
                $('#dbModalLabel').text('Edit Delivery Partner');
                $('#dbPassword').attr('required', false);
                $('#passwordHelp').text('Leave blank to keep current password.');
                $('#passwordLabel').find('.text-danger').hide();
                modal.show();
            }
        });
    });

    form.submit(function(e) {
        e.preventDefault();
        const id = $('#deliveryBoyId').val();
        const url = id ? `{{ url('admin/delivery-boys') }}/${id}` : `{{ route('admin.delivery-boys.store') }}`;
        const formData = new FormData(this);
        
        // Handle boolean switch
        formData.set('is_active', $('#dbStatus').is(':checked') ? '1' : '0');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                let msg = '';
                for (let key in errors) {
                    msg += errors[key][0] + '\n';
                }
                alert(msg || 'An error occurred.');
            }
        });
    });

    $(document).on('click', '.toggle-status-btn', function() {
        const url = $(this).data('url');
        const btn = $(this);
        $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
            if (res.success) {
                location.reload();
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        const url = $(this).data('url');
        if (confirm('Are you sure you want to delete this delivery partner?')) {
            $.ajax({
                url: url,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        location.reload();
                    }
                }
            });
        }
    });
});
</script>
@endpush
