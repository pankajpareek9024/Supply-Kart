@extends('admin.layouts.app')
@section('title', 'Create Admin Account')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admin Accounts</a></li>
    <li class="breadcrumb-item active">Create Admin</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Create Admin Account</h1>
        <p class="page-subtitle">Add a new administrator to the panel</p>
    </div>
    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form method="POST" action="{{ route('admin.admins.store') }}" enctype="multipart/form-data" id="createAdminForm">
    @csrf

    <div class="row g-3">

        {{-- ── Left Column: Main Fields ── --}}
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h6 class="admin-card-title"><i class="bi bi-person-fill me-2 text-primary"></i>Account Details</h6>
                </div>
                <div class="admin-card-body">

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="form-label fw-600">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="e.g. Rajesh Kumar"
                                required
                                autocomplete="name"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="form-label fw-600">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="e.g. admin2@supplykart.com"
                                required
                                autocomplete="email"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="mb-4">
                        <label for="role" class="form-label fw-600">
                            Account Role <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-shield-lock text-muted"></i></span>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor (Limited Access)</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Super Admins can manage other admins and global settings.</small>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label fw-600">
                            Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min 8 chars, uppercase, number"
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="input-group-text bg-light border-start-0" id="togglePwd">
                                <i class="bi bi-eye text-muted" id="pwdIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password strength meter --}}
                        <div class="mt-2">
                            <div class="progress" style="height:4px;border-radius:2px;">
                                <div id="strengthBar" class="progress-bar" style="width:0%;transition:.3s;"></div>
                            </div>
                            <small id="strengthText" class="text-muted"></small>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label fw-600">
                            Confirm Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Re-enter password"
                                required
                                autocomplete="new-password"
                            >
                        </div>
                        <div id="matchMsg" class="mt-1" style="font-size:.78rem;"></div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Right Column: Avatar + Submit ── --}}
        <div class="col-lg-4">

            {{-- Avatar Upload --}}
            <div class="admin-card mb-3">
                <div class="admin-card-header">
                    <h6 class="admin-card-title"><i class="bi bi-image me-2 text-primary"></i>Profile Photo</h6>
                </div>
                <div class="admin-card-body text-center">

                    {{-- Avatar Preview --}}
                    <div id="avatarWrapper" class="mb-3 position-relative d-inline-block">
                        <div id="avatarPlaceholder"
                             class="admin-avatar mx-auto"
                             style="width:90px;height:90px;font-size:2rem;transition:.2s;"
                             id="avatarInitial">
                            <i class="bi bi-person"></i>
                        </div>
                        <img id="avatarPreview"
                             src=""
                             alt="Preview"
                             class="rounded-circle"
                             style="display:none;width:90px;height:90px;object-fit:cover;border:3px solid var(--primary);">
                    </div>

                    <input
                        type="file"
                        name="avatar"
                        id="avatarInput"
                        class="form-control @error('avatar') is-invalid @enderror"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                    >
                    @error('avatar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-1">JPG, PNG, WebP — Max 1 MB</small>

                    {{-- Remove preview button --}}
                    <button type="button" id="removeAvatar" class="btn btn-sm btn-outline-secondary mt-2" style="display:none;">
                        <i class="bi bi-x me-1"></i>Remove
                    </button>
                </div>
            </div>

            {{-- Password requirements --}}
            <div class="admin-card mb-3" style="border:1px solid var(--border);">
                <div class="admin-card-body" style="padding:14px 18px;">
                    <p class="fw-600 mb-2" style="font-size:.82rem;">Password Requirements</p>
                    <ul class="mb-0 ps-3" style="font-size:.78rem;color:var(--text-muted);line-height:1.9;">
                        <li id="req-length">At least 8 characters</li>
                        <li id="req-upper">At least one uppercase letter (A–Z)</li>
                        <li id="req-number">At least one number (0–9)</li>
                        <li id="req-match">Passwords match</li>
                    </ul>
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="bi bi-person-check-fill me-2"></i>Create Admin Account
                </button>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
/* ── Password Toggle ── */
document.getElementById('togglePwd').addEventListener('click', function () {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('pwdIcon');
    if (pwd.type === 'password') {
        pwd.type  = 'text';
        icon.className = 'bi bi-eye-slash text-muted';
    } else {
        pwd.type  = 'password';
        icon.className = 'bi bi-eye text-muted';
    }
});

/* ── Password Strength ── */
const pwdInput  = document.getElementById('password');
const confInput = document.getElementById('password_confirmation');
const bar       = document.getElementById('strengthBar');
const txt       = document.getElementById('strengthText');
const matchMsg  = document.getElementById('matchMsg');

const reqLength = document.getElementById('req-length');
const reqUpper  = document.getElementById('req-upper');
const reqNumber = document.getElementById('req-number');
const reqMatch  = document.getElementById('req-match');

function checkReq(el, ok) {
    el.style.color = ok ? '#10b981' : 'var(--text-muted)';
    el.style.fontWeight = ok ? '600' : '400';
}

function evalPassword() {
    const val  = pwdInput.value;
    const hasLen  = val.length >= 8;
    const hasUpper = /[A-Z]/.test(val);
    const hasNum  = /[0-9]/.test(val);
    const isMatch = val === confInput.value && val !== '';

    checkReq(reqLength, hasLen);
    checkReq(reqUpper,  hasUpper);
    checkReq(reqNumber, hasNum);
    checkReq(reqMatch,  isMatch);

    const score = [hasLen, hasUpper, hasNum].filter(Boolean).length;

    const levels = [
        { pct: '0%',   cls: '',           label: '' },
        { pct: '33%',  cls: 'bg-danger',  label: 'Weak' },
        { pct: '66%',  cls: 'bg-warning', label: 'Fair' },
        { pct: '100%', cls: 'bg-success', label: 'Strong' },
    ];

    const lv = levels[score];
    bar.style.width  = lv.pct;
    bar.className    = `progress-bar ${lv.cls}`;
    txt.textContent  = lv.label;
    txt.style.color  = score === 3 ? '#10b981' : score === 2 ? '#f59e0b' : '#ef4444';
}

function checkMatch() {
    const ok = pwdInput.value === confInput.value && confInput.value !== '';
    checkReq(reqMatch, ok);
    if (confInput.value === '') {
        matchMsg.textContent = '';
    } else if (ok) {
        matchMsg.innerHTML  = '<span style="color:#10b981"><i class="bi bi-check-circle me-1"></i>Passwords match</span>';
    } else {
        matchMsg.innerHTML  = '<span style="color:#ef4444"><i class="bi bi-x-circle me-1"></i>Passwords do not match</span>';
    }
}

pwdInput.addEventListener('input',  () => { evalPassword(); checkMatch(); });
confInput.addEventListener('input', checkMatch);

/* ── Avatar Preview ── */
const avatarInput   = document.getElementById('avatarInput');
const avatarPreview = document.getElementById('avatarPreview');
const avatarPlaceholder = document.getElementById('avatarPlaceholder');
const removeBtn     = document.getElementById('removeAvatar');

avatarInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            avatarPreview.src = e.target.result;
            avatarPreview.style.display = 'block';
            avatarPlaceholder.style.display = 'none';
            removeBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    }
});

removeBtn.addEventListener('click', function () {
    avatarInput.value = '';
    avatarPreview.style.display = 'none';
    avatarPlaceholder.style.display = 'flex';
    this.style.display = 'none';
});

/* ── Name → Avatar Initial ── */
document.getElementById('name').addEventListener('input', function () {
    const initial = this.value.trim().charAt(0).toUpperCase() || '<i class="bi bi-person"></i>';
    if (avatarPreview.style.display === 'none') {
        avatarPlaceholder.textContent = initial;
    }
});
</script>
@endpush
