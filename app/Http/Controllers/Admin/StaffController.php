<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members
     */
    public function index()
    {
        $staff = User::whereHas('role', function($query) {
            $query->where('name', 'staff');
        })->with('role')->paginate(15);

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created staff member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Get staff role
        $staffRole = Role::where('name', 'staff')->first();
        
        if (!$staffRole) {
            return redirect()->back()->with('error', 'Staff role not found in the system.');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = $staffRole->id;
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    /**
     * Display the specified staff member
     */
    public function show(User $staff)
    {
        $staff->load(['role', 'ordersAssigned', 'createdOrders']);
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member
     */
    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member
     */
    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified staff member
     */
    public function destroy(User $staff)
    {
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    /**
     * Toggle staff status (active/inactive)
     */
    public function toggleStatus(User $staff)
    {
        $newStatus = $staff->status === 'active' ? 'inactive' : 'active';
        $staff->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Staff status updated successfully.',
            'status' => $newStatus
        ]);
    }
}


