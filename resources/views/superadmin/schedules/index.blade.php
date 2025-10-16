@extends('layouts.sidebar')

@section('title', 'Schedules Management - Super Admin')

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
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Schedules'"
            :add-button="true"
            :add-button-label="'Add New Schedule'"
            :add-button-action="'addSchedule'"
        />
    </div>

    <script>
        // Schedules Management Functions for Data Table Component
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

        function addSchedule() {
            console.log('Add new schedule');
            alert('Add new schedule form would open here');
        }
    </script>
@endsection