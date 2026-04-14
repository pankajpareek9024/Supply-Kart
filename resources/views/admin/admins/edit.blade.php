@extends('admin.layouts.app')
@section('title', 'Edit Admin — ' . $admin->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admin Accounts</a></li>
    <li class="breadcrumb-item active">Edit: {{ $admin->name }}</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Admin Account</h1>
        <p class="page-subtitle">Updating account for <strong>{{ $admin->name }}</strong></p>
    </div>
    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form method="POST" action="{{ route('admin.admins.update', $admin) }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">

        {{-- ── Left Column ── --}}
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h6 class="admin-card-title"><i class="bi bi-person-fill me-2 text-primary"></i>Account Details</h6>
                    @if($admin->id === auth('admin')->id())
                        <span class="badge" style="background:var(--primary-light);color:var(--primary);">Your Account</span>
                    @endif
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
                                value="{{ old('name', $admin->name) }}"
                                required
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
                                value="{{ old('email', $admin->email) }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Role (Locked for self) --}}
                    <div class="mb-4">
                        <label for="role" class="form-label fw-600">
                            Account Role <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-shield-lock text-muted"></i></span>
                            @if($admin->id === auth('admin')->id())
                                <input type="hidden" name="role" value="{{ $admin->role }}">
                                <select class="form-select bg-light" disabled>
                                    <option selected>{{ $admin->role === 'super_admin' ? 'Super Admin' : 'Editor' }}</option>
                                </select>
                            @else
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="editor" {{ old('role', $admin->role) === 'editor' ? 'selected' : '' }}>Editor (Limited Access)</option>
                                    <option value="super_admin" {{ old('role', $admin->role) === 'super_admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                                </select>
                            @endif
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if($admin->id === auth('admin')->id())
                            <small class="text-muted">You cannot change your own role.</small>
                        @endif
                    </div>

                    {{-- Password (optional on edit) --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-600">
                            New Password
                            <span class="text-muted fw-400" style="font-size:.78rem;">(leave blank to keep current)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Leave blank to keep current password"
                                autocomplete="new-password"
                            >
                            <button type="button" class="input-group-text bg-light border-start-0" id="togglePwd">
                                <i class="bi bi-eye text-muted" id="pwdIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Strength bar (only shown when typing) --}}
                        <div id="strengthWrap" class="mt-2" style="display:none;">
                            <div class="progress" style="height:4px;border-radius:2px;">
                                <div id="strengthBar" class="progress-bar" style="width:0%;transition:.3s;"></div>
                            </div>
                            <small id="strengthText" class="text-muted"></small>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label fw-600">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Re-enter new password"
                                autocomplete="new-password"
                            >
                        </div>
                        <div id="matchMsg" class="mt-1" style="font-size:.78rem;"></div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Right Column ── --}}
        <div class="col-lg-4">

            {{-- Avatar --}}
            <div class="admin-card mb-3">
                <div class="admin-card-header">
                    <h6 class="admin-card-title"><i class="bi bi-image me-2 text-primary"></i>Profile Photo</h6>
                </div>
                <div class="admin-card-body text-center">

                    @if($admin->avatar)
                        <img id="avatarPreview"
                             src="{{ Storage::url($admin->avatar) }}"
                             class="rounded-circle mb-3"
                             style="width:90px;height:90px;object-fit:cover;border:3px solid var(--primary);"
                             id="currentAvatar">
                        <div id="avatarPlaceholder" class="admin-avatar mx-auto mb-3"
                             style="width:90px;height:90px;font-size:2rem;display:none;">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                    @else
                        <img id="avatarPreview" src="" class="rounded-circle mb-3"
                             style="display:none;width:90px;height:90px;object-fit:cover;border:3px solid var(--primary);">
                        <div id="avatarPlaceholder" class="admin-avatar mx-auto mb-3"
                             style="width:90px;height:90px;font-size:2rem;">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                    @endif

                    <input type="file" name="avatar" id="avatarInput"
                        class="form-control @error('avatar') is-invalid @enderror"
                        accept="image/jpg,image/jpeg,image/png,image/webp">
                    @error('avatar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-1">Upload new photo to replace current.</small>
                </div>
            </div>

            {{-- Account Info --}}
            <div class="admin-card mb-3">
                <div class="admin-card-body" style="font-size:.83rem;">
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Account ID</span>
                        <span class="fw-600">#{{ $admin->id }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Created</span>
                        <span class="fw-600">{{ $admin->created_at ? $admin->created_at->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Last Updated</span>
                        <span class="fw-600">{{ $admin->updated_at ? $admin->updated_at->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <span class="status-badge {{ $admin->is_active ? 'active' : 'inactive' }}">
                            {{ $admin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-2"></i>Save Changes
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

/* ── Password Strength (only shown when typing) ── */
const pwdInput   = document.getElementById('password');
const confInput  = document.getElementById('password_confirmation');
const bar        = document.getElementById('strengthBar');
const txt        = document.getElementById('strengthText');
const matchMsg   = document.getElementById('matchMsg');
const strengthWrap = document.getElementById('strengthWrap');

pwdInput.addEventListener('input', function () {
    strengthWrap.style.display = this.value ? 'block' : 'none';

    const val      = this.value;
    const hasLen   = val.length >= 8;
    const hasUpper = /[A-Z]/.test(val);
    const hasNum   = /[0-9]/.test(val);
    const score    = [hasLen, hasUpper, hasNum].filter(Boolean).length;

    const levels = [
        { pct: '0%',   cls: '',           label: '' },
        { pct: '33%',  cls: 'bg-danger',  label: 'Weak' },
        { pct: '66%',  cls: 'bg-warning', label: 'Fair' },
        { pct: '100%', cls: 'bg-success', label: 'Strong' },
    ];
    const lv = levels[score];
    bar.style.width = lv.pct;
    bar.className   = `progress-bar ${lv.cls}`;
    txt.textContent = lv.label;

    checkMatch();
});

confInput.addEventListener('input', checkMatch);

function checkMatch() {
    if (!confInput.value) { matchMsg.textContent = ''; return; }
    const ok = pwdInput.value === confInput.value;
    matchMsg.innerHTML = ok
        ? '<span style="color:#10b981"><i class="bi bi-check-circle me-1"></i>Passwords match</span>'
        : '<span style="color:#ef4444"><i class="bi bi-x-circle me-1"></i>Passwords do not match</span>';
}

/* ── Avatar Preview ── */
document.getElementById('avatarInput').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const prev = document.getElementById('avatarPreview');
            const ph   = document.getElementById('avatarPlaceholder');
            prev.src   = e.target.result;
            prev.style.display = 'block';
            if (ph) ph.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
