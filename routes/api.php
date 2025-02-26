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

    Route::apiResource('recipes', RecipeController::class)->except(['index', 'show']);

    // Admin Routes
    Route::middleware(['auth:sanctum', 'can:admin'])->group(function () {
        Route::delete('/admin/recipes/{recipe}', [RecipeController::class, 'destroy']);
    });
});

// Public Recipe Routes
Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{recipe}', [RecipeController::class, 'show']);

// Search
Route::get('/recipes/search/{keyword}', [RecipeController::class, 'search']);
