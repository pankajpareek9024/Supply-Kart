@extends('delivery.layouts.app', ['hideNav' => true])
@section('title', 'Partner Registration')

@section('content')
<div class="d-flex flex-column align-items-center py-5">
    <div class="text-center mb-5">
        <div class="bg-primary rounded-4 d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-bicycle text-white fs-1"></i>
        </div>
        <h2 class="fw-bolder">Join SupplyKart</h2>
        <p class="text-muted">Register as a Delivery Partner</p>
    </div>

    <div class="card w-100 p-4 border-0 shadow-sm rounded-4">
        <form method="POST" action="{{ route('delivery.register.post') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-600 small text-muted text-uppercase">Full Name</label>
                <input type="text" name="name" class="form-control rounded-3" placeholder="Enter your full name" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small text-muted text-uppercase">Email Address</label>
                <input type="email" name="email" class="form-control rounded-3" placeholder="partner@supplykart.com" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small text-muted text-uppercase">Phone Number</label>
                <input type="text" name="phone" class="form-control rounded-3" placeholder="+91 98765 43210" value="{{ old('phone') }}" required>
                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-600 small text-muted text-uppercase">Address</label>
                <textarea name="address" class="form-control rounded-3" rows="2" placeholder="Your residential address">{{ old('address') }}</textarea>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label fw-600 small text-muted text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-600 small text-muted text-uppercase">Confirm</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="••••••••" required>
                </div>
                @error('password') <div class="col-12 mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm rounded-pill fw-bold">
                Register Now
            </button>
        </form>
    </div>

    <p class="mt-4 text-center">
        Already a partner? <a href="{{ route('delivery.login') }}" class="text-primary fw-bold text-decoration-none">Login here</a>
    </p>

    <p class="mt-5 text-muted small text-center">
        By registering, you agree to our <br>Terms of Service & Logistics Privacy Policy.
    </p>
</div>
@endsection
