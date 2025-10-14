<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use App\Models\User;

class AddSampleAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:add-sample {--count=5 : Number of sample announcements to add}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add sample announcements to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        
        // Get a super admin user
        $superAdmin = User::whereHas('role', function($query) {
            $query->where('name', 'superadmin');
        })->first();

        if (!$superAdmin) {
            $superAdmin = User::first();
        }

        if (!$superAdmin) {
            $this->error('No users found. Please create a user first.');
            return 1;
        }

        $sampleAnnouncements = [
            [
                'title' => '🎉 Welcome to Our System!',
                'message' => 'Thank you for using our laundry management system. We\'re excited to help you streamline your operations.',
                'type' => 'new',
                'visible_to' => 'all',
                'is_pinned' => true,
            ],
            [
                'title' => '📈 Performance Metrics',
                'message' => 'Check out the new analytics dashboard to track your business performance and identify growth opportunities.',
                'type' => 'improvement',
                'visible_to' => 'admin',
                'link' => '/admin/analytics',
            ],
            [
                'title' => '🔧 System Update',
                'message' => 'We\'ve released a new update with bug fixes and performance improvements. The system will be faster and more reliable.',
                'type' => 'fix',
                'visible_to' => 'all',
            ],
            [
                'title' => '🛠️ Scheduled Maintenance',
                'message' => 'System maintenance is scheduled for this weekend. Some features may be temporarily unavailable.',
                'type' => 'maintenance',
                'visible_to' => 'all',
                'expires_at' => now()->addDays(7),
            ],
            [
                'title' => '⚠️ Important Notice',
                'message' => 'Please ensure all data is backed up before the next system update. Contact support if you need assistance.',
                'type' => 'alert',
                'visible_to' => 'admin',
                'is_pinned' => true,
            ],
            [
                'title' => '🎯 New Features Available',
                'message' => 'Discover the latest features including automated reports, customer notifications, and inventory tracking.',
                'type' => 'new',
                'visible_to' => 'all',
                'link' => '/features',
            ],
            [
                'title' => '📱 Mobile App Update',
                'message' => 'Our mobile app has been updated with new features and improved performance. Download the latest version now!',
                'type' => 'improvement',
                'visible_to' => 'customer',
                'link' => 'https://example.com/mobile-app',
            ],
            [
                'title' => '🔒 Security Enhancement',
                'message' => 'We\'ve implemented additional security measures to protect your data. Two-factor authentication is now available.',
                'type' => 'improvement',
                'visible_to' => 'all',
                'link' => '/security/2fa',
            ],
            [
                'title' => '📊 Monthly Report Ready',
                'message' => 'Your monthly business report is now available. View detailed insights and performance metrics.',
                'type' => 'new',
                'visible_to' => 'admin',
                'link' => '/admin/reports/monthly',
            ],
            [
                'title' => '🎊 Holiday Schedule',
                'message' => 'Please note our holiday operating hours. We\'ll be closed on major holidays but open for emergency services.',
                'type' => 'maintenance',
                'visible_to' => 'customer',
                'expires_at' => now()->addDays(30),
            ]
        ];

        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            $announcement = $sampleAnnouncements[$i % count($sampleAnnouncements)];
            
            // Add some variation to make them unique
            $announcement['title'] = $announcement['title'] . ' #' . ($i + 1);
            $announcement['created_by'] = $superAdmin->id;
            $announcement['is_active'] = true;
            $announcement['created_at'] = now()->subDays(rand(0, 30));
            $announcement['updated_at'] = now();
            
            if (!isset($announcement['expires_at'])) {
                $announcement['expires_at'] = now()->addDays(rand(7, 90));
            }
            
            if (!isset($announcement['is_pinned'])) {
                $announcement['is_pinned'] = rand(0, 10) < 2; // 20% chance of being pinned
            }

            Announcement::create($announcement);
            $created++;
        }

        $this->info("Successfully created {$created} sample announcements!");
        $this->line("Total announcements in database: " . Announcement::count());
        
        return 0;
    }
}