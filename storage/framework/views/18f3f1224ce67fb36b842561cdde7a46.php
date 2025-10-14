<?php $__env->startSection('title', 'Schedules Management - Super Admin'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <!-- Schedules Management using Reusable Data Table -->
        <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'data' => $schedules,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'currentPage' => 1,'emptyMessage' => 'No schedules found','hoverEffects' => true,'alternatingRows' => true,'customClass' => 'bg-gray-800 text-gray-200','title' => 'Schedules'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sticky-header' => true,'add-button' => true,'add-button-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Add New Schedule'),'add-button-action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('addSchedule')]); ?>
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
        // Schedules Management Functions for Data Table Component
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

        function addSchedule() {
            console.log('Add new schedule');
            alert('Add new schedule form would open here');
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/superadmin/schedules/index.blade.php ENDPATH**/ ?>