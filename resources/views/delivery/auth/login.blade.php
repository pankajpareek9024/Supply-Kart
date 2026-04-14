@extends('delivery.layouts.app', ['hideNav' => true])
@section('title', 'Login')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="text-center mb-5">
        <div class="bg-primary rounded-4 d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-truck text-white fs-1"></i>
        </div>
        <h2 class="fw-bolder">SupplyKart</h2>
        <p class="text-muted">Delivery Partner Entrance</p>
    </div>

    <div class="card w-100 p-4">
        <form method="POST" action="{{ route('delivery.login.post') }}">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-600 small text-muted text-uppercase">Email Address</label>
                <div class="input-group border rounded-3 p-1">
                    <span class="input-group-text bg-transparent border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-0" placeholder="partner@supplykart.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-600 small text-muted text-uppercase">Password</label>
                <div class="input-group border rounded-3 p-1">
                    <span class="input-group-text bg-transparent border-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-0" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm">
                Sign In
            </button>
        </form>
    </div>

    <p class="mt-4 text-center">
        Don't have an account? <a href="{{ route('delivery.register') }}" class="text-primary fw-bold text-decoration-none">Register as a Partner</a>
    </p>

    <p class="mt-5 text-muted small text-center">
        © {{ date('Y') }} SupplyKart Logistics.<br>
        v1.0.4 Premium Edition
    </p>
</div>
@endsection
