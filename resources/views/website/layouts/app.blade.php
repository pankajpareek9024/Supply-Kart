<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Kart - B2B Wholesale</title>

    <!-- Minimal Custom SVG Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23059669'/><text x='50' y='70' font-family='Arial, sans-serif' font-size='60' font-weight='bold' fill='white' text-anchor='middle'>SK</text></svg>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            /* Premium Green & Orange Palette */
            --primary-green: #059669;
            /* Emerald 600 */
            --dark-green: #047857;
            /* Emerald 700 */
            --light-green: #d1fae5;
            /* Emerald 100 */
            --primary-orange: #f59e0b;
            /* Amber 500 */
            --hover-orange: #d97706;
            /* Amber 600 */
            --light-orange: #fef3c7;
            /* Amber 100 */

            --light-bg: #f8fafc;
            /* Slate 50 */
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            /* Slate 900 */
            --text-muted: #64748b;
            /* Slate 500 */

            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-pill: 9999px;

            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-glow: 0 0 15px rgba(5, 150, 105, 0.3);

            --transition-bounce: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        .text-primary-green {
            color: var(--primary-green) !important;
        }

        .text-primary-orange {
            color: var(--primary-orange) !important;
        }

        .bg-primary-green {
            background-color: var(--primary-green) !important;
            color: white;
        }

        .bg-primary-orange {
            background-color: var(--primary-orange) !important;
            color: white;
        }

        /* Premium Buttons */
        .btn {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            border-radius: var(--radius-md);
            padding: 0.6rem 1.5rem;
            transition: var(--transition-bounce);
            text-transform: capitalize;
            letter-spacing: 0.02em;
            position: relative;
            overflow: hidden;
        }

        .btn:active {
            transform: scale(0.95);
        }

        .btn-green {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
        }

        .btn-green:hover {
            background: linear-gradient(135deg, var(--dark-green), #065f46);
            color: white;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
            transform: translateY(-2px);
        }

        .btn-green::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-green:hover::after {
            left: 100%;
        }

        .btn-orange {
            background: linear-gradient(135deg, var(--primary-orange), var(--hover-orange));
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, var(--hover-orange), #b45309);
            color: white;
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
            transform: translateY(-2px);
        }

        .btn-outline-success {
            color: var(--primary-green);
            border-color: var(--primary-green);
            border-width: 2px;
        }

        .btn-outline-success:hover {
            background-color: var(--light-green);
            color: var(--dark-green);
            border-color: var(--primary-green);
        }

        /* Glassmorphism Elements */
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--shadow-sm);
        }

        /* Custom Badges */
        .badge-20min {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #b45309;
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: var(--radius-pill);
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(252, 211, 77, 0.3);
            border: 1px solid #fde047;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: var(--transition-bounce);
            background: var(--card-bg);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Navbar custom styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition-smooth);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--primary-green) !important;
            letter-spacing: -0.5px;
        }

        .search-container input {
            border-radius: var(--radius-pill) 0 0 var(--radius-pill);
            border: 2px solid #e2e8f0;
            border-right: none;
            padding-left: 1.5rem;
            font-size: 0.95rem;
            box-shadow: none !important;
            transition: var(--transition-smooth);
        }

        .search-container input:focus {
            border-color: var(--primary-green);
        }

        .search-container button {
            border-radius: 0 var(--radius-pill) var(--radius-pill) 0;
            padding-right: 1.5rem;
            background: var(--primary-green);
            color: white;
            border: 2px solid var(--primary-green);
        }

        .search-container button:hover {
            background: var(--dark-green);
        }

        /* Cart Icon Animation */
        .cart-icon-wrapper {
            position: relative;
            color: var(--text-dark);
            transition: var(--transition-bounce);
            display: inline-block;
        }

        .cart-icon-wrapper:hover {
            transform: scale(1.1);
        }

        .cart-icon-wrapper .bg-light {
            background: var(--light-bg) !important;
            transition: var(--transition-smooth);
        }

        .cart-icon-wrapper:hover .bg-light {
            background: var(--light-green) !important;
            color: var(--primary-green);
        }

        .cart-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            font-size: 0.65rem;
            background: linear-gradient(135deg, var(--primary-orange), var(--hover-orange));
            border: 2px solid white;
            animation: pulse-orange 2s infinite;
        }

        @keyframes pulse-orange {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(245, 158, 11, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: var(--radius-pill);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        main {
            min-height: 80vh;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hero Banner Styles */
        .hero-slider-container {
            max-height: 450px;
        }
        .hero-slide-item {
            min-height: 450px;
        }
        .hero-overlay {
            opacity: 0.65; 
            background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.4)); 
            position: absolute; top:0; left:0; width:100%; height:100%;
        }

        /* Mobile Specific Overrides */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 768px) {
            .hero-slider-container {
                max-height: 250px;
            }
            .hero-slide-item {
                min-height: 250px;
            }
            .hero-overlay {
                opacity: 0.7; 
                background: linear-gradient(to right, rgba(0,0,0,0.9), rgba(0,0,0,0.1));
            }
            .mobile-scroll-row {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                scroll-behavior: smooth;
            }
            .mobile-scroll-col {
                flex: 0 0 auto;
                width: 95px; /* compact width for multiple items */
            }
        }

        /* Compact Product Cards for Mobile */
        @media (max-width: 576px) {
            .card-body {
                padding: 0.75rem !important;
            }
            .card-title {
                font-size: 0.9rem !important;
            }
            .btn {
                padding: 0.4rem 1rem;
                font-size: 0.85rem;
            }
        }

        /* Bottom Nav */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            box-shadow: 0 -4px 15px rgba(0,0,0,0.08);
            z-index: 1050;
            border-top: 1px solid rgba(0,0,0,0.05);
            padding-bottom: env(safe-area-inset-bottom);
        }
        .bottom-nav-item {
            flex: 1;
            text-align: center;
            padding: 8px 0;
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition-smooth);
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }
        .bottom-nav-item i {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }
        .bottom-nav-item.active {
            color: var(--primary-green);
        }
        .bottom-nav-item:hover {
            color: var(--primary-green);
        }

        @media (max-width: 768px) {
            body {
                padding-bottom: 65px;
            }
            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
            }
            /* Hide desktop navigation elements on small screens */
            .navbar .navbar-nav {
                display: none !important;
            }
            /* Hide floating action buttons on mobile */
            #whatsapp-float-btn, #chatbot-toggle-btn {
                display: none !important;
            }
            /* Make chatbot take more space on mobile when opened from Account */
            #chatbot-widget {
                bottom: 80px;
                right: 16px;
                width: calc(100% - 32px);
                max-height: 70vh;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    @include('website.layouts.partials.navbar')

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Global Toast for AJAX responses -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="ajaxToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                    <span id="ajaxToastMessage">Success!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('website.layouts.partials.footer')

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('products.list') }}" class="bottom-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('cart.index') }}" class="bottom-nav-item position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Cart</span>
            <span class="position-absolute align-items-center justify-content-center d-flex badge rounded-pill bg-danger" style="top: 2px; right: 20%; font-size: 0.6rem; width: 18px; height: 18px; pointer-events: none;">
                @auth('customer')
                    {{ \App\Models\Cart::where('customer_id', Auth::guard('customer')->id())->sum('quantity') }}
                @else
                    0
                @endauth
            </span>
        </a>
        <a href="{{ route('profile.index') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fa-regular fa-user"></i>
            <span>Account</span>
        </a>
    </nav>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 position-absolute top-0 end-0 z-3">
                    <button type="button" class="btn-close bg-white rounded-circle p-2 shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-5 bg-light p-4 d-flex align-items-center justify-content-center" style="min-height: 300px;">
                            <img src="" id="qv-image" class="img-fluid" alt="Product" style="max-height: 250px; object-fit: contain;">
                        </div>
                        <div class="col-md-7 p-4 p-md-5">
                            <span id="qv-category" class="text-uppercase text-muted small fw-bold tracking-wider"></span>
                            <h3 id="qv-title" class="fw-bolder text-dark mb-3 mt-1"></h3>
                            
                            <div class="d-flex align-items-baseline mb-3">
                                <span id="qv-price" class="fs-2 fw-bolder text-dark me-2"></span>
                                <span id="qv-mrp" class="text-muted text-decoration-line-through me-3"></span>
                                <span id="qv-stock-badge" class="badge bg-success rounded-pill px-2 py-1"></span>
                            </div>
                            
                            <p id="qv-description" class="text-muted mb-4 lh-base"></p>

                            @auth('customer')
                            <form action="{{ route('cart.add') }}" method="POST" class="ajax-cart-form" id="qv-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" id="qv-product-id" value="">
                                <input type="hidden" name="quantity" value="1">
                                <div class="d-grid mt-4">
                                    <button type="submit" id="qv-add-btn" class="btn btn-green btn-lg fw-bold rounded-pill shadow-sm">
                                        <i class="fa-solid fa-cart-plus me-2"></i> Add to Cart
                                    </button>
                                </div>
                            </form>
                            @else
                            <div class="d-grid mt-4">
                                <a href="{{ route('login') }}" class="btn btn-green btn-lg fw-bold rounded-pill shadow-sm">
                                    <i class="fa-solid fa-lock me-2"></i> Login to Buy
                                </a>
                            </div>
                            @endauth
                            <div class="mt-3 text-center">
                                <a href="#" id="qv-details-btn" class="text-success text-decoration-none fw-medium small">View Full Details <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('ajaxToast');
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });

            // Quick View Logic
            const qvModal = new bootstrap.Modal(document.getElementById('quickViewModal'));
            document.querySelectorAll('.quick-view-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('qv-title').innerText = this.getAttribute('data-name');
                    document.getElementById('qv-category').innerText = this.getAttribute('data-category');
                    document.getElementById('qv-price').innerText = '₹' + this.getAttribute('data-price');
                    document.getElementById('qv-image').src = this.getAttribute('data-image');
                    document.getElementById('qv-description').innerText = this.getAttribute('data-description');
                    document.getElementById('qv-details-btn').href = this.getAttribute('data-url');
                    
                    const mrp = parseFloat(this.getAttribute('data-mrp'));
                    const price = parseFloat(this.getAttribute('data-price'));
                    if(mrp > price) {
                        document.getElementById('qv-mrp').innerText = '₹' + mrp;
                    } else {
                        document.getElementById('qv-mrp').innerText = '';
                    }

                    const stock = parseInt(this.getAttribute('data-stock'));
                    const badge = document.getElementById('qv-stock-badge');
                    const formBtn = document.getElementById('qv-add-btn');

                    if(stock > 0) {
                        badge.innerText = 'In Stock';
                        badge.className = 'badge bg-success rounded-pill px-2 py-1';
                        if(formBtn) {
                            formBtn.disabled = false;
                            formBtn.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i> Add to Cart';
                            formBtn.className = 'btn btn-green btn-lg fw-bold rounded-pill shadow-sm';
                        }
                    } else {
                        badge.innerText = 'Out of Stock';
                        badge.className = 'badge bg-danger rounded-pill px-2 py-1';
                        if(formBtn) {
                            formBtn.disabled = true;
                            formBtn.innerHTML = '<i class="fa-solid fa-ban me-2"></i> Out of Stock';
                            formBtn.className = 'btn btn-secondary btn-lg fw-bold rounded-pill shadow-sm';
                        }
                    }

                    const qvProductId = document.getElementById('qv-product-id');
                    if(qvProductId) {
                        qvProductId.value = this.getAttribute('data-id');
                    }

                    qvModal.show();
                });
            });

            // Universal fetch wrapper
            function handleAjaxForm(form, successCallback) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    
                    const btn = e.submitter || form.querySelector('button[type="submit"]');
                    const originalBtnContent = btn.innerHTML;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    btn.disabled = true;

                    // If it's a + or - button updating quantity, set the hidden field or append to formData
                    const formData = new FormData(form);
                    if (btn.name && btn.value) {
                        formData.set(btn.name, btn.value);
                    }
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        btn.innerHTML = originalBtnContent;
                        btn.disabled = false;

                        if (data.success) {
                            // Update cart count badge
                            const badge = document.getElementById('cart-badge-count');
                            if(badge) badge.innerText = data.cart_count;

                            // Show toast
                            document.getElementById('ajaxToastMessage').innerText = data.message;
                            toastEl.classList.remove('text-bg-danger');
                            toastEl.classList.add('text-bg-success');
                            toastEl.querySelector('.fa-solid').classList.replace('fa-circle-xmark', 'fa-circle-check');
                            toast.show();

                            // Close modal if it's the quick view form
                            if(form.id === 'qv-cart-form') {
                                qvModal.hide();
                            }

                            if (successCallback) successCallback(data, form, btn);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        btn.innerHTML = originalBtnContent;
                        btn.disabled = false;
                        
                        document.getElementById('ajaxToastMessage').innerText = 'Something went wrong. Please try again or refresh.';
                        toastEl.classList.remove('text-bg-success');
                        toastEl.classList.add('text-bg-danger');
                        toastEl.querySelector('.fa-solid').classList.replace('fa-circle-check', 'fa-circle-xmark');
                        toast.show();
                        
                        if (error.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                });
            }

            document.querySelectorAll('.ajax-cart-form').forEach(form => {
                handleAjaxForm(form);
            });

            document.querySelectorAll('.ajax-cart-update-form').forEach(form => {
                handleAjaxForm(form, function(data, formElement, submitterBtn) {
                    // Format currency helper
                    const formatIN = (num) => '₹' + parseFloat(num).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    
                    // Update input quantity
                    let qtyInput = formElement.querySelector('.qty-input');
                    if(qtyInput && submitterBtn.value) {
                        qtyInput.value = submitterBtn.value;
                    }
                    
                    // Update the buttons so they hold the correct values next time
                    const decreBtn = formElement.querySelector('button[value="'+(parseInt(submitterBtn.value)-1)+'"]');
                    const increBtn = formElement.querySelector('button[value="'+(parseInt(submitterBtn.value)+1)+'"]');
                    
                    if (submitterBtn.value <= 1) {
                        formElement.querySelector('button[name="quantity"][value="0"]').disabled = true;
                    } else {
                        const minusBtn = formElement.querySelector('button[name="quantity"]:first-of-type');
                        minusBtn.disabled = false;
                        minusBtn.value = parseInt(qtyInput.value) - 1;
                    }
                    
                    const plusBtn = formElement.querySelector('button[name="quantity"]:last-of-type');
                    plusBtn.value = parseInt(qtyInput.value) + 1;

                    // Update totals
                    if(document.getElementById('summary-total-mrp')) {
                        document.getElementById('summary-total-mrp').innerText = formatIN(data.cart_mrp);
                        document.getElementById('summary-discount').innerText = '- ' + formatIN(data.savings);
                        
                        if(data.delivery_charge == 0) {
                            document.getElementById('summary-delivery-charge').innerHTML = 'FREE <small class="text-muted text-decoration-line-through">₹50</small>';
                            document.getElementById('summary-delivery-charge').className = 'text-success fw-bold';
                        } else {
                            document.getElementById('summary-delivery-charge').innerText = formatIN(data.delivery_charge);
                            document.getElementById('summary-delivery-charge').className = 'text-danger fw-bold';
                        }
                        
                        document.getElementById('summary-final-total').innerText = formatIN(data.final_total);
                        document.getElementById('summary-savings').innerText = formatIN(data.savings);
                    }
                });
            });

            // Live Search Logic
            const searchInput = document.getElementById('live-search-input');
            const searchResults = document.getElementById('live-search-results');
            const searchList = document.getElementById('search-results-list');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();

                    if (query.length < 2) {
                        searchResults.classList.add('d-none');
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        fetch(`{{ route('search.live') }}?query=${encodeURIComponent(query)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            searchList.innerHTML = '';
                            
                            if (data.length > 0) {
                                data.forEach(product => {
                                    const image = product.image_url;
                                    const url = `{{ url('products') }}/${product.slug}`;
                                    const price = `₹${parseFloat(product.price).toLocaleString('en-IN')}`;
                                    
                                    searchList.innerHTML += `
                                        <a href="${url}" class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom hover-bg-light transition-smooth">
                                            <img src="${image}" alt="${product.name}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: contain;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-dark fw-bold text-truncate" style="max-width: 250px;">${product.name}</h6>
                                                <small class="text-success fw-bolder">${price}</small>
                                            </div>
                                        </a>
                                    `;
                                });
                            } else {
                                searchList.innerHTML = `
                                    <div class="p-4 text-center text-muted">
                                        <i class="fa-solid fa-box-open fs-3 mb-2 opacity-50"></i>
                                        <p class="mb-0">No products found matching "<b>${query}</b>"</p>
                                    </div>
                                `;
                            }
                            
                            searchResults.classList.remove('d-none');
                        })
                        .catch(err => console.error('Live search error:', err));
                    }, 300); // 300ms debounce
                });

                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.add('d-none');
                    }
                });
            }
        });
    </script>
    @stack('scripts')

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- WhatsApp Floating Button & Chatbot Widget           --}}
    {{-- ═══════════════════════════════════════════════════ --}}

    {{-- WhatsApp Button --}}
    <a id="whatsapp-float-btn"
       href="https://wa.me/919876543210?text=Hello%20Supply%20Kart%2C%20I%20need%20help%20with%20my%20order."
       target="_blank" rel="noopener noreferrer"
       title="Chat with us on WhatsApp"
       aria-label="WhatsApp Support">
        <div class="wa-pulse-ring"></div>
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    {{-- Chatbot Toggle Button --}}
    <button id="chatbot-toggle-btn" title="Ask Support" aria-label="Open Chatbot">
        <span id="chatbot-icon-open"><i class="fa-solid fa-comment-dots"></i></span>
        <span id="chatbot-icon-close" style="display:none;"><i class="fa-solid fa-xmark"></i></span>
        <span class="chatbot-notif-dot" id="chatbot-notif-dot"></span>
    </button>

    {{-- Chatbot Widget --}}
    <div id="chatbot-widget" class="chatbot-hidden">
        <div class="chatbot-header">
            <div class="chatbot-header-avatar">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="chatbot-header-info">
                <div class="chatbot-header-name">Kart Assistant</div>
                <div class="chatbot-header-status"><span class="chatbot-online-dot"></span> Online &bull; Typically replies instantly</div>
            </div>
            <button class="chatbot-close-btn" id="chatbot-close-btn" aria-label="Close chat"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="chatbot-body" id="chatbot-body">
            {{-- Messages render here --}}
        </div>

        <div class="chatbot-quick-replies" id="chatbot-quick-replies"></div>

        <div class="chatbot-footer">
            <input type="text" id="chatbot-input" placeholder="Type your message…" autocomplete="off" maxlength="200">
            <button id="chatbot-send-btn" aria-label="Send message"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>

    {{-- ── Styles ── --}}
    <style>
        /* ── WhatsApp Floating Button ── */
        #whatsapp-float-btn {
            position: fixed;
            bottom: 90px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #25d366, #128c7e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
            z-index: 9990;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
        }
        #whatsapp-float-btn:hover {
            transform: translateY(-4px) scale(1.08);
            box-shadow: 0 10px 28px rgba(37, 211, 102, 0.55);
            color: white;
        }
        .wa-pulse-ring {
            position: absolute;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            background: rgba(37, 211, 102, 0.35);
            animation: wa-pulse 2.2s ease-out infinite;
            pointer-events: none;
        }
        @keyframes wa-pulse {
            0%   { transform: scale(1); opacity: 0.75; }
            80%  { transform: scale(1.8); opacity: 0; }
            100% { opacity: 0; }
        }

        /* ── Chatbot Toggle Button ── */
        #chatbot-toggle-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669, #047857);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
            z-index: 9999;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #chatbot-toggle-btn:hover {
            transform: translateY(-3px) scale(1.08);
            box-shadow: 0 10px 28px rgba(5, 150, 105, 0.5);
        }
        .chatbot-notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 12px;
            height: 12px;
            background: #f59e0b;
            border: 2px solid white;
            border-radius: 50%;
            animation: notif-bounce 1.4s ease infinite;
        }
        @keyframes notif-bounce {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.25); }
        }

        /* ── Chatbot Widget ── */
        #chatbot-widget {
            position: fixed;
            bottom: 90px;
            right: 88px;
            width: 360px;
            max-height: 560px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.07);
            display: flex;
            flex-direction: column;
            z-index: 9998;
            overflow: hidden;
            transform-origin: bottom right;
            transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease;
        }
        .chatbot-hidden {
            transform: scale(0.7) translateY(20px) !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
        @media (max-width: 480px) {
            #chatbot-widget {
                width: calc(100vw - 16px);
                right: 8px;
                bottom: 86px;
            }
        }

        /* Header */
        .chatbot-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 18px;
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            border-radius: 20px 20px 0 0;
            flex-shrink: 0;
        }
        .chatbot-header-avatar {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            border: 2px solid rgba(255,255,255,0.4);
        }
        .chatbot-header-name {
            font-weight: 700;
            font-size: 15px;
            letter-spacing: -0.02em;
        }
        .chatbot-header-status {
            font-size: 11.5px;
            opacity: 0.85;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 2px;
        }
        .chatbot-online-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #a7f3d0;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(167,243,208,0.4);
            animation: online-blink 2s ease infinite;
        }
        @keyframes online-blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }
        .chatbot-close-btn {
            margin-left: auto;
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .chatbot-close-btn:hover { background: rgba(255,255,255,0.3); }

        /* Body / message area */
        .chatbot-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f0fdf4;
            scroll-behavior: smooth;
        }
        .chatbot-body::-webkit-scrollbar { width: 4px; }
        .chatbot-body::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }

        /* Message bubbles */
        .cb-msg {
            display: flex;
            flex-direction: column;
            max-width: 82%;
            animation: cb-msg-in 0.3s cubic-bezier(0.175,0.885,0.32,1.275) both;
        }
        @keyframes cb-msg-in {
            from { opacity: 0; transform: translateY(10px) scale(0.95); }
            to   { opacity: 1; transform: none; }
        }
        .cb-msg.bot  { align-self: flex-start; }
        .cb-msg.user { align-self: flex-end; }

        .cb-bubble {
            padding: 10px 14px;
            border-radius: 16px;
            font-size: 13.5px;
            line-height: 1.55;
            word-break: break-word;
        }
        .cb-msg.bot  .cb-bubble { background: #ffffff; color: #0f172a; border-bottom-left-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .cb-msg.user .cb-bubble { background: linear-gradient(135deg, #059669, #047857); color: white; border-bottom-right-radius: 4px; }
        .cb-time { font-size: 10px; color: #94a3b8; margin-top: 3px; padding: 0 4px; }
        .cb-msg.user .cb-time { text-align: right; }

        /* Typing indicator */
        .cb-typing {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 10px 14px;
            background: #ffffff;
            border-radius: 16px;
            border-bottom-left-radius: 4px;
            width: fit-content;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .cb-typing span {
            width: 7px; height: 7px;
            background: #059669;
            border-radius: 50%;
            display: inline-block;
            animation: cb-dot 1.2s ease-in-out infinite;
        }
        .cb-typing span:nth-child(2) { animation-delay: 0.2s; }
        .cb-typing span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes cb-dot {
            0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
            40%            { transform: scale(1.1); opacity: 1; }
        }

        /* Quick replies */
        .chatbot-quick-replies {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 8px 14px;
            background: #f0fdf4;
            border-top: 1px solid #d1fae5;
            min-height: 0;
            transition: min-height 0.2s;
        }
        .cb-qr-btn {
            background: #ffffff;
            border: 1.5px solid #059669;
            color: #059669;
            border-radius: 999px;
            padding: 5px 13px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            font-family: 'Outfit', sans-serif;
        }
        .cb-qr-btn:hover {
            background: #059669;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(5,150,105,0.3);
        }

        /* Footer input */
        .chatbot-footer {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 14px;
            background: white;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        #chatbot-input {
            flex: 1;
            border: 1.5px solid #e2e8f0;
            border-radius: 999px;
            padding: 9px 16px;
            font-size: 13.5px;
            outline: none;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.2s;
            background: #f8fafc;
        }
        #chatbot-input:focus { border-color: #059669; background: white; }
        #chatbot-send-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669, #047857);
            border: none;
            color: white;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            flex-shrink: 0;
        }
        #chatbot-send-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(5,150,105,0.35);
        }
    </style>

    {{-- ── Chatbot Script ── --}}
    <script>
    (function () {
        // ── Configuration ──────────────────────────────────────────
        const WHATSAPP_NUMBER = '919876543210';
        const BOT_NAME        = 'Kart Assistant';

        // ── Knowledge Base ──────────────────────────────────────────
        const KB = [
            {
                patterns: ['hello','hi','hey','hii','helo','greet','good morning','good evening'],
                response: `Hello! 👋 Welcome to **Supply Kart**! I'm your virtual assistant. How can I help you today?`,
                quick: ['📦 Track Order','🚚 Delivery Info','💰 Payment Methods','🔁 Return Policy','📞 Contact Support']
            },
            {
                patterns: ['order','place order','how to order','buy','purchase','ordering'],
                response: `🛒 **How to Place an Order:**\n1. Browse our products or search by name\n2. Add items to your cart\n3. Go to Checkout\n4. Enter your delivery address\n5. Choose COD or Pay Online\n6. Click **Confirm Order** — done! You'll receive a confirmation instantly.`,
                quick: ['🚚 Delivery Time','💰 Payment Methods','📦 Track Order']
            },
            {
                patterns: ['track','order status','where is my order','tracking','my order'],
                response: `📦 **Track Your Order:**\nLogin to your account → Go to **My Orders** → Click on any order to see its live status.\n\nOrder statuses: Pending → Processing → Packed → Out for Delivery → Delivered`,
                quick: ['📞 Contact Support','🔁 Return Policy']
            },
            {
                patterns: ['delivery','shipping','how long','when will','dispatch','time'],
                response: `🚚 **Delivery Info:**\n• We offer **fast delivery** for B2B wholesale orders\n• Estimated delivery: **1–3 business days** based on your location\n• **Free delivery** on orders above ₹999!\n• Below ₹999: flat ₹90 delivery charge`,
                quick: ['💰 Payment Methods','📦 Track Order','📞 Contact Support']
            },
            {
                patterns: ['payment','pay','upi','card','cod','online payment','razorpay','net banking'],
                response: `💳 **Payment Methods We Accept:**\n• 💵 Cash on Delivery (COD)\n• 📱 UPI (GPay, PhonePe, Paytm)\n• 💳 Credit / Debit Cards\n• 🏦 Net Banking\n• 💼 Wallets\n\nAll online payments are secured via **Razorpay** — 100% safe & encrypted.`,
                quick: ['📦 Track Order','🚚 Delivery Info','🔁 Return Policy']
            },
            {
                patterns: ['return','refund','cancel','replace','exchange','money back'],
                response: `🔁 **Return & Refund Policy:**\n• Returns accepted within **7 days** of delivery\n• Item must be unused and in original packaging\n• Damaged/wrong items: full refund or free replacement\n• Refund processed in **3–5 business days**\n\nTo initiate a return, contact our support team on WhatsApp.`,
                quick: ['📞 Contact Support','💰 Payment Methods']
            },
            {
                patterns: ['contact','support','help','phone','email','whatsapp','talk','agent','human'],
                response: `📞 **Contact Our Support Team:**\n• 📱 WhatsApp: +91 98765 43210\n• 📧 Email: support@supplykart.in\n• 🕘 Working Hours: Mon–Sat, 9AM–7PM\n\nClick the **WhatsApp** button below to chat instantly! 👇`,
                quick: ['💬 Open WhatsApp','📦 Track Order','🔁 Return Policy']
            },
            {
                patterns: ['minimum','min order','moq','quantity', 'minimum order'],
                response: `📋 **Minimum Order:**\n• Minimum order value: **₹199**\n• No minimum quantity limit on most products\n• Bulk discount available on orders above ₹5,000 — contact us for custom pricing!`,
                quick: ['📞 Contact Support','💰 Payment Methods','🚚 Delivery Info']
            },
            {
                patterns: ['discount','offer','sale','promo','coupon','deal','wholesale price'],
                response: `🏷️ **Discounts & Offers:**\n• Wholesale prices already applied on all products\n• **Free delivery** on orders ₹999+\n• Bulk order discounts available — contact us via WhatsApp for a custom quote!\n• Watch for seasonal sales & special offers on our homepage.`,
                quick: ['📞 Contact Support','📦 Track Order']
            },
            {
                patterns: ['register','sign up','create account','new account','join'],
                response: `👤 **How to Register:**\n1. Click **Login / Register** in the top navbar\n2. Enter your mobile number\n3. Verify with OTP\n4. Fill in your business details\n5. Start shopping immediately!\n\nRegistration is **free** and takes less than 2 minutes.`,
                quick: ['🛒 How to Order','📞 Contact Support']
            },
            {
                patterns: ['invoice','bill','receipt','gst','tax'],
                response: `🧾 **Invoice & GST:**\n• GST invoices are automatically generated for every order\n• Download your invoice anytime from **My Orders** → Order Details → **Download Invoice**\n• PDF format, ready for your business records`,
                quick: ['📦 Track Order','📞 Contact Support']
            },
            {
                patterns: ['about','who are you','supply kart','supplykart','what is supply kart'],
                response: `🏪 **About Supply Kart:**\nSupply Kart is a **B2B wholesale platform** built for kirana stores, pan-gallas, and small businesses. We offer:\n• ✅ Quality products at wholesale prices\n• ✅ Fast delivery\n• ✅ Easy online ordering\n• ✅ Trusted by hundreds of retailers\n\nOur mission: *Empowering small businesses with smart supply chain solutions.*`,
                quick: ['🛒 How to Order','📞 Contact Support']
            }
        ];

        const DEFAULT_RESPONSE = {
            response: `🤔 Hmm, I'm not sure about that. Here are some things I can help you with:`,
            quick: ['📦 Track Order','🚚 Delivery Info','💰 Payment Methods','🔁 Return Policy','📞 Contact Support']
        };

        const QUICK_REPLY_MAP = {
            '📦 Track Order':     'track order',
            '🚚 Delivery Info':   'delivery',
            '💰 Payment Methods': 'payment',
            '🔁 Return Policy':   'return',
            '📞 Contact Support': 'contact support',
            '💬 Open WhatsApp':   '__whatsapp__',
            '🛒 How to Order':    'how to order',
        };

        // ── Helpers ────────────────────────────────────────────────
        function fmtTime() {
            const d = new Date();
            return d.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' });
        }
        function mdToHtml(text) {
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
        }

        function findAnswer(text) {
            const lower = text.toLowerCase().trim();
            for (const item of KB) {
                for (const pattern of item.patterns) {
                    if (lower.includes(pattern)) return item;
                }
            }
            return DEFAULT_RESPONSE;
        }

        // ── DOM Refs ───────────────────────────────────────────────
        const widget      = document.getElementById('chatbot-widget');
        const toggleBtn   = document.getElementById('chatbot-toggle-btn');
        const closeBtn    = document.getElementById('chatbot-close-btn');
        const body        = document.getElementById('chatbot-body');
        const input       = document.getElementById('chatbot-input');
        const sendBtn     = document.getElementById('chatbot-send-btn');
        const qrArea      = document.getElementById('chatbot-quick-replies');
        const iconOpen    = document.getElementById('chatbot-icon-open');
        const iconClose   = document.getElementById('chatbot-icon-close');
        const notifDot    = document.getElementById('chatbot-notif-dot');
        let   isOpen      = false;
        let   typingEl    = null;

        // ── Render Functions ───────────────────────────────────────
        function appendMessage(text, sender = 'bot') {
            const wrap = document.createElement('div');
            wrap.className = `cb-msg ${sender}`;
            const bubble = document.createElement('div');
            bubble.className = 'cb-bubble';
            bubble.innerHTML = mdToHtml(text);
            const time = document.createElement('div');
            time.className = 'cb-time';
            time.textContent = fmtTime();
            wrap.appendChild(bubble);
            wrap.appendChild(time);
            body.appendChild(wrap);
            body.scrollTop = body.scrollHeight;
        }

        function showTyping() {
            const wrap = document.createElement('div');
            wrap.className = 'cb-msg bot';
            wrap.id = 'cb-typing-wrap';
            const t = document.createElement('div');
            t.className = 'cb-typing';
            t.innerHTML = '<span></span><span></span><span></span>';
            wrap.appendChild(t);
            body.appendChild(wrap);
            body.scrollTop = body.scrollHeight;
            typingEl = wrap;
        }

        function hideTyping() {
            if (typingEl) { typingEl.remove(); typingEl = null; }
        }

        function setQuickReplies(replies) {
            qrArea.innerHTML = '';
            if (!replies || replies.length === 0) return;
            replies.forEach(r => {
                const btn = document.createElement('button');
                btn.className = 'cb-qr-btn';
                btn.textContent = r;
                btn.addEventListener('click', () => handleQuickReply(r));
                qrArea.appendChild(btn);
            });
        }

        function botReply(userText) {
            showTyping();
            setTimeout(() => {
                hideTyping();
                const match = findAnswer(userText);
                appendMessage(match.response, 'bot');
                setQuickReplies(match.quick || []);
            }, 800 + Math.random() * 500);
        }

        // ── Event Handlers ─────────────────────────────────────────
        function toggleWidget() {
            isOpen = !isOpen;
            if (isOpen) {
                widget.classList.remove('chatbot-hidden');
                iconOpen.style.display  = 'none';
                iconClose.style.display = 'flex';
                if (notifDot) notifDot.style.display = 'none';
                input.focus();
            } else {
                widget.classList.add('chatbot-hidden');
                iconOpen.style.display  = 'flex';
                iconClose.style.display = 'none';
            }
        }

        function sendMessage() {
            const text = input.value.trim();
            if (!text) return;
            appendMessage(text, 'user');
            input.value = '';
            qrArea.innerHTML = '';
            botReply(text);
        }

        function handleQuickReply(label) {
            if (QUICK_REPLY_MAP[label] === '__whatsapp__') {
                window.open(`https://wa.me/${WHATSAPP_NUMBER}`, '_blank');
                return;
            }
            const query = QUICK_REPLY_MAP[label] || label;
            appendMessage(label, 'user');
            qrArea.innerHTML = '';
            botReply(query);
        }

        toggleBtn.addEventListener('click', toggleWidget);
        closeBtn.addEventListener('click', toggleWidget);
        sendBtn.addEventListener('click', sendMessage);
        input.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });

        // ── Init ───────────────────────────────────────────────────
        setTimeout(() => {
            appendMessage(`👋 Hi there! I'm **${BOT_NAME}**, Supply Kart's virtual support assistant.\n\nHow can I help you today?`, 'bot');
            setQuickReplies(['📦 Track Order','🚚 Delivery Info','💰 Payment Methods','🔁 Return Policy','📞 Contact Support']);
        }, 600);
    })();
    </script>
</body>

</html>