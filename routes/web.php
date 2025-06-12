<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Guest routes
Route::redirect('/', '/home'); // Redirects root to /home
Route::get('/home', function () {
    return view('home');
})->name('home');

// Authentication routes (for guests)
Route::middleware(['guest'])->group(function () {
    // Show login form
    Route::get('/home/login', [AuthController::class, 'login'])->name('login');
    // Handle login submission
    Route::post('/home/login', [AuthController::class, 'loginForm'])->name('login.form'); // Changed URI to be RESTful

    // Show registration form
    Route::get('/home/register', [AuthController::class, 'register'])->name('register');
    // Handle registration submission
    Route::post('/home/register', [AuthController::class, 'registerForm'])->name('register.form'); // Changed URI to be RESTful
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::post('/home/logout', [AuthController::class, 'logout'])->name('logout');
});
