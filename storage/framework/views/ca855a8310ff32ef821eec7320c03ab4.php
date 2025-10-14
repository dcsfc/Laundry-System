<?php $__env->startSection('title', 'User Management'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/tables.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/search-filters.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/action-menu.js')); ?>"></script>
<script src="<?php echo e(asset('js/tables-modular.js')); ?>"></script>
<script src="<?php echo e(asset('js/table-data-fetcher.js')); ?>"></script>
<script src="<?php echo e(asset('js/modern-notifications.js')); ?>"></script>
<script src="<?php echo e(asset('js/notification-demo.js')); ?>"></script>
<script>
    // ActionMenuManager is now loaded from action-menu.js
    
    // Pass roles to window object for modal component
    window.passedRoles = <?php echo json_encode($roles, 15, 512) ?>;
    
    // View modal will auto-initialize via Alpine.js x-data
    
    // Function to open add user modal
    function openAddUserModal() {
        // Find the modal component and open it
        const modal = document.querySelector('[x-data*="userModal"]');
        
        if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
            modal._x_dataStack[0].openCreateModal();
        } else {
            // Fallback: dispatch event
            window.dispatchEvent(new CustomEvent('open-user-modal', { 
                detail: { action: 'create' } 
            }));
        }
    }


    // Global function to open delete confirmation modal
    window.openDeleteConfirmation = function(item, options = {}) {
        // Find the modal component
        const modalElement = document.querySelector('[x-data*="deleteConfirmationModal"]');
        if (modalElement && modalElement._x_dataStack && modalElement._x_dataStack[0]) {
            const modal = modalElement._x_dataStack[0];
            modal.openModal(item, options);
        } else {
            console.error('Delete confirmation modal not found');
        }
    };
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Debug: Check data being passed -->
<?php if(config('app.debug')): ?>
<script>
    console.log('Users data from controller:', <?php echo json_encode($users, 15, 512) ?>);
    console.log('Columns data:', <?php echo json_encode($columns, 15, 512) ?>);
    console.log('Actions data:', <?php echo json_encode($actions, 15, 512) ?>);
</script>
<?php endif; ?>

<div class="table-container" 
     x-data="usersTable(<?php echo \Illuminate\Support\Js::from($users)->toHtml() ?>, 10)"
     x-init="init()">

    <!-- Header -->
    <div class="table-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="header-text">
                    <h2 class="table-title">User Management</h2>
                    <p class="table-description">Manage system users, roles, and permissions</p>
                </div>
            </div>

            <div class="header-actions">
                <button class="btn btn-primary" onclick="openAddUserModal()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New User
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <?php echo $__env->make('components.tables.search-filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Table -->
    <?php echo $__env->make('components.tables.table', [
        'columns' => $columns,
        'actions' => $actions,
        'sortable' => true,
        'emptyMessage' => 'No users found',
        'emptyDescription' => 'Start by adding your first user to the system.'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Pagination -->
    <?php echo $__env->make('components.tables.pagination', ['itemName' => 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- User Modal -->
    <?php echo $__env->make('components.user-modal', ['roles' => $roles], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- User View Modal -->
    <?php echo $__env->make('components.user-view-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Delete Confirmation Modal -->
    <?php echo $__env->make('components.delete-confirmation-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/superadmin/users/index.blade.php ENDPATH**/ ?>