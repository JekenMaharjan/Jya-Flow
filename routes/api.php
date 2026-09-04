<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// // Public API Routes
// Route::name('api.')->group(function () {
//     Route::post('/register', [AuthController::class, 'register'])->name('register');
//     Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');
// });

// // Protected API Routes (Requires Sanctum Bearer Token)
// Route::middleware('auth:sanctum')
//     ->name('api.')      // automatically prefixed all inner route names with 'api.'    
//     ->group(function () {
//         Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
//         Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
//         Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
//         Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

//         Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// });

// Route::get('/users', [UserController::class, 'showUsers'])->name('users');