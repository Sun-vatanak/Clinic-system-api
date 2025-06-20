<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MedicineController;

Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('auth/user', [AuthController::class, 'user']);
    Route::put('auth/change-password', [AuthController::class, 'changePassword']);

    // User management routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/statistics', [UserController::class, 'statistics']);
        Route::post('/', [UserController::class, 'store']);

        // Individual user routes - using route model binding
        Route::prefix('{user}')->group(function () {
            Route::get('/', [UserController::class, 'show']);
            Route::put('/', [UserController::class, 'update']);
            Route::delete('/', [UserController::class, 'destroy']);
            Route::put('/activate', [UserController::class, 'activate']);
            Route::put('/deactivate', [UserController::class, 'deactivate']);
            Route::put('/change-password', [UserController::class, 'changePassword']);
        });
    });
    Route::get('/profile', [ProfileController::class, 'getUserProfile']);

    // Update profile
    Route::put('/profile', [ProfileController::class, 'updateProfile']);

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
        Route::get('/{id}/medicines', [CategoryController::class, 'medicines']);
    });

    // Medicine Routes
    Route::prefix('medicines')->group(function () {
        Route::get('/', [MedicineController::class, 'index']);
        Route::post('/', [MedicineController::class, 'store']);
        Route::get('/{id}', [MedicineController::class, 'show']);
        Route::put('/{id}', [MedicineController::class, 'update']);
        Route::delete('/{id}', [MedicineController::class, 'destroy']);
        Route::get('/category/{categoryId}', [MedicineController::class, 'byCategory']);
        Route::get('/search/{term}', [MedicineController::class, 'search']);
    });
});
