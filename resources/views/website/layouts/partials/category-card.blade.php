<a href="{{ route('products.list') }}" class="text-decoration-none">
    <div class="card category-card text-center h-100 border-0 bg-white">
        <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
            <div class="category-icon-wrapper rounded-circle shadow-sm d-flex align-items-center justify-content-center mb-3" style="width: 84px; height: 84px; background: linear-gradient(145deg, #ffffff, #f3f4f6);">
                <img src="{{ $image ?? asset('images/default-category.svg') }}" alt="Category" class="img-fluid p-2" style="max-height: 55px; transition: transform 0.3s ease;">
            </div>
            <h6 class="card-title text-dark fw-bold mb-0 category-title">{{ $title ?? 'Category Name' }}</h6>
        </div>
    </div>
</a>

<style>
    .category-card {
        border-radius: var(--radius-md) !important;
        transition: var(--transition-bounce) !important;
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }
    .category-card:hover {
        background: linear-gradient(135deg, var(--primary-green), var(--dark-green)) !important;
        transform: translateY(-8px) !important;
        box-shadow: 0 15px 30px rgba(5, 150, 105, 0.2) !important;
    }
    .category-card:hover .category-icon-wrapper {
        background: white !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    .category-card:hover img {
        transform: scale(1.15) rotate(5deg);
    }
    .category-card:hover .category-title {
        color: white !important;
        transform: translateY(2px);
    }
    .category-title {
        transition: var(--transition-smooth);
    }
</style>
