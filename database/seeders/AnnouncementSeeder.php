<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
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

        $announcements = [
            [
                'title' => '🚀 New Dashboard Features Released',
                'message' => 'We\'ve added advanced analytics, real-time notifications, and improved user management tools. Check out the new features in your dashboard!',
                'type' => 'new',
                'link' => 'https://example.com/new-features',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(30),
                'is_pinned' => true,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '⚡ Performance Improvements',
                'message' => 'System performance has been optimized with 40% faster load times and improved database queries. Your experience should be noticeably smoother.',
                'type' => 'improvement',
                'link' => null,
                'visible_to' => 'all',
                'expires_at' => now()->addDays(60),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🔧 Bug Fixes & Security Updates',
                'message' => 'Fixed several minor bugs in the payment processing system and applied important security patches. All systems are now running smoothly.',
                'type' => 'fix',
                'link' => 'https://example.com/security-updates',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(45),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '📢 Scheduled Maintenance Notice',
                'message' => 'We will be performing scheduled maintenance on Sunday from 2:00 AM to 4:00 AM EST. Some features may be temporarily unavailable.',
                'type' => 'maintenance',
                'link' => null,
                'visible_to' => 'all',
                'expires_at' => now()->addDays(7),
                'is_pinned' => true,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🎉 Customer Appreciation Month',
                'message' => 'Thank you for being amazing customers! Enjoy 25% off all premium services throughout February. Use code APPRECIATE25 at checkout.',
                'type' => 'new',
                'link' => 'https://example.com/promotion',
                'visible_to' => 'customer',
                'expires_at' => now()->addDays(45),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '📱 Mobile App Update Available',
                'message' => 'Version 2.1.0 of our mobile app is now available with improved offline support, faster sync, and a redesigned interface.',
                'type' => 'improvement',
                'link' => 'https://example.com/mobile-app',
                'visible_to' => 'customer',
                'expires_at' => now()->addDays(30),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🔒 Enhanced Security Features',
                'message' => 'We\'ve implemented two-factor authentication and advanced encryption for all user data. Your account is now more secure than ever.',
                'type' => 'improvement',
                'link' => 'https://example.com/security-features',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(90),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '📊 New Reporting Dashboard',
                'message' => 'Access detailed analytics and custom reports with our new reporting dashboard. Track your business metrics in real-time.',
                'type' => 'new',
                'link' => 'https://example.com/reports',
                'visible_to' => 'admin',
                'expires_at' => now()->addDays(60),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '⚠️ System Alert: High Traffic Expected',
                'message' => 'We\'re expecting high traffic during peak hours today. Please be patient if you experience slower response times.',
                'type' => 'alert',
                'link' => null,
                'visible_to' => 'all',
                'expires_at' => now()->addDays(1),
                'is_pinned' => true,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🛠️ Staff Training Session',
                'message' => 'Mandatory training session for all staff members on the new inventory management system. Please attend the session scheduled for next week.',
                'type' => 'maintenance',
                'link' => 'https://example.com/training',
                'visible_to' => 'staff',
                'expires_at' => now()->addDays(14),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '📈 Revenue Target Achieved',
                'message' => 'Congratulations! We\'ve exceeded our monthly revenue target by 15%. Great work team!',
                'type' => 'new',
                'link' => null,
                'visible_to' => 'admin',
                'expires_at' => now()->addDays(30),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'title' => '🔧 Laundry Machine Maintenance',
                'message' => 'Regular maintenance scheduled for laundry machines #3 and #5. They will be temporarily out of service.',
                'type' => 'maintenance',
                'link' => null,
                'visible_to' => 'staff',
                'expires_at' => now()->addDays(3),
                'is_pinned' => false,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ]
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}