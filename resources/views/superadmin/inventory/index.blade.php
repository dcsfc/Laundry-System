@extends('layouts.sidebar')

@section('title', 'Inventory Management - Super Admin')

@section('content')
        <div class="container">
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
            :current-page="1"
            :empty-message="'No inventory items found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Inventory Management'"
            :description="$description"
            :add-button="true"
            :add-button-label="'Add New Item'"
            :add-button-action="'addInventoryItem'"
            formType="inventory"
            colorScheme="emerald"
        />
    </div>

    <script>
        // Inventory Management Functions for Data Table Component
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
@endsection