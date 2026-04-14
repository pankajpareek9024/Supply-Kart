@extends('admin.layouts.app')
@section('title', 'Add Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Add Product</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Add Product</h1>
        <p class="page-subtitle">Fill in the details to create a new product</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="admin-card mb-3">
                <div class="admin-card-header"><h6 class="admin-card-title">Product Information</h6></div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="e.g. A4 Paper Ream" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Unit <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                <option value="pcs"  {{ old('unit','pcs') === 'pcs'  ? 'selected' : '' }}>Pieces (pcs)</option>
                                <option value="box"  {{ old('unit') === 'box'  ? 'selected' : '' }}>Box</option>
                                <option value="kg"   {{ old('unit') === 'kg'   ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="ltr"  {{ old('unit') === 'ltr'  ? 'selected' : '' }}>Litre (ltr)</option>
                                <option value="pack" {{ old('unit') === 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="set"  {{ old('unit') === 'set'  ? 'selected' : '' }}>Set</option>
                            </select>
                            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" step="0.01" min="0" placeholder="0.00" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-600">MRP (₹)</label>
                            <input type="number" name="mrp" class="form-control @error('mrp') is-invalid @enderror"
                                value="{{ old('mrp') }}" step="0.01" min="0" placeholder="0.00">
                            @error('mrp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Product description…">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-3">
                <div class="admin-card-header"><h6 class="admin-card-title">Product Image</h6></div>
                <div class="admin-card-body text-center">
                    <img id="productPreview" src="" class="mb-3" style="display:none;max-width:100%;border-radius:8px;max-height:200px;object-fit:contain;">
                    <div id="productPlaceholder" class="img-thumb-placeholder mx-auto mb-3" style="width:100%;height:150px;font-size:2.5rem;">
                        <i class="bi bi-image"></i>
                    </div>
                    <input type="file" name="image" id="productImage" class="form-control img-input" data-preview="productPreview" accept="image/*">
                    <small class="text-muted d-block mt-1">Max 2MB. JPG, PNG, WebP</small>
                </div>
            </div>

            <div class="admin-card mb-3">
                <div class="admin-card-header"><h6 class="admin-card-title">Visibility</h6></div>
                <div class="admin-card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="productStatus" value="1" checked>
                        <label class="form-check-label fw-600" for="productStatus">Active (visible to customers)</label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-lg me-1"></i>Save Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.getElementById('productImage').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('productPreview').src = e.target.result;
            document.getElementById('productPreview').style.display = 'block';
            document.getElementById('productPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
