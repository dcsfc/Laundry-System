<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\AnnouncementController as SuperAdminAnnouncementController;
use App\Http\Controllers\SuperAdmin\ServiceController;
use App\Http\Controllers\SuperAdmin\DashboardController;

// Admin namespace controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;

// Staff namespace controllers
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\OrderController as StaffOrderController;

// Customer namespace controllers
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\AnnouncementController as CustomerAnnouncementController;
use App\Http\Controllers\Customer\ScheduleController as CustomerScheduleController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

// Landing page - redirect to customer login
Route::get('/', function () {
    return redirect()->route('login');
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
        Route::get('/announcements/dashboard', [SuperAdminAnnouncementController::class, 'getDashboardAnnouncements'])->name('announcements.dashboard');
    });

    /**
     * 🔑 Admin Routes
     * URL: /admin/dashboard
     * Name: admin.dashboard
     */
    Route::middleware(['auth:admin', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Service Management
        Route::resource('services', AdminServiceController::class);
        Route::post('/services/{service}/toggle-status', [AdminServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        
        // User Management (Admin can manage staff and customers)
        Route::get('/users', [UserController::class, 'adminUsers'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Staff Management
        Route::resource('staff', AdminStaffController::class);
        Route::post('/staff/{staff}/toggle-status', [AdminStaffController::class, 'toggleStatus'])->name('staff.toggle-status');
        
        // Schedules Management
        Route::resource('schedules', AdminScheduleController::class);
        
        // Inventory Management
        Route::resource('inventory', AdminInventoryController::class);
        Route::post('/inventory/{inventory}/update-stock', [AdminInventoryController::class, 'updateStock'])->name('inventory.update-stock');
        
        // Payments Management
        Route::resource('payments', AdminPaymentController::class);
        
        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
        
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
        // Dashboard
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        
        
        // API routes for modals
        Route::get('/api/customers', function() {
            $customers = \App\Models\User::whereHas('role', function($query) {
                $query->where('name', 'customer');
            })->select('id', 'name', 'email')->get();
            
            return response()->json(['customers' => $customers]);
        });
        
        // Schedules Management (Approval Workflow)
        Route::get('/schedules', [\App\Http\Controllers\Staff\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/stats', [\App\Http\Controllers\Staff\ScheduleController::class, 'getStats'])->name('schedules.stats');
        Route::post('/schedules/fetch', [\App\Http\Controllers\Staff\ScheduleController::class, 'index'])->name('schedules.fetch');
        Route::get('/schedules/all', [\App\Http\Controllers\Staff\ScheduleController::class, 'getAllSchedules'])->name('schedules.all');
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\Staff\ScheduleController::class, 'show'])->name('schedules.show');
        Route::post('/schedules/{schedule}/approve', [\App\Http\Controllers\Staff\ScheduleController::class, 'approve'])->name('schedules.approve');
        Route::post('/schedules/{schedule}/reject', [\App\Http\Controllers\Staff\ScheduleController::class, 'reject'])->name('schedules.reject');
        Route::put('/schedules/{schedule}/status', [\App\Http\Controllers\Staff\ScheduleController::class, 'updateStatus'])->name('schedules.status');
        Route::put('/schedules/{schedule}/pricing', [\App\Http\Controllers\Staff\ScheduleController::class, 'setPricing'])->name('schedules.pricing');
        Route::post('/schedules/{schedule}/cancel', [\App\Http\Controllers\Staff\ScheduleController::class, 'cancel'])->name('schedules.cancel');
        Route::post('/schedules/{schedule}/start-processing', [\App\Http\Controllers\Staff\ScheduleController::class, 'startProcessing'])->name('schedules.start-processing');
        Route::post('/schedules/{schedule}/mark-ready', [\App\Http\Controllers\Staff\ScheduleController::class, 'markReadyForPickup'])->name('schedules.mark-ready');
        Route::post('/schedules/{schedule}/mark-completed', [\App\Http\Controllers\Staff\ScheduleController::class, 'markCompleted'])->name('schedules.mark-completed');
        
        // Inventory Management
        Route::get('/inventory', [\App\Http\Controllers\Staff\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [\App\Http\Controllers\Staff\InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [\App\Http\Controllers\Staff\InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/{inventory}', [\App\Http\Controllers\Staff\InventoryController::class, 'show'])->name('inventory.show');
        Route::get('/inventory/{inventory}/edit', [\App\Http\Controllers\Staff\InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/inventory/{inventory}', [\App\Http\Controllers\Staff\InventoryController::class, 'update'])->name('inventory.update');
        Route::post('/inventory/{inventory}/update-stock', [\App\Http\Controllers\Staff\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
        Route::delete('/inventory/{inventory}', [\App\Http\Controllers\Staff\InventoryController::class, 'destroy'])->name('inventory.destroy');
        
        // Payments Management
        Route::get('/payments', [\App\Http\Controllers\Staff\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [\App\Http\Controllers\Staff\PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [\App\Http\Controllers\Staff\PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [\App\Http\Controllers\Staff\PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/edit', [\App\Http\Controllers\Staff\PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [\App\Http\Controllers\Staff\PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [\App\Http\Controllers\Staff\PaymentController::class, 'destroy'])->name('payments.destroy');
        
        // Reports Management
        Route::get('/reports', [\App\Http\Controllers\Staff\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [\App\Http\Controllers\Staff\ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [\App\Http\Controllers\Staff\ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [\App\Http\Controllers\Staff\ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/edit', [\App\Http\Controllers\Staff\ReportController::class, 'edit'])->name('reports.edit');
        Route::get('/reports/{report}/export', [\App\Http\Controllers\Staff\ReportController::class, 'export'])->name('reports.export');
        
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
        // Dashboard
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        
        // Schedules Management (Customer can schedule laundry)
        Route::get('/schedules', [CustomerScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/schedules', [CustomerScheduleController::class, 'store'])->name('schedules.store');
        
        // Schedule History (Customer can view completed/cancelled schedules) - MUST be before parameterized routes
        Route::get('/schedules/history', [\App\Http\Controllers\SuperAdmin\ScheduleHistoryController::class, 'index'])->name('schedules.history');
        
        Route::get('/schedules/{schedule}', [CustomerScheduleController::class, 'show'])->name('schedules.show');
        // Update schedule
        Route::post('/schedules/{schedule}/update', [CustomerScheduleController::class, 'update'])->name('schedules.update');
        // Cancel schedule
        Route::post('/schedules/{schedule}/cancel', [CustomerScheduleController::class, 'cancel'])->name('cancel');
        
        // Orders Management (Customer can view their orders)
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
        
        // Announcements (Customer can view announcements)
        Route::get('/announcements', [CustomerAnnouncementController::class, 'index'])->name('announcements');
        
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

// Debug route for staff schedules
Route::get('/debug-staff-schedule/{id}', function ($id) {
    try {
        $order = \App\Models\Order::findOrFail($id);
        $order->load(['customer', 'staff', 'service']);
        
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'weight' => $order->weight,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

// Authentication routes
require __DIR__.'/auth.php';
