@extends('layouts.sidebar')

@section('title', 'Inventory Management - Staff')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
<!-- Inventory Management using Reusable Data Table -->
<x-data-table
    :columns="$columns"
    :data="$inventory"
    :actions="$actions"
    :bulk-actions="false"
    :searchable="true"
    :sortable="true"
    :pagination="true"
    :page-size="10"
    :empty-message="'No inventory items found'"
    :empty-description="'Add your first inventory item to get started'"
    :hover-effects="true"
    :alternating-rows="true"
    :sticky-header="true"
    :custom-class="'bg-slate-800 text-slate-200'"
    :title="'Inventory Management'"
    :description="'Manage supplies, track stock levels, and monitor thresholds'"
    :add-button="true"
    :add-button-label="'Add New Item'"
    :add-button-action="'addInventoryItem'"
    form-type="inventory"
    color-scheme="sky"
/>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle inventory-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewInventoryItem(row);
                break;
            case 'edit':
                editInventoryItem(row);
                break;
            case 'update_stock':
                updateStock(row);
                break;
            case 'delete':
                deleteInventoryItem(row);
                break;
        }
    });
});

// Inventory Management Functions
function viewInventoryItem(row) {
    console.log('View inventory item:', row);
    
    // Fetch detailed item information
    fetch(`/staff/inventory/${row.id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.item;
                alert(`Inventory Item Details:\n\n` +
                      `Name: ${item.item_name}\n` +
                      `Quantity: ${item.quantity} ${item.unit}\n` +
                      `Threshold: ${item.threshold}\n` +
                      `Created: ${item.created_at}\n` +
                      `Updated: ${item.updated_at}`);
            }
        })
        .catch(error => {
            console.error('Error fetching item details:', error);
            alert('Error loading item details');
        });
}

function editInventoryItem(row) {
    console.log('Edit inventory item:', row);
    window.location.href = `/staff/inventory/${row.id}/edit`;
}

function updateStock(row) {
    console.log('Update stock for item:', row);
    const newQuantity = prompt(`Enter new quantity for ${row.item_name}:`, row.quantity);
    
    if (newQuantity !== null && newQuantity !== '') {
        fetch(`/staff/inventory/${row.id}/update-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: parseInt(newQuantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Stock updated successfully for ${row.item_name}: ${row.quantity} → ${newQuantity}`);
                // Refresh the table
                location.reload();
            } else {
                alert('Error updating stock');
            }
        })
        .catch(error => {
            console.error('Error updating stock:', error);
            alert('Error updating stock');
        });
    }
}

function deleteInventoryItem(row) {
    console.log('Delete inventory item:', row);
    
    if (confirm(`Are you sure you want to delete "${row.item_name}"? This action cannot be undone.`)) {
        fetch(`/staff/inventory/${row.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Inventory item "${row.item_name}" deleted successfully`);
                // Refresh the table
                location.reload();
            } else {
                alert('Error deleting inventory item');
            }
        })
        .catch(error => {
            console.error('Error deleting item:', error);
            alert('Error deleting inventory item');
        });
    }
}

function addInventoryItem() {
    console.log('Add new inventory item');
    window.location.href = '/staff/inventory/create';
}
</script>
@endpush
@endsection
