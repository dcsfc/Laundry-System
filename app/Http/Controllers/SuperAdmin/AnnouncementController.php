<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class AnnouncementController extends Controller {
    public function index() {
        // Sample announcements data for the reusable data table component
        $announcements = collect([
            [
                'id' => 1,
                'title' => 'Holiday Schedule Update',
                'message' => 'We will be closed on January 1st for New Year\'s Day. Regular hours resume on January 2nd.',
                'status' => 'Active',
                'priority' => 'High',
                'created_by' => 'Admin User',
                'created_at' => '2024-01-15 10:30:00',
                'expires_at' => '2024-01-31 23:59:59'
            ],
            [
                'id' => 2,
                'title' => 'New Service Available',
                'message' => 'We now offer eco-friendly dry cleaning services. Ask about our green cleaning options!',
                'status' => 'Active',
                'priority' => 'Medium',
                'created_by' => 'Staff Member',
                'created_at' => '2024-01-14 14:20:00',
                'expires_at' => '2024-02-14 23:59:59'
            ],
            [
                'id' => 3,
                'title' => 'System Maintenance',
                'message' => 'Scheduled maintenance on Sunday, January 21st from 2:00 AM to 4:00 AM. Online services may be temporarily unavailable.',
                'status' => 'Active',
                'priority' => 'High',
                'created_by' => 'Admin User',
                'created_at' => '2024-01-13 09:15:00',
                'expires_at' => '2024-01-22 23:59:59'
            ],
            [
                'id' => 4,
                'title' => 'Customer Appreciation Week',
                'message' => 'Thank you for your continued support! Enjoy 20% off all services this week.',
                'status' => 'Inactive',
                'priority' => 'Low',
                'created_by' => 'Staff Member',
                'created_at' => '2024-01-12 16:45:00',
                'expires_at' => '2024-01-19 23:59:59'
            ]
        ]);

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'title', 'label' => 'Title', 'sortable' => true],
            ['key' => 'message', 'label' => 'Message', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'priority', 'label' => 'Priority', 'sortable' => true],
            ['key' => 'created_by', 'label' => 'Created By', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Created At', 'sortable' => true],
            ['key' => 'expires_at', 'label' => 'Expires At', 'sortable' => true]
        ];

        // Define actions for the data table
        $actions = [
            ['label' => 'View', 'onclick' => 'viewAnnouncement'],
            ['label' => 'Edit', 'onclick' => 'editAnnouncement'],
            ['label' => 'Toggle Status', 'onclick' => 'toggleAnnouncementStatus'],
            ['label' => 'Delete', 'onclick' => 'deleteAnnouncement']
        ];

        return view('superadmin.announcements.index', compact('announcements', 'columns', 'actions'));
    }
    public function create() {
        return view('superadmin.announcements.create');
    }
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            // Accept either 'message' or legacy 'body'
            'message' => 'nullable|string',
            'body' => 'nullable|string',
        ]);

        $payload = [
            'title' => $request->title,
            'message' => $request->message ?? $request->body,
            'created_by' => auth()->id() ?? 1,
        ];

        Announcement::create($payload);
        return redirect()->route('superadmin.announcements.index');
    }
    public function edit(Announcement $announcement) {
        return view('superadmin.announcements.edit', compact('announcement'));
    }
    public function update(Request $request, Announcement $announcement) {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'body' => 'nullable|string',
        ]);

        $payload = [
            'title' => $request->title,
            'message' => $request->message ?? $request->body,
        ];

        $announcement->update($payload);
        return redirect()->route('superadmin.announcements.index');
    }
    public function destroy(Announcement $announcement) {
        $announcement->delete();
        return redirect()->route('superadmin.announcements.index');
    }
}
