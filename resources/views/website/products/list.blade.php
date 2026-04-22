@extends('website.layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
    </nav>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm border-0 animate__animated animate__fadeInDown" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px; z-index: 10;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Filters</h5>
                    
                    <form action="{{ route('products.list') }}" method="GET" id="filterForm">
                        <!-- Search -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Search</h6>
                            <input type="text" name="search" class="form-control focus-ring-success" placeholder="Search products..." value="{{ request('search') }}">
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Categories</h6>
                            @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input focus-ring-success category-filter" type="radio" name="category" value="{{ $category->slug }}" id="cat_{{ $category->id }}" {{ request('category') == $category->slug ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-green w-100 btn-sm mb-2">Apply Filters</button>
                        <a href="{{ route('products.list') }}" class="btn btn-outline-secondary w-100 btn-sm">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                <div>
                    <h5 class="mb-0 fw-bold">Showing {{ $products->total() }} Products</h5>
                    <small class="text-muted">Delivery in 20 minutes available for selected areas.</small>
                </div>
                <div class="d-none d-md-flex gap-2 bg-light p-1 rounded-pill view-mode-toggle">
                    <button class="btn btn-sm btn-white rounded-pill active px-3 shadow-sm" id="btn-grid-view"><i class="fa-solid fa-border-all"></i> Grid</button>
                    <button class="btn btn-sm btn-light border-0 rounded-pill px-3 text-muted" id="btn-list-view"><i class="fa-solid fa-list-ul"></i> List</button>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty_products.png') }}" alt="No Products" class="img-fluid mb-4" style="max-height: 220px;" onerror="this.style.display='none'">
                    <h5 class="fw-bold text-dark">No Products Found</h5>
                    <p class="text-muted mb-4">We couldn't find any products matching your selected filters or search criteria.</p>
                    <a href="{{ route('products.list') }}" class="btn btn-outline-success px-4 rounded-pill">Clear All Filters</a>
                </div>
            @else
            <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 g-2 g-md-4" id="product-grid">
                @foreach($products as $product)
                <div class="col product-col">
                    @php
                        $discount = $product->mrp > $product->price ? round((($product->mrp - $product->price) / $product->mrp) * 100) : 0;
                    @endphp
                    @include('website.layouts.partials.product-card', [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'title' => $product->name,
                        'price' => $product->price,
                        'originalPrice' => $product->mrp,
                        'margin' => $discount,
                        'discount' => $discount,
                        'category' => $product->category->name ?? 'General',
                        'image' => $product->image_url,
                        'stock' => $product->stock,
                        'description' => $product->description
                    ])
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center align-items-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@push('styles')
<style>
    /* List View Specific Styles */
    .list-view-active #product-grid {
        flex-direction: column;
    }
    .list-view-active .product-col {
        width: 100% !important;
    }
    .list-view-active .product-card {
        flex-direction: row !important;
        align-items: center;
    }
    .list-view-active .card-img-wrapper {
        width: 250px;
        height: auto !important;
        flex-shrink: 0;
        border-radius: var(--radius-md) 0 0 var(--radius-md) !important;
    }
    .list-view-active .card-img-wrapper img {
        max-height: 140px !important;
    }
    .list-view-active .card-body {
        padding-left: 1.5rem !important;
    }
    .list-view-active .btn-add-cart {
        max-width: 200px;
        margin-top: 0 !important;
    }
    .list-view-active .ajax-cart-form {
        margin-top: 0 !important;
        width: auto !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnGrid = document.getElementById('btn-grid-view');
        const btnList = document.getElementById('btn-list-view');
        const gridContainer = document.getElementById('product-grid');

        // Check local storage for preference
        if(localStorage.getItem('product-view-mode') === 'list') {
            enableListView();
        }

        btnGrid.addEventListener('click', function() {
            gridContainer.parentElement.classList.remove('list-view-active');
            
            btnGrid.className = 'btn btn-sm btn-white rounded-pill active px-3 shadow-sm';
            btnList.className = 'btn btn-sm btn-light border-0 rounded-pill px-3 text-muted';
            
            localStorage.setItem('product-view-mode', 'grid');
        });

        btnList.addEventListener('click', function() {
            enableListView();
        });
        
        // Auto-submit form on category change
        document.querySelectorAll('.category-filter').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Optional: debounce search input auto-submit
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 800);
            });
        }

        function enableListView() {
            gridContainer.parentElement.classList.add('list-view-active');
            
            btnList.className = 'btn btn-sm btn-white rounded-pill active px-3 shadow-sm';
            btnGrid.className = 'btn btn-sm btn-light border-0 rounded-pill px-3 text-muted';
            
            localStorage.setItem('product-view-mode', 'list');
        }
    });
</script>
@endpush
@endsection
