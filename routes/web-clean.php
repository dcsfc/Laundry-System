<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\AnnouncementController;
use App\Http\Controllers\SuperAdmin\SettingController;
use App\Http\Controllers\SuperAdmin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Super Admin routes
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('users/debug', [UserController::class, 'debug'])->name('users.debug');
        Route::post('users/fetch', [UserController::class, 'fetchUsers']);
        Route::get('users/datatable', [UserController::class, 'fetchUsers'])->name('users.datatable');
        Route::post('users/get-data', [UserController::class, 'getUserData']);
        Route::post('users/update-ajax', [UserController::class, 'updateUserAjax']);
        Route::post('users/store-ajax', [UserController::class, 'storeAjax']);
        Route::get('roles', [UserController::class, 'getRoles']);
        Route::post('users/toggle-status', [UserController::class, 'toggleUserStatus']);
        Route::post('users/reset-password', [UserController::class, 'resetUserPassword']);
        Route::get('users/activity', [UserController::class, 'getUserActivity']);
        
        Route::resource('users', UserController::class);
        Route::resource('announcements', AnnouncementController::class)->only(['index']);
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
    });

    // Administrator routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.admin');
        })->name('dashboard');
        
        Route::get('/users', [UserController::class, 'adminUsers'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Staff routes
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.staff');
        })->name('dashboard');
    });

    // Customer routes
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.customer');
        })->name('dashboard');
    });
});
