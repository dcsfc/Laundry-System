@extends('layouts.sidebar')

@section('title', 'Schedule Management - Staff')

@push('styles')
@vite(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/table/table-data-fetcher.js', 'resources/js/modules/notifications/modern-notifications.js', 'resources/js/modules/notifications/notification-demo.js'])
<script>
    // Global function to open rejection modal
    window.openRejectionModal = function(orderId, orderData) {
        const modal = document.querySelector('[x-data*="rejectionModal"]');
        if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
            modal._x_dataStack[0].openModal(orderId, orderData);
        }
    };

    // Global function to open schedule details modal
    window.openScheduleDetailsModal = function(orderData) {
        const modal = document.querySelector('[x-data*="scheduleDetailsModal"]');
        if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
            modal._x_dataStack[0].openModal(orderData);
        }
    };

    // Global function to open pricing modal
    window.openPricingModal = function(row) {
        console.log('Opening pricing modal for row:', row);
        const modal = document.querySelector('[x-data*="pricingModal"]');
        console.log('Modal element found:', modal);
        if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
            console.log('Modal data stack found, opening modal');
            modal._x_dataStack[0].open(row);
        } else {
            console.error('Modal not found or not initialized');
        }
    };
</script>
@endpush

@section('content')

<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Pending Approvals -->
        <div class="bg-gradient-to-br from-amber-500/20 to-orange-500/20 border border-amber-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-400 text-sm font-medium">Pending Approvals</p>
                    <p class="text-2xl font-bold text-white" id="pendingCount">{{ $pendingCount }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-amber-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Approved Today -->
        <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-400 text-sm font-medium">Approved Today</p>
                    <p class="text-2xl font-bold text-white" id="approvedToday">0</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Rejected Today -->
        <div class="bg-gradient-to-br from-red-500/20 to-pink-500/20 border border-red-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-400 text-sm font-medium">Rejected Today</p>
                    <p class="text-2xl font-bold text-white" id="rejectedToday">0</p>
                </div>
                <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Processed -->
        <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-400 text-sm font-medium">Processed Today</p>
                    <p class="text-2xl font-bold text-white" id="processedToday">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tasks text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- All Schedules Table -->
    <!-- VIEW DEBUG: $allSchedules count = {{ count($allSchedules ?? []) }} -->
    <!-- VIEW DEBUG: Is array? {{ is_array($allSchedules ?? null) ? 'YES' : 'NO' }} -->
    <!-- VIEW DEBUG: First item = {{ !empty($allSchedules) && is_array($allSchedules) && count($allSchedules) > 0 ? json_encode($allSchedules[0]) : 'EMPTY OR NOT ARRAY' }} -->
    
    <x-data-table
        :columns="$scheduleColumns"
        :items="$allSchedules"
        :actions="$scheduleActions"
        :bulk-actions="false"
        :searchable="true"
        :sortable="true"
        :pagination="true"
        :page-size="10"
        :empty-message="'No schedules found'"
        :empty-description="'No customer schedules have been created yet'"
        :hover-effects="true"
        :alternating-rows="true"
        :sticky-header="true"
        :custom-class="'bg-slate-800 text-slate-200'"
        :title="'Schedule Management'"
        :description="'Manage all customer schedules and track their progress'"
        :add-button="false"
        :show-role-filter="false"
        color-scheme="sky"
    />
</div>

<!-- Schedule Details Modal -->
@include('components.schedule-details-modal')

<!-- Rejection Modal -->
@include('components.schedule-rejection-modal')

<!-- Delete Confirmation Modal -->
@include('components.delete-confirmation-modal')

@push('scripts')
<script>
// Schedule Management Functions
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle schedule-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewSchedule(row);
                break;
            case 'approve':
                approveSchedule(row);
                break;
            case 'reject':
                rejectSchedule(row);
                break;
        }
    });
    
    // Load stats
    window.loadScheduleStats();
});

// Schedule Management Functions
function viewSchedule(row) {
    console.log('View schedule:', row);
    window.openScheduleDetailsModal(row);
}

function approveSchedule(row) {
    console.log('Approve schedule:', row);
    
    fetch(`/staff/schedules/${row.id}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to approve schedule');
        }
    })
    .catch(error => {
        console.error('Error approving schedule:', error);
        alert('Failed to approve schedule');
    });
}

function rejectSchedule(row) {
    console.log('Reject schedule:', row);
    window.openRejectionModal(row.id, row);
}

// Make loadScheduleStats globally accessible
window.loadScheduleStats = function() {
    fetch('/staff/schedules/stats')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.stats) {
                const { pending_count = 0, approved_today = 0, rejected_today = 0, total_processed_today = 0 } = data.stats;
                const pendingEl = document.getElementById('pendingCount');
                const approvedEl = document.getElementById('approvedToday');
                const rejectedEl = document.getElementById('rejectedToday');
                const processedEl = document.getElementById('processedToday');
                
                if (pendingEl) pendingEl.textContent = pending_count;
                if (approvedEl) approvedEl.textContent = approved_today;
                if (rejectedEl) rejectedEl.textContent = rejected_today;
                if (processedEl) processedEl.textContent = total_processed_today;
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
};

// Global functions for modals
window.openPricingModal = function(row) {
    console.log('Opening pricing modal for row:', row);
    const modal = document.querySelector('[x-data*="pricingModal"]');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        modal._x_dataStack[0].open(row);
    }
};

window.savePricing = function(orderId, weight, price) {
    console.log('Saving pricing for order:', orderId, 'weight:', weight, 'price:', price);
    
    if (!weight || !price || weight <= 0 || price <= 0) {
        alert('Please enter valid weight and price values');
        return;
    }
    
    fetch(`/staff/schedules/${orderId}/pricing`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            weight: parseFloat(weight),
            price: parseFloat(price)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to save pricing');
        }
    })
    .catch(error => {
        console.error('Error saving pricing:', error);
        alert('Failed to save pricing');
    });
};

window.handleRejection = function(orderId, reason) {
    console.log('Handling rejection for order:', orderId, 'reason:', reason);
    
    fetch(`/staff/schedules/${orderId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            rejection_reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to reject schedule');
        }
    })
    .catch(error => {
        console.error('Error rejecting schedule:', error);
        alert('Failed to reject schedule');
    });
};
</script>
@endpush
@endsection