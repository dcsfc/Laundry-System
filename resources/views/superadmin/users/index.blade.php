@extends('layouts.sidebar')

@section('title', 'User Management - Super Admin')

@section('content')
<div class="container-fluid">
    
    @php
        $tableData = $data;
    @endphp
    
        
        <x-data-table 
            :columns="$columns"
            :tableData="$tableData"
            :actions="$actions"
            title="User Management"
            description="Manage system users and their permissions"
            :searchable="true"
            :sortable="true" 
            :pagination="true"
            :pageSize="10"
            :showRoleFilter="true"
            :availableRoles="['admin', 'staff', 'customer']"
        />
</div>

<script>
// Handle data table events
document.addEventListener('datatable:action', function(event) {
    const { row, action } = event.detail;
    console.log('Action:', action, 'Row:', row);
    
    switch(action) {
        case 'viewUser':
            alert('View user: ' + row.name + '\nEmail: ' + row.email + '\nRole: ' + row.role);
            break;
        case 'editUser':
            alert('Edit user: ' + row.name);
            // You can open a modal or redirect to edit page here
            break;
        case 'toggleUserStatus':
            const newStatus = row.status === 'Active' ? 'Inactive' : 'Active';
            if (confirm('Toggle status for ' + row.name + ' to ' + newStatus + '?')) {
                // Make AJAX call to toggle status
                fetch('/superadmin/users/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: row.id,
                        status: newStatus.toLowerCase()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated successfully!');
                        location.reload(); // Refresh the page
                    } else {
                        alert('Error updating status: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating status');
                });
            }
            break;
        case 'deleteUser':
            if (confirm('Are you sure you want to delete ' + row.name + '? This action cannot be undone.')) {
                // Make AJAX call to delete user
                fetch('/superadmin/users/' + row.id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully!');
                        location.reload(); // Refresh the page
                    } else {
                        alert('Error deleting user: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting user');
                });
            }
            break;
    }
});

document.addEventListener('datatable:add', function(event) {
    alert('Add new user clicked!');
    // You can open a modal or redirect to create page here
    // window.location.href = '/superadmin/users/create';
});
</script>
@endsection