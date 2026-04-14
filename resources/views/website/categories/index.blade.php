@extends('website.layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb bg-white p-3 rounded-pill shadow-sm d-inline-flex border">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-house px-1"></i> Home</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">All Categories</li>
        </ol>
    </nav>

    <div class="text-center mb-5">
        <h2 class="fw-bolder mb-3 text-dark"><i class="fa-solid fa-layer-group me-2 text-primary-orange"></i> Shop by Category</h2>
        <p class="text-muted fs-5" style="max-width: 600px; margin: 0 auto;">Browse our extensive wholesale catalog organized perfectly for your convenience.</p>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 z-1 position-relative">
        @foreach($categories as $category)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center hover-lift category-card bg-white position-relative overflow-hidden group">
                <a href="{{ route('category.products', $category->slug) }}" class="text-decoration-none text-dark d-block p-4 h-100 overlay-link">
                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center border" style="width: 100px; height: 100px; transition: var(--transition-smooth);">
                        <img src="{{ $category->image_url }}" 
                            alt="{{ $category->name }}" 
                            class="img-fluid object-fit-contain mix-blend-multiply" 
                            style="width: 60px; height: 60px;">
                    </div>
                    <h5 class="fw-bold mb-1 group-hover-green">{{ $category->name }}</h5>
                    <small class="text-muted">{{ $category->products_count }} Products</small>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.hover-lift {
    transition: var(--transition-bounce);
}
.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg) !important;
    border: 1px solid rgba(5, 150, 105, 0.2) !important;
}
.hover-lift:hover .bg-light {
    background: var(--light-green) !important;
    transform: scale(1.1);
}
.group-hover-green {
    transition: var(--transition-smooth);
}
.hover-lift:hover .group-hover-green {
    color: var(--primary-green) !important;
}
</style>
@endsection
