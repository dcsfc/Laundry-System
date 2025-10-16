@extends('layouts.sidebar')

@section('title', 'Reports Management - Administrator')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

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
            :empty-message="'No reports found'"
            :empty-description="'Generate your first report to get started'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Reports Management'"
            :description="'View and analyze performance reports'"
            :add-button="true"
            :add-button-label="'Generate Report'"
            :add-button-action="'addReport'"
            :show-role-filter="false"
            color-scheme="sky"
        />
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle report-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewReport(row);
                break;
            case 'export':
                downloadReport(row);
                break;
        }
    });
});

// Reports Management Functions
function viewReport(row) {
    console.log('View report:', row);
    alert('View report: ' + row.id + ' - ' + row.report_name);
}

function downloadReport(row) {
    console.log('Download report:', row);
    alert('Download report: ' + row.id + ' - ' + row.report_name);
}

function addReport() {
    console.log('Generate new report');
    alert('Generate new report form would open here');
}
</script>
@endpush
@endsection

