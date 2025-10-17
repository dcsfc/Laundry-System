

<?php $__env->startSection('title', 'Reports Management - Administrator'); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <!-- Reports Management using Reusable Data Table -->
        <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'emptyMessage' => 'No reports found','emptyDescription' => 'Generate your first report to get started','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-slate-800 text-slate-200','title' => 'Reports Management','description' => 'View and analyze performance reports','addButton' => true,'addButtonLabel' => 'Generate Report','addButtonAction' => 'addReport','showRoleFilter' => false,'colorScheme' => 'sky'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reports)]); ?>
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
    
    // Handle report-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewReport(row);
                break;
            case 'export':
                downloadReport(row);
                break;
        }
    });
});

// Reports Management Functions
function viewReport(row) {
    console.log('View report:', row);
    alert('View report: ' + row.id + ' - ' + row.report_name);
}

function downloadReport(row) {
    console.log('Download report:', row);
    alert('Download report: ' + row.id + ' - ' + row.report_name);
}

function addReport() {
    console.log('Generate new report');
    alert('Generate new report form would open here');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>