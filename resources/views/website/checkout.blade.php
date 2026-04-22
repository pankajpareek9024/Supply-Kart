@extends('website.layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-pill shadow-sm d-inline-flex border">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-house px-1"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted fw-medium">Cart</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <h2 class="fw-bolder mb-4 text-dark"><i class="fa-solid fa-credit-card me-2 text-primary-orange"></i>Secure Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <form id="checkoutForm" action="{{ route('orders.place') }}" method="POST">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Shipping Address -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-location-dot me-2 text-primary-green"></i>Shipping Address</h5>
                    </div>
                    <div class="card-body p-4">
                        <textarea class="form-control focus-ring-success" name="shipping_address" rows="3" required placeholder="Enter delivery address">{{ Auth::guard('customer')->user()->address }}</textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-wallet me-2 text-primary-green"></i>Payment Method</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input ms-1" type="radio" name="payment_method" id="payCOD" value="cod" checked>
                            <label class="form-check-label ms-2 fw-medium d-flex align-items-center" for="payCOD">
                                Cash on Delivery (COD) <i class="fa-solid fa-money-bill-1-wave ms-auto text-success fs-4"></i>
                            </label>
                        </div>
                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input ms-1" type="radio" name="payment_method" id="payOnline" value="online">
                            <label class="form-check-label ms-2 fw-medium d-flex align-items-center" for="payOnline">
                                Pay Online (UPI / Card / NetBanking) <i class="fa-brands fa-cc-visa ms-auto text-primary fs-4"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bolder mb-4 text-dark border-bottom pb-3">Order Summary</h5>
                        
                        @php $totalPrice = 0; @endphp
                        @foreach($cartItems as $item)
                        @php $totalPrice += $item->product->price * $item->quantity; @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-truncate" style="max-width: 200px;">{{ $item->quantity }}x {{ $item->product->name }}</span>
                            <span>₹{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                        @endforeach
                        
                        <hr>
                        
                        @php $deliveryCharge = $totalPrice < 999 && $totalPrice > 0 ? 90 : 0; @endphp
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Delivery</span>
                            <span class="{!! $deliveryCharge == 0 ? 'text-success fw-bold' : 'text-danger fw-bold' !!}">
                                {!! $deliveryCharge == 0 ? 'FREE' : '₹90' !!}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-end mb-4 border-top pt-3">
                            <h5 class="fw-bolder mb-0 text-dark">Total Amount</h5>
                            <h4 class="fw-bolder mb-0 text-success">₹{{ number_format($totalPrice + $deliveryCharge, 2) }}</h4>
                        </div>

                        <button type="submit" class="btn btn-green btn-lg w-100 fw-bold rounded-pill shadow-lg py-3">
                            Confirm Order
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e){
        e.preventDefault();
        
        var form = this;
        var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const totalAmount = {{ ($totalPrice + $deliveryCharge) * 100 }}; // amount in paise

        if(paymentMethod === 'online'){
            var options = {
                "key": "{{ config('services.razorpay.key') }}", // Replace with your key
                "amount": totalAmount,
                "currency": "INR",
                "name": "Supply Kart",
                "description": "Wholesale Purchase",
                "image": "{{ asset('images/default-category.svg') }}",
                "handler": function (response){
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    form.submit();
                },
                "prefill": {
                    "name": "{{ Auth::guard('customer')->user()->owner_name }}",
                    "contact": "{{ Auth::guard('customer')->user()->mobile }}"
                },
                "theme": {
                    "color": "#059669"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', function (response){
                alert("Payment Failed! " + response.error.description);
            });
            rzp1.open();
        } else {
            // COD
            form.submit();
        }
    });
</script>
@endpush
@endsection
