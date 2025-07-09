<?php

use App\Enums\PermissionsEnum;
use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\API\V1\Admin\DashboardController;
use App\Http\Controllers\API\V1\Admin\ProductController as AdminProductController;
use App\Http\Controllers\API\V1\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\API\V1\Auth\AuthenticationController;
use App\Http\Controllers\API\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\API\V1\Auth\ProfileController;
use App\Http\Controllers\API\V1\Auth\VerifyAccountController;
use App\Http\Controllers\API\V1\User\CategoryController;
use App\Http\Controllers\API\V1\User\MessageController;
use App\Http\Controllers\API\V1\User\ProductController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::apiResource('/products', ProductController::class)->only(['index', 'show']);
Route::get('products/latest', [ProductController::class, 'latestProducts']);
Route::get('products/featured', [ProductController::class, 'featuredProducts']);
Route::get('/products/{product}/similler', [ProductController::class, 'simillerProducts']);

Route::apiResource('/categories', CategoryController::class)->only(['index', 'show']);

Route::post('/contact-us', [MessageController::class, 'store']);

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgot']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);
});

Route::middleware('auth:sanctum')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/delete-account', [ProfileController::class, 'deleteAccount']);

    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/logout-other-devices', [AuthenticationController::class, 'logoutOtherDevices']);

    // Email verification
    Route::post("/send-verification", [VerifyAccountController::class, 'sendVerificationEmail']);
    Route::post("/verify-account", [VerifyAccountController::class, 'verifyAccount']);


    Route::middleware('permission:' . PermissionsEnum::VIEW_DASHBOARD->value)->prefix('admin')->group(function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);

        // User management
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/change-role', [UserController::class, 'changeRole']);

        // Roles management
        Route::apiResource('roles', RoleController::class);
        Route::get('permissions', [RoleController::class, 'permissions']);

        // Categories management
        Route::apiResource('categories', AdminCategoryController::class);

        // Products management
        Route::resource('products', AdminProductController::class);
        Route::get('products/{product}/similler', [AdminProductController::class, 'simillerProducts']);
        Route::post('products/images/upload', [AdminProductController::class, 'uploadImages']);
        Route::delete('products/images/{image}/delete', [AdminProductController::class, 'deleteImage']);
        Route::put('products/images/{image}/primary', [AdminProductController::class, 'setPrimary']);

        // Messages management
        Route::apiResource('messages', AdminMessageController::class)->only(['index', 'show', 'destroy']);
        Route::put('messages/{message}/mark-as-read', [AdminMessageController::class, 'markAsRead'])->name('messages.mark-as-read');
        Route::post('messages/mark-all-as-read', [AdminMessageController::class, 'markAllAsRead'])->name('messages.mark-all-as-read');
    });
});