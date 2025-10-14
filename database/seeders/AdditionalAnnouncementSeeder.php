<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;

class AdditionalAnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a super admin user to be the creator
        $superAdmin = User::whereHas('role', function($query) {
            $query->where('name', 'superadmin');
        })->first();

        if (!$superAdmin) {
            $superAdmin = User::first(); // Fallback to first user
        }

        $additionalAnnouncements = [
            [
                'title' => '🎯 Q4 Goals Update',
                'message' => 'We\'re 75% through Q4 and on track to meet our annual targets. Keep up the excellent work everyone!',
                'type' => 'new',
                'link' => null,
                'visible_to' => 'admin',
                'expires_at' => now()->addDays(45),
                'is_pinned' => true,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🔔 New Notification System',
                'message' => 'Real-time notifications are now live! You\'ll receive instant updates for orders, payments, and important announcements.',
                'type' => 'improvement',
                'link' => 'https://example.com/notifications-guide',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(60),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '⚠️ Payment Processing Update',
                'message' => 'We\'ve temporarily disabled credit card payments due to a security update. Cash and mobile payments are still available.',
                'type' => 'alert',
                'link' => 'https://example.com/payment-status',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(3),
                'is_pinned' => true,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '📱 Mobile App Bug Fix',
                'message' => 'Fixed the login issue on Android devices. Please update to the latest version from the app store.',
                'type' => 'fix',
                'link' => 'https://play.google.com/store/apps/details?id=com.latino.laundry',
                'visible_to' => 'customer',
                'expires_at' => now()->addDays(30),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🏆 Employee of the Month',
                'message' => 'Congratulations to Maria Santos for outstanding customer service this month! Thank you for your dedication.',
                'type' => 'new',
                'link' => null,
                'visible_to' => 'staff',
                'expires_at' => now()->addDays(30),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🔧 Equipment Maintenance Complete',
                'message' => 'All washing machines and dryers have been serviced and are running at optimal performance.',
                'type' => 'maintenance',
                'link' => null,
                'visible_to' => 'staff',
                'expires_at' => now()->addDays(7),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '💳 New Payment Methods',
                'message' => 'We now accept PayPal and Apple Pay! More convenient payment options for our customers.',
                'type' => 'improvement',
                'link' => 'https://example.com/payment-methods',
                'visible_to' => 'customer',
                'expires_at' => now()->addDays(90),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '📊 Weekly Report Available',
                'message' => 'This week\'s performance report is now available in the admin dashboard. Revenue up 12% from last week!',
                'type' => 'new',
                'link' => '/admin/reports',
                'visible_to' => 'admin',
                'expires_at' => now()->addDays(7),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🌱 Eco-Friendly Initiative',
                'message' => 'We\'re going green! All our detergents are now 100% biodegradable and environmentally safe.',
                'type' => 'improvement',
                'link' => 'https://example.com/eco-friendly',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(120),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🚨 Security Alert',
                'message' => 'Please change your passwords immediately. We\'ve detected suspicious activity on some accounts.',
                'type' => 'alert',
                'link' => '/security/change-password',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(1),
                'is_pinned' => true,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ]
        ];

        foreach ($additionalAnnouncements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
