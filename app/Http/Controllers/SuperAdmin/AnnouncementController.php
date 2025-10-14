<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('createdBy')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('superadmin.announcements.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:new,improvement,fix,maintenance,alert',
            'link' => 'nullable|url',
            'visible_to' => 'required|in:all,admin,staff,customer',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'is_pinned' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $announcement = Announcement::create([
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'link' => $request->link,
                'visible_to' => $request->visible_to,
                'expires_at' => $request->expires_at,
                'is_pinned' => $request->boolean('is_pinned'),
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Announcement created successfully',
                'announcement' => $announcement->load('createdBy')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Announcement $announcement)
    {
        return response()->json([
            'success' => true,
            'announcement' => $announcement->load('createdBy')
        ]);
    }

    public function edit(Announcement $announcement)
    {
        return response()->json([
            'success' => true,
            'announcement' => $announcement
        ]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:new,improvement,fix,maintenance,alert',
            'link' => 'nullable|url',
            'visible_to' => 'required|in:all,admin,staff,customer',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'is_pinned' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $announcement->update([
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'link' => $request->link,
                'visible_to' => $request->visible_to,
                'expires_at' => $request->expires_at,
                'is_pinned' => $request->boolean('is_pinned'),
                'is_active' => $request->boolean('is_active', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully',
                'announcement' => $announcement->load('createdBy')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Announcement deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Announcement $announcement)
    {
        try {
            $announcement->update(['is_active' => !$announcement->is_active]);
            
            return response()->json([
                'success' => true,
                'message' => 'Announcement status updated successfully',
                'is_active' => $announcement->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update announcement status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function togglePin(Announcement $announcement)
    {
        try {
            $announcement->update(['is_pinned' => !$announcement->is_pinned]);
            
            return response()->json([
                'success' => true,
                'message' => 'Announcement pin status updated successfully',
                'is_pinned' => $announcement->is_pinned
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update announcement pin status: ' . $e->getMessage()
            ], 500);
        }
    }
}