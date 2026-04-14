@extends('admin.layouts.app')
@section('title', 'Categories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Categories</h1>
        <p class="page-subtitle">Manage product categories</p>
    </div>
    <button class="btn btn-primary" id="btnAddCategory">
        <i class="bi bi-plus-lg me-1"></i>Add Category
    </button>
</div>

{{-- Filters --}}
<form method="GET" class="filters-bar mb-3">
    <input type="text" name="search" class="form-control" style="max-width:260px;" placeholder="Search categories…" value="{{ request('search') }}">
    <button class="btn btn-primary btn-sm">Search</button>
    @if(request('search'))
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    @endif
</form>

{{-- Table --}}
<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td>
                            @if($cat->image)
                                <img src="{{ Storage::url($cat->image) }}" class="img-thumb" alt="{{ $cat->name }}">
                            @else
                                <div class="img-thumb-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td><span class="fw-600">{{ $cat->name }}</span><br><small class="text-muted">{{ $cat->slug }}</small></td>
                        <td><span class="badge bg-light text-dark">{{ $cat->products_count }}</span></td>
                        <td>
                            <span class="status-badge {{ $cat->is_active ? 'active' : 'inactive' }}">
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $cat->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <button class="btn-action edit edit-category-btn"
                                    data-url="{{ route('admin.categories.edit', $cat) }}"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action delete delete-btn"
                                    data-url="{{ route('admin.categories.destroy', $cat) }}"
                                    data-name="{{ $cat->name }}"
                                    title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn toggle-status-btn p-0"
                                    data-url="{{ route('admin.categories.toggle-status', $cat) }}"
                                    title="{{ $cat->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($cat->is_active)
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
                            <i class="bi bi-grid-3x3-gap fs-1 d-block mb-2"></i>No categories found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $categories->firstItem() }}–{{ $categories->lastItem() }} of {{ $categories->total() }}</small>
        {{ $categories->links() }}
    </div>
    @endif
</div>

{{-- ── Add / Edit Modal ── --}}
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="catModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="categoryId" name="_category_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="catName" class="form-control" placeholder="e.g. Stationery" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Image</label>
                        <input type="file" name="image" id="catImage" class="form-control" accept="image/*">
                        <img id="categoryPreview" src="" class="mt-2 img-thumb" style="display:none;width:80px;height:80px;">
                    </div>
                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" name="is_active" id="catStatus" value="1" checked>
                        <label class="form-check-label" for="catStatus">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
