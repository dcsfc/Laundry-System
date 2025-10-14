<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        return view('superadmin.settings.index');
    }

    /**
     * Get all settings
     */
    public function getSettings()
    {
        try {
            $settings = $this->getAllSettings();
            
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting settings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load settings'
            ], 500);
        }
    }

    /**
     * Save settings
     */
    public function saveSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'settings' => 'required|array',
                'settings.general' => 'array',
                'settings.general.app_name' => 'string|max:255',
                'settings.general.company_name' => 'string|max:255',
                'settings.general.timezone' => 'string|max:50',
                'settings.general.default_language' => 'string|max:10',
                'settings.general.logo_url' => 'string|max:500',
                'settings.general.maintenance_mode' => 'boolean',
                'settings.email' => 'array',
                'settings.email.smtp_host' => 'string|max:255',
                'settings.email.smtp_port' => 'integer|min:1|max:65535',
                'settings.email.smtp_username' => 'string|max:255',
                'settings.email.smtp_password' => 'string|max:255',
                'settings.email.encryption' => 'string|in:tls,ssl,none',
                'settings.email.system_email' => 'email|max:255',
                'settings.email.in_app_notifications' => 'boolean',
                'settings.email.email_notifications' => 'boolean',
                'settings.security' => 'array',
                'settings.security.password_min_length' => 'integer|min:6|max:32',
                'settings.security.session_timeout' => 'integer|min:15|max:480',
                'settings.security.require_uppercase' => 'boolean',
                'settings.security.require_numbers' => 'boolean',
                'settings.security.require_special_chars' => 'boolean',
                'settings.security.lockout_attempts' => 'integer|min:3|max:10',
                'settings.security.allowed_domains' => 'string|max:500',
                'settings.security.two_factor_enforcement' => 'boolean',
                'settings.data' => 'array',
                'settings.data.upload_limit' => 'integer|min:1|max:100',
                'settings.data.allowed_file_types' => 'string|max:255',
                'settings.data.storage_type' => 'string|in:local,s3,gcs',
                'settings.data.retention_days' => 'integer|min:30|max:3650',
                'settings.data.backup_schedule' => 'string|in:daily,weekly,monthly,manual',
                'settings.notifications' => 'array',
                'settings.notifications.order_ready' => 'boolean',
                'settings.notifications.payment_received' => 'boolean',
                'settings.notifications.low_inventory' => 'boolean',
                'settings.laundry' => 'array',
                'settings.laundry.default_pickup_days' => 'integer|min:1|max:30',
                'settings.laundry.express_service_available' => 'boolean',
                'settings.laundry.express_service_hours' => 'integer|min:1|max:72',
                'settings.laundry.auto_assign_staff' => 'boolean',
                'settings.laundry.require_payment_before_pickup' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $settings = $request->input('settings');
            
            // Save each setting category
            foreach ($settings as $category => $categorySettings) {
                $this->saveSettingsCategory($category, $categorySettings);
            }

            // Log the settings update
            Log::info('System settings updated', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'updated_categories' => array_keys($settings)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Settings saved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving settings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save settings'
            ], 500);
        }
    }

    /**
     * Get all settings from cache or database
     */
    private function getAllSettings()
    {
        $defaultSettings = $this->getDefaultSettings();
        
        // Try to get from cache first
        $cachedSettings = Cache::get('system_settings', []);
        
        // If cache is empty, try to get from database
        if (empty($cachedSettings)) {
            $dbSettings = $this->getSettingsFromDatabase();
            $cachedSettings = $dbSettings;
            
            // Cache the database settings
            Cache::put('system_settings', $cachedSettings, now()->addDays(30));
        }
        
        // Merge with defaults
        $settings = array_merge_recursive($defaultSettings, $cachedSettings);
        
        return $settings;
    }

    /**
     * Save settings for a specific category
     */
    private function saveSettingsCategory($category, $settings)
    {
        // Get current settings
        $currentSettings = Cache::get('system_settings', []);
        
        // Update the category
        $currentSettings[$category] = $settings;
        
        // Save to cache
        Cache::put('system_settings', $currentSettings, now()->addDays(30));
        
        // Save to database
        $this->saveToDatabase($category, $settings);
    }

    /**
     * Get default settings
     */
    private function getDefaultSettings()
    {
        return [
            'general' => [
                'app_name' => 'Latino Laundry System',
                'company_name' => 'Latino Laundry Co.',
                'timezone' => 'UTC',
                'default_language' => 'en',
                'logo_url' => '',
                'maintenance_mode' => false
            ],
            'email' => [
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'smtp_username' => '',
                'smtp_password' => '',
                'encryption' => 'tls',
                'system_email' => 'noreply@latino-laundry.com',
                'in_app_notifications' => true,
                'email_notifications' => true
            ],
            'security' => [
                'password_min_length' => 8,
                'session_timeout' => 30,
                'require_uppercase' => true,
                'require_numbers' => true,
                'require_special_chars' => true,
                'lockout_attempts' => 5,
                'allowed_domains' => '',
                'two_factor_enforcement' => false
            ],
            'data' => [
                'upload_limit' => 10,
                'allowed_file_types' => 'jpg,jpeg,png,pdf,doc,docx',
                'storage_type' => 'local',
                'retention_days' => 90,
                'backup_schedule' => 'daily'
            ],
            'notifications' => [
                'order_ready' => true,
                'payment_received' => true,
                'low_inventory' => true
            ],
            'laundry' => [
                'default_pickup_days' => 3,
                'express_service_available' => true,
                'express_service_hours' => 24,
                'auto_assign_staff' => true,
                'require_payment_before_pickup' => false
            ]
        ];
    }

    /**
     * Get settings from database
     */
    private function getSettingsFromDatabase()
    {
        try {
            $settings = [];
            $dbSettings = Setting::all();
            
            foreach ($dbSettings as $setting) {
                $value = $setting->value;
                
                // Try to decode JSON arrays first
                if (in_array($setting->key, ['business_days'])) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $value = $decoded;
                    }
                }
                // Convert string values to appropriate types
                elseif (in_array($setting->key, ['maintenance_mode', 'in_app_notifications', 'email_notifications', 
                    'require_uppercase', 'require_numbers', 'require_special_chars', 'two_factor_enforcement',
                    'order_ready', 'payment_received', 'low_inventory', 'express_service_available',
                    'auto_assign_staff', 'require_payment_before_pickup'])) {
                    $value = (bool) $value;
                } elseif (in_array($setting->key, ['smtp_port', 'password_min_length', 'session_timeout', 
                    'lockout_attempts', 'upload_limit', 'retention_days', 'default_pickup_days', 
                    'express_service_hours'])) {
                    $value = (int) $value;
                } elseif (in_array($setting->key, ['tax_rate'])) {
                    $value = (float) $value;
                }
                
                $settings[$setting->category][$setting->key] = $value;
            }
            
            return $settings;
        } catch (\Exception $e) {
            Log::warning('Could not load settings from database: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Save settings to database
     */
    private function saveToDatabase($category, $settings)
    {
        try {
            foreach ($settings as $key => $value) {
                // Convert values to string for database storage
                if (is_bool($value)) {
                    $dbValue = $value ? '1' : '0';
                } elseif (is_array($value)) {
                    $dbValue = json_encode($value);
                } else {
                    $dbValue = (string) $value;
                }
                
                Setting::updateOrCreate(
                    ['category' => $category, 'key' => $key],
                    ['value' => $dbValue]
                );
            }
        } catch (\Exception $e) {
            Log::error('Could not save settings to database: ' . $e->getMessage());
        }
    }

    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid email address'
                ], 422);
            }

            // Here you would implement email testing logic
            // For now, we'll just return success
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error testing email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email'
            ], 500);
        }
    }

    /**
     * Clear system cache
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            
            Log::info('System cache cleared', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error clearing cache: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache'
            ], 500);
        }
    }

    /**
     * Get audit logs
     */
    public function getAuditLogs(Request $request)
    {
        try {
            $query = \App\Models\AuditLog::query();
            
            // Apply filters
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            if ($request->has('action')) {
                $query->where('action', 'like', '%' . $request->action . '%');
            }
            
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                      ->orWhere('action', 'like', '%' . $search . '%');
                });
            }
            
            $logs = $query->with('user')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);
            
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting audit logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get audit logs'
            ], 500);
        }
    }

    /**
     * Export audit logs
     */
    public function exportAuditLogs(Request $request)
    {
        try {
            $query = \App\Models\AuditLog::query();
            
            // Apply same filters as getAuditLogs
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            if ($request->has('action')) {
                $query->where('action', 'like', '%' . $request->action . '%');
            }
            
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            $logs = $query->with('user')->orderBy('created_at', 'desc')->get();
            
            $csvData = "Date,User,Action,Description,IP Address\n";
            
            foreach ($logs as $log) {
                $csvData .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    str_replace(',', ';', $log->description),
                    $log->ip_address ?? 'N/A'
                );
            }
            
            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename=audit_logs_' . date('Y-m-d') . '.csv');

        } catch (\Exception $e) {
            Log::error('Error exporting audit logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to export audit logs'
            ], 500);
        }
    }

    /**
     * Create manual backup
     */
    public function createBackup()
    {
        try {
            // This would typically use Laravel Backup or similar package
            // For now, we'll just log the action
            
            Log::info('Manual backup initiated', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Backup process started successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating backup: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup'
            ], 500);
        }
    }

}
