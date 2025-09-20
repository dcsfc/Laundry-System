<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\AnnouncementController;
use App\Http\Controllers\SuperAdmin\SettingController;
use App\Http\Controllers\SuperAdmin\ServiceController;
use App\Http\Controllers\SuperAdmin\DashboardController;

// Landing page
Route::get('/', function () {
    return view('landing'); // resources/views/landing.blade.php
});

// Test route for data table
Route::get('/test-datatable', function () {
    return view('test-datatable');
})->name('test.datatable');

// Fixed data table test route
Route::get('/test-datatable-fixed', function () {
    return view('test-datatable-fixed');
})->name('test.datatable.fixed');

// Default dashboard route - redirects based on user role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role) {
        switch (strtolower($user->role->name)) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard');
            case 'administrator':
                return redirect()->route('admin.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'customer':
                return redirect()->route('customer.dashboard');
            default:
                return redirect()->route('superadmin.dashboard');
        }
    }
    return redirect()->route('superadmin.dashboard');
})->middleware(['auth'])->name('dashboard');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    /**
     * 🔑 Superadmin Routes
     * URL: /superadmin/...
     * Names: superadmin.*
     */
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // AJAX routes for user management (must come BEFORE resource route)
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
        
        // Service Management
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        
        // Data Table Examples
        Route::get('/data-table-examples', function () {
            return view('examples.user-management-example');
        })->name('data-table-examples');
        
        Route::get('/data-table-products', function () {
            return view('examples.products-example');
        })->name('data-table-products');
        
        Route::get('/data-table-orders', function () {
            return view('examples.orders-example');
        })->name('data-table-orders');
        
        // Test route for data table
        Route::get('/test-datatable', function () {
            $users = [
                ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'active', 'created_at' => '2024-01-15'],
                ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'inactive', 'created_at' => '2024-01-20'],
                ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'status' => 'active', 'created_at' => '2024-02-01'],
            ];
            
            $columns = [
                ['key' => 'id', 'label' => 'ID', 'sortable' => true],
                ['key' => 'name', 'label' => 'Name', 'sortable' => true],
                ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                ['key' => 'status', 'label' => 'Status', 'sortable' => true],
                ['key' => 'created_at', 'label' => 'Created', 'sortable' => true],
            ];
            
            $actions = [
                ['label' => 'View', 'onclick' => 'viewUser'],
                ['label' => 'Edit', 'onclick' => 'editUser'],
            ];
            
            return view('test-datatable', compact('users', 'columns', 'actions'));
        })->name('test-datatable');
        
        // Individual user routes
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Debug route to check authentication
        Route::get('debug-auth', function() {
            return response()->json([
                'auth_check' => auth()->check(),
                'user_id' => auth()->id(),
                'user_name' => auth()->user()?->name,
                'user_role' => auth()->user()?->role?->name,
                'session_id' => session()->getId()
            ]);
        });

        // Features
        Route::resource('users', UserController::class);
        Route::resource('services', \App\Http\Controllers\SuperAdmin\ServiceController::class);
        Route::resource('orders', \App\Http\Controllers\SuperAdmin\OrderController::class);
        Route::resource('schedules', \App\Http\Controllers\SuperAdmin\ScheduleController::class);
        Route::resource('payments', \App\Http\Controllers\SuperAdmin\PaymentController::class);
        Route::resource('inventory', \App\Http\Controllers\SuperAdmin\InventoryController::class);
        Route::resource('reports', \App\Http\Controllers\SuperAdmin\ReportController::class);
        Route::resource('announcements', AnnouncementController::class);
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
        
        // Profile routes
        Route::get('/profile', function () {
            return view('profile.edit');
        })->name('profile.edit');
        Route::put('/profile', function () {
            // Simple profile update logic
            $user = auth()->user();
            $user->update(request()->only(['name', 'email', 'phone_number']));
            return redirect()->back()->with('success', 'Profile updated successfully');
        })->name('profile.update');
    });

    /**
     * 🔑 Admin Routes
     * URL: /admin/dashboard
     * Name: admin.dashboard
     */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.admin'); // resources/views/dashboard/admin.blade.php
        })->name('dashboard');
        
        // Service Management
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
        
        // User Management (Admin can manage staff and customers)
        Route::get('/users', [UserController::class, 'adminUsers'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Orders Management
        Route::get('/orders', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/edit', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{order}', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'destroy'])->name('orders.destroy');
        
        // Schedules Management
        Route::get('/schedules', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/create', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'show'])->name('schedules.show');
        Route::get('/schedules/{schedule}/edit', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'destroy'])->name('schedules.destroy');
        
        // Inventory Management
        Route::get('/inventory', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/{inventory}', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'show'])->name('inventory.show');
        Route::get('/inventory/{inventory}/edit', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/inventory/{inventory}', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/inventory/{inventory}', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'destroy'])->name('inventory.destroy');
        
        // Payments Management
        Route::get('/payments', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/edit', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'destroy'])->name('payments.destroy');
        
        // Reports
        Route::get('/reports', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/export', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'export'])->name('reports.export');
        
        // Profile routes
        Route::get('/profile', function () {
            return view('profile.edit');
        })->name('profile.edit');
        Route::put('/profile', function () {
            // Simple profile update logic
            $user = auth()->user();
            $user->update(request()->only(['name', 'email', 'phone_number']));
            return redirect()->back()->with('success', 'Profile updated successfully');
        })->name('profile.update');
    });

    /**
     * 🔑 Staff Routes
     * URL: /staff/dashboard
     * Name: staff.dashboard
     */
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.staff'); // resources/views/dashboard/staff.blade.php
        })->name('dashboard');
        
        // Orders Management
        Route::get('/orders', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'update'])->name('orders.update');
        
        // Schedules Management
        Route::get('/schedules', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'show'])->name('schedules.show');
        Route::put('/schedules/{schedule}', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'update'])->name('schedules.update');
        
        // Inventory Management
        Route::get('/inventory', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/{inventory}', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'show'])->name('inventory.show');
        Route::put('/inventory/{inventory}', [\App\Http\Controllers\SuperAdmin\InventoryController::class, 'update'])->name('inventory.update');
        
        // Payments Management
        Route::get('/payments', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments', [\App\Http\Controllers\SuperAdmin\PaymentController::class, 'store'])->name('payments.store');
        
        // Reports (Weekly only)
        Route::get('/reports', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/index', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'weekly'])->name('reports.index');
        
        // Profile routes
        Route::get('/profile', function () {
            return view('profile.edit');
        })->name('profile.edit');
        Route::put('/profile', function () {
            // Simple profile update logic
            $user = auth()->user();
            $user->update(request()->only(['name', 'email', 'phone_number']));
            return redirect()->back()->with('success', 'Profile updated successfully');
        })->name('profile.update');
    });

    /**
     * 🔑 Customer Routes
     * URL: /customer/dashboard
     * Name: customer.dashboard
     */
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
        
        // Schedules Management (Customer can schedule laundry)
        Route::get('/schedules', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'customerSchedules'])->name('schedules.index');
        Route::get('/schedules/create', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'customerScheduleShow'])->name('schedules.show');
        
        // Orders Management (Customer can view their orders)
        Route::get('/orders', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'customerOrders'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\SuperAdmin\OrderController::class, 'customerOrderShow'])->name('orders.show');
        
        // Profile routes
        Route::get('/profile', function () {
            return view('profile.edit');
        })->name('profile.edit');
        Route::put('/profile', function () {
            // Simple profile update logic
            $user = auth()->user();
            $user->update(request()->only(['name', 'email', 'phone_number']));
            return redirect()->back()->with('success', 'Profile updated successfully');
        })->name('profile.update');
    });
});

// Authentication routes (Laravel Breeze/Fortify/etc.)
require __DIR__.'/auth.php';
