@extends('layouts.sidebar')

@section('title', 'User Management')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/table/table-data-fetcher.js', 'resources/js/modules/notifications/modern-notifications.js', 'resources/js/modules/notifications/notification-demo.js'])
<script>
    // ActionMenuManager is now loaded from action-menu.js
    
    // Pass roles to window object for modal component
    window.passedRoles = @json($roles);
    
    // View modal will auto-initialize via Alpine.js x-data
    
    // Function to open add user modal
    function openAddUserModal() {
        // Find the modal component and open it
        const modal = document.querySelector('[x-data*="userModal"]');
        
        if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
            modal._x_dataStack[0].openCreateModal();
        } else {
            // Fallback: dispatch event
            window.dispatchEvent(new CustomEvent('open-user-modal', { 
                detail: { action: 'create' } 
            }));
        }
    }


    // Global function to open delete confirmation modal
    window.openDeleteConfirmation = function(item, options = {}) {
        // Find the modal component
        const modalElement = document.querySelector('[x-data*="deleteConfirmationModal"]');
        if (modalElement && modalElement._x_dataStack && modalElement._x_dataStack[0]) {
            const modal = modalElement._x_dataStack[0];
            modal.openModal(item, options);
        } else {
            console.error('Delete confirmation modal not found');
        }
    };
</script>
@endpush

@section('content')

<div class="table-container" 
     x-data="usersTable(@js($users), 10)"
     x-init="init()">

    <!-- Header -->
    <div class="table-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="header-text">
                    <h2 class="table-title">User Management</h2>
                    <p class="table-description">Manage system users, roles, and permissions</p>
                </div>
            </div>

            <div class="header-actions">
                <button class="btn btn-primary" onclick="openAddUserModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New User
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    @include('components.tables.search-filters')

    <!-- Table -->
    @include('components.tables.table', [
        'columns' => $columns,
        'actions' => $actions,
        'sortable' => true,
        'emptyMessage' => 'No users found',
        'emptyDescription' => 'Start by adding your first user to the system.'
    ])

    <!-- Pagination -->
    @include('components.tables.pagination', ['itemName' => 'users'])

    <!-- User Modal -->
    @include('components.user-modal', ['roles' => $roles])

    <!-- User View Modal -->
    @include('components.user-view-modal')

    <!-- Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</div>
@endsection
