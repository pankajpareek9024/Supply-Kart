<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DeliveryBoyController as AdminDeliveryBoyController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\AdminController as AdminManagementController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

use Illuminate\Support\Facades\Route;

// ─── Home ────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Public Product & Category Routes ──────────────────────────────────────────
Route::get('/products', [ProductController::class, 'index'])->name('products.list');
Route::get('/categories', [ProductController::class, 'categories'])->name('categories.index');
Route::get('/search/live', [ProductController::class, 'liveSearch'])->name('search.live');
Route::get('/products/{slug}', [ProductController::class, 'details'])->name('products.details');
Route::get('/category/{slug}', [ProductController::class, 'categoryProducts'])->name('category.products');

// ─── Auth (Guests only) ───────────────────────────────────────────────────────
Route::middleware('guest:customer')->group(function () {

    // Register
    Route::get('/register', [CustomerController::class, 'index'])->name('register');
    Route::post('/send-otp', [CustomerController::class, 'sendOtp'])->name('send.otp');

    // Login
    Route::get('/login', [CustomerController::class, 'loginPage'])->name('login');
    Route::post('/login-otp', [CustomerController::class, 'loginOtp'])->name('login.otp');

    // OTP Verify
    Route::get('/otp', function () {
        if (!session('otp_mobile')) {
            return redirect()->route('login');
        }
        return view('website.auth.otp');
    })->name('otp');

    Route::post('/verify-otp', [CustomerController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [CustomerController::class, 'resendOtp'])->name('resend.otp');
});

// ─── Protected Routes (Logged in Customers only) ──────────────────────────────
Route::middleware('auth:customer')->group(function () {

    // Logout
    Route::get('/logout', [CustomerController::class, 'logout'])->name('logout');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('orders.place');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'details'])->name('orders.details');
    Route::post('/orders/{id}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('orders.review');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

});

// ═══════════════════════════════════════════════════════════════════════════════
// ────────────────────────────── ADMIN PANEL ROUTES ─────────────────────────────
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('admin')->name('admin.')->group(function () {

    // ── Guest-only (login page) ───────────────────────────────────────────────
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

        // Admin Registration
        Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AdminAuthController::class, 'register'])->name('register.post');
    });

    // ── Protected admin routes ────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Categories
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

        // Products
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::post('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');

        // Customers
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
        Route::post('/customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/assign-delivery-boy', [AdminOrderController::class, 'assignDeliveryBoy'])->name('orders.assign-delivery-boy');
        Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'downloadInvoice'])->name('orders.invoice');

        // Delivery Boys
        Route::get('/delivery-boys', [AdminDeliveryBoyController::class, 'index'])->name('delivery-boys.index');
        Route::post('/delivery-boys', [AdminDeliveryBoyController::class, 'store'])->name('delivery-boys.store');
        Route::get('/delivery-boys/{deliveryBoy}/edit', [AdminDeliveryBoyController::class, 'edit'])->name('delivery-boys.edit');
        Route::post('/delivery-boys/{deliveryBoy}', [AdminDeliveryBoyController::class, 'update'])->name('delivery-boys.update');
        Route::delete('/delivery-boys/{deliveryBoy}', [AdminDeliveryBoyController::class, 'destroy'])->name('delivery-boys.destroy');
        Route::post('/delivery-boys/{deliveryBoy}/toggle-status', [AdminDeliveryBoyController::class, 'toggleStatus'])->name('delivery-boys.toggle-status');

        // Customer Reviews
        Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/toggle-visibility', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleVisibility'])->name('reviews.toggle-visibility');
        Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Payments
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-csv', [AdminReportController::class, 'exportCsv'])->name('reports.export-csv');
        Route::get('/reports/export-pdf', [AdminReportController::class, 'exportPdf'])->name('reports.export-pdf');

        // Super Admin Only
        Route::middleware(['admin.super'])->group(function () {
            // Settings
            Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
            Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

            // Admin Management
            Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
            Route::get('/admins/create', [AdminManagementController::class, 'create'])->name('admins.create');
            Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
            Route::get('/admins/{admin}/edit', [AdminManagementController::class, 'edit'])->name('admins.edit');
            Route::post('/admins/{admin}', [AdminManagementController::class, 'update'])->name('admins.update');
            Route::delete('/admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
            Route::post('/admins/{admin}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('admins.toggle-status');
        });

        // My Profile (logged-in admin)
        Route::get('/my-profile', [AdminProfileController::class, 'index'])->name('profile.index');
        Route::post('/my-profile/update-info', [AdminProfileController::class, 'updateInfo'])->name('profile.update-info');
        Route::post('/my-profile/update-password', [AdminProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::post('/my-profile/update-avatar', [AdminProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    });
});

// ═══════════════════════════════════════════════════════════════════════════════
// ────────────────────────── DELIVERY PANEL ROUTES ──────────────────────────────
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('delivery')->name('delivery.')->group(function () {
    
    // Auth Routes
    Route::get('/login', [\App\Http\Controllers\Delivery\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Delivery\AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [\App\Http\Controllers\Delivery\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Delivery\AuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [\App\Http\Controllers\Delivery\AuthController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware(['delivery'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Delivery\DashboardController::class, 'index'])->name('dashboard');

        // Orders
        Route::get('/orders', [\App\Http\Controllers\Delivery\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/completed', [\App\Http\Controllers\Delivery\OrderController::class, 'completed'])->name('orders.completed');
        Route::get('/orders/{id}', [\App\Http\Controllers\Delivery\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/update-status', [\App\Http\Controllers\Delivery\OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Delivery\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [\App\Http\Controllers\Delivery\ProfileController::class, 'update'])->name('profile.update');
    });
});
