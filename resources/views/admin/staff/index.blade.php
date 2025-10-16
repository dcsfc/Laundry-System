@extends('layouts.sidebar')

@section('title', 'Staff Management - Administrator')

@section('content')
    <div class="container">
        <!-- Staff Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :data="$staff"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No staff members found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Staff Management'"
            :add-button="false"
            formType="staff"
            colorScheme="blue"
        />
    </div>

    <script>
        // Staff Management Functions for Data Table Component
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
    </script>
@endsection

