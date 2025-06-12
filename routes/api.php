<?php

use App\Http\Controllers\API\V1\Admin\CategoryController;
use App\Http\Controllers\API\V1\Admin\MessageController;
use App\Http\Controllers\API\V1\Admin\ProductController;
use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\UserController;
use Illuminate\Support\Facades\Route;



Route::prefix('admin')->group(function () {

    // User management
    Route::apiResource('users', UserController::class);
    Route::post('users/{user}/change-role', [UserController::class, 'changeRole']);

    // Roles management
    Route::apiResource('roles', RoleController::class);
    Route::get('permissions', [RoleController::class, 'permissions']);

    // Categories management
    Route::apiResource('categories', CategoryController::class);

    // Products management
    Route::resource('products', ProductController::class);
    Route::get('products/{product}/similler', [ProductController::class, 'simillerProducts']);
    Route::post('products/images/upload', [ProductController::class, 'uploadImages']);
    Route::delete('products/images/{image}/delete', [ProductController::class, 'deleteImage']);
    Route::put('products/images/{image}/primary', [ProductController::class, 'setPrimary']);

    // Messages management
    Route::apiResource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
    Route::put('messages/{message}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::post('messages/mark-all-as-read', [MessageController::class, 'markAllAsRead'])->name('messages.mark-all-as-read');
});