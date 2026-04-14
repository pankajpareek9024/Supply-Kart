@extends('website.layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-pill shadow-sm d-inline-flex border">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-house px-1"></i> Home</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Your Cart</li>
        </ol>
    </nav>

    <h2 class="fw-bolder mb-4 text-dark"><i class="fa-solid fa-cart-shopping me-2 text-primary-orange"></i>Your Wholesale Cart</h2>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <!-- Empty Cart State -->
        <div class="card border-0 shadow-sm rounded-5 overflow-hidden mt-4">
            <div class="card-body p-5 text-center my-5">
                <div class="mb-4 position-relative d-inline-block">
                    <div class="bg-light rounded-circle position-absolute top-50 start-50 translate-middle" style="width: 150px; height: 150px; z-index: 0;"></div>
                    <i class="fa-solid fa-cart-flatbed text-muted position-relative z-1" style="font-size: 6rem; opacity: 0.5;"></i>
                </div>
                <h3 class="fw-bolder text-dark mb-3">Your cart is empty</h3>
                <p class="text-muted fs-5 mb-5" style="max-width: 500px; margin: 0 auto;">Looks like you haven't added any wholesale products to your cart yet. Let's fix that!</p>
                <a href="{{ route('products.list') }}" class="btn btn-green btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg shadow-glow">Start Shopping Now <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    @else
        <!-- Cart with Items -->
        <div class="row g-4">
            <!-- Left Column: Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Included Items ({{ $cartItems->count() }})</h5>
                        <span class="badge bg-light text-success border border-success border-opacity-25 px-3 py-2 rounded-pill"><i class="fa-solid fa-bolt me-1"></i> 20 Min Delivery</span>
                    </div>
                    <div class="card-body p-0">
                        @php $totalPrice = 0; $totalMrp = 0; @endphp
                        @foreach($cartItems as $item)
                        @php 
                            $totalPrice += $item->product->price * $item->quantity; 
                            $totalMrp += $item->product->mrp * $item->quantity;
                        @endphp
                        <!-- Product Item -->
                        <div class="p-4 border-bottom hover-bg-light transition-smooth">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-3 col-4">
                                    <div class="bg-light rounded-3 p-2 text-center" style="height: 100px;">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="img-fluid h-100 object-fit-contain mix-blend-multiply">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-8">
                                    <h6 class="fw-bold mb-1 text-dark">{{ $item->product->name }}</h6>
                                    <div class="text-muted small mb-2">Unit: {{ $item->product->unit }}</div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="fw-bolder fs-5 text-dark me-2">₹{{ number_format($item->product->price, 2) }}</span>
                                        @if($item->product->mrp > $item->product->price)
                                        <span class="text-muted text-decoration-line-through small">₹{{ number_format($item->product->mrp, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-5 col-12 mt-3 mt-sm-0 d-flex justify-content-between justify-content-sm-end align-items-center gap-3">
                                    <!-- Quantity Control -->
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center border rounded-pill p-1 bg-white shadow-sm ajax-cart-update-form" style="width: 120px;">
                                        @csrf
                                        <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="btn btn-sm btn-white rounded-circle text-muted border-0" style="width: 32px; height: 32px;" {{ $item->quantity <= 1 ? 'disabled' : '' }}><i class="fa-solid fa-minus"></i></button>
                                        <input type="text" class="form-control form-control-sm text-center border-0 fw-bold px-0 qty-input" value="{{ $item->quantity }}" readonly style="background: transparent;">
                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="btn btn-sm btn-white rounded-circle text-primary-green border border-success border-opacity-25 shadow-sm" style="width: 32px; height: 32px;"><i class="fa-solid fa-plus"></i></button>
                                    </form>
                                    
                                    <!-- Remove -->
                                    <a href="{{ route('cart.remove', $item->id) }}" class="btn btn-light text-danger rounded-circle d-flex align-items-center justify-content-center p-0 shadow-sm border hover-danger" style="width: 40px; height: 40px;" title="Remove part">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bolder mb-4 text-dark border-bottom pb-3">Bill Details</h5>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total MRP</span>
                            <span id="summary-total-mrp">₹{{ number_format($totalMrp, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fw-medium text-success">
                            <span>Wholesale Discount</span>
                            <span id="summary-discount">- ₹{{ number_format($totalMrp - $totalPrice, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted border-bottom pb-3">
                            <span>Delivery Partner Fee</span>
                            @php $deliveryCharge = $totalPrice < 999 && $totalPrice > 0 ? 90 : 0; @endphp
                            <span id="summary-delivery-charge" class="{!! $deliveryCharge == 0 ? 'text-success fw-bold' : 'text-danger fw-bold' !!}">
                                {!! $deliveryCharge == 0 ? 'FREE <small class="text-muted text-decoration-line-through">₹50</small>' : '₹90' !!}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <div>
                                <h4 id="summary-final-total" class="fw-bolder mb-0 text-dark">₹{{ number_format($totalPrice + $deliveryCharge, 2) }}</h4>
                                <small class="text-muted">Includes all taxes & delivery</small>
                            </div>
                        </div>

                        <div class="bg-success bg-opacity-10 rounded-3 p-3 mb-4 border border-success border-opacity-25 d-flex align-items-center shadow-sm">
                            <i class="fa-solid fa-money-bill-trend-up text-success fs-3 me-3"></i>
                            <div>
                                <div class="fw-bold text-success">Your Total Savings</div>
                                <div id="summary-savings" class="fw-bolder text-dark fs-5">₹{{ number_format($totalMrp - $totalPrice, 2) }}</div>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn btn-orange btn-lg w-100 fw-bold rounded-pill shadow-lg py-3">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
