@extends('website.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                {{-- Alert Messages --}}
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <div class="card border-0 shadow-lg p-4 p-md-5 rounded-4">
                    <div class="text-center mb-4">
                        <div class="bg-warning bg-opacity-10 text-primary-orange rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                            style="width: 72px; height: 72px;">
                            <i class="fa-solid fa-shop" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="fw-bold text-primary-orange mb-1">Register Your Shop</h3>
                        <p class="text-muted mb-0">Join the fastest B2B wholesale network.</p>
                    </div>

                    <form action="{{ route('send.otp') }}" method="POST" id="registerForm">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="shopName" class="form-label fw-semibold">Shop Name</label>
                                <input
                                    type="text"
                                    name="shopName"
                                    class="form-control @error('shopName') is-invalid @enderror"
                                    id="shopName"
                                    placeholder="E.g. Gupta Enterprises"
                                    value="{{ old('shopName') }}"
                                    required>
                                @error('shopName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ownerName" class="form-label fw-semibold">Owner Name</label>
                                <input
                                    type="text"
                                    name="ownerName"
                                    class="form-control @error('ownerName') is-invalid @enderror"
                                    id="ownerName"
                                    placeholder="Full Name"
                                    value="{{ old('ownerName') }}"
                                    required>
                                @error('ownerName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="mobile" class="form-label fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-muted">+91</span>
                                    <input
                                        type="tel"
                                        name="mobile"
                                        class="form-control @error('mobile') is-invalid @enderror"
                                        id="mobile"
                                        placeholder="10 digit number"
                                        value="{{ old('mobile') }}"
                                        maxlength="10"
                                        inputmode="numeric"
                                        required>
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="gst" class="form-label fw-semibold">
                                    GST Number <span class="text-muted small fw-normal">(Optional)</span>
                                </label>
                                <input
                                    type="text"
                                    name="gst"
                                    class="form-control @error('gst') is-invalid @enderror"
                                    id="gst"
                                    placeholder="GSTIN (if applicable)"
                                    value="{{ old('gst') }}"
                                    style="text-transform: uppercase;">
                                @error('gst')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">Shop Address</label>
                                <textarea
                                    name="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    id="address"
                                    rows="2"
                                    placeholder="Full address of your shop"
                                    required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-orange w-100 py-2 fw-bold rounded-pill" id="registerBtn">
                                    <span id="registerBtnText">
                                        <i class="fa-solid fa-paper-plane me-2"></i>Register &amp; Send OTP
                                    </span>
                                    <span id="registerBtnSpinner" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted">Already have an account?</span>
                            <a href="{{ route('login') }}"
                                class="text-primary-green fw-bold text-decoration-none ms-1">Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Only allow numeric input for mobile
    document.getElementById('mobile').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Uppercase GST number
    document.getElementById('gst').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    // Show loading spinner on submit
    document.getElementById('registerForm').addEventListener('submit', function () {
        document.getElementById('registerBtnText').classList.add('d-none');
        document.getElementById('registerBtnSpinner').classList.remove('d-none');
        document.getElementById('registerBtn').disabled = true;
    });
</script>
@endpush