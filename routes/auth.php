<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

// Registration Routes (Customer only)
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Customer Login Routes
Route::get('/login', [CustomerAuthController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [CustomerAuthController::class, 'store'])
    ->middleware('guest')
    ->name('customer.login');

// Administrator Login Routes
Route::get('/administrator/login', [AdminAuthController::class, 'create'])
    ->middleware('guest')
    ->name('admin.login');

Route::post('/administrator/login', [AdminAuthController::class, 'store'])
    ->middleware('guest');

// Logout Routes
Route::post('/logout', [CustomerAuthController::class, 'destroy'])
    ->middleware('auth:customer')
    ->name('logout');

Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])
    ->middleware('auth:admin')
    ->name('admin.logout');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
