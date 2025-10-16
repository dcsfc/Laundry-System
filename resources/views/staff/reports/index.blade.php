@extends('layouts.sidebar')

@section('title', 'Weekly Reports - Staff')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
<!-- Weekly Reports using Reusable Data Table -->
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
    :empty-description="'Generate your first weekly report to get started'"
    :hover-effects="true"
    :alternating-rows="true"
    :sticky-header="true"
    :custom-class="'bg-slate-800 text-slate-200'"
    :title="'Weekly Reports'"
    :description="'View and analyze your weekly performance reports'"
    :add-button="true"
    :add-button-label="'Generate Report'"
    :add-button-action="'generateReport'"
    form-type="report"
    color-scheme="sky"
/>

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
                exportReport(row);
                break;
        }
    });
});

// Report Management Functions
function viewReport(row) {
    console.log('View report:', row);
    
    // Fetch detailed report information
    fetch(`/staff/reports/${row.id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const report = data.report;
                let reportDetails = `Weekly Report Details:\n\n` +
                      `Week Period: ${report.week_period}\n` +
                      `Total Orders: ${report.total_orders}\n` +
                      `Completed Orders: ${report.completed_orders}\n` +
                      `Total Revenue: ₱${report.total_revenue}\n\n`;
                
                if (report.orders && report.orders.length > 0) {
                    reportDetails += `Recent Orders:\n`;
                    report.orders.slice(0, 5).forEach(order => {
                        reportDetails += `• Order #${order.id} - ${order.customer_name} - ₱${order.total_price}\n`;
                    });
                    if (report.orders.length > 5) {
                        reportDetails += `... and ${report.orders.length - 5} more orders\n`;
                    }
                }
                
                alert(reportDetails);
            }
        })
        .catch(error => {
            console.error('Error fetching report details:', error);
            alert('Error loading report details');
        });
}

function exportReport(row) {
    console.log('Export report:', row);
    
    fetch(`/staff/reports/${row.id}/export`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Report export for ${data.week_period} would be downloaded here.\n\nNote: Export functionality needs to be implemented.`);
            } else {
                alert('Error exporting report');
            }
        })
        .catch(error => {
            console.error('Error exporting report:', error);
            alert('Error exporting report');
        });
}

function generateReport() {
    console.log('Generate new report');
    window.location.href = '/staff/reports/create';
}
</script>
@endpush
@endsection
