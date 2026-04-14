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
</body>

</html>