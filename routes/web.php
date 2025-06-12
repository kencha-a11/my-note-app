<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', function () {
    return view('home');
})->name('home');


// Guest routes (authentication)
Route::middleware('guest')->group(function () {
    // Registration routes
    Route::get('/register', [UserController::class, 'create'])->name('register');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    // Login routes
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::delete('/sessions', [SessionController::class, 'destroy'])->name('sessions.destroy');
    // Alternative: if you prefer POST for logout (common for forms)
    // Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
});

