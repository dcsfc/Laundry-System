@extends('layouts.sidebar')

@section('title', 'Service Management - Administrator')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
    <div class="container">
        <!-- Service Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :items="$services"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :empty-message="'No services found'"
            :empty-description="'Add your first service to get started'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Service Management'"
            :description="$description"
            :add-button="true"
            :add-button-label="'Add New Service'"
            :add-button-action="'addService'"
            :show-role-filter="false"
            color-scheme="sky"
        />
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle service-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewService(row);
                break;
            case 'edit':
                editService(row);
                break;
            case 'toggle_status':
                toggleServiceStatus(row);
                break;
            case 'delete':
                deleteService(row);
                break;
        }
    });
});

// Service Management Functions
function viewService(row) {
    console.log('View service:', row);
    alert('View service: ' + row.id + ' - ' + row.name);
}

function editService(row) {
    console.log('Edit service:', row);
    alert('Edit service: ' + row.id + ' - ' + row.name);
}

function toggleServiceStatus(row) {
    console.log('Toggle status for service:', row);
    const newStatus = row.status === 'Active' ? 'Inactive' : 'Active';
    if (confirm(`Are you sure you want to ${newStatus.toLowerCase()} service ${row.id}?`)) {
        alert(`Service ${row.id} status changed to ${newStatus}`);
    }
}

function deleteService(row) {
    console.log('Delete service:', row);
    if (confirm('Are you sure you want to delete service ' + row.id + '? This action cannot be undone.')) {
        alert('Service ' + row.id + ' deleted');
    }
}

function addService() {
    console.log('Add new service');
    alert('Add new service form would open here');
}
</script>
@endpush
@endsection

