@extends('layouts.sidebar')

@section('title', 'Orders Management - Administrator')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
    <div class="container">
        <!-- Orders Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :items="$orders"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :empty-message="'No orders found'"
            :empty-description="'No customer orders have been created yet'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Orders Management'"
            :description="'Manage customer orders and track their progress'"
            :add-button="false"
            :show-role-filter="false"
            color-scheme="sky"
        />
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle order-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewOrder(row);
                break;
            case 'edit':
                editOrder(row);
                break;
        }
    });
});

// Orders Management Functions
function viewOrder(row) {
    console.log('View order:', row);
    alert('View order: ' + row.id + ' - Customer: ' + row.customer_name);
}

function editOrder(row) {
    console.log('Edit order:', row);
    alert('Edit order: ' + row.id + ' - Customer: ' + row.customer_name);
}
</script>
@endpush
@endsection

