<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs with filtering and pagination
     */
    public function index(Request $request)
    {
        $auditLogs = AuditLog::with(['user.role'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);



        // If no audit logs exist, create some sample data for testing
        if ($auditLogs->count() === 0 && $auditLogs->total() === 0) {
            $this->createSampleAuditLogs();
            // Refresh the query
            $auditLogs = AuditLog::with(['user.role'])
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        }

        
        return view('superadmin.audit-logs.index', compact('auditLogs'));
    }

    /**
     * Get recent audit logs for dashboard
     */
    public function getRecent($limit = 3)
    {
        return AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs for a specific user
     */
    public function getUserActivity($userId)
    {
        $user = User::findOrFail($userId);
        
        $auditLogs = AuditLog::with('user')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('superadmin.audit-logs.user-activity', compact('auditLogs', 'user'));
    }


    /**
     * Clear old audit logs (for maintenance)
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        $days = $request->days;
        $cutoffDate = now()->subDays($days);

        $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        Log::info('Audit logs cleared', [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name,
            'days_old' => $days,
            'deleted_count' => $deletedCount
        ]);

        return response()->json([
            'success' => true,
            'message' => "Cleared {$deletedCount} audit logs older than {$days} days"
        ]);
    }



    /**
     * Create sample audit logs for testing
     */
    private function createSampleAuditLogs()
    {
        $users = User::take(3)->get();
        $superAdmin = $users->first();
        
        if (!$superAdmin) {
            return;
        }

        $sampleLogs = [
            [
                'action' => 'USER_CREATED',
                'description' => 'Created new user "John Doe"
• Email: <span class=\'text-green-400 font-semibold\'>\'john@example.com\'</span>
• Phone: <span class=\'text-green-400 font-semibold\'>\'+1234567890\'</span>
• Role: <span class=\'text-green-400 font-semibold\'>\'customer\'</span>
• Status: <span class=\'text-green-400 font-semibold\'>\'active\'</span>',
                'user_id' => $superAdmin->id,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => [
                    'user_id' => 2,
                    'user_name' => 'John Doe',
                    'user_email' => 'john@example.com',
                    'role_name' => 'customer',
                    'status' => 'active'
                ],
                'created_at' => now()->subMinutes(5)
            ],
            [
                'action' => 'USER_UPDATED',
                'description' => 'Updated user "Jane Smith" — changed
• Role: <span class=\'text-slate-400\'>\'staff\'</span> → <span class=\'text-green-400 font-semibold\'>\'customer\'</span>
• Status: <span class=\'text-slate-400\'>\'inactive\'</span> → <span class=\'text-green-400 font-semibold\'>\'active\'</span>',
                'user_id' => $superAdmin->id,
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => [
                    'user_id' => 3,
                    'user_name' => 'Jane Smith',
                    'user_email' => 'jane@example.com',
                    'old_role' => 'staff',
                    'new_role' => 'customer',
                    'old_status' => 'inactive',
                    'new_status' => 'active'
                ],
                'created_at' => now()->subMinutes(3)
            ],
            [
                'action' => 'USER_STATUS_CHANGED',
                'description' => 'Changed user "Bob Johnson" — changed
• Status: <span class=\'text-slate-400\'>\'active\'</span> → <span class=\'text-green-400 font-semibold\'>\'inactive\'</span>',
                'user_id' => $superAdmin->id,
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => [
                    'user_id' => 4,
                    'user_name' => 'Bob Johnson',
                    'user_email' => 'bob@example.com',
                    'old_status' => 'active',
                    'new_status' => 'inactive',
                    'action_type' => 'account_disabled'
                ],
                'created_at' => now()->subMinutes(1)
            ],
            [
                'action' => 'USER_LOGIN',
                'description' => 'User "Super Admin" logged in successfully from 192.168.1.103',
                'user_id' => $superAdmin->id,
                'ip_address' => '192.168.1.103',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => [
                    'user_id' => $superAdmin->id,
                    'user_name' => $superAdmin->name,
                    'user_email' => $superAdmin->email,
                    'user_role' => 'superadmin',
                    'login_time' => now()->subSeconds(30)->toDateTimeString(),
                    'session_id' => 'abc123def456'
                ],
                'created_at' => now()->subSeconds(30)
            ],
            [
                'action' => 'USER_DELETED',
                'description' => 'Deleted user "Old User"
• Email: <span class=\'text-slate-400\'>\'old@example.com\'</span>
• Role: <span class=\'text-slate-400\'>\'customer\'</span>
• Status: <span class=\'text-slate-400\'>\'active\'</span>',
                'user_id' => $superAdmin->id,
                'ip_address' => '192.168.1.104',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => [
                    'deleted_user_id' => 5,
                    'deleted_user_name' => 'Old User',
                    'deleted_user_email' => 'old@example.com',
                    'deleted_user_role' => 'customer',
                    'deletion_reason' => 'Admin deletion',
                    'deleted_at' => now()->subMinutes(10)->toDateTimeString()
                ],
                'created_at' => now()->subMinutes(10)
            ]
        ];

        foreach ($sampleLogs as $logData) {
            AuditLog::create($logData);
        }
    }
}
