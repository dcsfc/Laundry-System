<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Announcement;
use App\Models\AuditLog;
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
        
        // Calculate growth percentages (comparing current month vs last month)
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Total users growth
        $totalUsersCurrentMonth = User::where('created_at', '>=', $currentMonth)->count();
        $totalUsersLastMonth = User::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        $totalUsersGrowth = $totalUsersLastMonth > 0 ? round((($totalUsersCurrentMonth - $totalUsersLastMonth) / $totalUsersLastMonth) * 100, 1) : 0;
        
        // Administrators growth
        $adminsCurrentMonth = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'administrator')
            ->where('users.created_at', '>=', $currentMonth)
            ->count();
        $adminsLastMonth = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'administrator')
            ->whereBetween('users.created_at', [$lastMonth, $currentMonth])
            ->count();
        $adminsGrowth = $adminsLastMonth > 0 ? round((($adminsCurrentMonth - $adminsLastMonth) / $adminsLastMonth) * 100, 1) : 0;
        
        // Staff growth
        $staffCurrentMonth = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'staff')
            ->where('users.created_at', '>=', $currentMonth)
            ->count();
        $staffLastMonth = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'staff')
            ->whereBetween('users.created_at', [$lastMonth, $currentMonth])
            ->count();
        $staffGrowth = $staffLastMonth > 0 ? round((($staffCurrentMonth - $staffLastMonth) / $staffLastMonth) * 100, 1) : 0;
        
        // Customers growth
        $customersCurrentMonth = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'customer')
            ->where('users.created_at', '>=', $currentMonth)
            ->count();
        $customersLastMonth = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'customer')
            ->whereBetween('users.created_at', [$lastMonth, $currentMonth])
            ->count();
        $customersGrowth = $customersLastMonth > 0 ? round((($customersCurrentMonth - $customersLastMonth) / $customersLastMonth) * 100, 1) : 0;
        
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
        
        // Generate sparkline data for the last 7 days
        $sparklineData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $sparklineData[] = User::whereDate('created_at', $date)->count();
        }
        
        // Generate role-specific sparkline data
        $adminsSparklineData = [];
        $staffSparklineData = [];
        $customersSparklineData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            
            $adminsSparklineData[] = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->where('roles.name', 'administrator')
                ->whereDate('users.created_at', $date)
                ->count();
                
            $staffSparklineData[] = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->where('roles.name', 'staff')
                ->whereDate('users.created_at', $date)
                ->count();
                
            $customersSparklineData[] = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->where('roles.name', 'customer')
                ->whereDate('users.created_at', $date)
                ->count();
        }
        
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
        
        // Get recent audit logs (3 most recent)
        $auditLogController = new \App\Http\Controllers\SuperAdmin\AuditLogController();
        $recentAuditLogs = $auditLogController->getRecent(3);
        
        // Get real role changes from audit logs
        $roleChanges = $this->getRoleChanges();
        
        // Order and payment statistics removed from Super Admin access
        
        return view('dashboard.superadmin', compact(
            'totalUsers',
            'admins',
            'staff',
            'customers',
            'totalUsersGrowth',
            'adminsGrowth',
            'staffGrowth',
            'customersGrowth',
            'recentAnnouncements',
            'userGrowthData',
            'sparklineData',
            'adminsSparklineData',
            'staffSparklineData',
            'customersSparklineData',
            'roleDistribution',
            'recentAuditLogs',
            'recentUsers',
            'roleChanges'
        ));
    }

    /**
     * Get real role changes from audit logs (only actual role changes, not new users)
     */
    private function getRoleChanges()
    {
        return AuditLog::with('user')
            ->where('action', 'USER_UPDATED')
            ->whereNotNull('metadata')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->filter(function ($log) {
                $metadata = is_string($log->metadata) ? json_decode($log->metadata, true) : $log->metadata;
                // Only include logs that have actual role changes
                return isset($metadata['old_role']) && isset($metadata['new_role']) && 
                       $metadata['old_role'] !== $metadata['new_role'];
            })
            ->map(function ($log) {
                $metadata = is_string($log->metadata) ? json_decode($log->metadata, true) : $log->metadata;
                
                return [
                    'type' => 'role_change',
                    'icon' => 'fas fa-exchange-alt',
                    'iconColor' => 'text-amber-400',
                    'iconBg' => 'bg-amber-500/20',
                    'title' => 'Role Changed',
                    'description' => 'User "' . ($metadata['user_name'] ?? 'Unknown') . '" role changed from "' . ucfirst($metadata['old_role']) . '" to "' . ucfirst($metadata['new_role']) . '"',
                    'time' => $log->created_at->diffForHumans(),
                    'admin' => $log->user ? $log->user->name : 'System'
                ];
            });
    }
}
