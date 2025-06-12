<?php

use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\API\V1\Admin\ProductController as AdminProductController;
use App\Http\Controllers\API\V1\Admin\MessageController as AdminMessageController;
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


Route::prefix('admin')->group(function () {

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