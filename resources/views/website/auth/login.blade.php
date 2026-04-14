@extends('website.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

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
                        <div class="bg-success bg-opacity-10 text-primary-green rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                            style="width: 72px; height: 72px;">
                            <i class="fa-solid fa-store" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="fw-bold text-primary-green mb-1">Welcome Back</h3>
                        <p class="text-muted mb-0">Login to your wholesale account.</p>
                    </div>

                    <form action="{{ route('login.otp') }}" method="POST" id="loginForm">
                        @csrf
                        <div class="mb-4">
                            <label for="mobile" class="form-label fw-semibold">Mobile Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 fw-bold text-muted">+91</span>
                                <input
                                    type="tel"
                                    name="mobile"
                                    id="mobile"
                                    class="form-control border-start-0 @error('mobile') is-invalid @enderror"
                                    placeholder="Enter 10 digit mobile number"
                                    value="{{ old('mobile') }}"
                                    maxlength="10"
                                    inputmode="numeric"
                                    required>
                            </div>
                            @error('mobile')
                                <div class="text-danger small mt-1">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-green w-100 py-2 fw-bold mt-1 rounded-pill" id="loginBtn">
                            <span id="loginBtnText"><i class="fa-solid fa-paper-plane me-2"></i>Send OTP</span>
                            <span id="loginBtnSpinner" class="d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...
                            </span>
                        </button>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted">New to Supply Kart?</span>
                            <a href="{{ route('register') }}"
                                class="text-primary-orange fw-bold text-decoration-none ms-1">Register Shop</a>
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

    // Show loading spinner on submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        document.getElementById('loginBtnText').classList.add('d-none');
        document.getElementById('loginBtnSpinner').classList.remove('d-none');
        document.getElementById('loginBtn').disabled = true;
    });
</script>
@endpush