<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Fetch authenticated user details
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);

    Route::apiResource('recipes', RecipeController::class)->except(['index', 'show']);

    // Admin Routes
    Route::middleware(['auth:sanctum', 'can:admin'])->group(function () {
        Route::delete('/admin/recipes/{recipe}', [RecipeController::class, 'destroy']);
    });
});

// Public Recipe Routes
Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{recipe}', [RecipeController::class, 'show']);
Route::get('/recipes/search/{keyword}', [RecipeController::class, 'search']);

// Authenticated Recipe Routes (Require valid Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/recipes', [RecipeController::class, 'store']);
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update']);
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy']);
});
