<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        
        // Orders removed from Super Admin access
        
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
        
        // Order and payment statistics removed from Super Admin access
        
        return view('dashboard.superadmin', compact(
            'totalUsers',
            'admins',
            'staff',
            'customers',
            'recentAnnouncements',
            'userGrowthData',
            'roleDistribution',
            'recentUsers'
        ));
    }
}
