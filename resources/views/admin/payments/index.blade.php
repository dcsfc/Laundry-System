@extends('layouts.sidebar')

@section('title', 'Payments Management - Administrator')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
    <div class="container">
        <!-- Payments Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :items="$payments"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No payments found'"
            :empty-description="'Record your first payment to get started'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'Payment Management'"
            :description="$description"
            :add-button="true"
            :add-button-label="'Record Payment'"
            :add-button-action="'addPayment'"
            :show-role-filter="false"
            color-scheme="sky"
        />
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle payment-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewPayment(row);
                break;
            case 'edit':
                editPayment(row);
                break;
            case 'delete':
                deletePayment(row);
                break;
        }
    });
});

// Payment Management Functions
function viewPayment(row) {
    console.log('View payment:', row);
    alert('View payment: ' + row.id + ' - Order #' + row.order_id);
}

function editPayment(row) {
    console.log('Edit payment:', row);
    alert('Edit payment: ' + row.id + ' - Order #' + row.order_id);
}

function deletePayment(row) {
    console.log('Delete payment:', row);
    if (confirm('Are you sure you want to delete payment ' + row.id + '? This action cannot be undone.')) {
        alert('Payment ' + row.id + ' deleted');
    }
}

function addPayment() {
    console.log('Record new payment');
    alert('Record new payment form would open here');
}
</script>
@endpush
@endsection

