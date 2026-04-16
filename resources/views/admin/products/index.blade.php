@extends('admin.layouts.app')
@section('title', 'Products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Products</h1>
        <p class="page-subtitle">Manage your product catalog</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Product
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="filters-bar">
    <input type="text" name="search" class="form-control" style="max-width:240px;" placeholder="Search products…" value="{{ request('search') }}">
    <select name="category_id" class="form-select" style="max-width:180px;">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <select name="status" class="form-select" style="max-width:140px;">
        <option value="">All Status</option>
        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    <select name="stock" class="form-select" style="max-width:160px;">
        <option value="">All Stock</option>
        <option value="in_stock"  {{ request('stock') === 'in_stock'  ? 'selected' : '' }}>In Stock</option>
        <option value="out_stock" {{ request('stock') === 'out_stock' ? 'selected' : '' }}>Out of Stock</option>
    </select>
    <button class="btn btn-primary btn-sm">Filter</button>
    @if(request()->hasAny(['search','category_id','status','stock']))
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    @endif
</form>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="img-thumb" alt="{{ $product->name }}">
                            @else
                                <div class="img-thumb-placeholder"><i class="bi bi-box"></i></div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-600">{{ $product->name }}</span><br>
                            <small class="text-muted">{{ $product->unit }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $product->category->name ?? '—' }}</span></td>
                        <td>
                            <span class="fw-600">₹{{ number_format($product->price, 2) }}</span>
                            @if($product->mrp && $product->mrp > $product->price)
                                <br><small class="text-muted text-decoration-line-through">₹{{ number_format($product->mrp, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="status-badge danger">Out of Stock</span>
                            @elseif($product->stock <= $product->low_stock_threshold)
                                <span class="status-badge warning">{{ $product->stock }} Low Stock</span>
                            @else
                                <span class="status-badge success">{{ $product->stock }} In Stock</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-action edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn-action delete delete-btn"
                                    data-url="{{ route('admin.products.destroy', $product) }}"
                                    data-name="{{ $product->name }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn toggle-status-btn p-0"
                                    data-url="{{ route('admin.products.toggle-status', $product) }}">
                                    @if($product->is_active)
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
                            <i class="bi bi-box-seam fs-1 d-block mb-2"></i>No products found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</small>
        {{ $products->links() }}
    </div>
    @endif
</div>

@endsection
