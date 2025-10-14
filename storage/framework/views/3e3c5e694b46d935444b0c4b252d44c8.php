

<?php $__env->startSection('title', 'Schedule Management - Staff'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/tables.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/search-filters.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/status-badges.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/action-menu.js')); ?>"></script>
<script src="<?php echo e(asset('js/tables-modular.js')); ?>"></script>
<script src="<?php echo e(asset('js/table-data-fetcher.js')); ?>"></script>
<script src="<?php echo e(asset('js/modern-notifications.js')); ?>"></script>
<script src="<?php echo e(asset('js/notification-demo.js')); ?>"></script>
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
<!-- Debug: Check data being passed -->
<?php if(config('app.debug')): ?>
<script>
    console.log('Pending schedules data:', <?php echo json_encode($pendingSchedules, 15, 512) ?>);
    console.log('Approved schedules data:', <?php echo json_encode($approvedSchedules, 15, 512) ?>);
</script>
<?php endif; ?>

<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Pending Approvals -->
        <div class="bg-gradient-to-br from-amber-500/20 to-orange-500/20 border border-amber-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-400 text-sm font-medium">Pending Approvals</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($pendingSchedules->count()); ?></p>
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

    <!-- Pending Schedules Table -->
    <div class="table-container" 
         x-data="pendingSchedulesTable(<?php echo \Illuminate\Support\Js::from($pendingSchedules)->toHtml() ?>, 10)"
         x-init="init()">

        <!-- Header -->
        <div class="table-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-clock text-amber-400"></i>
                    </div>
                    <div class="header-text">
                        <h2 class="table-title">Schedule Management</h2>
                        <p class="table-description">Manage all customer schedules and track their progress</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <?php echo $__env->make('components.tables.search-filters', ['showRoleFilter' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Table -->
        <div class="table-scroll-container">
            <table class="data-table">
                <thead class="table-head">
                    <tr>
                        <!-- Headers with sorting -->
                        <?php $__currentLoopData = $pendingColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="table-header-cell">
                            <?php if(true): ?>
                            <button type="button" class="sort-button" @click="sort('<?php echo e($column['key']); ?>')">
                                <?php echo e($column['label']); ?>

                                <?php echo $__env->make('components.tables.sort-arrows', ['column' => $column['key']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </button>
                            <?php else: ?>
                            <?php echo e($column['label']); ?>

                            <?php endif; ?>
                        </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <th class="actions-header">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Loading State -->
                    <template x-if="isLoading">
                        <tr>
                            <td :colspan="<?php echo e(count($pendingColumns) + 1); ?>" class="text-center py-8">
                                <div class="flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                                    <span class="ml-2 text-slate-400">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Data Rows -->
                    <template x-for="(row, index) in paginatedData" :key="row.id || index">
                        <?php echo $__env->make('components.tables.schedule-row', ['columns' => $pendingColumns], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </template>
                </tbody>

                <!-- Empty State -->
                <tfoot x-show="paginatedData.length === 0 && !isLoading">
                    <tr>
                        <td :colspan="<?php echo e(count($pendingColumns) + 1); ?>" class="empty-content">
                            <div class="empty-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3>No schedules found</h3>
                            <p>All schedule requests have been processed.</p>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <?php echo $__env->make('components.tables.pagination', ['itemName' => 'schedules'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>


    
</div>

<!-- Schedule Details Modal -->
<?php echo $__env->make('components.schedule-details-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Rejection Modal -->
<?php echo $__env->make('components.schedule-rejection-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Delete Confirmation Modal -->
<?php echo $__env->make('components.delete-confirmation-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
// Custom table function for pending schedules
function pendingSchedulesTable(data, pageSize = 10) {
    const baseTable = new BaseTable(data, pageSize, {
        searchable: true,
        sortable: true,
        pagination: true,
        bulkActions: false
    });
    
    return {
        ...baseTable,
        
        // State expected by filters and table
        searchQuery: '',
        statusFilter: '',
        serviceFilter: '',
        paymentFilter: '',
        roleFilter: '',
        defaultVisibleStatuses: ['pending','approved','processing','ready_for_pickup'],
        
        init() {
            console.log('Pending schedules table initializing with data:', this.originalData);
            baseTable.validateData.call(this);
            this.applyFilters();
            if (typeof this.setupPortalSystem === 'function') {
                this.setupPortalSystem();
            }
            if (typeof this.setupEventListeners === 'function') {
                this.setupEventListeners();
            }
            this.loadStats();
            console.log('Pending schedules table initialized. Paginated data:', this.paginatedData);
        },

        // No-op fallbacks to avoid undefined errors if BaseTable didn't mix these in
        setupPortalSystem() {},
        setupEventListeners() {},

        // Sorting wrapper (fallback if base doesn't provide)
        sort(columnKey) {
            if (typeof baseTable.sort === 'function') {
                return baseTable.sort.call(this, columnKey);
            }
            // Fallback simple sort
            if (this.sortColumn === columnKey) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = columnKey;
                this.sortDirection = 'asc';
            }
            const dir = this.sortDirection === 'asc' ? 1 : -1;
            this.filteredData.sort((a, b) => {
                const av = (a[columnKey] ?? '').toString().toLowerCase();
                const bv = (b[columnKey] ?? '').toString().toLowerCase();
                if (av < bv) return -1 * dir;
                if (av > bv) return 1 * dir;
                return 0;
            });
            this.updatePagination(this.filteredData);
        },
        openPricingModal(row) {
            window.openPricingModal(row);
        },
        
        loadStats() {
            fetch('/staff/schedules/stats')
                .then(async response => {
                    if (!response.ok) return null;
                    const text = await response.text();
                    try { return JSON.parse(text); } catch { return null; }
                })
                .then(data => {
                    if (data && data.success && data.stats) {
                        const { approved_today = 0, rejected_today = 0, total_processed_today = 0 } = data.stats;
                        const a = document.getElementById('approvedToday'); if (a) a.textContent = approved_today;
                        const r = document.getElementById('rejectedToday'); if (r) r.textContent = rejected_today;
                        const p = document.getElementById('processedToday'); if (p) p.textContent = total_processed_today;
                    }
                })
                .catch(() => { /* silent */ });
        },
        
        applyFilters() {
            let filtered = [...this.originalData];
            
            if (this.searchQuery?.trim()) {
                const query = this.searchQuery.toLowerCase().trim();
                filtered = filtered.filter(row => {
                    return Object.values(row).some(value => 
                        String(value).toLowerCase().includes(query)
                    );
                });
            }
            
            if (this.statusFilter && this.statusFilter !== '') {
                filtered = filtered.filter(row => {
                    const s = String(row.status ?? '').trim().toLowerCase();
                    return s === this.statusFilter.toLowerCase();
                });
            } else {
                // Default view: include only active/in-progress statuses
                filtered = filtered.filter(row => {
                    const s = String(row.status ?? '').trim().toLowerCase();
                    return this.defaultVisibleStatuses.includes(s);
                });
            }
            
            if (this.paymentFilter && this.paymentFilter !== '') {
                filtered = filtered.filter(row => String(row.payment_status ?? '').toLowerCase() === this.paymentFilter.toLowerCase());
            }
            
            if (this.serviceFilter && this.serviceFilter !== '') {
                filtered = filtered.filter(row => {
                    return String(row.service_name ?? '').toLowerCase() === this.serviceFilter.toLowerCase();
                });
            }
            
            if (this.sortColumn && typeof sortData === 'function') {
                filtered = sortData(filtered, this.sortColumn, this.sortDirection, getNestedValue, isDate);
            }
            
            this.filteredData = filtered;
            this.updatePagination(filtered);
        },

        // Pagination (fallback if base doesn't provide)
        updatePagination(list) {
            if (typeof baseTable.updatePagination === 'function') {
                return baseTable.updatePagination.call(this, list);
            }
            const items = Array.isArray(list) ? list : [];
            const pageSize = Number(this.pageSize) || 10;
            const total = items.length;
            const pages = Math.max(1, Math.ceil(total / pageSize));
            this.totalItems = total;
            this.totalPages = pages;
            if (!this.currentPage || this.currentPage < 1) this.currentPage = 1;
            if (this.currentPage > pages) this.currentPage = pages;
            const start = (this.currentPage - 1) * pageSize;
            const end = start + pageSize;
            this.paginatedData = items.slice(start, end);
        },

        // Filters API expected by the filters component
        handleFilterChange(name, value) {
            this[name] = value;
            this.currentPage = 1;
            this.applyFilters();
        },
        handleSearch() {
            this.currentPage = 1;
            this.applyFilters();
        },
        clearSearch() {
            this.searchQuery = '';
            this.currentPage = 1;
            this.applyFilters();
        },
        clearAllFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.paymentFilter = '';
            this.serviceFilter = '';
            this.roleFilter = '';
            this.currentPage = 1;
            this.applyFilters();
        },
        
        getActionsForRow(rowData) {
            return [
                { key: 'view', label: 'View Details', icon: 'view' },
                { key: 'approve', label: 'Approve', icon: 'check' },
                { key: 'reject', label: 'Reject', icon: 'times' }
            ];
        },
        
        viewSchedule(schedule) {
            window.openScheduleDetailsModal(schedule);
        },
        
        async approveSchedule(schedule) {
            if (confirm(`Are you sure you want to approve schedule #${schedule.id}?`)) {
                try {
                    const response = await fetch(`/staff/schedules/${schedule.id}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.showNotification(data.message, 'success');
                        this.refreshTable();
                        this.loadStats();
                    } else {
                        const error = await response.json();
                        this.showNotification(error.message, 'error');
                    }
                } catch (error) {
                    console.error('Error approving schedule:', error);
                    this.showNotification('Error approving schedule', 'error');
                }
            }
        },
        
        rejectSchedule(schedule) {
            window.openRejectionModal(schedule.id, schedule);
        },
        
        handleAction(rowData, action) {
            switch(action.key) {
                case 'view':
                    this.viewSchedule(rowData);
                    break;
                case 'approve':
                    this.approveSchedule(rowData);
                    break;
                case 'reject':
                    this.rejectSchedule(rowData);
                    break;
                default:
                    console.log('Unknown action:', action.key);
            }
        },
        
        async refreshTable() {
            try {
                const response = await fetch('/staff/schedules/fetch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = response.ok ? await response.json() : null;
                if (data && data.success && Array.isArray(data.schedules)) {
                    this.originalData = data.schedules;
                }

                this.applyFilters();
            } catch (error) {
                console.error('Error refreshing table:', error);
                window.location.reload();
            }
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        ${type === 'success' ? 
                            '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>' :
                            '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'
                        }
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.add('translate-x-0', 'opacity-100');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('-translate-x-full', 'opacity-0');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        },
        
        getStatusBadgeClass(status) {
            const statusClasses = {
                'pending': 'status-pending',
                'approved': 'status-approved',
                'processing': 'status-processing',
                'ready_for_pickup': 'status-ready',
                'completed': 'status-completed',
                'cancelled': 'status-cancelled'
            };
            return statusClasses[status] || 'status-default';
        }
    };
}

// Make the function globally available
window.pendingSchedulesTable = pendingSchedulesTable;

// Global function to update schedule status
window.updateStatus = async function(scheduleId, newStatus) {
    try {
        const response = await fetch(`/staff/schedules/${scheduleId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: newStatus
            })
        });

        if (response.ok) {
            const data = await response.json();
            showToast(data.message, 'success');
            
            // Refresh the table
            const tableElement = document.querySelector('[x-data*="pendingSchedulesTable"]');
            if (tableElement && tableElement._x_dataStack && tableElement._x_dataStack[0]) {
                tableElement._x_dataStack[0].refreshTable();
                tableElement._x_dataStack[0].loadStats();
            }
        } else {
            const error = await response.json();
            showToast(error.message, 'error');
        }
    } catch (error) {
        console.error('Error updating status:', error);
        showToast('Error updating schedule status', 'error');
    }
};

// Global function to view schedule details
window.viewSchedule = function(scheduleId) {
    // Find the schedule data
    const tableElement = document.querySelector('[x-data*="pendingSchedulesTable"]');
    if (tableElement && tableElement._x_dataStack && tableElement._x_dataStack[0]) {
        const table = tableElement._x_dataStack[0];
        const schedule = table.originalData.find(s => s.id === scheduleId);
        if (schedule) {
            window.openScheduleDetailsModal(schedule);
        }
    }
};

// Global function to show toast notifications
window.showToast = function(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full opacity-0 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' ? 
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>' :
                    '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        notification.classList.remove('translate-x-0', 'opacity-100');
        notification.classList.add('-translate-x-full', 'opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
};

// Pricing modal controller
function pricingModal() {
    return {
        isOpen: false,
        scheduleId: null,
        form: { weight: null, price: null },
        
        init() {
            // Modal starts hidden by default, no need to set isOpen here
            console.log('Pricing modal initialized, isOpen:', this.isOpen);
        },
        open(row) {
            console.log('Pricing modal open method called with:', row);
            this.scheduleId = row.id;
            this.form.weight = row.weight || null;
            this.form.price = row.price || null;
            this.isOpen = true;
            console.log('Modal isOpen set to:', this.isOpen);
        },
        close() {
            this.isOpen = false;
            this.scheduleId = null;
            this.form = { weight: null, price: null };
        },
        async submit() {
            try {
                const response = await fetch(`/staff/schedules/${this.scheduleId}/pricing`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        weight: this.form.weight,
                        price: this.form.price
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    window.showToast(data.message, 'success');
                    this.close();
                    const tableElement = document.querySelector('[x-data*="pendingSchedulesTable"]');
                    if (tableElement && tableElement._x_dataStack && tableElement._x_dataStack[0]) {
                        tableElement._x_dataStack[0].refreshTable();
                        tableElement._x_dataStack[0].loadStats();
                    }
                } else {
                    const error = await response.json();
                    window.showToast(error.message || 'Failed to save pricing', 'error');
                }
            } catch (e) {
                console.error(e);
                window.showToast('Failed to save pricing', 'error');
            }
        }
    };
}
</script>
<?php $__env->stopSection(); ?>

<!-- Pricing Modal - Outside content section for full viewport coverage -->
<div x-data="pricingModal()" x-init="init()" x-show="isOpen" class="fixed inset-0 z-[10000]" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; height: 100vh !important; display: none;">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()" style="position: absolute !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100% !important; height: 100% !important;"></div>
    <div class="relative flex items-center justify-center w-full h-full p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl w-full max-w-md p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold">Add Weight & Price</h3>
                <button @click="close()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" min="0" x-model.number="form.weight" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-lg text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Total Price</label>
                    <input type="number" step="0.01" min="0" x-model.number="form.price" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded-lg text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-6">
                <button @click="close()" class="px-3 py-2 text-xs rounded bg-slate-700 text-white hover:bg-slate-600">Cancel</button>
                <button @click="submit()" class="px-3 py-2 text-xs rounded bg-blue-600 text-white hover:bg-blue-700">Save & Move to Processing</button>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/staff/schedules/index.blade.php ENDPATH**/ ?>