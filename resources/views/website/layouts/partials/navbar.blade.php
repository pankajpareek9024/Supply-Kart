<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="fa-solid fa-cart-shopping me-2 text-primary-orange"></i>
            Supply <span class="text-primary-orange ms-1">Kart</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- Search Bar -->
            <div class="position-relative mx-auto w-100 my-3 my-lg-0 px-lg-4" style="max-width: 500px;">
                <form class="d-flex search-container w-100" action="{{ route('products.list') }}" method="GET">
                    <input id="live-search-input" name="search" class="form-control focus-ring focus-ring-success"
                        type="search" placeholder="Search for wholesale products..." aria-label="Search"
                        value="{{ request('search') }}" autocomplete="off">
                    <button class="btn btn-green px-4" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <!-- Live Search Results Dropdown -->
                <div id="live-search-results"
                    class="position-absolute w-100 bg-white rounded-3 shadow-lg z-3 d-none border mt-1"
                    style="max-height: 400px; overflow-y: auto; left: 0; right: 0; margin-left: auto; margin-right: auto; width: calc(100% - 3rem);">
                    <div class="list-group list-group-flush" id="search-results-list">
                        <!-- Results injected here -->
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ route('products.list') }}">Products</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ route('categories.index') }}">Categories</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ route('orders.index') }}">My Orders</a>
                </li>

                <!-- Auth / Profile Links -->
                <li class="nav-item dropdown ms-lg-2">
                    <a class="nav-link dropdown-toggle fw-medium" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-regular fa-user me-1"></i>
                        @auth('customer')
                            {{ Auth::guard('customer')->user()->owner_name }}
                        @else
                            Account
                        @endauth
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        @auth('customer')
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fa-regular fa-user me-2 text-muted"></i>My Profile
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="fa-solid fa-box me-2 text-muted"></i>My Orders
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                                </a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">
                                    <i class="fa-solid fa-right-to-bracket me-2 text-muted"></i>Login
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">
                                    <i class="fa-solid fa-shop me-2 text-muted"></i>Register Shop
                                </a></li>
                        @endauth
                    </ul>
                </li>

                <!-- Cart Icon -->
                <li class="nav-item ms-lg-3 ms-0 mt-2 mt-lg-0">
                    <a href="{{ route('cart.index') }}" class="cart-icon-wrapper text-decoration-none">
                        <div class="p-2 bg-light rounded-circle text-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <span id="cart-badge-count"
                            class="position-absolute translate-middle badge rounded-pill cart-badge">
                            @auth('customer')
                                {{ \App\Models\Cart::where('customer_id', Auth::guard('customer')->id())->sum('quantity') }}
                            @else
                                0
                            @endauth
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>