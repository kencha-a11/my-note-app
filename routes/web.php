<?php

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
    // how to use the resource controller -> notes.index notes.create notes.store notes.show notes.edit notes.update notes.destroy
});
