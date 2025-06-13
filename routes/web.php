<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\NoteController;
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

    // notes
    Route::resource('notes', NoteController::class)
        ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);

    // Exclude 'store' from the resource to avoid conflict with registration
    Route::resource('users', UserController::class)
        ->except(['store']); // This prevents POST /users conflict

    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::resource('users', AdminController::class);

    // Additional admin actions
    Route::patch('users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])
        ->name('users.toggle-admin');
});
