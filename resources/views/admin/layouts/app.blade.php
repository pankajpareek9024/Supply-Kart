<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — SupplyKart</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Toastr -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <!-- Admin CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ── Sidebar ── -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="brand-text">
                <span class="brand-name">SupplyKart</span>
                <span class="brand-sub">Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-section-label">Catalog</div>

            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap"></i>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Products</span>
            </a>

            <div class="nav-section-label">People</div>

            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('admin.delivery-boys.index') }}" class="nav-link {{ request()->routeIs('admin.delivery-boys.*') ? 'active' : '' }}">
                <i class="bi bi-bicycle"></i>
                <span>Delivery Boys</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                <span>Customer Reviews</span>
            </a>

            <div class="nav-section-label">Commerce</div>

            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i>
                <span>Orders</span>
            </a>

            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i>
                <span>Payments</span>
            </a>

            <div class="nav-section-label">Insights</div>

            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span>Reports</span>
            </a>

            @if(auth('admin')->check() && auth('admin')->user()->isSuperAdmin())
                <div class="nav-section-label">System</div>

                <a href="{{ route('admin.admins.index') }}" class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i>
                    <span>Admin Accounts</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            @if(auth('admin')->check())
                <a href="{{ route('admin.profile.index') }}" class="d-flex align-items-center gap-2 text-decoration-none flex-grow-1 min-width-0" title="My Profile">
                    @if(auth('admin')->user()->avatar)
                        <img src="{{ Storage::url(auth('admin')->user()->avatar) }}"
                             class="rounded-circle flex-shrink-0"
                             style="width:36px;height:36px;object-fit:cover;border:2px solid rgba(255,255,255,.2);">
                    @else
                        <div class="admin-avatar flex-shrink-0">
                            {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="admin-info">
                        <span class="admin-name">{{ auth('admin')->user()->name }}</span>
                        <span class="admin-role">My Profile</span>
                    </div>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            @endif
        </div>
    </aside>

    <!-- ── Main Content ── -->
    <div class="admin-main" id="adminMain">

        <!-- Top Navbar -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            <div class="topbar-right">
                <div class="topbar-time">
                    <i class="bi bi-clock me-1"></i>
                    <span id="topbarClock"></span>
                </div>
                <a href="{{ route('admin.profile.index') }}" class="btn btn-sm btn-outline-secondary" title="My Profile">
                    <i class="bi bi-person-circle me-1"></i>Profile
                </a>
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-globe me-1"></i>Visit Site
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- ── Scripts ── -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')
</body>
</html>
