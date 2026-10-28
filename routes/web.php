<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Views
Route::get('/', function () {
    return view('login', [
        'googleConfigured' => !empty(config('services.google.client_id')),
    ]);
})->name('home');

// Admin Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Mahasiswa Dashboard
Route::get('/dashboard-mahasiswa', function () {
    return view('dashboard-mahasiswa');
})->name('dashboard.mahasiswa');

Route::get('/login', function () {
    return view('login', [
        'googleConfigured' => !empty(config('services.google.client_id')),
    ]);
})->name('login');

// Google OAuth 2.0 Web Flow
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');

// API Authentication Endpoints
Route::prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/login-status', [AuthController::class, 'checkLoginStatus'])->name('api.login.status');
    Route::post('/reset-attempts', [AuthController::class, 'resetAttempts'])->name('api.reset.attempts');
    Route::post('/auth/google', [AuthController::class, 'googleLogin'])->name('api.google.login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.forgot.password');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('api.verify.otp');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('api.reset.password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});
