@extends('website.layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded-pill shadow-sm d-inline-flex border">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium hover-success"><i class="fa-solid fa-house px-1"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.list') }}" class="text-decoration-none text-muted fw-medium hover-success">Products</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="bg-white rounded-5 shadow-sm p-4 p-md-5 border-0 hover-elevate transition-smooth">
        <div class="row g-5 align-items-center">
            <!-- Product Images -->
            <div class="col-md-5">
                <div class="position-relative border-0 rounded-4 p-5 text-center d-flex align-items-center justify-content-center bg-light transition-smooth product-image-container zoom-container overflow-hidden" style="height: 480px; box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);">
                    <div class="position-absolute top-0 start-0 m-4 z-1">
                        <span class="badge-20min shadow-sm px-3 py-2 fs-6"><i class="fa-solid fa-bolt me-1 text-danger"></i> 20 MIN</span>
                    </div>
                    @if($product->mrp > $product->price)
                    <div class="position-absolute top-0 end-0 m-4 z-1">
                        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm">{{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}% OFF</span>
                    </div>
                    @endif
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid product-img-main drop-shadow-xl" style="max-height: 380px; object-fit: contain; transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-7">
                <div class="d-flex flex-column h-100 pe-lg-4">
                    <div class="text-muted mb-2 text-uppercase tracking-wider fw-bolder" style="letter-spacing: 1px; font-size: 0.8rem;">Category: <span class="text-primary-green">{{ $product->category->name ?? 'General' }}</span></div>
                    <h1 class="fw-bolder mb-3 text-dark lh-tight" style="font-size: 2.5rem; letter-spacing: -0.5px;">{{ $product->name }}</h1>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-baseline mb-2">
                            <span class="fs-1 fw-bolder text-dark me-3">₹{{ number_format($product->price, 2) }}</span>
                            @if($product->mrp > $product->price)
                            <span class="fs-5 text-muted text-decoration-line-through fw-medium">₹{{ number_format($product->mrp, 2) }}</span>
                            @endif
                        </div>
                        @if($product->mrp > $product->price)
                        <div class="text-success fw-bolder p-3 bg-success bg-opacity-10 rounded-pill d-inline-block border border-success border-opacity-25 shadow-sm">
                            <i class="fa-solid fa-arrow-trend-up me-2"></i> Your Margin: {{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}% (₹{{ number_format($product->mrp - $product->price, 2) }} Profit)
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="ajax-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="mb-5">
                            <h6 class="fw-bold text-dark mb-3">Unit</h6>
                            <div class="btn-group gap-3 w-100 d-flex flex-wrap" role="group">
                                <input type="radio" class="btn-check" name="unit_type" id="btnradio1" autocomplete="off" checked>
                                <label class="btn btn-outline-success py-3 px-4 rounded-4 fw-bolder flex-grow-1 border-2" for="btnradio1">1 {{ $product->unit }}</label>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold text-dark mb-3">Quantity</h6>
                            <div class="d-flex align-items-center p-1 border rounded-pill bg-white shadow-sm" style="width: fit-content;">
                                <button type="button" onclick="document.getElementById('qty').value = Math.max(1, parseInt(document.getElementById('qty').value) - 1)" class="btn btn-white rounded-circle d-flex align-items-center justify-content-center text-dark hover-bg-light transition-smooth" style="width: 44px; height: 44px;"><i class="fa-solid fa-minus"></i></button>
                                <input type="number" name="quantity" id="qty" class="form-control text-center border-0 fw-bolder fs-5 text-dark px-3" value="1" min="1" max="{{ $product->stock }}" style="width: 80px; background: transparent;">
                                <button type="button" onclick="document.getElementById('qty').value = parseInt(document.getElementById('qty').value) + 1" class="btn btn-white rounded-circle d-flex align-items-center justify-content-center text-primary-green hover-bg-light transition-smooth shadow-sm border" style="width: 44px; height: 44px;"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <small class="text-muted mt-2 d-block">{{ $product->stock }} {{ $product->unit }} in stock</small>
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            @auth('customer')
                                @if($product->stock <= 0)
                                    <button class="btn btn-secondary btn-lg flex-grow-1 fw-bold rounded-pill py-3" disabled>
                                        <i class="fa-solid fa-ban me-2"></i> Out of Stock
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-green btn-lg flex-grow-1 fw-bold rounded-pill py-3 shadow-lg shadow-glow"><i class="fa-solid fa-cart-plus me-2"></i> Add to Cart</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-green btn-lg flex-grow-1 fw-bold rounded-pill py-3 shadow-lg"><i class="fa-solid fa-lock me-2"></i> Login to Buy</a>
                            @endauth
                        </div>
                    </form>

                    <div class="mt-4 pt-4 border-top">
                        <h5 class="fw-bolder mb-3 text-dark">Product Description</h5>
                        <p class="text-muted fs-6 lh-lg mb-4">{{ $product->description }}</p>
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge bg-light text-dark border p-2 px-3 fw-medium"><i class="fa-solid fa-check text-success me-2"></i> 100% Genuine</span>
                            <span class="badge bg-light text-dark border p-2 px-3 fw-medium"><i class="fa-solid fa-building text-success me-2"></i> Sourced Directly</span>
                            <span class="badge bg-light text-dark border p-2 px-3 fw-medium"><i class="fa-solid fa-file-invoice text-success me-2"></i> GST Bill Provided</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-success:hover { color: var(--primary-green) !important; }
    .drop-shadow-xl { filter: drop-shadow(0 20px 30px rgba(0,0,0,0.15)); }
    .zoom-container:hover .product-img-main { transform: scale(1.1); }
    .lh-tight { line-height: 1.2; }
    .shadow-glow { box-shadow: 0 0 20px rgba(5, 150, 105, 0.4); }
</style>
@endsection
