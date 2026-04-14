@extends('website.layouts.app')

@section('content')
    <div class="container py-5 animate__animated animate__fadeIn">
        <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
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

                {{-- 🔑 OTP hint for testing (remove in production) --}}
                @if(session('otp_hint'))
                    <div class="alert alert-warning d-flex align-items-center rounded-3 mb-4 shadow-sm border-0" role="alert">
                        <i class="fa-solid fa-flask me-2"></i>
                        <div>
                            <strong>Dev Mode:</strong> Your OTP is
                            <span class="badge bg-dark fs-6 ms-1">{{ session('otp_hint') }}</span>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-lg p-4 p-md-5 text-center rounded-4 bg-white position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: linear-gradient(90deg, #059669, #f59e0b);"></div>

                    <div class="mb-4 mt-2">
                        <div class="bg-success bg-opacity-10 text-primary-green rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                            style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-mobile-screen-button" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="fw-bolder text-dark mb-2">Verify OTP</h3>
                        <p class="text-muted mb-0 fs-6">We have sent a 4-digit code to</p>
                        <p class="fw-bold fs-5 text-dark mt-1 mb-0">
                            +91 {{ session('otp_mobile') }}
                            @if(session('otp_type') === 'register')
                                <a href="{{ route('register') }}"
                                   class="text-primary-orange ms-2 text-decoration-none"
                                   style="font-size: 0.85rem;" title="Change number">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-primary-orange ms-2 text-decoration-none"
                                   style="font-size: 0.85rem;" title="Change number">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                            @endif
                        </p>
                    </div>

                    <form action="{{ route('verify.otp') }}" method="POST" id="otpForm">
                        @csrf
                        {{-- Hidden combined OTP field submitted to controller --}}
                        <input type="hidden" name="otp" id="otp" maxlength="4">

                        {{-- 4 visual OTP inputs --}}
                        <div class="d-flex justify-content-center gap-3 mb-4" id="otpInputs">
                            <input type="text" class="otp-digit form-control text-center fs-3 fw-bolder p-2 rounded-4 border-2 shadow-sm"
                                maxlength="1" inputmode="numeric" autocomplete="off" style="width: 60px; height: 65px;" autofocus>
                            <input type="text" class="otp-digit form-control text-center fs-3 fw-bolder p-2 rounded-4 border-2 shadow-sm"
                                maxlength="1" inputmode="numeric" autocomplete="off" style="width: 60px; height: 65px;">
                            <input type="text" class="otp-digit form-control text-center fs-3 fw-bolder p-2 rounded-4 border-2 shadow-sm"
                                maxlength="1" inputmode="numeric" autocomplete="off" style="width: 60px; height: 65px;">
                            <input type="text" class="otp-digit form-control text-center fs-3 fw-bolder p-2 rounded-4 border-2 shadow-sm"
                                maxlength="1" inputmode="numeric" autocomplete="off" style="width: 60px; height: 65px;">
                        </div>

                        <button type="submit" class="btn btn-green w-100 py-3 rounded-pill fw-bold mb-4 shadow-sm fs-5" id="verifyBtn">
                            <span id="verifyBtnText"><i class="fa-solid fa-shield-halved me-2"></i>Verify &amp; Login</span>
                            <span id="verifyBtnSpinner" class="d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>Verifying...
                            </span>
                        </button>
                    </form>

                    {{-- Resend OTP Section (Moved outside main form to avoid nesting) --}}
                    <div class="resend-section">
                        <div id="timerDisplay" class="text-muted fw-medium fs-6 bg-light rounded-pill py-2 px-4 d-inline-block border mb-3">
                            Resend OTP in <span id="countdown" class="fw-bolder text-dark ms-1">05:00</span>
                        </div>
                        <form id="resendForm" action="{{ route('resend.otp') }}" method="POST" class="d-none">
                            @csrf
                            <button type="submit" class="btn btn-outline-success rounded-pill px-4 fw-bold">
                                <i class="fa-solid fa-rotate-right me-2"></i>Resend OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ─── OTP Inputs Auto-focus & Combination ────────────────────────────────
    const digits    = document.querySelectorAll('.otp-digit');
    const hiddenOtp = document.getElementById('otp');
    const otpForm   = document.getElementById('otpForm');

    function updateHiddenOtp() {
        hiddenOtp.value = Array.from(digits).map(d => d.value).join('');
    }

    digits.forEach((input, index) => {
        // Only allow digits
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
            updateHiddenOtp();
            if (this.value && index < digits.length - 1) {
                digits[index + 1].focus();
            }
        });

        // Backspace moves to previous input
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                digits[index - 1].focus();
                digits[index - 1].value = '';
                updateHiddenOtp();
            }
        });

        // Handle paste on any input (from sms OTP autofill or manual paste)
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            pastedData.split('').forEach((char, i) => {
                if (digits[i]) {
                    digits[i].value = char;
                }
            });
            updateHiddenOtp();
            const lastFilled = Math.min(pastedData.length, digits.length) - 1;
            if (digits[lastFilled]) digits[lastFilled].focus();
        });

        // Highlight border on focus
        input.addEventListener('focus', function () {
            this.style.borderColor = 'var(--primary-green)';
            this.style.boxShadow   = '0 0 0 3px rgba(5,150,105,0.15)';
        });
        input.addEventListener('blur', function () {
            this.style.borderColor = '';
            this.style.boxShadow   = '';
        });
    });

    // Validate 4 digits entered before submit
    otpForm.addEventListener('submit', function (e) {
        updateHiddenOtp();
        if (hiddenOtp.value.length !== 4) {
            e.preventDefault();
            // Highlight empty boxes
            digits.forEach(d => {
                if (!d.value) {
                    d.style.borderColor = '#dc3545';
                }
            });
            return;
        }
        // Show spinner
        document.getElementById('verifyBtnText').classList.add('d-none');
        document.getElementById('verifyBtnSpinner').classList.remove('d-none');
        document.getElementById('verifyBtn').disabled = true;
    });

    // ─── Countdown Timer ────────────────────────────────────────────────────
    let totalSeconds = 5 * 60; // 5 minutes
    const countdownEl   = document.getElementById('countdown');
    const timerDisplay  = document.getElementById('timerDisplay');
    const resendForm    = document.getElementById('resendForm');

    function formatTime(s) {
        const m = String(Math.floor(s / 60)).padStart(2, '0');
        const sec = String(s % 60).padStart(2, '0');
        return `${m}:${sec}`;
    }

    countdownEl.textContent = formatTime(totalSeconds);

    const timer = setInterval(function () {
        totalSeconds--;
        countdownEl.textContent = formatTime(totalSeconds);

        if (totalSeconds <= 0) {
            clearInterval(timer);
            timerDisplay.classList.add('d-none');
            resendForm.classList.remove('d-none');
        }
    }, 1000);
})();
</script>
@endpush