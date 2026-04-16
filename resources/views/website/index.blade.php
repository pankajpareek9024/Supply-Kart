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

    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4 shadow-lg overflow-hidden">
            <div class="carousel-item active">
                <div class="p-5 text-white d-flex flex-column justify-content-center align-items-center position-relative animate__animated animate__fadeIn" style="min-height: 450px; background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
                    <div style="opacity: 0.65; background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.4));"></div>
                    <div class="position-relative z-1 text-center animate__animated animate__fadeInUp animate__delay-1s">
                        <span class="badge bg-primary mb-3 px-3 py-2 rounded-pill fs-6 border border-2 border-light">🚚 Modern Wholesale Platform</span>
                        <h1 class="display-4 fw-bolder mb-3 text-shadow-sm">Grow Your Business<br>with SupplyKart</h1>
                        <p class="fs-5 mb-4 text-light" style="max-width: 600px; margin: 0 auto;">Discover unbeatable prices, curated products, and seamless delivery for your shop. Experience the next-gen B2B marketplace.</p>
                        <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg fw-bold px-5 py-3 rounded-pill shadow-lg shadow-glow">Start Shopping <i class="fa-solid fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="p-5 text-white d-flex flex-column justify-content-center align-items-center position-relative animate__animated animate__fadeIn" style="min-height: 450px; background-image: url('https://images.unsplash.com/photo-1534452203293-494d7ddbf7e0?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0.65; background: linear-gradient(to right, rgba(0,0,0,0.4), rgba(0,0,0,0.8));"></div>
                    <div class="position-relative z-1 text-center animate__animated animate__fadeInUp animate__delay-1s">
                        <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fs-6 border border-2 border-light"><i class="fa-solid fa-bolt text-danger"></i> Fast & Reliable</span>
                        <h1 class="display-4 fw-bolder mb-3 text-warning text-shadow-sm">Lightning Delivery<br>for Your Store</h1>
                        <p class="fs-5 mb-4 text-light" style="max-width: 600px; margin: 0 auto;">Never miss a sale. We deliver your inventory on time, every time, with our trusted logistics network.</p>
                        <a href="{{ route('products.list') }}" class="btn btn-warning btn-lg fw-bold px-5 py-3 rounded-pill shadow-lg">Browse Catalog <i class="fa-solid fa-bolt ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark p-3 rounded-circle shadow" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark p-3 rounded-circle shadow" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Shop by Category -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h4 class="fw-bold mb-0">Shop by Category</h4>
        <a href="{{ route('categories.index') }}" class="text-decoration-none text-primary-green fw-medium">View All Categories <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>
    
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
        @foreach($categories as $category)
        <div class="col">
            <a href="{{ route('category.products', $category->slug) }}" class="text-decoration-none text-dark">
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
        <h4 class="fw-bold mb-0">Featured For You</h4>
        <a href="{{ route('products.list') }}" class="text-decoration-none text-primary-green fw-medium">View All <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
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
