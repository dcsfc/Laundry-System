@extends('layouts.sidebar')

@section('title', 'Schedules Management - Administrator')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
    <div class="container">
        <!-- Schedules Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :items="$schedules"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No schedules found'"
            :empty-description="'No customer schedules have been created yet'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Schedule Management'"
            :description="'Manage all customer schedules and track their progress'"
            :add-button="false"
            :show-role-filter="false"
            color-scheme="sky"
        />
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle schedule-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewSchedule(row);
                break;
            case 'edit':
                editSchedule(row);
                break;
            case 'update_status':
                updateScheduleStatus(row);
                break;
            case 'delete':
                deleteSchedule(row);
                break;
        }
    });
});

// Schedules Management Functions
function viewSchedule(row) {
    console.log('View schedule:', row);
    alert('View schedule: ' + row.id + ' - ' + row.customer_name);
}

function editSchedule(row) {
    console.log('Edit schedule:', row);
    alert('Edit schedule: ' + row.id + ' - ' + row.customer_name);
}

function updateScheduleStatus(row) {
    console.log('Update status for schedule:', row);
    const newStatus = row.status === 'Confirmed' ? 'Pending' : 'Confirmed';
    if (confirm(`Are you sure you want to update schedule ${row.id} status to ${newStatus}?`)) {
        alert(`Schedule ${row.id} status updated to ${newStatus}`);
    }
}

function deleteSchedule(row) {
    console.log('Delete schedule:', row);
    if (confirm('Are you sure you want to delete schedule ' + row.id + '? This action cannot be undone.')) {
        alert('Schedule ' + row.id + ' deleted');
    }
}
</script>
@endpush
@endsection

