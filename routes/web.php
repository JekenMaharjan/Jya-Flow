<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Starting Route
Route::get('/', function () {
    return view('intro');
})->name('intro');

// Guest Routes (Only accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    // Register Routes    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // Apply the rate limiter using throttle: 5 times per 1 minute
});

// Authenticated Routes (Requires user to be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});