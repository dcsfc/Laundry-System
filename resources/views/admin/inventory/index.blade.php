@extends('layouts.sidebar')

@section('title', 'Inventory Management - Administrator')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
        <div class="container">
        <!-- Inventory Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :items="$inventory"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No inventory items found'"
            :empty-description="'Add your first inventory item to get started'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Inventory Management'"
            :description="$description"
            :add-button="true"
            :add-button-label="'Add New Item'"
            :add-button-action="'addInventoryItem'"
            :show-role-filter="false"
            color-scheme="sky"
        />
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle inventory-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewItem(row);
                break;
            case 'edit':
                editItem(row);
                break;
            case 'update_stock':
                updateStock(row);
                break;
            case 'delete':
                deleteItem(row);
                break;
        }
    });
});

// Inventory Management Functions
function viewItem(row) {
    console.log('View item:', row);
    alert('View item: ' + row.id + ' - ' + row.item_name);
}

function editItem(row) {
    console.log('Edit item:', row);
    alert('Edit item: ' + row.id + ' - ' + row.item_name);
}

function updateStock(row) {
    console.log('Update stock for item:', row);
    const newQuantity = prompt(`Enter new quantity for ${row.item_name}:`, row.quantity);
    if (newQuantity !== null) {
        alert(`Stock updated for ${row.item_name}: ${row.quantity} → ${newQuantity}`);
    }
}

function deleteItem(row) {
    console.log('Delete item:', row);
    if (confirm('Are you sure you want to delete item ' + row.id + '? This action cannot be undone.')) {
        alert('Item ' + row.id + ' deleted');
    }
}

function addInventoryItem() {
    console.log('Add new inventory item');
    alert('Add new inventory item form would open here');
}
</script>
@endpush
@endsection

