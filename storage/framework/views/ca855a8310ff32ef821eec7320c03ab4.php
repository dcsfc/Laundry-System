<?php $__env->startSection('title', 'User Management - Super Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <?php
        $tableData = $data;
    ?>
    
        
        <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'actions' => $actions,'title' => 'User Management','description' => 'Manage system users and their permissions','searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'showRoleFilter' => true,'availableRoles' => ['admin', 'staff', 'customer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tableData' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tableData)]); ?>
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

<script>
// Handle data table events
document.addEventListener('datatable:action', function(event) {
    const { row, action } = event.detail;
    console.log('Action:', action, 'Row:', row);
    
    switch(action) {
        case 'viewUser':
            alert('View user: ' + row.name + '\nEmail: ' + row.email + '\nRole: ' + row.role);
            break;
        case 'editUser':
            alert('Edit user: ' + row.name);
            // You can open a modal or redirect to edit page here
            break;
        case 'toggleUserStatus':
            const newStatus = row.status === 'Active' ? 'Inactive' : 'Active';
            if (confirm('Toggle status for ' + row.name + ' to ' + newStatus + '?')) {
                // Make AJAX call to toggle status
                fetch('/superadmin/users/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: row.id,
                        status: newStatus.toLowerCase()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated successfully!');
                        location.reload(); // Refresh the page
                    } else {
                        alert('Error updating status: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating status');
                });
            }
            break;
        case 'deleteUser':
            if (confirm('Are you sure you want to delete ' + row.name + '? This action cannot be undone.')) {
                // Make AJAX call to delete user
                fetch('/superadmin/users/' + row.id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully!');
                        location.reload(); // Refresh the page
                    } else {
                        alert('Error deleting user: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting user');
                });
            }
            break;
    }
});

document.addEventListener('datatable:add', function(event) {
    alert('Add new user clicked!');
    // You can open a modal or redirect to create page here
    // window.location.href = '/superadmin/users/create';
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/superadmin/users/index.blade.php ENDPATH**/ ?>