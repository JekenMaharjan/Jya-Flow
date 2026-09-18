<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\FirebaseConnectionController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Starting Route
Route::get('/', function () {
    return view('intro');
})->name('intro');

// Users Route
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'showUsers')->name('users');
});

// Testing firebase
// Route::get('/firebase-test', [FirebaseController::class, 'test']);
Route::get('/firebase-test', [FirebaseConnectionController::class, 'index']);

// Guest Routes (Only accessible when NOT logged in)
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::get('/login', 'showLogin')->name('login');
    
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->middleware('throttle:5,1')->name('login');
});

// Authenticated Routes (Requires user to be logged in)
Route::middleware('auth')->group(function () {
    // Task Routes
    Route::controller(TaskController::class)->prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        // Route::patch('/{task}', 'update')->name('update');
        // Route::delete('/{task}', 'destroy')->name('destroy');
        Route::get('/{task}', 'preview')->name('preview');
        Route::put('/{task}', 'change')->name('change');
    });

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// File Upload Routes
Route::controller(FileUploadController::class)->prefix('upload')->name('upload.')->group(function () {
    Route::get('/', 'uploadUI')->name('index');
    Route::post('/', 'store')->name('store');
    Route::delete('/{file}', 'destroy')->name('destroy');
    Route::put('/{file}', 'edit')->name('edit');
});

