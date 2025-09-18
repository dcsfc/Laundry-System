<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the superadmin dashboard with real data
     */
    public function index()
    {
        // Get total users count
        $totalUsers = User::count();
        
        // Get users by role
        $usersByRole = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->pluck('count', 'name')
            ->toArray();
        
        $admins = $usersByRole['administrator'] ?? 0;
        $staff = $usersByRole['staff'] ?? 0;
        $customers = $usersByRole['customer'] ?? 0;
        
        // Get recent announcements
        $recentAnnouncements = Announcement::with('createdBy')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Get recent orders
        $recentOrders = Order::with(['customer', 'staff'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get user growth data for the last 6 months
        $userGrowthData = User::select(
                DB::raw('strftime("%Y-%m", created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        // Fill in missing months with 0 values
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('Y-m');
        }
        
        foreach ($months as $month) {
            if (!isset($userGrowthData[$month])) {
                $userGrowthData[$month] = 0;
            }
        }
        
        // Sort by month
        ksort($userGrowthData);
        
        // Get role distribution data
        $roleDistribution = [
            'Administrators' => $admins,
            'Staff' => $staff,
            'Customers' => $customers
        ];
        
        // Get recent activity (user registrations)
        $recentUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Get orders statistics
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::whereIn('status', ['scheduled', 'priced', 'in_progress'])->count();
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        
        // Get monthly revenue for the last 6 months
        $revenueData = Payment::select(
                DB::raw('strftime("%Y-%m", paid_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_status', 'paid')
            ->where('paid_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        // Fill in missing months with 0 values for revenue
        foreach ($months as $month) {
            if (!isset($revenueData[$month])) {
                $revenueData[$month] = 0;
            }
        }
        
        // Sort by month
        ksort($revenueData);
        
        return view('dashboard.superadmin', compact(
            'totalUsers',
            'admins',
            'staff',
            'customers',
            'recentAnnouncements',
            'recentOrders',
            'userGrowthData',
            'roleDistribution',
            'recentUsers',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'totalRevenue',
            'revenueData'
        ));
    }
}
