<?php $__env->startSection('title', 'Schedule Management - Staff'); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/table/table-data-fetcher.js', 'resources/js/modules/notifications/modern-notifications.js', 'resources/js/modules/notifications/notification-demo.js']); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Pending Approvals -->
        <div class="bg-gradient-to-br from-amber-500/20 to-orange-500/20 border border-amber-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-400 text-sm font-medium">Pending Approvals</p>
                    <p class="text-2xl font-bold text-white" id="pendingCount"><?php echo e($pendingCount); ?></p>
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
    <!-- VIEW DEBUG: $allSchedules count = <?php echo e(count($allSchedules ?? [])); ?> -->
    <!-- VIEW DEBUG: Is array? <?php echo e(is_array($allSchedules ?? null) ? 'YES' : 'NO'); ?> -->
    <!-- VIEW DEBUG: First item = <?php echo e(!empty($allSchedules) && is_array($allSchedules) && count($allSchedules) > 0 ? json_encode($allSchedules[0]) : 'EMPTY OR NOT ARRAY'); ?> -->
    
    <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $scheduleColumns,'actions' => $scheduleActions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'emptyMessage' => 'No schedules found','emptyDescription' => 'No customer schedules have been created yet','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-slate-800 text-slate-200','title' => 'Schedule Management','description' => 'Manage all customer schedules and track their progress','addButton' => false,'showRoleFilter' => false,'colorScheme' => 'sky'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($allSchedules)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae)): ?>
<?php $attributes = $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae; ?>
<?php unset($__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb539fdd4bceece4a667dd360eb69c7ae)): ?>
<?php $component = $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae; ?>
<?php unset($__componentOriginalb539fdd4bceece4a667dd360eb69c7ae); ?>
<?php endif; ?>
</div>

<!-- Schedule Details Modal -->
<?php echo $__env->make('components.schedule-details-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Pricing Modal -->
<?php echo $__env->make('components.schedule-pricing-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Rejection Modal -->
<?php echo $__env->make('components.schedule-rejection-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Delete Confirmation Modal -->
<?php echo $__env->make('components.delete-confirmation-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>
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
            case 'add_price':
                window.openPricingModal(row);
                break;
            case 'start_processing':
                startProcessing(row);
                break;
            case 'mark_ready':
                markReadyForPickup(row);
                break;
            case 'mark_completed':
                markCompleted(row);
                break;
            case 'cancel':
                cancelSchedule(row);
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
    
    // Show row loading state
    setRowLoading(row.id, true);
    
    fetch(`/staff/schedules/${row.id}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Update row data without page reload
            updateTableRow(row.id, {
                status: 'Confirmed',
                raw_status: 'confirmed'
            });
            // Refresh stats
            window.loadScheduleStats();
        } else {
            alert(data.message || 'Failed to approve schedule');
        }
        setRowLoading(row.id, false);
    })
    .catch(error => {
        console.error('Error approving schedule:', error);
        alert('Failed to approve schedule: ' + error.message);
        setRowLoading(row.id, false);
    });
}

function rejectSchedule(row) {
    console.log('Reject schedule:', row);
    window.openRejectionModal(row.id, row);
}

// Make loadScheduleStats globally accessible
window.loadScheduleStats = function() {
    fetch('/staff/schedules/stats', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
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
            // Silently fail for stats - not critical
        });
};

// Global functions for modals
window.openPricingModal = function(row) {
    console.log('Opening pricing modal for row:', row);
    const modal = document.querySelector('[x-data*="pricingModal"]');
    if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
        modal._x_dataStack[0].open(row);
    } else {
        console.error('Pricing modal not found or not initialized');
    }
};

function startProcessing(row) {
    if (confirm('Start processing this order?')) {
        setRowLoading(row.id, true);
        
        fetch(`/staff/schedules/${row.id}/start-processing`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                updateTableRow(row.id, {
                    status: 'Processing',
                    raw_status: 'processing'
                });
                window.loadScheduleStats();
            } else {
                alert(data.message || 'Failed to start processing');
            }
            setRowLoading(row.id, false);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to start processing: ' + error.message);
            setRowLoading(row.id, false);
        });
    }
}

function markReadyForPickup(row) {
    if (confirm('Mark this order as ready for pickup?')) {
        setRowLoading(row.id, true);
        
        fetch(`/staff/schedules/${row.id}/mark-ready`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                updateTableRow(row.id, {
                    status: 'Ready for Pickup',
                    raw_status: 'ready_for_pickup'
                });
                window.loadScheduleStats();
            } else {
                alert(data.message || 'Failed to mark as ready');
            }
            setRowLoading(row.id, false);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to mark as ready: ' + error.message);
            setRowLoading(row.id, false);
        });
    }
}

function markCompleted(row) {
    if (confirm('Mark this order as completed?')) {
        setRowLoading(row.id, true);
        
        fetch(`/staff/schedules/${row.id}/mark-completed`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                updateTableRow(row.id, {
                    status: 'Completed',
                    raw_status: 'completed'
                });
                window.loadScheduleStats();
            } else {
                alert(data.message || 'Failed to mark as completed');
            }
            setRowLoading(row.id, false);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to mark as completed: ' + error.message);
            setRowLoading(row.id, false);
        });
    }
}

function cancelSchedule(row) {
    if (confirm('Cancel this order? This action cannot be undone.')) {
        setRowLoading(row.id, true);
        
        fetch(`/staff/schedules/${row.id}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                updateTableRow(row.id, {
                    status: 'Cancelled',
                    raw_status: 'cancelled'
                });
                window.loadScheduleStats();
            } else {
                alert(data.message || 'Failed to cancel order');
            }
            setRowLoading(row.id, false);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to cancel order: ' + error.message);
            setRowLoading(row.id, false);
        });
    }
}

