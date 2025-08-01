<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/signup', [LoginController::class, 'showRegister'])->name('signup');
    Route::post('/register', [LoginController::class, 'register'])->name('register.store');

    // Login
    Route::get('/', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    // Password Reset - Request Link
    Route::get('/forgot-password', [LoginController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');

    // Password Reset - Reset Form & Submission
    Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    })->name('logout');

    // User Dashboard
    Route::get('/user/dashboard', [LoginController::class, 'userDashboard'])->name('user.dashboard');

    // Admin Dashboard
    Route::get('/admin/dashboard', [LoginController::class, 'adminDashboard'])->name('admin.dashboard');

    // Admin Approval Action
    Route::post('/admin/approve/{id}', [LoginController::class, 'approve'])->name('admin.approve');
});

// Authenticated users only
Route::middleware('auth')->group(function () {
    Route::get('/set-password', [LoginController::class, 'showSetPasswordForm'])->name('password.set');
   
});

Route::post('reset/store', [LoginController::class, 'storeNewPass'])->name('reset.store');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/users/{id}/view', [LoginController::class, 'viewUser'])->name('admin.users.view');
    Route::get('/users/{id}/edit', [LoginController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}/update', [LoginController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}/delete', [LoginController::class, 'deleteUser'])->name('admin.users.delete');
});

Route::post('/admin/upload', [LoginController::class, 'upload'])->name('admin.upload');

use App\Http\Controllers\FileController;

Route::get('/upload', [LoginController::class, 'uploadForm'])->name('files.upload');
Route::post('/upload', [LoginController::class, 'uploadFile'])->name('files.store');
Route::get('/files', [LoginController::class, 'listFiles'])->name('files.list');
       
Route::delete('/delete-file/{id}', [LoginController::class, 'delete'])->name('file.delete');
