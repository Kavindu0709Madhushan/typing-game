<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TypingGameController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
});

// Protected Routes (Authenticated users only)
Route::middleware('auth')->group(function () {
    Route::get('/typing-game', [TypingGameController::class, 'index'])->name('typing.game');
    Route::post('/typing-game/save-score', [TypingGameController::class, 'saveScore'])->name('typing.save-score');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});