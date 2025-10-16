@extends('layouts.sidebar')

@section('title', 'Orders Management - Administrator')

@section('content')
    <div class="container">
        <!-- Orders Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :data="$orders"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No orders found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Orders Management'"
            :add-button="false"
            formType="order"
            colorScheme="indigo"
        />
    </div>

    <script>
        // Orders Management Functions for Data Table Component
        function viewOrder(row) {
            console.log('View order:', row);
            alert('View order: ' + row.id + ' - Customer: ' + row.customer_name);
        }

        function updateOrderStatus(row) {
            console.log('Update status for order:', row);
            const newStatus = prompt('Enter new status (scheduled/priced/in_progress/completed):', row.status);
            if (newStatus !== null) {
                alert(`Order ${row.id} status updated to ${newStatus}`);
            }
        }
    </script>
@endsection

