<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirebaseConnectionController;
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
    Route::get('/register', 'showRegister');
    Route::get('/login', 'showLogin');
    
    Route::post('/register', 'register');
    Route::post('/login', 'login')->middleware('throttle:5,1');
});

// Authenticated Routes (Requires user to be logged in)
Route::middleware('auth')->group(function () {
    // Task Routes
    Route::controller(TaskController::class)->prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{task}', 'preview');
        Route::put('/{task}', 'change');
    });

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);
});



