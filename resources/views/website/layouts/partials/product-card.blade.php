<div class="card h-100 position-relative border-0 product-card">
    <div class="card-img-wrapper position-relative overflow-hidden p-4 bg-white rounded-top" style="height: 220px; display: flex; align-items: center; justify-content: center;">
        <!-- 20 Min Delivery Badge -->
        <div class="position-absolute top-0 start-0 m-3 z-1">
            <span class="badge-20min shadow-sm"><i class="fa-solid fa-bolt me-1 text-danger"></i> 20 MIN</span>
        </div>
        
        <!-- Discount Badge -->
        @if(isset($discount) && $discount > 0)
            <div class="position-absolute top-0 end-0 m-3 z-1">
                <span class="badge bg-danger rounded-pill px-2 py-1 shadow-sm">{{ $discount }}% OFF</span>
            </div>
        @endif

        <a href="{{ isset($slug) ? route('products.details', $slug) : '#' }}" class="text-decoration-none d-block">
            <img src="{{ $image ?? asset('images/default-product.svg') }}" class="product-img img-fluid" alt="Product Image" style="object-fit: contain; max-height: 160px; transition: transform 0.4s ease;">
        </a>
        
        <!-- Hover Quick Action -->
        <div class="quick-action position-absolute bottom-0 start-0 w-100 p-2 opacity-0 text-center transition-smooth" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(4px);">
            <button class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 quick-view-btn"
                data-id="{{ $id ?? '' }}"
                data-name="{{ $title ?? 'Product' }}"
                data-price="{{ $price ?? 0 }}"
                data-mrp="{{ $originalPrice ?? 0 }}"
                data-image="{{ $image ?? asset('images/default-product.svg') }}"
                data-category="{{ $category ?? 'General' }}"
                data-description="{{ $description ?? 'Premium quality wholesale item.' }}"
                data-stock="{{ $stock ?? 10 }}"
                data-url="{{ isset($slug) ? route('products.details', $slug) : '#' }}"
            >
                <i class="fa-regular fa-eye me-1"></i> Quick View
            </button>
        </div>
    </div>
    
    <div class="card-body d-flex flex-column pt-3">
        <div class="text-muted small mb-1 fw-medium text-uppercase" style="letter-spacing: 0.5px; font-size: 0.7rem;">{{ $category ?? 'General' }}</div>
        <h6 class="card-title fw-bold mb-2 lh-base">
            <a href="{{ isset($slug) ? route('products.details', $slug) : '#' }}" class="text-dark text-decoration-none product-title d-block" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $title ?? 'Sample Product Item Name' }}
            </a>
        </h6>
        
        <div class="mb-3 mt-auto">
            <div class="d-flex align-items-baseline mb-1">
                <span class="fs-4 fw-bolder text-dark">₹{{ $price ?? '150' }}</span>
                @if(isset($originalPrice))
                    <span class="text-muted text-decoration-line-through ms-2 small">₹{{ $originalPrice }}</span>
                @endif
            </div>
            <div class="small fw-bold text-success bg-success bg-opacity-10 d-inline-block px-2 py-1 rounded">
                <i class="fa-solid fa-arrow-trend-up me-1"></i> Margin: {{ $margin ?? '15' }}%
            </div>
        </div>

        @auth('customer')
            @if(isset($stock) && $stock <= 0)
                <button class="btn btn-outline-danger w-100 fw-bold border-2 d-flex align-items-center justify-content-center opacity-50" disabled>
                    <i class="fa-solid fa-ban me-2"></i> Out of Stock
                </button>
            @else
                <form action="{{ route('cart.add') }}" method="POST" class="mt-auto w-100 ajax-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $id ?? '' }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-outline-success w-100 fw-bold border-2 d-flex align-items-center justify-content-center btn-add-cart">
                        <i class="fa-solid fa-plus me-2"></i> Add
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-success w-100 fw-bold border-2 d-flex align-items-center justify-content-center btn-add-cart">
                <i class="fa-solid fa-plus me-2"></i> Add
            </a>
        @endauth
    </div>
</div>

<style>
    .product-card {
        border-radius: var(--radius-md) !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
        transition: var(--transition-bounce) !important;
        border: 1px solid rgba(0,0,0,0.03) !important;
    }
    .product-card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-color: rgba(5, 150, 105, 0.2) !important;
    }
    .product-card:hover .product-img {
        transform: scale(1.1);
    }
    .product-title:hover {
        color: var(--primary-green) !important;
    }
    .product-card:hover .quick-action {
        opacity: 1 !important;
        transform: translateY(-10px);
    }
    .btn-add-cart {
        border-radius: var(--radius-sm);
        transition: all 0.3s;
    }
    .btn-add-cart:hover {
        background: var(--primary-green);
        color: white;
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
    }
    .transition-smooth {
        transition: all 0.3s ease;
    }
</style>
