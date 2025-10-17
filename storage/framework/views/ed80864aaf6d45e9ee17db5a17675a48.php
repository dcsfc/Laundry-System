

<?php $__env->startSection('title', 'Schedules Management - Administrator'); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <!-- Schedules Management using Reusable Data Table -->
        <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'currentPage' => 1,'emptyMessage' => 'No schedules found','emptyDescription' => 'No customer schedules have been created yet','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-slate-800 text-slate-200','title' => 'Schedule Management','description' => 'Manage all customer schedules and track their progress','addButton' => false,'showRoleFilter' => false,'colorScheme' => 'sky'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($schedules)]); ?>
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
    
    // Handle schedule-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewSchedule(row);
                break;
            case 'edit':
                editSchedule(row);
                break;
            case 'update_status':
                updateScheduleStatus(row);
                break;
            case 'delete':
                deleteSchedule(row);
                break;
        }
    });
});

// Schedules Management Functions
function viewSchedule(row) {
    console.log('View schedule:', row);
    alert('View schedule: ' + row.id + ' - ' + row.customer_name);
}

function editSchedule(row) {
    console.log('Edit schedule:', row);
    alert('Edit schedule: ' + row.id + ' - ' + row.customer_name);
}

function updateScheduleStatus(row) {
    console.log('Update status for schedule:', row);
    const newStatus = row.status === 'Confirmed' ? 'Pending' : 'Confirmed';
    if (confirm(`Are you sure you want to update schedule ${row.id} status to ${newStatus}?`)) {
        alert(`Schedule ${row.id} status updated to ${newStatus}`);
    }
}

function deleteSchedule(row) {
    console.log('Delete schedule:', row);
    if (confirm('Are you sure you want to delete schedule ' + row.id + '? This action cannot be undone.')) {
        alert('Schedule ' + row.id + ' deleted');
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/admin/schedules/index.blade.php ENDPATH**/ ?>