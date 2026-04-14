@extends('admin.layouts.app')
@section('title', 'Customer Reviews')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Customer Reviews</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Customer Reviews</h1>
        <p class="page-subtitle">Feedback on delivery partners and services</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Delivery Partner</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Visibility</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td class="fw-bold">#{{ $review->order->order_number ?? 'N/A' }}</td>
                        <td>{{ $review->customer->name ?? 'Guest' }}</td>
                        <td>{{ $review->deliveryBoy->name ?? 'Deleted' }}</td>
                        <td>
                            <div class="text-warning">
                                @foreach(range(1, 5) as $i)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" title="{{ $review->comment }}">
                                {{ \Str::limit($review->comment, 30) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge {{ $review->is_visible ? 'active' : 'inactive' }}">
                                {{ $review->is_visible ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $review->created_at->format('d M, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn-action edit toggle-visibility" data-url="{{ route('admin.reviews.toggle-visibility', $review) }}" title="Toggle Visibility">
                                    <i class="bi bi-eye{{ $review->is_visible ? '-slash' : '' }}"></i>
                                </button>
                                <button class="btn-action delete delete-btn" data-url="{{ route('admin.reviews.destroy', $review) }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No reviews found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reviews->hasPages())
    <div class="admin-card-body border-top">
        {{ $reviews->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-visibility').click(function() {
        const url = $(this).data('url');
        $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
            if (res.success) {
                location.reload();
            }
        });
    });

    $('.delete-btn').click(function() {
        if (confirm('Are you sure you want to delete this review?')) {
            const url = $(this).data('url');
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
