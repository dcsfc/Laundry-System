@extends('layouts.sidebar')

@section('title', 'Orders & Transactions - Super Admin')

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
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Orders & Transactions'"
            :add-button="true"
            :add-button-label="'Add New Order'"
            :add-button-action="'addOrder'"
            formType="order"
            colorScheme="blue"
        />
    </div>

    <script>
        // Orders Management Functions for Data Table Component
        function viewOrder(row) {
            console.log('View order:', row);
            alert('View order: ' + row.id + ' - ' + row.customer_name);
        }

        function editOrder(row) {
            console.log('Edit order:', row);
            alert('Edit order: ' + row.id + ' - ' + row.customer_name);
        }

        function updateOrderStatus(row) {
            console.log('Update status for order:', row);
            const newStatus = row.status === 'Completed' ? 'In Progress' : 'Completed';
            if (confirm(`Are you sure you want to update order ${row.id} status to ${newStatus}?`)) {
                alert(`Order ${row.id} status updated to ${newStatus}`);
            }
        }

        function deleteOrder(row) {
            console.log('Delete order:', row);
            if (confirm('Are you sure you want to delete order ' + row.id + '? This action cannot be undone.')) {
                alert('Order ' + row.id + ' deleted');
            }
        }

        function addOrder() {
            console.log('Add new order');
            alert('Add new order form would open here');
        }
    </script>
@endsection