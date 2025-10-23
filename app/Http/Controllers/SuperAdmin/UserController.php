<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        set_time_limit(120);
        
        $roles = cache()->remember('roles_list', 300, function() {
            return Role::orderBy('name')->get();
        });
        
        $users = User::with(['role', 'createdBy'])
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'users.status',
                'users.created_at',
                'users.role_id',
                'users.created_by'
            ])
            ->limit(50)
            ->orderBy('users.id', 'desc')
            ->get();
            
        if ($users->isEmpty()) {
            $users = User::select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'users.status',
                'users.created_at',
                'users.role_id',
                'users.created_by'
            ])
            ->limit(50)
            ->orderBy('users.id', 'desc')
            ->get();
        }
            
        
        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'name', 'label' => 'Name', 'sortable' => true],
            ['key' => 'email', 'label' => 'Email', 'sortable' => true],
            ['key' => 'phone_number', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'role_name', 'label' => 'Role', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'status'],
            ['key' => 'created_at', 'label' => 'Created', 'sortable' => true, 'type' => 'date'],
            ['key' => 'created_by_name', 'label' => 'Created By', 'sortable' => true],
            ['key' => 'account_age', 'label' => 'Account Age', 'sortable' => true]
        ];

        // Define actions for the data table
        $actions = [
            ['key' => 'viewUser', 'label' => 'View', 'icon' => 'view'],
            ['key' => 'editUser', 'label' => 'Edit', 'icon' => 'edit'],
            ['key' => 'toggleUserStatus', 'label' => 'Toggle Status', 'icon' => 'toggle'],
            ['key' => 'resetUserPassword', 'label' => 'Reset Password', 'icon' => 'key'],
            ['key' => 'deleteUser', 'label' => 'Delete', 'icon' => 'delete']
        ];
        
        $users = $users->map(function ($user) {
            $creatorName = 'System';
            if ($user->created_by && $user->createdBy) {
                $creatorName = $user->createdBy->name;
            } elseif ($user->created_by) {
                $creator = User::find($user->created_by);
                $creatorName = $creator ? $creator->name : 'Unknown User';
            }
            
            $roleName = $user->role ? ucfirst($user->role->name) : 'No Role';
            
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number ?? 'N/A',
                'role' => $roleName,
                'role_name' => $roleName, // For compatibility
                'status' => ucfirst($user->status ?? 'Active'),
                'created_at' => $user->created_at ? $user->created_at->format('M d, Y') : 'N/A',
                'created_by' => $user->created_by,
                'created_by_name' => $creatorName,
                'account_age' => $user->created_at ? floor($user->created_at->diffInDays(now())) . ' days' : 'N/A'
            ];
        })->toArray();
        
        
        return view('superadmin.users.index', compact('users', 'roles', 'columns', 'actions'));
    }

    public function fetchUsers(Request $request)
    {
        try {
            set_time_limit(120);
            
            $draw = $request->get('draw');
            $start = (int) $request->get('start', 0);
            $length = (int) $request->get('length', 10);
            $search = $request->get('search')['value'] ?? '';
            $roleFilter = $request->get('role');
            $statusFilter = $request->get('status');

            // Build query with proper joins for role filtering and creator info
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

            // Apply status filter
            if ($statusFilter && $statusFilter !== '') {
                $query->where('users.status', $statusFilter);
            }

            // Apply role filter
            if ($roleFilter && $roleFilter !== '') {
                $query->where('users.role_id', $roleFilter);
            }

            // Apply search filter
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.email', 'like', "%{$search}%")
                      ->orWhere('users.phone_number', 'like', "%{$search}%")
                      ->orWhere('roles.name', 'like', "%{$search}%");
                });
            }

            // Handle DataTables ordering
            if ($request->has('order')) {
                $orderColumn = $request->get('order')[0]['column'];
                $orderDirection = $request->get('order')[0]['dir'];
                
                // Map column index to actual column name
                $columns = [
                    0 => 'users.id',
                    1 => 'users.name', 
                    2 => 'users.email',
                    3 => 'users.phone_number',
                    4 => 'roles.name',
                    5 => 'users.status',
                    6 => 'users.created_at',
                    7 => 'creator.name', // created_by_name maps to creator.name
                    8 => 'users.created_at' // account_age is calculated from created_at
                ];
                
                if (isset($columns[$orderColumn])) {
                    $query->orderBy($columns[$orderColumn], $orderDirection);
                }
            } else {
                // Default ordering
                $query->orderBy('users.id', 'asc');
            }

            return DataTables::of($query)
                ->addColumn('created_by_name', function ($user) {
                    return $user->created_by_name ?? 'System';
                })
                ->addColumn('account_age', function ($user) {
                    if (!$user->created_at) return '0 days';
                    $createdAt = \Carbon\Carbon::parse($user->created_at);
                    $now = \Carbon\Carbon::now();
                    $days = floor($createdAt->diffInDays($now));
                    return $days . ' day' . ($days !== 1 ? 's' : '');
                })
                ->make(true);
                
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch users',
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 500);
        }
    }

    private function generateTableHtml($users)
    {
        $html = '<table class="data-table"><thead><tr>';
        $html .= '<th>ID</th><th>NAME</th><th>EMAIL</th><th>PHONE</th><th>ROLE</th>';
        $html .= '<th>STATUS</th><th>CREATED BY</th><th>LAST LOGIN</th><th>CREATED AT</th>';
        $html .= '<th>ACCOUNT AGE</th><th>ACTION</th></tr></thead><tbody>';

        foreach ($users as $user) {
            $html .= '<tr>';
            $html .= '<td data-label="ID">' . $user->id . '</td>';
            $html .= '<td data-label="NAME">' . $user->name . '</td>';
            $html .= '<td data-label="EMAIL">' . $user->email . '</td>';
            $html .= '<td data-label="PHONE">' . ($user->phone_number ?? '+63 900 000 0000') . '</td>';
            $html .= '<td data-label="ROLE">' . ($user->role->name ?? 'No Role') . '</td>';
            
            $status = $user->status ?? 'active';
            $html .= '<td data-label="STATUS"><span class="status-badge status-' . $status . '">' . ucfirst($status) . '</span></td>';
            
            $createdBy = $user->created_by ? (User::find($user->created_by)->name ?? 'Unknown') : '-';
            $html .= '<td data-label="CREATED BY">' . $createdBy . '</td>';
            $html .= '<td data-label="CREATED AT">' . $user->created_at->format('m/d/Y') . '</td>';
            $html .= '<td data-label="ACCOUNT AGE">' . $user->account_age . ' ' . Str::plural('day', $user->account_age) . '</td>';
            
            $html .= '<td data-label="ACTION">';
            $html .= '<div class="action-dropdown">';
            $html .= '<button class="action-dropdown-btn" onclick="toggleDropdown(' . $user->id . ', event)">';
            $html .= '<i class="fas fa-cog"></i> Actions <i class="fas fa-chevron-down"></i></button>';
            $html .= '<div id="dropdown-' . $user->id . '" class="action-dropdown-content" data-user-id="' . $user->id . '" data-user-status="' . $status . '">';
            $html .= '<a href="javascript:void(0)" onclick="editUser(' . $user->id . ')" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>';
            $html .= '<a href="javascript:void(0)" onclick="toggleUserStatus(' . $user->id . ', \'' . $status . '\')" class="dropdown-item">';
            $html .= '<i class="fas fa-' . ($status === 'active' ? 'ban' : 'check') . '"></i> ';
            $html .= ($status === 'active' ? 'Deactivate' : 'Activate') . '</a>';
            $html .= '<a href="javascript:void(0)" onclick="resetUserPassword(' . $user->id . ')" class="dropdown-item"><i class="fas fa-key"></i> Reset Password</a>';
            $html .= '<a href="javascript:void(0)" onclick="viewUserActivity(' . $user->id . ')" class="dropdown-item"><i class="fas fa-chart-line"></i> View Activity</a>';
            $html .= '<a href="javascript:void(0)" onclick="deleteUser(' . $user->id . ')" class="dropdown-item delete-btn"><i class="fas fa-trash"></i> Delete</a>';
            $html .= '</div>';
            $html .= '</div></td></tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

    private function generatePaginationHtml($currentPage, $totalPages, $totalUsers)
    {
        if ($totalPages <= 1) return '';

        $html = '<div class="pagination-controls">';
        
        // Pagination info (left side) - dynamic based on actual data
        $startEntry = ($currentPage - 1) * 5 + 1;
        $endEntry = min($currentPage * 5, $totalUsers);
        
        // Fix: Don't show entries if we're beyond the data
        if ($startEntry > $totalUsers) {
            $startEntry = $totalUsers;
            $endEntry = $totalUsers;
        }
        
        $html .= '<div class="pagination-info">Showing ' . $startEntry . ' to ' . $endEntry . ' of ' . $totalUsers . ' entries</div>';
        
        // Previous button
        if ($currentPage > 1) {
            $html .= '<button onclick="fetchUsersWithValidation(' . ($currentPage - 1) . ')" class="pagination-btn">Prev</button>';
        }
        
        // Page numbers
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($i == $currentPage) ? ' active' : '';
            $html .= '<button onclick="fetchUsersWithValidation(' . $i . ')" class="pagination-btn page-number' . $activeClass . '">' . $i . '</button>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $html .= '<button onclick="fetchUsersWithValidation(' . ($currentPage + 1) . ')" class="pagination-btn">Next</button>';
        }
        
        $html .= '</div>';
        return $html;
    }

    public function getUserData(Request $request)
    {
        try {
            $userId = $request->get('user_id');
            $user = User::with('role')->find($userId);
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
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
                    'role' => $user->role
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting user data:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to get user data']);
        }
    }

    public function updateUserAjax(Request $request)
    {
        $userId = $request->get('userId');
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'status' => 'in:active,inactive'
        ]);

        $user->update($request->only(['name', 'email', 'phone_number', 'role_id', 'status']));
        
        return response()->json(['success' => true]);
    }

    public function getRoles()
    {
        try {
            $roles = \App\Models\Role::all(['id', 'name']);
            
            
            return response()->json([
                'success' => true,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching roles:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load roles: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|min:6',
            'status' => 'in:active,inactive'
        ]);
        
        $userData = $request->all();
        $userData['password'] = bcrypt($request->password);
        $userData['created_by'] = auth()->id();
        
        User::create($userData);
        return redirect()->route('superadmin.users.index');
    }

    public function storeAjax(Request $request)
    {
        try {
            
            $isUpdate = $request->get('operation') === 'update';
            $userId = $request->get('id');
            
            // Different validation rules for create vs update
            if ($isUpdate) {
                $request->validate([
                    'name' => 'required|string|max:100',
                    'email' => 'required|email|unique:users,email,' . $userId,
                    'role_id' => 'required|exists:roles,id',
                    'password' => 'nullable|min:8', // Password is optional for updates
                    'status' => 'in:active,inactive'
                ]);
            } else {
                $request->validate([
                    'name' => 'required|string|max:100',
                    'email' => 'required|email|unique:users',
                    'role_id' => 'required|exists:roles,id',
                    'password' => 'required|min:8',
                    'status' => 'in:active,inactive'
                ]);
            }
            
            if ($isUpdate) {
                // Update existing user
                $user = User::findOrFail($userId);
                
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'role_id' => $request->role_id,
                    'status' => $request->status ?? 'active'
                ];
                
                // Only update password if provided
                if ($request->password) {
                    $userData['password'] = bcrypt($request->password);
                }
                
                
                // Get old values for comparison BEFORE updating
                $oldRole = $user->role->name ?? 'Unknown';
                $oldStatus = $user->status;
                $oldName = $user->name;
                $oldEmail = $user->email;
                $oldPhone = $user->phone_number;
                
                
                $user->update($userData);
                
                // Refresh the user model to get updated relationships
                $user->refresh();
                $user->load('role');
                
                // Get the new values
                $newRole = $user->role->name ?? 'Unknown';
                $newStatus = $user->status;
                $newName = $user->name;
                $newEmail = $user->email;
                $newPhone = $user->phone_number;
                
                
                // Log user update with organized change detection
                $changes = [];
                
                // Check name changes
                if ($oldName !== $newName) {
                    $changes[] = "• Name: <span class='text-slate-400'>'{$oldName}'</span> → <span class='text-green-400 font-semibold'>'{$newName}'</span>";
                }
                
                // Check email changes
                if ($oldEmail !== $newEmail) {
                    $changes[] = "• Email: <span class='text-slate-400'>'{$oldEmail}'</span> → <span class='text-green-400 font-semibold'>'{$newEmail}'</span>";
                }
                
                // Check phone changes
                if ($oldPhone !== $newPhone) {
                    $changes[] = "• Phone: <span class='text-slate-400'>'{$oldPhone}'</span> → <span class='text-green-400 font-semibold'>'{$newPhone}'</span>";
                }
                
                // Check role changes
                if ($oldRole !== $newRole) {
                    $changes[] = "• Role: <span class='text-slate-400'>'{$oldRole}'</span> → <span class='text-green-400 font-semibold'>'{$newRole}'</span>";
                }
                
                // Check status changes
                if ($oldStatus !== $newStatus) {
                    $changes[] = "• Status: <span class='text-slate-400'>'{$oldStatus}'</span> → <span class='text-green-400 font-semibold'>'{$newStatus}'</span>";
                }
                
                // Check if password was changed
                if (isset($userData['password'])) {
                    $changes[] = "• Password: <span class='text-green-400 font-semibold'>[Changed]</span>";
                }
                
                
                $changeDescription = !empty($changes) ? "\n" . implode("\n", $changes) : 'profile information';
                
                AuditLog::log(
                    'USER_UPDATED',
                    "Updated user '{$user->name}' — changed {$changeDescription}",
                    auth()->id(),
                    [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'old_name' => $oldName,
                        'new_name' => $newName,
                        'old_email' => $oldEmail,
                        'new_email' => $newEmail,
                        'old_phone' => $oldPhone,
                        'new_phone' => $newPhone,
                        'old_role' => $oldRole,
                        'new_role' => $newRole,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'password_changed' => isset($userData['password']),
                        'changes' => $userData
                    ]
                );
                
                $message = 'User updated successfully';
            } else {
                // Create new user
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone_number' => $request->phone_number,
                    'role_id' => $request->role_id,
                    'status' => $request->status ?? 'active',
                    'created_by' => auth()->id()
                ];
                
                
                $user = User::create($userData);
                
                // Log user creation with organized format
                $userDetails = [
                    "• Email: <span class='text-green-400 font-semibold'>'{$user->email}'</span>",
                    "• Phone: <span class='text-green-400 font-semibold'>'{$user->phone_number}'</span>",
                    "• Role: <span class='text-green-400 font-semibold'>'{$user->role->name}'</span>",
                    "• Status: <span class='text-green-400 font-semibold'>'{$user->status}'</span>"
                ];
                
                $userDetailsString = "\n" . implode("\n", $userDetails);
                
                AuditLog::log(
                    'USER_CREATED',
                    "Created new user '{$user->name}'{$userDetailsString}",
                    auth()->id(),
                    [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'role_id' => $user->role_id,
                        'role_name' => $user->role->name ?? 'Unknown',
                        'status' => $user->status,
                        'phone_number' => $user->phone_number
                    ]
                );
                
                $message = 'User created successfully';
            }
            
            // Get creator's name - use current authenticated user's name
            $currentUser = auth()->user();
            $creatorName = $currentUser ? $currentUser->name : 'System';
            
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'status' => $user->status,
                    'created_at' => $user->created_at->format('M d, Y'),
                    'created_by' => $user->created_by,
                    'created_by_name' => $creatorName
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating user:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'status' => 'in:active,inactive'
        ]);
        
        $user->update($request->all());
        return redirect()->route('superadmin.users.index');
    }

    public function updateAjax(Request $request, User $user)
    {
        try {
            
            $validationRules = [
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role_id' => 'required|exists:roles,id',
                'status' => 'in:active,inactive'
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $validationRules['password'] = 'min:8';
            }

            $request->validate($validationRules);
            
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role_id' => $request->role_id,
                'status' => $request->status ?? 'active'
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }
            
            
            $user->update($userData);
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'status' => $user->status,
                    'role_id' => $user->role_id,
                    'updated_at' => $user->updated_at->format('M d, Y')
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating user:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('superadmin.users.index');
    }

    public function deleteUserAjax(Request $request)
    {
        
        try {
            $userId = $request->get('user_id');
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Check if user is trying to delete themselves
            if ($user->id === auth()->id()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account'], 400);
            }

            // Check if user is the last superadmin
            if ($user->role->name === 'superadmin') {
                $superadminCount = User::whereHas('role', function($query) {
                    $query->where('name', 'superadmin');
                })->count();
                
                if ($superadminCount <= 1) {
                    return response()->json(['success' => false, 'message' => 'Cannot delete the last superadmin account'], 400);
                }
            }

            $userName = $user->name;
            $userEmail = $user->email;
            $userRole = $user->role->name ?? 'Unknown';
            
            // Log user deletion with organized format
            $deletedUserDetails = [
                "• Email: <span class='text-slate-400'>'{$userEmail}'</span>",
                "• Role: <span class='text-slate-400'>'{$userRole}'</span>",
                "• Status: <span class='text-slate-400'>'{$user->status}'</span>"
            ];
            
            $deletedUserDetailsString = "\n" . implode("\n", $deletedUserDetails);
            
            AuditLog::log(
                'USER_DELETED',
                "Deleted user '{$userName}'{$deletedUserDetailsString}",
                auth()->id(),
                [
                    'deleted_user_id' => $user->id,
                    'deleted_user_name' => $userName,
                    'deleted_user_email' => $userEmail,
                    'deleted_user_role' => $userRole,
                    'deletion_reason' => 'Admin deletion',
                    'deleted_at' => now()->toDateTimeString()
                ]
            );
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
                'deleted_user' => $userName
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting user:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleUserStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $oldStatus = $user->status;
            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);
            
            // Log status change
            AuditLog::log(
                'USER_STATUS_CHANGED',
                "Changed user '{$user->name}' status from '{$oldStatus}' to '{$newStatus}' — account access " . ($newStatus === 'active' ? 'enabled' : 'disabled'),
                auth()->id(),
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'action_type' => $newStatus === 'active' ? 'account_enabled' : 'account_disabled'
                ]
            );
            
            return response()->json([
                'success' => true, 
                'message' => 'User status updated successfully',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            \Log::error('Error toggling user status:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function resetUserPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            
            // Generate new password
            $newPassword = \Illuminate\Support\Str::random(10);
            $user->update(['password' => bcrypt($newPassword)]);
            
            // For now, just return the password in response
            return response()->json([
                'success' => true, 
                'message' => 'Password reset successfully',
                'new_password' => $newPassword
            ]);
        } catch (\Exception $e) {
            \Log::error('Error resetting user password:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getUserActivity(Request $request)
    {
        
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $user = User::with(['role', 'orders'])->findOrFail($request->user_id);
            
            $activityData = [
                'created_at' => $user->created_at->format('M d, Y'),
                'assigned_orders' => []
            ];

            // Get user's order summary if they're a customer
            if ($user->role->name === 'customer') {
                try {
                    $orderStats = $user->orders()
                        ->selectRaw('COUNT(*) as total_orders, SUM(total_price) as total_spent, AVG(total_price) as avg_order_value')
                        ->first();
                    
                    $activityData['total_orders'] = $orderStats->total_orders ?? 0;
                    $activityData['total_spent'] = $orderStats->total_spent ? number_format($orderStats->total_spent, 2) : '0.00';
                    $activityData['avg_order_value'] = $orderStats->avg_order_value ? number_format($orderStats->avg_order_value, 2) : '0.00';
                    
                    // Get recent order status
                    $recentOrder = $user->orders()
                        ->select('status', 'created_at')
                        ->orderBy('created_at', 'desc')
                        ->first();
                    
                    $activityData['last_order_status'] = $recentOrder ? ucfirst(str_replace('_', ' ', $recentOrder->status)) : 'No orders';
                    $activityData['last_order_date'] = $recentOrder ? \Carbon\Carbon::parse($recentOrder->created_at)->format('M d, Y') : 'N/A';
                    
                    // Get detailed order history for display
                    $activityData['orders'] = $user->orders()
                        ->select('id', 'status', 'dropoff_date', 'pickup_date', 'total_price', 'created_at')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get()
                        ->map(function($order) {
                            return [
                                'id' => $order->id,
                                'status' => ucfirst(str_replace('_', ' ', $order->status)),
                                'dropoff_date' => \Carbon\Carbon::parse($order->dropoff_date)->format('M d, Y'),
                                'pickup_date' => \Carbon\Carbon::parse($order->pickup_date)->format('M d, Y'),
                                'total_price' => $order->total_price ? number_format($order->total_price, 2) : '0.00'
                            ];
                        });
                    
                } catch (\Exception $e) {
                    \Log::error('Error fetching customer order stats:', ['error' => $e->getMessage()]);
                    $activityData['total_orders'] = 0;
                    $activityData['total_spent'] = '0.00';
                    $activityData['avg_order_value'] = '0.00';
                    $activityData['last_order_status'] = 'No orders';
                    $activityData['last_order_date'] = 'N/A';
                    $activityData['orders'] = [];
                }
            }

            // Get orders assigned to user if they're staff
            if ($user->role->name === 'staff') {
                try {
                    $activityData['assigned_orders'] = \App\Models\Order::where('staff_id', $user->id)
                        ->with('customer:id,name')
                        ->select('id', 'status', 'customer_id')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get()
                        ->map(function($order) {
                            return [
                                'id' => $order->id,
                                'status' => ucfirst(str_replace('_', ' ', $order->status)),
                                'customer_name' => $order->customer->name ?? 'Unknown'
                            ];
                        });
                } catch (\Exception $e) {
                    \Log::error('Error fetching staff assigned orders:', ['error' => $e->getMessage()]);
                    $activityData['assigned_orders'] = [];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $activityData,
                'role' => $user->role->name
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting user activity:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of users for Admin role (staff and customers only)
     */
    public function adminUsers()
    {
        // Load roles for the form and filter dropdowns with caching
        $roles = cache()->remember('admin_roles_list', 300, function() {
            return Role::whereIn('name', ['staff', 'customer'])->orderBy('name')->get();
        });
        
        // Load users for the reusable data table component (staff and customers only)
        $users = User::with(['role', 'createdBy'])
            ->whereHas('role', function($query) {
                $query->whereIn('name', ['staff', 'customer']);
            })
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'users.status',
                'users.created_at',
                'users.role_id',
                'users.created_by'
            ])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number ?? 'N/A',
                    'role' => $user->role ? ucfirst($user->role->name) : 'No Role',
                    'status' => ucfirst($user->status ?? 'Active'),
                    'created_at' => $user->created_at ? $user->created_at->format('M d, Y') : 'N/A',
                    'created_by' => $user->created_by,
                    'created_by_name' => $user->createdBy ? $user->createdBy->name : 'System',
                    'account_age' => $user->created_at ? floor($user->created_at->diffInDays(now())) . ' days' : 'N/A'
                ];
            })->toArray(); // Convert to array for the data table

        // If no users found, create some sample data for testing
        if (empty($users)) {
            $users = [
                [
                    'id' => 1,
                    'name' => 'Maria Santos',
                    'email' => 'maria.santos@example.com',
                    'phone_number' => '+63 912 345 6789',
                    'role' => 'Staff',
                    'status' => 'Active',
                    'created_at' => '2024-01-15',
                    'created_by' => 1,
                    'created_by_name' => 'System',
                    'account_age' => '45 days'
                ],
                [
                    'id' => 2,
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan.delacruz@example.com',
                    'phone_number' => '+63 917 123 4567',
                    'role' => 'Customer',
                    'status' => 'Active',
                    'created_at' => '2024-01-20',
                    'created_by' => 1,
                    'created_by_name' => 'System',
                    'account_age' => '40 days'
                ],
                [
                    'id' => 3,
                    'name' => 'Ana Garcia',
                    'email' => 'ana.garcia@example.com',
                    'phone_number' => '+63 918 765 4321',
                    'role' => 'Staff',
                    'status' => 'Active',
                    'created_at' => '2024-01-25',
                    'created_by' => 1,
                    'created_by_name' => 'System',
                    'account_age' => '35 days'
                ]
            ];
        }

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'name', 'label' => 'Name', 'sortable' => true, 'searchable' => true],
            ['key' => 'email', 'label' => 'Email', 'sortable' => true, 'searchable' => true],
            ['key' => 'phone_number', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'role', 'label' => 'Role', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Joined', 'sortable' => true],
            ['key' => 'account_age', 'label' => 'Account Age', 'sortable' => true]
        ];

        // Define actions for the data table
        $actions = [
            ['key' => 'viewUser', 'label' => 'View', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'editUser', 'label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow'],
            ['key' => 'viewUserActivity', 'label' => 'View Activity', 'icon' => 'settings', 'color' => 'green'],
            ['key' => 'deactivateUser', 'label' => 'Toggle Status', 'icon' => 'toggle', 'color' => 'orange']
        ];

        $description = 'Manage staff and customer accounts, view their activity, and handle user support';

        return view('admin.users.index', compact('columns', 'actions', 'description', 'roles') + ['data' => $users]);
    }

    /**
     * Log user login activity
     */
    public function logUserLogin($userId)
    {
        $user = User::find($userId);
        if ($user) {
            AuditLog::log(
                'USER_LOGIN',
                "User '{$user->name}' logged in successfully",
                $userId,
                [
                    'user_id' => $userId,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'login_time' => now()->toDateTimeString()
                ]
            );
        }
    }

}
