<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'category' => 'general',
                'key' => 'app_name',
                'value' => 'Latino Laundry System',
                'description' => 'Application name displayed throughout the system'
            ],
            [
                'category' => 'general',
                'key' => 'company_name',
                'value' => 'Latino Laundry Co.',
                'description' => 'Company name for business operations'
            ],
            [
                'category' => 'general',
                'key' => 'timezone',
                'value' => 'America/New_York',
                'description' => 'Default timezone for the system'
            ],
            [
                'category' => 'general',
                'key' => 'default_language',
                'value' => 'en',
                'description' => 'Default language for the system'
            ],
            [
                'category' => 'general',
                'key' => 'logo_url',
                'value' => '',
                'description' => 'URL or path to company logo'
            ],
            [
                'category' => 'general',
                'key' => 'maintenance_mode',
                'value' => '0',
                'description' => 'Enable maintenance mode (0 = disabled, 1 = enabled)'
            ],

            // Email Settings
            [
                'category' => 'email',
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'description' => 'SMTP server hostname'
            ],
            [
                'category' => 'email',
                'key' => 'smtp_port',
                'value' => '587',
                'description' => 'SMTP server port'
            ],
            [
                'category' => 'email',
                'key' => 'smtp_username',
                'value' => '',
                'description' => 'SMTP authentication username'
            ],
            [
                'category' => 'email',
                'key' => 'smtp_password',
                'value' => '',
                'description' => 'SMTP authentication password'
            ],
            [
                'category' => 'email',
                'key' => 'encryption',
                'value' => 'tls',
                'description' => 'SMTP encryption type (tls, ssl, none)'
            ],
            [
                'category' => 'email',
                'key' => 'system_email',
                'value' => 'noreply@latino-laundry.com',
                'description' => 'System email address for outgoing emails'
            ],
            [
                'category' => 'email',
                'key' => 'in_app_notifications',
                'value' => '1',
                'description' => 'Enable in-app notifications (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'email',
                'key' => 'email_notifications',
                'value' => '1',
                'description' => 'Enable email notifications (0 = disabled, 1 = enabled)'
            ],

            // Security Settings
            [
                'category' => 'security',
                'key' => 'password_min_length',
                'value' => '8',
                'description' => 'Minimum password length requirement'
            ],
            [
                'category' => 'security',
                'key' => 'session_timeout',
                'value' => '30',
                'description' => 'Session timeout in minutes'
            ],
            [
                'category' => 'security',
                'key' => 'require_uppercase',
                'value' => '1',
                'description' => 'Require uppercase letters in passwords (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'security',
                'key' => 'require_numbers',
                'value' => '1',
                'description' => 'Require numbers in passwords (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'security',
                'key' => 'require_special_chars',
                'value' => '1',
                'description' => 'Require special characters in passwords (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'security',
                'key' => 'lockout_attempts',
                'value' => '5',
                'description' => 'Number of failed login attempts before account lockout'
            ],
            [
                'category' => 'security',
                'key' => 'allowed_domains',
                'value' => '',
                'description' => 'Comma-separated list of allowed email domains for registration'
            ],
            [
                'category' => 'security',
                'key' => 'two_factor_enforcement',
                'value' => '0',
                'description' => 'Enforce two-factor authentication (0 = disabled, 1 = enabled)'
            ],

            // Data & Storage Settings
            [
                'category' => 'data',
                'key' => 'upload_limit',
                'value' => '10',
                'description' => 'Maximum file upload size in MB'
            ],
            [
                'category' => 'data',
                'key' => 'allowed_file_types',
                'value' => 'jpg,jpeg,png,pdf,doc,docx',
                'description' => 'Comma-separated list of allowed file extensions'
            ],
            [
                'category' => 'data',
                'key' => 'storage_type',
                'value' => 'local',
                'description' => 'Storage type (local, s3, gcs)'
            ],
            [
                'category' => 'data',
                'key' => 'retention_days',
                'value' => '90',
                'description' => 'Data retention period in days'
            ],
            [
                'category' => 'data',
                'key' => 'backup_schedule',
                'value' => 'daily',
                'description' => 'Backup schedule (daily, weekly, monthly, manual)'
            ],

            // Notification Settings
            [
                'category' => 'notifications',
                'key' => 'order_ready',
                'value' => '1',
                'description' => 'Notify when order is ready (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'notifications',
                'key' => 'payment_received',
                'value' => '1',
                'description' => 'Notify when payment is received (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'notifications',
                'key' => 'low_inventory',
                'value' => '1',
                'description' => 'Notify when inventory is low (0 = disabled, 1 = enabled)'
            ],


            // Laundry Specific Settings
            [
                'category' => 'laundry',
                'key' => 'default_pickup_days',
                'value' => '3',
                'description' => 'Default number of days for pickup after drop-off'
            ],
            [
                'category' => 'laundry',
                'key' => 'express_service_available',
                'value' => '1',
                'description' => 'Express service availability (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'laundry',
                'key' => 'express_service_hours',
                'value' => '24',
                'description' => 'Express service completion time in hours'
            ],
            [
                'category' => 'laundry',
                'key' => 'auto_assign_staff',
                'value' => '1',
                'description' => 'Automatically assign staff to orders (0 = disabled, 1 = enabled)'
            ],
            [
                'category' => 'laundry',
                'key' => 'require_payment_before_pickup',
                'value' => '0',
                'description' => 'Require payment before pickup (0 = disabled, 1 = enabled)'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                [
                    'category' => $setting['category'],
                    'key' => $setting['key']
                ],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description']
                ]
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}

