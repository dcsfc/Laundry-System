@extends('layouts.sidebar')

@section('title', 'Reports & Analytics - Super Admin')

@section('content')
    <div class="container">
        <!-- Reports Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :items="$reports"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No reports found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Reports & Analytics'"
            :add-button="true"
            :add-button-label="'Generate Report'"
            :add-button-action="'generateReport'"
        />
    </div>

    <script>
        // Reports Management Functions for Data Table Component
        function viewReport(row) {
            console.log('View report:', row);
            alert('View report: ' + row.id + ' - ' + row.report_name);
        }

        function downloadReport(row) {
            console.log('Download report:', row);
            alert('Download report: ' + row.id + ' - ' + row.report_name);
        }

        function editReport(row) {
            console.log('Edit report:', row);
            alert('Edit report: ' + row.id + ' - ' + row.report_name);
        }

        function deleteReport(row) {
            console.log('Delete report:', row);
            if (confirm('Are you sure you want to delete report ' + row.id + '? This action cannot be undone.')) {
                alert('Report ' + row.id + ' deleted');
            }
        }

        function generateReport() {
            console.log('Generate new report');
            alert('Generate new report form would open here');
        }
    </script>
@endsection