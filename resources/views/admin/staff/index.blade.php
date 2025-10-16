@extends('layouts.sidebar')

@section('title', 'Staff Management - Administrator')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
    <div class="container">
        <!-- Staff Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :items="$staff"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :empty-message="'No staff members found'"
            :empty-description="'Add your first staff member to get started'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Staff Management'"
            :description="$description"
            :add-button="false"
            :show-role-filter="false"
            color-scheme="sky"
        />
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle staff-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewStaff(row);
                break;
            case 'edit':
                editStaff(row);
                break;
            case 'toggle_status':
                toggleStaffStatus(row);
                break;
            case 'delete':
                deleteStaff(row);
                break;
        }
    });
});

// Staff Management Functions
function viewStaff(row) {
    console.log('View staff:', row);
    alert('View staff: ' + row.id + ' - ' + row.name);
}

function editStaff(row) {
    console.log('Edit staff:', row);
    alert('Edit staff: ' + row.id + ' - ' + row.name);
}

function toggleStaffStatus(row) {
    console.log('Toggle status for staff:', row);
    const newStatus = row.status === 'Active' ? 'Inactive' : 'Active';
    if (confirm(`Are you sure you want to ${newStatus.toLowerCase()} staff member ${row.id}?`)) {
        alert(`Staff ${row.id} status changed to ${newStatus}`);
    }
}

function deleteStaff(row) {
    console.log('Delete staff:', row);
    if (confirm('Are you sure you want to delete staff member ' + row.id + '? This action cannot be undone.')) {
        alert('Staff ' + row.id + ' deleted');
    }
}
</script>
@endpush
@endsection

