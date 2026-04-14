@extends('admin.layouts.app')
@section('title', 'My Profile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">My Profile</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your account information and security settings</p>
    </div>
</div>

<div class="row g-3">

    {{-- ── Left: Profile Header Card ── --}}
    <div class="col-lg-4">
        <div class="admin-card text-center p-4 mb-3">
            {{-- Avatar --}}
            @if($admin->avatar)
                <img src="{{ Storage::url($admin->avatar) }}"
                     class="rounded-circle mb-3"
                     style="width:90px;height:90px;object-fit:cover;border:3px solid var(--primary);box-shadow:0 4px 16px rgba(79,70,229,.25);"
                     alt="{{ $admin->name }}">
            @else
                <div class="admin-avatar mx-auto mb-3"
                     style="width:90px;height:90px;font-size:2rem;box-shadow:0 4px 16px rgba(79,70,229,.25);">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
            @endif

            <h5 class="fw-700 mb-1">{{ $admin->name }}</h5>
            <p class="text-muted mb-3" style="font-size:.85rem;">{{ $admin->email }}</p>
            <span class="status-badge active">Administrator</span>

            <hr class="my-3">

            <div class="text-start" style="font-size:.83rem;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Account ID</span>
                    <span class="fw-600">#{{ $admin->id }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    <span class="status-badge active" style="font-size:.65rem;">Active</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Member since</span>
                    <span class="fw-600">{{ $admin->created_at ? $admin->created_at->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Last updated</span>
                    <span class="fw-600">{{ $admin->updated_at ? $admin->updated_at->format('d M Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="admin-card p-3">
            <p class="fw-600 mb-2" style="font-size:.82rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Quick Links</p>
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 py-2 text-decoration-none text-dark border-bottom" style="font-size:.85rem;">
                <i class="bi bi-speedometer2 text-primary"></i> Dashboard
            </a>
            <a href="{{ route('admin.admins.index') }}" class="d-flex align-items-center gap-2 py-2 text-decoration-none text-dark border-bottom" style="font-size:.85rem;">
                <i class="bi bi-shield-lock text-primary"></i> Admin Accounts
            </a>
            <a href="{{ route('admin.settings.index') }}" class="d-flex align-items-center gap-2 py-2 text-decoration-none text-dark" style="font-size:.85rem;">
                <i class="bi bi-gear text-primary"></i> Settings
            </a>
        </div>
    </div>

    {{-- ── Right: Tab Sections ── --}}
    <div class="col-lg-8">

        {{-- Tab Navigation --}}
        <ul class="nav nav-tabs mb-0" id="profileTabs" style="border-bottom:none;">
            <li class="nav-item">
                <button class="nav-link {{ session('active_tab') === 'password' ? '' : 'active' }}"
                    id="info-tab" data-bs-toggle="tab" data-bs-target="#infoTab" type="button">
                    <i class="bi bi-person me-1"></i>Profile Info
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ session('active_tab') === 'password' ? 'active' : '' }}"
                    id="pwd-tab" data-bs-toggle="tab" data-bs-target="#pwdTab" type="button">
                    <i class="bi bi-shield-lock me-1"></i>Change Password
                </button>
            </li>
        </ul>

        <div class="tab-content admin-card" style="border-top-left-radius:0;border-top:none;">

            {{-- ── Tab 1: Profile Info ── --}}
            <div class="tab-pane fade {{ session('active_tab') === 'password' ? '' : 'show active' }}" id="infoTab">
                <div class="admin-card-header">
                    <h6 class="admin-card-title">Update Profile Information</h6>
                </div>
                <div class="admin-card-body">

                    @if(session('success') && session('active_tab') !== 'password')
                        <div class="alert alert-success d-flex gap-2 alert-dismissible fade show mb-4">
                            <i class="bi bi-check-circle-fill mt-1"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.profile.update-info') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-600">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $admin->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="profileEmail" class="form-label fw-600">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" id="profileEmail"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $admin->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600">Profile Photo</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                {{-- Current avatar thumbnail --}}
                                @if($admin->avatar)
                                    <img id="profileAvatarPreview"
                                         src="{{ Storage::url($admin->avatar) }}"
                                         class="rounded-circle"
                                         style="width:52px;height:52px;object-fit:cover;border:2px solid var(--primary);">
                                @else
                                    <img id="profileAvatarPreview" src="" class="rounded-circle"
                                         style="display:none;width:52px;height:52px;object-fit:cover;border:2px solid var(--primary);">
                                    <div id="profileAvatarInitial" class="admin-avatar"
                                         style="width:52px;height:52px;font-size:1.1rem;">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                @endif
                                <input type="file" name="avatar" id="profileAvatarInput"
                                    class="form-control @error('avatar') is-invalid @enderror"
                                    accept="image/jpg,image/jpeg,image/png,image/webp">
                            </div>
                            @error('avatar')<div class="text-danger" style="font-size:.82rem;">{{ $message }}</div>@enderror
                            <small class="text-muted">JPG, PNG, WebP. Max 1 MB. Leave empty to keep current photo.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Tab 2: Change Password ── --}}
            <div class="tab-pane fade {{ session('active_tab') === 'password' ? 'show active' : '' }}" id="pwdTab">
                <div class="admin-card-header">
                    <h6 class="admin-card-title">Change Password</h6>
                </div>
                <div class="admin-card-body">

                    @if(session('success') && session('active_tab') === 'password')
                        <div class="alert alert-success d-flex gap-2 alert-dismissible fade show mb-4">
                            <i class="bi bi-check-circle-fill mt-1"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.profile.update-password') }}">
                        @csrf

                        {{-- Current Password --}}
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-600">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Enter your current password" required>
                                <button type="button" class="input-group-text bg-light border-start-0 toggle-eye" data-target="current_password">
                                    <i class="bi bi-eye text-muted"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- New Password --}}
                        <div class="mb-4">
                            <label for="new_password" class="form-label fw-600">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-muted"></i></span>
                                <input type="password" name="password" id="new_password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Min 8 chars, uppercase, number" required>
                                <button type="button" class="input-group-text bg-light border-start-0 toggle-eye" data-target="new_password">
                                    <i class="bi bi-eye text-muted"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Strength --}}
                            <div class="mt-2">
                                <div class="progress" style="height:4px;border-radius:2px;">
                                    <div id="pwdStrengthBar" class="progress-bar" style="width:0%;transition:.3s;"></div>
                                </div>
                                <small id="pwdStrengthText" class="text-muted"></small>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-600">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control"
                                    placeholder="Re-enter new password" required>
                            </div>
                            <div id="pwdMatchMsg" class="mt-1" style="font-size:.78rem;"></div>
                        </div>

                        {{-- Requirements checklist --}}
                        <div class="p-3 rounded mb-4" style="background:var(--body-bg);font-size:.8rem;">
                            <p class="fw-600 mb-2">Password must contain:</p>
                            <div class="d-flex flex-wrap gap-3">
                                <div id="c-len"  class="d-flex align-items-center gap-1"><i class="bi bi-circle text-muted"></i> 8+ characters</div>
                                <div id="c-up"   class="d-flex align-items-center gap-1"><i class="bi bi-circle text-muted"></i> Uppercase (A–Z)</div>
                                <div id="c-num"  class="d-flex align-items-center gap-1"><i class="bi bi-circle text-muted"></i> Number (0–9)</div>
                                <div id="c-match" class="d-flex align-items-center gap-1"><i class="bi bi-circle text-muted"></i> Passwords match</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-check me-1"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>{{-- end tab-content --}}
    </div>{{-- end col-lg-8 --}}

