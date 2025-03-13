<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Category Routes
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{category_id}', [CategoryController::class, 'show']);
    Route::put('/category/{category_id}', [CategoryController::class, 'update']);
    Route::delete('/category/{category_id}', [CategoryController::class, 'destroy']);
    Route::get('/category/{category}/tasks', [CategoryController::class, 'tasks']);

    // Task Routes
    Route::get('/task', [TaskController::class, 'index']);
    Route::post('/task', [TaskController::class, 'store']);
    Route::get('/task/{task_id}', [TaskController::class, 'show']);
    Route::put('/task/{task_id}', [TaskController::class, 'update']);
    Route::delete('/task/{task_id}', [TaskController::class, 'destroy']);
});

