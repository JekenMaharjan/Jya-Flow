<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Starting Route
Route::get('/', function () {
    return view('intro');
})->name('intro');

// Guest Routes (Only accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');
});

// Authenticated Routes (Requires user to be logged in)
Route::middleware('auth')->group(function () {

    // Task Routes
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/{task}', [TaskController::class, 'preview'])->name('tasks.preview');
    Route::put('/tasks/{task}', [TaskController::class, 'change'])->name('tasks.change');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// File Upload Routes
Route::get('/upload', [FileUploadController::class, 'uploadUI'])->name('upload');
Route::post('/upload', [FileUploadController::class, 'store'])->name('upload.store');
Route::delete('/upload/{file}', [FileUploadController::class, 'destroy'])->name('upload.destroy');
Route::put('/upload/{file}', [FileUploadController::class, 'edit'])->name('upload.edit');

