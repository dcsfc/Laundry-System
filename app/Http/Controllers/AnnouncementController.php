<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Get announcements for dashboard widget based on user role
     */
    public function getDashboardAnnouncements(Request $request)
    {
        $user = auth()->user();
        $role = $user->role->name;

        $announcements = Announcement::active()
            ->visibleTo($role)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'announcements' => $announcements
        ]);
    }

    /**
     * Get announcements for a specific role (for dashboard widgets)
     */
    public function getAnnouncementsForRole($role)
    {
        $announcements = Announcement::active()
            ->visibleTo($role)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $announcements;
    }
}
