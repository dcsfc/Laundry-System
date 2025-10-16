<?php

namespace App\Http\Controllers\Customer;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    /**
     * Display all announcements for customers
     */
    public function index()
    {
        $announcements = Announcement::with('createdBy')
            ->active()
            ->where(function($query) {
                $query->where('visible_to', 'customer')
                      ->orWhere('visible_to', 'all');
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.announcements', compact('announcements'));
    }
}


