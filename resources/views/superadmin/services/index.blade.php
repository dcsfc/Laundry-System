@extends('layouts.sidebar')

@section('title', 'Service Management - Super Admin')

@section('content')
    <div class="container">
        <!-- Service Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :data="$services"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No services found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Service Management'"
            :add-button="true"
            :add-button-label="'Add New Service'"
            :add-button-action="'addService'"
            formType="service"
            colorScheme="sky"
        />
    </div>

    <script>
        // Service Management Functions for Data Table Component
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
@endsection