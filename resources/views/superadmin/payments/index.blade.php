@extends('layouts.sidebar')

@section('title', 'Payments Management - Super Admin')

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
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Payments Management'"
            :add-button="true"
            :add-button-label="'Record Payment'"
            :add-button-action="'recordPayment'"
            formType="payment"
            colorScheme="green"
        />
    </div>

    <script>
        // Payments Management Functions for Data Table Component
        function viewPayment(row) {
            console.log('View payment:', row);
            alert('View payment: ' + row.id + ' - $' + row.amount);
        }

        function editPayment(row) {
            console.log('Edit payment:', row);
            alert('Edit payment: ' + row.id + ' - $' + row.amount);
        }

        function updatePaymentStatus(row) {
            console.log('Update status for payment:', row);
            const newStatus = row.payment_status === 'Paid' ? 'Pending' : 'Paid';
            if (confirm(`Are you sure you want to update payment ${row.id} status to ${newStatus}?`)) {
                alert(`Payment ${row.id} status updated to ${newStatus}`);
            }
        }

        function deletePayment(row) {
            console.log('Delete payment:', row);
            if (confirm('Are you sure you want to delete payment ' + row.id + '? This action cannot be undone.')) {
                alert('Payment ' + row.id + ' deleted');
            }
        }

        function recordPayment() {
            console.log('Record new payment');
            alert('Record new payment form would open here');
        }
    </script>
@endsection