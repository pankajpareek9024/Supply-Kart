<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Delivery Panel') — SupplyKart</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #eef2ff;
            --secondary: #10b981;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            padding-bottom: 70px; /* Space for bottom nav */
        }

        .top-navbar {
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        .nav-item {
            text-decoration: none;
            color: var(--text-muted);
            text-align: center;
            font-size: 0.75rem;
            font-weight: 500;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .nav-item i {
            font-size: 1.4rem;
        }

        .nav-item.active {
            color: var(--primary);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
        }

        .btn-success {
            background: var(--secondary);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-picked_up { background: #e0f2fe; color: #075985; }
        .status-out_for_delivery { background: #f3e8ff; color: #6b21a8; }
        .status-delivered { background: #dcfce7; color: #166534; }

        /* Animation for status change */
        .updating {
            opacity: 0.6;
            pointer-events: none;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 600px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @if(!isset($hideNav))
    <div class="top-navbar">
        <a href="{{ route('delivery.dashboard') }}" class="brand">
            <i class="bi bi-truck"></i>
            SupplyKart <span>Delivery</span>
        </a>
        <div class="d-flex align-items-center gap-3">
            @auth('delivery_boy')
            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Online</span>
            @endauth
        </div>
    </div>
    @endif

    <div class="container py-4">
        @yield('content')
    </div>

    @if(!isset($hideNav))
    <div class="bottom-nav">
        <a href="{{ route('delivery.dashboard') }}" class="nav-item {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house{{ request()->routeIs('delivery.dashboard') ? '-fill' : '' }}"></i>
            Home
        </a>
        <a href="{{ route('delivery.orders.index') }}" class="nav-item {{ request()->routeIs('delivery.orders.index') ? 'active' : '' }}">
            <i class="bi bi-box-seam{{ request()->routeIs('delivery.orders.index') ? '-fill' : '' }}"></i>
            My Tasks
        </a>
        <a href="{{ route('delivery.orders.completed') }}" class="nav-item {{ request()->routeIs('delivery.orders.completed') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            History
        </a>
        <a href="{{ route('delivery.profile') }}" class="nav-item {{ request()->routeIs('delivery.profile') ? 'active' : '' }}">
            <i class="bi bi-person{{ request()->routeIs('delivery.profile') ? '-fill' : '' }}"></i>
            Profile
        </a>
    </div>
    @endif

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
        <div id="statusToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message) {
            const toastEl = document.getElementById('statusToast');
            document.getElementById('toastMessage').innerText = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        // Global AJAX setup
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('ajax-form')) {
                e.preventDefault();
                const form = e.target;
                const btn = form.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                btn.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    if (data.success) {
                        showToast(data.message);
                        if (data.redirect) window.location.href = data.redirect;
                        else if (window.location.reload_on_success) location.reload();
                    }
                })
                .catch(err => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    showToast('Something went wrong. Please try again.');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
