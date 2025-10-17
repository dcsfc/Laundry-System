

<?php $__env->startSection('title', 'Service Management - Administrator'); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <!-- Service Management using Reusable Data Table -->
        <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'emptyMessage' => 'No services found','emptyDescription' => 'Add your first service to get started','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-slate-800 text-slate-200','title' => 'Service Management','description' => $description,'addButton' => true,'addButtonLabel' => 'Add New Service','addButtonAction' => 'addService','showRoleFilter' => false,'colorScheme' => 'sky'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($services)]); ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle service-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewService(row);
                break;
            case 'edit':
                editService(row);
                break;
            case 'toggle_status':
                toggleServiceStatus(row);
                break;
            case 'delete':
                deleteService(row);
                break;
        }
    });
});

// Service Management Functions
function viewService(row) {
    console.log('View service:', row);
    alert('View service: ' + row.id + ' - ' + row.name);
}

function editService(row) {
    console.log('Edit service:', row);
    alert('Edit service: ' + row.id + ' - ' + row.name);
}

function toggleServiceStatus(row) {
    console.log('Toggle status for service:', row);
    const newStatus = row.status === 'Active' ? 'Inactive' : 'Active';
    if (confirm(`Are you sure you want to ${newStatus.toLowerCase()} service ${row.id}?`)) {
        alert(`Service ${row.id} status changed to ${newStatus}`);
    }
}

function deleteService(row) {
    console.log('Delete service:', row);
    if (confirm('Are you sure you want to delete service ' + row.id + '? This action cannot be undone.')) {
        alert('Service ' + row.id + ' deleted');
    }
}

function addService() {
    console.log('Add new service');
    alert('Add new service form would open here');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/admin/services/index.blade.php ENDPATH**/ ?>