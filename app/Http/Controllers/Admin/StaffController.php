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
        })->with(['role', 'createdBy'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number ?? 'N/A',
                    'status' => ucfirst($user->status ?? 'Active'),
                    'status_color' => $user->status === 'active' ? 'green' : 'red',
                    'created_by' => $user->createdBy ? $user->createdBy->name : 'System',
                    'created_at' => $user->created_at->format('M j, Y'),
                    'last_login' => $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('M j, Y g:i A') : 'Never',
                ];
            })->toArray();

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'name', 'label' => 'Name', 'sortable' => true, 'searchable' => true],
            ['key' => 'email', 'label' => 'Email', 'sortable' => true, 'searchable' => true],
            ['key' => 'phone_number', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'created_by', 'label' => 'Created By', 'sortable' => true],
            ['key' => 'last_login', 'label' => 'Last Login', 'sortable' => true],
        ];

        // Define actions for the data table
        $actions = [
            ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
            ['key' => 'edit', 'label' => 'Edit', 'icon' => 'fas fa-edit', 'color' => 'yellow'],
            ['key' => 'toggle_status', 'label' => 'Toggle Status', 'icon' => 'fas fa-toggle-on', 'color' => 'green'],
            ['key' => 'delete', 'label' => 'Delete', 'icon' => 'fas fa-trash', 'color' => 'red'],
        ];

        $description = 'Manage staff members, their accounts, and permissions';

        return view('admin.staff.index', compact('staff', 'columns', 'actions', 'description'));
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


