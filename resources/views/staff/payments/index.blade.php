@extends('layouts.sidebar')

@section('title', 'Payment Management - Staff')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js'])
@endpush

@section('content')
<!-- Payment Management using Reusable Data Table -->
<x-data-table
    :columns="$columns"
    :items="$payments"
    :actions="$actions"
    :bulk-actions="false"
    :searchable="true"
    :sortable="true"
    :pagination="true"
    :page-size="10"
    :empty-message="'No payments found'"
    :empty-description="'Record your first payment to get started'"
    :hover-effects="true"
    :alternating-rows="true"
    :sticky-header="true"
    :custom-class="'bg-slate-800 text-slate-200'"
    :title="'Payment Management'"
    :description="'Manage payment records and transactions'"
    :add-button="true"
    :add-button-label="'Record Payment'"
    :add-button-action="'recordPayment'"
    form-type="payment"
    color-scheme="sky"
/>

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
    
    // Fetch detailed payment information
    fetch(`/staff/payments/${row.id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const payment = data.payment;
                alert(`Payment Details:\n\n` +
                      `Payment ID: ${payment.id}\n` +
                      `Order ID: ${payment.order_id}\n` +
                      `Customer: ${payment.customer_name}\n` +
                      `Amount: ₱${payment.amount}\n` +
                      `Method: ${payment.payment_method}\n` +
                      `Status: ${payment.payment_status}\n` +
                      `Reference: ${payment.reference_number}\n` +
                      `Recorded By: ${payment.recorded_by}\n` +
                      `Paid At: ${payment.paid_at}\n` +
                      `Created: ${payment.created_at}`);
            }
        })
        .catch(error => {
            console.error('Error fetching payment details:', error);
            alert('Error loading payment details');
        });
}

function editPayment(row) {
    console.log('Edit payment:', row);
    window.location.href = `/staff/payments/${row.id}/edit`;
}

function deletePayment(row) {
    console.log('Delete payment:', row);
    
    if (confirm(`Are you sure you want to delete payment #${row.id}? This action cannot be undone.`)) {
        fetch(`/staff/payments/${row.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Payment #${row.id} deleted successfully`);
                // Refresh the table
                location.reload();
            } else {
                alert('Error deleting payment');
            }
        })
        .catch(error => {
            console.error('Error deleting payment:', error);
            alert('Error deleting payment');
        });
    }
}

function recordPayment() {
    console.log('Record new payment');
    window.location.href = '/staff/payments/create';
}
</script>
@endpush
@endsection
