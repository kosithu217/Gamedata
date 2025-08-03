<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Public game routes
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/featured', [GameController::class, 'featured']);
    Route::get('/games/popular', [GameController::class, 'popular']);
    Route::get('/games/{slug}', [GameController::class, 'show']);
    Route::post('/games/{slug}/play', [GameController::class, 'play']);
    Route::get('/games/category/{categorySlug}', [GameController::class, 'byCategory']);
    
    // Public category routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    
    // Public blog routes
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/featured', [BlogController::class, 'featured']);
    Route::get('/blogs/{slug}', [BlogController::class, 'show']);
    Route::get('/blogs/category/{categorySlug}', [BlogController::class, 'byCategory']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User profile routes
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    
    // Admin only routes
    Route::middleware('admin')->group(function () {
        // User management
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::post('/users/{id}/activate', [UserController::class, 'activate']);
        Route::post('/users/{id}/deactivate', [UserController::class, 'deactivate']);
        Route::post('/users/{id}/extend-expiry', [UserController::class, 'extendExpiry']);
        
        // Category management
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
        
        // Blog management
        Route::post('/blogs', [BlogController::class, 'store']);
        Route::put('/blogs/{id}', [BlogController::class, 'update']);
        Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
        
        // Game management
        Route::post('/games', [GameController::class, 'store']);
        Route::put('/games/{id}', [GameController::class, 'update']);
        Route::delete('/games/{id}', [GameController::class, 'destroy']);
    });
});

// Legacy route for backward compatibility
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
