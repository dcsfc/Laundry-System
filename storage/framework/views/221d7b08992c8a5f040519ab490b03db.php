

<?php $__env->startSection('title', 'Reports Management - Administrator'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <!-- Reports Management using Reusable Data Table -->
        <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'currentPage' => 1,'emptyMessage' => 'No reports found','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-gray-800 text-gray-200','title' => 'Reports Management','addButton' => true,'addButtonLabel' => 'Generate Report','addButtonAction' => 'addReport','formType' => 'report','colorScheme' => 'purple'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

    <script>
        // Reports Management Functions for Data Table Component
        function viewReport(row) {
            console.log('View report:', row);
            alert('View report: ' + row.id + ' - ' + row.report_name);
        }

        function downloadReport(row) {
            console.log('Download report:', row);
            alert('Download report: ' + row.id + ' - ' + row.report_name);
        }

        function editReport(row) {
            console.log('Edit report:', row);
            alert('Edit report: ' + row.id + ' - ' + row.report_name);
        }

        function deleteReport(row) {
            console.log('Delete report:', row);
            if (confirm('Are you sure you want to delete report ' + row.id + '? This action cannot be undone.')) {
                alert('Report ' + row.id + ' deleted');
            }
        }

        function addReport() {
            console.log('Generate new report');
            alert('Generate new report form would open here');
        }
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>