</div>{{-- end row --}}

@endsection

@push('scripts')
<script>
/* ── Eye toggles ── */
document.querySelectorAll('.toggle-eye').forEach(btn => {
    btn.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);
        const icon  = this.querySelector('i');
        if (input.type === 'password') {
            input.type  = 'text';
            icon.className = 'bi bi-eye-slash text-muted';
        } else {
            input.type  = 'password';
            icon.className = 'bi bi-eye text-muted';
        }
    });
});

/* ── Password strength ── */
const newPwd  = document.getElementById('new_password');
const confPwd = document.getElementById('password_confirmation');
const bar     = document.getElementById('pwdStrengthBar');
const txt     = document.getElementById('pwdStrengthText');
const matchMsg= document.getElementById('pwdMatchMsg');

function setCheck(id, ok) {
    const el = document.getElementById(id);
    if (!el) return;
    el.querySelector('i').className = ok ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-muted';
    el.style.color = ok ? '#10b981' : 'var(--text-muted)';
    el.style.fontWeight = ok ? '600' : '400';
}

newPwd.addEventListener('input', function () {
    const v = this.value;
    const hasLen  = v.length >= 8;
    const hasUp   = /[A-Z]/.test(v);
    const hasNum  = /[0-9]/.test(v);
    const isMatch = v === confPwd.value && v !== '';

    setCheck('c-len',   hasLen);
    setCheck('c-up',    hasUp);
    setCheck('c-num',   hasNum);
    setCheck('c-match', isMatch);

    const score = [hasLen, hasUp, hasNum].filter(Boolean).length;
    const levels= [
        { pct:'0%',   cls:'',           label:'' },
        { pct:'33%',  cls:'bg-danger',  label:'Weak' },
        { pct:'66%',  cls:'bg-warning', label:'Fair' },
        { pct:'100%', cls:'bg-success', label:'Strong' },
    ];
    bar.style.width = levels[score].pct;
    bar.className   = 'progress-bar ' + levels[score].cls;
    txt.textContent = levels[score].label;

    checkMatch();
});

confPwd.addEventListener('input', checkMatch);

function checkMatch() {
    if (!confPwd.value) { matchMsg.textContent = ''; setCheck('c-match', false); return; }
    const ok = newPwd.value === confPwd.value;
    setCheck('c-match', ok);
    matchMsg.innerHTML = ok
        ? '<span style="color:#10b981"><i class="bi bi-check-circle me-1"></i>Passwords match</span>'
        : '<span style="color:#ef4444"><i class="bi bi-x-circle me-1"></i>Passwords do not match</span>';
}

/* ── Avatar preview ── */
document.getElementById('profileAvatarInput').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const prev = document.getElementById('profileAvatarPreview');
            const init = document.getElementById('profileAvatarInitial');
            prev.src = e.target.result;
            prev.style.display = 'block';
            if (init) init.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
