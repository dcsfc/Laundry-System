<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DataTableController extends Controller
{
    /**
     * Display a listing of users for the data table
     */
    public function index()
    {
        $users = User::with('role')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number ?? 'N/A',
                'role' => $user->role ? $user->role->name : 'N/A',
                'status' => ucfirst($user->status ?? 'active'),
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
            ];
        });

        // Convert collection to array for proper JavaScript handling
        $usersArray = $users->toArray();

        if ($users->isEmpty()) {
            $usersArray = [
                ['id' => 1, 'name' => 'Super Admin', 'email' => 'admin@laundryapp.com', 'phone_number' => '+1234567890', 'role' => 'superadmin', 'status' => 'Active', 'created_at' => '2024-01-01'],
                ['id' => 2, 'name' => 'John Staff', 'email' => 'john@laundryapp.com', 'phone_number' => '+1234567891', 'role' => 'staff', 'status' => 'Active', 'created_at' => '2024-01-10'],
            ];
        }

        $columns = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Full Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'phone_number', 'label' => 'Phone Number'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Created'],
        ];

        $actions = [
            ['label' => 'View'],
            ['label' => 'Edit'],
            ['label' => 'Delete'],
        ];

        return view('superadmin.users.usermanagement', [
            'users' => $usersArray,
            'columns' => $columns,
            'actions' => $actions,
        ]);
    }

    /**
     * Fetch users data via AJAX for DataTables
     */
    public function fetchUsers(Request $request)
    {
        try {
            // Get DataTables parameters
            $draw = $request->get('draw');
            $start = (int) $request->get('start', 0);
            $length = (int) $request->get('length', 10);
            $search = $request->get('search')['value'] ?? '';
            
            // Get custom filters
            $roleFilter = $request->get('role');
            $statusFilter = $request->get('status');
            
            // Build query
            $query = User::select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'users.status',
                'users.created_at',
                'users.role_id',
                'users.created_by',
                'roles.name as role_name',
                'creator.name as created_by_name'
            ])
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('users as creator', 'users.created_by', '=', 'creator.id');

            // Apply filters
            if ($statusFilter && $statusFilter !== '') {
                $query->where('users.status', $statusFilter);
            }

            if ($roleFilter && $roleFilter !== '') {
                $query->where('users.role_id', $roleFilter);
            }

            // Apply search
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.email', 'like', "%{$search}%")
                      ->orWhere('users.phone_number', 'like', "%{$search}%")
                      ->orWhere('roles.name', 'like', "%{$search}%");
                });
            }

            // Get total count before pagination
            $totalRecords = $query->count();
            
            // Apply pagination
            $users = $query->skip($start)
                          ->take($length)
                          ->orderBy('users.id', 'desc')
                          ->get()
                          ->map(function ($user) {
                              return [
                                  'id' => $user->id,
                                  'name' => $user->name,
                                  'email' => $user->email,
                                  'phone_number' => $user->phone_number ?? 'N/A',
                                  'role' => ucfirst($user->role_name ?? 'No Role'),
                                  'status' => ucfirst($user->status ?? 'Active'),
                                  'created_at' => $user->created_at ? $user->created_at->format('M d, Y') : 'N/A',
                                  'created_by_name' => $user->created_by_name ?? 'System',
                                  'account_age' => $user->created_at ? floor($user->created_at->diffInDays(now())) . ' days' : 'N/A'
                              ];
                          });

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $users
            ]);

        } catch (\Exception $e) {
            Log::error('Fetch Users Error: ' . $e->getMessage());
            
            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error fetching data'
            ], 500);
        }
    }

    /**
     * Get user data for editing
     */
    public function getUserData(Request $request)
    {
        try {
            $userId = $request->get('id');
            $user = User::with(['role'])->find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role_id' => $user->role_id,
                    'status' => $user->status,
                    'role_name' => $user->role ? $user->role->name : 'No Role'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get User Data Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user data'
            ], 500);
        }
    }

    /**
     * Update user via AJAX
     */
    public function updateUserAjax(Request $request)
    {
        try {
            $userId = $request->get('id');
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Validate input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $userId,
                'phone_number' => 'nullable|string|max:20',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|in:active,inactive'
            ]);

            // Update user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role_id' => $request->role_id,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role' => $user->role ? ucfirst($user->role->name) : 'No Role',
                    'status' => ucfirst($user->status)
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update User Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating user'
            ], 500);
        }
    }

    /**
     * Create user via AJAX
     */
    public function storeAjax(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'nullable|string|max:20',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|in:active,inactive',
                'password' => 'required|string|min:6'
            ]);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role_id' => $request->role_id,
                'status' => $request->status,
                'password' => bcrypt($request->password),
                'created_by' => auth()->id()
            ]);

            // Load relationships
            $user->load(['role', 'createdBy']);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role' => $user->role ? ucfirst($user->role->name) : 'No Role',
                    'status' => ucfirst($user->status),
                    'created_at' => $user->created_at->format('M d, Y'),
                    'created_by_name' => $user->createdBy ? $user->createdBy->name : 'System'
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Create User Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating user'
            ], 500);
        }
    }

    /**
     * Toggle user status
     */
    public function toggleUserStatus(Request $request)
    {
        try {
            $userId = $request->get('id');
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'new_status' => ucfirst($newStatus)
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle User Status Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating user status'
            ], 500);
        }
    }

    /**
     * Get roles for dropdown
     */
    public function getRoles()
    {
        try {
            $roles = Role::orderBy('name')->get(['id', 'name']);
            
            return response()->json([
                'success' => true,
                'roles' => $roles
            ]);

        } catch (\Exception $e) {
            Log::error('Get Roles Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching roles'
            ], 500);
        }
    }

    /**
     * Debug method to check database connection
     */
    public function debug()
    {
        try {
            $userCount = User::count();
            $roleCount = Role::count();
            
            return response()->json([
                'status' => 'success',
                'user_count' => $userCount,
                'role_count' => $roleCount,
                'message' => 'Database connection working'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
