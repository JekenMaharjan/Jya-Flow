<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Public API Routes
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('api.login');

// Protected API Routes (Requires Sanctum Bearer Token)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/tasks', [TaskController::class, 'index'])->name('api.tasks');
//     Route::post('/tasks', [TaskController::class, 'store'])->name('api.tasks.store');
//     Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');
//     Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('api.tasks.destroy');

//     Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
// });