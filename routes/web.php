<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\AnnouncementController as SuperAdminAnnouncementController;
use App\Http\Controllers\SuperAdmin\ServiceController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\AnnouncementController;

// Landing page
Route::get('/', function () {
    return view('landing');
});

// Default dashboard route - redirects based on user role
Route::get('/dashboard', function () {
    $user = null;
    
    // Check which guard the user is authenticated with
    if (Auth::guard('admin')->check()) {
        $user = Auth::guard('admin')->user();
    } elseif (Auth::guard('customer')->check()) {
        $user = Auth::guard('customer')->user();
    } elseif (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
    }
    
    if ($user && $user->role) {
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
                return redirect()->route('customer.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware(['auth:admin,customer,web'])->name('dashboard');

// Authenticated routes
Route::middleware(['auth:admin,customer,web'])->group(function () {

    /**
     * 🔑 Superadmin Routes
     * URL: /superadmin/...
     * Names: superadmin.*
     */
    Route::middleware(['auth:admin', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // AJAX routes for user management (must come BEFORE resource route)
        Route::post('users/fetch', [UserController::class, 'fetchUsers']);
        Route::get('users/datatable', [UserController::class, 'fetchUsers'])->name('users.datatable');
        Route::post('users/get-data', [UserController::class, 'getUserData']);
        Route::post('users/update-ajax', [UserController::class, 'updateUserAjax']);
        Route::post('users/store-ajax', [UserController::class, 'storeAjax']);
        Route::put('users/{user}', [UserController::class, 'updateAjax']);
        Route::get('roles', [UserController::class, 'getRoles']);
        Route::post('users/toggle-status', [UserController::class, 'toggleUserStatus']);
        Route::post('users/delete-ajax', [UserController::class, 'deleteUserAjax']);
        Route::get('users/activity', [UserController::class, 'getUserActivity']);
        
        // Audit Logs
        Route::get('audit-logs', [App\Http\Controllers\SuperAdmin\AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('audit-logs/user/{userId}', [App\Http\Controllers\SuperAdmin\AuditLogController::class, 'getUserActivity'])->name('audit-logs.user');
        Route::post('audit-logs/clear-old', [App\Http\Controllers\SuperAdmin\AuditLogController::class, 'clearOld'])->name('audit-logs.clear-old');
        
        // System Settings
        Route::get('settings', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('settings/data', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'getSettings']);
        Route::post('settings', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'saveSettings']);
        Route::post('settings/test-email', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'testEmail']);
        Route::post('settings/clear-cache', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'clearCache']);
        Route::get('settings/audit-logs', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'getAuditLogs']);
        Route::get('settings/audit-logs/export', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'exportAuditLogs']);
        Route::post('settings/backup', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'createBackup']);

        // Features - Super Admin limited to User Management, Announcements, and System Settings
        Route::resource('users', UserController::class);
        Route::resource('announcements', SuperAdminAnnouncementController::class);
        Route::post('announcements/{announcement}/toggle-status', [SuperAdminAnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');
        Route::post('announcements/{announcement}/toggle-pin', [SuperAdminAnnouncementController::class, 'togglePin'])->name('announcements.toggle-pin');
        
        // Profile routes (defined earlier in this group)
    });

    // Dashboard announcements for all authenticated users
    Route::middleware('auth:admin,customer,web')->group(function () {
        Route::get('/announcements/dashboard', [AnnouncementController::class, 'getDashboardAnnouncements'])->name('announcements.dashboard');
    });

    /**
     * 🔑 Admin Routes
     * URL: /admin/dashboard
     * Name: admin.dashboard
     */
    Route::middleware(['auth:admin', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.admin'); // resources/views/dashboard/admin.blade.php
        })->name('dashboard');
        
        // Service Management - Only index implemented
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        
        // User Management (Admin can manage staff and customers)
        Route::get('/users', [UserController::class, 'adminUsers'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        
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
    Route::middleware(['auth:admin', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard'])->name('dashboard');
        
        
        // API routes for modals
        Route::get('/api/customers', function() {
            $customers = \App\Models\User::whereHas('role', function($query) {
                $query->where('name', 'customer');
            })->select('id', 'name', 'email')->get();
            
            return response()->json(['customers' => $customers]);
        });
        
        // Schedules Management (Approval Workflow)
        Route::get('/schedules', [\App\Http\Controllers\Staff\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\Staff\ScheduleController::class, 'show'])->name('schedules.show');
        Route::post('/schedules/{schedule}/approve', [\App\Http\Controllers\Staff\ScheduleController::class, 'approve'])->name('schedules.approve');
        Route::post('/schedules/{schedule}/reject', [\App\Http\Controllers\Staff\ScheduleController::class, 'reject'])->name('schedules.reject');
        Route::put('/schedules/{schedule}/status', [\App\Http\Controllers\Staff\ScheduleController::class, 'updateStatus'])->name('schedules.status');
        Route::put('/schedules/{schedule}/pricing', [\App\Http\Controllers\Staff\ScheduleController::class, 'setPricing'])->name('schedules.pricing');
        Route::get('/schedules/stats', [\App\Http\Controllers\Staff\ScheduleController::class, 'getStats'])->name('schedules.stats');
        Route::post('/schedules/fetch', [\App\Http\Controllers\Staff\ScheduleController::class, 'index'])->name('schedules.fetch');
        
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
    Route::middleware(['auth:customer', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
        
        // Schedules Management (Customer can schedule laundry)
        Route::get('/schedules', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'customerSchedules'])->name('schedules.index');
        Route::get('/schedules/create', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'customerScheduleCreate'])->name('schedules.store');
        
        // Schedule History (Customer can view completed/cancelled schedules) - MUST be before parameterized routes
        Route::get('/schedules/history', [\App\Http\Controllers\SuperAdmin\ScheduleHistoryController::class, 'index'])->name('schedules.history');
        
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'customerScheduleShow'])->name('schedules.show');
        // Update schedule
        Route::post('/schedules/{schedule}/update', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'customerScheduleUpdate'])->name('schedules.update');
        // Cancel schedule
        Route::post('/schedules/{schedule}/cancel', [\App\Http\Controllers\SuperAdmin\ScheduleController::class, 'customerScheduleCancel'])->name('schedules.cancel');
        
        // Orders Management (Customer can view their orders)
        
        // Announcements (Customer can view announcements)
        Route::get('/announcements', [\App\Http\Controllers\CustomerController::class, 'announcements'])->name('announcements');
        
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

// Debug route for customer schedules
Route::get('/debug-customer-schedules', function () {
    $currentUser = auth()->user();
    
    if (!$currentUser) {
        return 'No user logged in';
    }
    
    $orders = \App\Models\Order::where('customer_id', $currentUser->id)
        ->with(['service', 'staff', 'customer'])
        ->get();
    
    $debug = [
        'user_id' => $currentUser->id,
        'user_name' => $currentUser->name,
        'total_orders' => $orders->count(),
        'orders' => $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer ? $order->customer->name : 'NULL',
                'customer_phone' => $order->customer ? $order->customer->phone_number : 'NULL',
                'service_name' => $order->service ? $order->service->name : 'NULL',
                'staff_name' => $order->staff ? $order->staff->name : 'NULL',
                'status' => $order->status,
            ];
        })
    ];
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
})->middleware('auth:customer');

// Authentication routes
require __DIR__.'/auth.php';
