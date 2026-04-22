@extends('website.layouts.app')

@section('content')

<!-- Hero Slider -->
<div class="container mb-5 mt-4">
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm border-0 animate__animated animate__fadeInDown" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div id="heroCarousel" class="carousel slide carousel-fade mb-md-4 mb-2" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4 shadow-sm overflow-hidden hero-slider-container">
            <div class="carousel-item active">
                <div class="p-4 p-md-5 text-white d-flex flex-column justify-content-center align-items-start align-items-md-center position-relative animate__animated animate__fadeIn hero-slide-item" style="background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
                    <div class="hero-overlay"></div>
                    <div class="position-relative z-1 animate__animated animate__fadeInUp animate__delay-1s w-100 pe-4 text-md-center container">
                        <span class="badge bg-primary mb-2 mb-md-3 px-2 px-md-3 py-1 py-md-2 rounded-pill hero-badge">🚀 Fastest Delivery</span>
                        <h2 class="fw-bolder mb-2 mb-md-3 text-shadow-sm hero-title text-white">Wholesale Delivery<br>in Minutes</h2>
                        <p class="fs-5 mb-4 text-light d-none d-md-block" style="max-width: 600px; margin: 0 auto;">Discover unbeatable prices, curated products, and seamless delivery for your shop. Experience the next-gen B2B marketplace.</p>
                        <a href="{{ route('products.list') }}" class="btn btn-sm btn-md-lg btn-primary fw-bold rounded-pill px-3 px-md-5 py-md-3 shadow-lg shadow-glow">Order Now <i class="fa-solid fa-arrow-right ms-1 ms-md-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="p-4 p-md-5 text-white d-flex flex-column justify-content-center align-items-start align-items-md-center position-relative animate__animated animate__fadeIn hero-slide-item" style="background-image: url('{{ asset('images/hero_banner_2.jpg') }}'); background-size: cover; background-position: center;">
                    <div class="hero-overlay"></div>
                    <div class="position-relative z-1 animate__animated animate__fadeInUp animate__delay-1s w-100 pe-4 text-md-center container">
                        <span class="badge bg-warning text-dark mb-2 mb-md-3 px-2 px-md-3 py-1 py-md-2 rounded-pill hero-badge"><i class="fa-solid fa-tags"></i> Top Margins</span>
                        <h2 class="fw-bolder mb-2 mb-md-3 text-warning text-shadow-sm hero-title">Best B2B Prices<br>Guaranteed</h2>
                        <p class="fs-5 mb-4 text-light d-none d-md-block" style="max-width: 600px; margin: 0 auto;">Never miss a sale. We deliver your inventory on time, every time, with our trusted logistics network.</p>
                        <a href="{{ route('products.list') }}" class="btn btn-sm btn-md-lg btn-warning fw-bold rounded-pill text-dark px-3 px-md-5 py-md-3 shadow-lg">Browse <i class="fa-solid fa-bolt ms-1 ms-md-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Indicators (Dots instead of arrows for modern look) -->
        <div class="carousel-indicators mb-2 mb-md-4 p-0">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active rounded-circle bg-white shadow-sm" aria-current="true" aria-label="Slide 1" style="width: 10px; height: 10px;"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" class="rounded-circle bg-white shadow-sm mx-1" aria-label="Slide 2" style="width: 10px; height: 10px;"></button>
        </div>
    </div>
</div>

<!-- Shop by Category -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h4 class="fw-bold mb-0">Shop by Category</h4>
        <a href="{{ route('categories.index') }}" class="text-decoration-none text-primary-green fw-medium">View All Categories <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>
    
    <div class="row row-cols-4 row-cols-md-4 row-cols-lg-6 g-3 mobile-scroll-row hide-scrollbar pb-2">
        @foreach($categories as $category)
        <div class="col mobile-scroll-col px-1 px-md-2">
            <a href="javascript:void(0)" onclick="loadCategoryProducts({{ $category->id }}, '{{ $category->name }}')" class="text-decoration-none text-dark category-filter-link" data-id="{{ $category->id }}">
                @include('website.layouts.partials.category-card', [
                    'title' => $category->name, 
                    'image' => $category->image_url
                ])
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- Featured Products -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h4 class="fw-bold mb-0" id="featured-products-title">Featured For You</h4>
        <a href="{{ route('products.list') }}" class="text-decoration-none text-primary-green fw-medium">View All <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2 g-md-4" id="products-grid">
        @foreach($featuredProducts as $product)
        <div class="col">
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
</div>

<!-- Info Banners -->
<div class="container mb-5 pb-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="p-4 rounded-4 d-flex align-items-center h-100 position-relative overflow-hidden shadow-sm hover-elevate" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
                <div class="position-absolute top-0 end-0 opacity-10 mt-n4 me-n4">
                    <i class="fa-solid fa-truck-fast text-success" style="font-size: 10rem;"></i>
                </div>
                <div class="bg-white p-3 rounded-circle shadow-sm me-4 position-relative z-1">
                    <i class="fa-solid fa-truck-fast fs-2 text-primary-green"></i>
                </div>
                <div class="position-relative z-1">
                    <h4 class="fw-bolder mb-1 text-dark">Lightning Fast 20-Min Delivery</h4>
                    <p class="text-muted mb-0 fs-6">Get your inventory restocked within minutes. No more waiting.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 rounded-4 d-flex align-items-center h-100 position-relative overflow-hidden shadow-sm hover-elevate" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
                <div class="position-absolute top-0 end-0 opacity-10 mt-n4 me-n4">
                    <i class="fa-solid fa-tags text-warning" style="font-size: 10rem;"></i>
                </div>
                <div class="bg-white p-3 rounded-circle shadow-sm me-4 position-relative z-1">
                    <i class="fa-solid fa-tags fs-2 text-primary-orange"></i>
                </div>
                <div class="position-relative z-1">
                    <h4 class="fw-bolder mb-1 text-dark">Wholesale Margins & Discounts</h4>
                    <p class="text-muted mb-0 fs-6">Enjoy B2B exclusive pricing to maximize your shop's profit.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-shadow-sm {
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    .hover-elevate {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
</style>

@endsection

@push('scripts')
<script>
    function loadCategoryProducts(categoryId, categoryName) {
        // Show loading state
        const grid = document.getElementById('products-grid');
        const title = document.getElementById('featured-products-title');
        
        grid.style.opacity = '0.5';
        grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        // Highlight selected category
        document.querySelectorAll('.category-filter-link').forEach(link => {
            link.querySelector('.card').classList.remove('border-success', 'shadow');
            if(link.getAttribute('data-id') == categoryId) {
                link.querySelector('.card').classList.add('border-success', 'shadow');
            }
        });

        title.innerText = categoryName + ' Products';

        fetch(`/ajax/category/${categoryId}/products`)
            .then(response => response.json())
            .then(data => {
                grid.innerHTML = data.html;
                grid.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                grid.innerHTML = '<div class="col-12 text-center py-4 text-danger"><p>Failed to load products. Please try again later.</p></div>';
                grid.style.opacity = '1';
            });
    }
</script>
@endpush
