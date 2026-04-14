@extends('delivery.layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="mb-4 text-center">
    <div class="position-relative d-inline-block mb-3">
        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 100px; height: 100px; font-size: 2.5rem;">
            {{ strtoupper(substr(auth('delivery_boy')->user()->name, 0, 1)) }}
        </div>
    </div>
    <h4 class="fw-bolder mb-1">{{ auth('delivery_boy')->user()->name }}</h4>
    <p class="text-muted small">Delivery Partner #{{ auth('delivery_boy')->id() }}</p>
</div>

<div class="card border-0 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('delivery.profile.update') }}" method="POST" class="ajax-form">
            @csrf
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">FULL NAME</label>
                <input type="text" name="name" class="form-control" value="{{ $deliveryBoy->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">PHONE NUMBER</label>
                <input type="text" name="phone" class="form-control" value="{{ $deliveryBoy->phone }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">ADDRESS</label>
                <textarea name="address" class="form-control" rows="3">{{ $deliveryBoy->address }}</textarea>
            </div>

            <hr class="my-4 opacity-5">

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">NEW PASSWORD (OPTIONAL)</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep same">
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">CONFIRM PASSWORD</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3">
                Update Profile
            </button>
        </form>
    </div>
</div>

<div class="d-grid gap-2 mb-4">
    <form action="{{ route('delivery.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100 py-3 border-2">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</div>

<div class="text-center p-4">
    <img src="https://via.placeholder.com/150x50/f1f5f9/64748b?text=SupplyKart+Logistics" class="img-fluid opacity-50" style="max-height: 30px;">
    <p class="text-muted small mt-2">Privacy Policy • Terms of Service</p>
</div>

@endsection
