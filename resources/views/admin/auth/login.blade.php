<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — SupplyKart</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
<div class="admin-login-page">
    <div class="login-card">
        <div class="login-logo">
            <i class="bi bi-box-seam-fill"></i>
        </div>
        <h1>SupplyKart Admin</h1>
        <p class="subtitle">Sign in to access the admin panel</p>

        @if($errors->any())
            <div class="alert alert-danger mb-3" style="font-size:.85rem;border-radius:8px;">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-3" style="font-size:.85rem;border-radius:8px;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-right:none;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="admin@supplykart.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        style="border-left:none;"
                    >
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-right:none;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        style="border-left:none;"
                    >
                    <button class="input-group-text" type="button" id="togglePwd" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-left:none;cursor:pointer;">
                        <i class="bi bi-eye text-muted" id="pwdIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="color:#94a3b8;font-size:.85rem;">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-arrow-right-circle me-2"></i>Sign In
            </button>
        </form>

        <div class="text-center mt-3">
            <p style="font-size:.85rem;color:#94a3b8;">
                New administrator? <a href="{{ route('admin.register') }}" style="color:white;font-weight:600;text-decoration:none;">Register your account</a>
            </p>
        </div>

        <p class="text-center mt-4" style="font-size:.75rem;color:#475569;">
            © {{ date('Y') }} SupplyKart. All rights reserved.
        </p>
    </div>
</div>

<script>
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
</script>
</body>
</html>
