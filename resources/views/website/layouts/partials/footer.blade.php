<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row gy-4">
            <!-- Brand Info -->
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('home') }}" class="text-decoration-none h4 text-white d-flex align-items-center mb-3">
                    <i class="fa-solid fa-cart-shopping me-2 text-primary-orange"></i>
                    Supply <span class="text-primary-orange ms-1">Kart</span>
                </a>
                <p class="text-secondary mb-4">
                    Your trusted B2B partner for wholesale products. Equipping pan-galla and kirana stores with quality
                    goods at the best wholesale rates with lightning fast 20-minute delivery.
                </p>
                <div class="d-flex gap-3">
                    <a href="#"
                        class="text-white text-decoration-none bg-secondary bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width:36px; height:36px;"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"
                        class="text-white text-decoration-none bg-secondary bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width:36px; height:36px;"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"
                        class="text-white text-decoration-none bg-secondary bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width:36px; height:36px;"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"
                        class="text-white text-decoration-none bg-secondary bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width:36px; height:36px;"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="mb-3 text-white">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}"
                            class="text-secondary text-decoration-none hover-white">Home</a></li>
                    <li class="mb-2"><a href="{{ route('products.list') }}"
                            class="text-secondary text-decoration-none hover-white">All Products</a></li>
                    <li class="mb-2"><a href="{{ route('cart.index') }}"
                            class="text-secondary text-decoration-none hover-white">View Cart</a></li>
                    <li class="mb-2"><a href="{{ route('orders.index') }}"
                            class="text-secondary text-decoration-none hover-white">Track Order</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="mb-3 text-white">Categories</h5>
                <ul class="list-unstyled">
                    @php $footerCategories = \App\Models\Category::take(5)->get(); @endphp
                    @foreach($footerCategories as $fcat)
                    <li class="mb-2"><a href="{{ route('products.list', ['category' => $fcat->slug]) }}" class="text-secondary text-decoration-none hover-white">{{ $fcat->name }}</a>
                    </li>
                    @endforeach
                    <li class="mb-2"><a href="{{ route('categories.index') }}" class="text-primary-green fw-bold text-decoration-none hover-white">View All Categories <i class="fa-solid fa-arrow-right ms-1 small"></i></a></li>
                </ul>
            </div>

            <!-- Contact Setup -->
            <div class="col-lg-4 col-md-6">
                <h5 class="mb-3 text-white">Contact Us</h5>
                <ul class="list-unstyled text-secondary">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fa-solid fa-location-dot mt-1 me-3 text-primary-orange"></i>
                        <span>123 Wholesale Market, Business District, New Delhi, 110001</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="fa-solid fa-phone me-3 text-primary-orange"></i>
                        <span>+91 98765 43210</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="fa-solid fa-envelope me-3 text-primary-orange"></i>
                        <span>support@supplykart.in</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 text-secondary small">&copy; {{ date('Y') }} Supply Kart. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_Pay_logo.svg/512px-Apple_Pay_logo.svg.png?20200813142211"
                    alt="Payment Methods" height="24" class="opacity-75 me-2 filter-invert">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Rupay-Logo.png/640px-Rupay-Logo.png"
                    alt="Rupay" height="24" class="opacity-75 me-2 bg-white rounded p-1">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/UPI-Logo-vector.svg/1024px-UPI-Logo-vector.svg.png"
                    alt="UPI" height="24" class="opacity-75 bg-white rounded p-1">
            </div>
        </div>
    </div>
</footer>

<style>
    .hover-white:hover {
        color: white !important;
    }
</style>