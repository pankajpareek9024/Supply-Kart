<a href="{{ route('products.list') }}" class="text-decoration-none">
    <div class="category-card-modern text-center d-flex flex-column align-items-center justify-content-center">
        <div class="category-icon-wrapper rounded-circle shadow-sm d-flex align-items-center justify-content-center mb-2" style="width: 72px; height: 72px; background: linear-gradient(145deg, #ffffff, #f3f4f6);">
            <img src="{{ $image ?? asset('images/default-category.svg') }}" alt="Category" class="img-fluid p-2" style="max-height: 40px; transition: transform 0.3s ease;">
        </div>
        <h6 class="text-dark fw-bold mb-0 category-title" style="font-size: 0.8rem; line-height: 1.1;">{{ $title ?? 'Category Name' }}</h6>
    </div>
</a>

<style>
    .category-card-modern {
        transition: var(--transition-bounce) !important;
        cursor: pointer;
    }
    .category-card-modern:hover .category-icon-wrapper {
        background: linear-gradient(135deg, var(--light-green), #ffffff) !important;
        transform: translateY(-4px) !important;
        box-shadow: 0 8px 15px rgba(5, 150, 105, 0.15) !important;
    }
    .category-card-modern:hover img {
        transform: scale(1.1) !important;
    }
    .category-card-modern:hover .category-title {
        color: var(--primary-green) !important;
    }
    .category-title {
        transition: var(--transition-smooth);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
