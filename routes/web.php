<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BendaharaDashboardController;
use App\Http\Controllers\DosenPengajarDashboardController;
use App\Http\Controllers\DosenWaliDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SI-AKAD
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC & AUTHENTICATION
// ==========================================
Route::get('/', function () {
    return view('login', [
        'googleConfigured' => !empty(config('services.google.client_id')),
    ]);
})->name('home');

Route::get('/login', function () {
    return view('login', [
        'googleConfigured' => !empty(config('services.google.client_id')),
    ]);
})->name('login');

// Google OAuth 2.0 Web Flow
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');

// ==========================================
// 2. ADMIN PORTAL (/admin/...)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/manajemen-pengguna', [AdminUserController::class, 'index'])->name('users.alias');
});

// Admin Route Aliases / Backward Compatibility
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// ==========================================
// 3. MAHASISWA PORTAL (/mahasiswa/...)
// ==========================================
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Mahasiswa Dashboard
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
});

// Mahasiswa Route Aliases / Backward Compatibility
Route::get('/dashboard-mahasiswa', function () {
    return redirect()->route('mahasiswa.dashboard');
})->name('dashboard.mahasiswa');

// ==========================================
// 4. DOSEN WALI PORTAL (/dosen-wali/...)
// ==========================================
Route::prefix('dosen-wali')->name('dosen-wali.')->group(function () {
    Route::get('/dashboard', [DosenWaliDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/dosen-wali', function () {
    return redirect()->route('dosen-wali.dashboard');
});

// ==========================================
// 5. DOSEN PENGAJAR PORTAL (/dosen-pengajar/...)
// ==========================================
Route::prefix('dosen-pengajar')->name('dosen-pengajar.')->group(function () {
    Route::get('/dashboard', [DosenPengajarDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/dosen-pengajar', function () {
    return redirect()->route('dosen-pengajar.dashboard');
});

// ==========================================
// 6. BENDAHARA PORTAL (/bendahara/...)
// ==========================================
Route::prefix('bendahara')->name('bendahara.')->group(function () {
    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/bendahara', function () {
    return redirect()->route('bendahara.dashboard');
});

// ==========================================
// 7. API ENDPOINTS (/api/...)
// ==========================================
Route::prefix('api')->group(function () {
    // Auth API
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/login-status', [AuthController::class, 'checkLoginStatus'])->name('api.login.status');
    Route::post('/reset-attempts', [AuthController::class, 'resetAttempts'])->name('api.reset.attempts');
    Route::post('/auth/google', [AuthController::class, 'googleLogin'])->name('api.google.login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.forgot.password');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('api.verify.otp');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('api.reset.password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // Admin API
    Route::prefix('admin')->name('api.admin.')->group(function () {
        // Admin Dashboard Stats
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'apiStats'])->name('dashboard.stats');

        // User Management CRUD
        Route::get('/users', [AdminUserController::class, 'apiIndex'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'apiStore'])->name('users.store');
        Route::get('/users/{id}', [AdminUserController::class, 'apiShow'])->name('users.show');
        Route::put('/users/{id}', [AdminUserController::class, 'apiUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'apiDestroy'])->name('users.destroy');
    });
});
