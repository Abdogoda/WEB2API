<?php

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/categories/{category}', [ShopController::class, 'category'])->name('category.show');
Route::get('/products/{product}', [ShopController::class, 'product'])->name('product.show');

Route::view('/cart', 'user.cart')->name('cart');
Route::post('contact-us', [HomeController::class, 'contactUs'])->name('contact-us');

// Authentication routes
Route::middleware('guest')->group(function () {
  Route::view('/login', 'auth.login')->name('login');
  Route::post('/login', [AuthenticationController::class, 'login']);

  Route::view('/register', 'auth.register')->name('register');
  Route::post('/register', [AuthenticationController::class, 'register']);

  Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
  Route::post('/forgot-password', [ForgotPasswordController::class, 'forgot'])->name('password.email');

  Route::view('/reset-password', 'auth.reset-password')->name('password.reset');
  Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

// Customer authenticated routes
Route::middleware(['auth'])->group(function () {

  // Profile
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
  Route::put('/profile', [ProfileController::class, 'update']);
  Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.change');
  Route::post('/delete-account', [ProfileController::class, 'deleteAccount'])->name('delete-account');

  Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
  Route::post('/logout-other-devices', [AuthenticationController::class, 'logoutOtherDevices'])->name('logout-other-devices');

  // Email verification
  Route::post("/send-verification", [VerifyAccountController::class, 'sendVerificationEmail'])->name('verification.send');
  Route::view("/verify-account", 'auth.verify-account')->name('verification.verify');
  Route::post("/verify-account", [VerifyAccountController::class, 'verifyAccount']);

  // Orders
  Route::controller(OrderController::class)->middleware('verified:profile')->prefix('orders')->name('orders.')->group(function () {
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::put('/{order}', [OrderController::class, 'update'])->name('update');
    Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
  });

  // ==============  Admin routes ================
  Route::middleware('permission:' . PermissionsEnum::VIEW_DASHBOARD->value)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::get('users/{user}/change-role', [UserController::class, 'changeRole']);
    Route::resource('roles', RoleController::class)->only(['index', 'store', 'update', 'destroy']);

    // Categories management
    Route::resource('categories', CategoryController::class);

    // Products management
    Route::resource('products', ProductController::class);
    Route::post('products/images/upload', [ProductController::class, 'uploadImages'])->name('products.images.store');
    Route::delete('products/images/{image}/delete', [ProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::put('products/images/{image}/primary', [ProductController::class, 'setPrimary'])->name('products.images.primary');

    // Messages management
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
    Route::put('messages/{message}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::post('messages/mark-all-as-read', [MessageController::class, 'markAllAsRead'])->name('messages.mark-all-as-read');

    // Orders management
    Route::controller(AdminOrderController::class)->prefix('orders')->name('orders.')->group(function () {
      Route::get('/', 'index')->name('index');
      Route::get('/{order}', 'show')->name('show');
      Route::post('/{order}/status', 'status')->name('status');
    });
  });
});

