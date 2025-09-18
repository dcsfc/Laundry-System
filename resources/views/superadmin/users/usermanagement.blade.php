@extends('layouts.sidebar')

@section('title', 'User Management - Super Admin')

@section('content')
    <div class="container">
        <!-- User Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :data="$users"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No users found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'User Management'"
            :description="'Manage system users, roles, and permissions for all staff and customers'"
            :add-button="true"
            :add-button-label="'Add New User'"
            :add-button-action="'addUser'"
            :show-role-filter="true"
            :available-roles="$roles"
            formType="user"
            colorScheme="indigo"
        />
    </div>

    <script>
        // User Management Functions for Data Table Component
        function viewUser(row) {
            console.log('View user:', row);
            alert('View user: ' + row.id + ' - ' + row.name);
        }

        function editUser(row) {
            console.log('Edit user:', row);
            alert('Edit user: ' + row.id + ' - ' + row.name);
        }

        function toggleUserStatus(row) {
            console.log('Toggle status for user:', row);
            const newStatus = row.status === 'Active' ? 'Inactive' : 'Active';
            if (confirm(`Are you sure you want to ${newStatus.toLowerCase()} user ${row.id}?`)) {
                alert(`User ${row.id} status changed to ${newStatus}`);
            }
        }

        function deleteUser(row) {
            console.log('Delete user:', row);
            if (confirm('Are you sure you want to delete user ' + row.id + '? This action cannot be undone.')) {
                alert('User ' + row.id + ' deleted');
            }
        }

        function addUser() {
            console.log('Add new user');
            alert('Add new user form would open here');
        }
    </script>
@endsection