// Helper functions for dynamic table updates
function setRowLoading(rowId, isLoading) {
    const dataTableEl = document.querySelector('[data-datatable]');
    if (dataTableEl && dataTableEl._x_dataStack && dataTableEl._x_dataStack[0]) {
        const dt = dataTableEl._x_dataStack[0];
        dt.rowLoadingId = isLoading ? rowId : null;
    }
}

function updateTableRow(orderId, updates) {
    const dataTableEl = document.querySelector('[data-datatable]');
    if (dataTableEl && dataTableEl._x_dataStack && dataTableEl._x_dataStack[0]) {
        const dt = dataTableEl._x_dataStack[0];
        const rowIndex = dt.originalData.findIndex(row => row.id == orderId);
        
        if (rowIndex !== -1) {
            // First apply the immediate updates
            Object.assign(dt.originalData[rowIndex], updates);
            
            // Force immediate reactivity for visible changes
            dt.originalData = [...dt.originalData];
            
            // Then fetch fresh row data with updated actions
            fetch(`/staff/schedules/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Response is not JSON. Content-Type: ' + contentType);
                }
                
                return response.json();
            })
            .then(data => {
                if (data.success && data.order) {
                    // Update the entire row with fresh data including actions
                    Object.assign(dt.originalData[rowIndex], data.order);
                    console.log('Updated row data:', dt.originalData[rowIndex]);
                    
                    // Force Alpine.js reactivity by triggering a small change
                    dt.originalData = [...dt.originalData];
                }
            })
            .catch(error => {
                console.error('Error fetching updated row:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack
                });
                // Don't show alert here - the main action already succeeded
            });
        }
    }
}

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
    
    setRowLoading(orderId, true);
    
    fetch(`/staff/schedules/${orderId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            rejection_reason: reason
        })
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            updateTableRow(orderId, {
                status: 'Cancelled',
                raw_status: 'cancelled'
            });
            window.loadScheduleStats();
        } else {
            alert(data.message || 'Failed to reject schedule');
        }
        setRowLoading(orderId, false);
    })
    .catch(error => {
        console.error('Error rejecting schedule:', error);
        alert('Failed to reject schedule: ' + error.message);
        setRowLoading(orderId, false);
    });
};
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/staff/schedules/index.blade.php ENDPATH**/ ?>