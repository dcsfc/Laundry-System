

<?php $__env->startSection('title', 'Inventory Management - Administrator'); ?>

<?php $__env->startSection('content'); ?>
        <div class="container">
        <!-- Inventory Management using Reusable Data Table -->
        <?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'currentPage' => 1,'emptyMessage' => 'No inventory items found','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-gray-800 text-gray-200','title' => 'Inventory Management','description' => $description,'addButton' => true,'addButtonLabel' => 'Add New Item','addButtonAction' => 'addInventoryItem','formType' => 'inventory','colorScheme' => 'emerald'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inventory)]); ?>
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
        // Inventory Management Functions for Data Table Component
        function viewItem(row) {
            console.log('View item:', row);
            alert('View item: ' + row.id + ' - ' + row.item_name);
        }

        function editItem(row) {
            console.log('Edit item:', row);
            alert('Edit item: ' + row.id + ' - ' + row.item_name);
        }

        function updateStock(row) {
            console.log('Update stock for item:', row);
            const newQuantity = prompt(`Enter new quantity for ${row.item_name}:`, row.quantity);
            if (newQuantity !== null) {
                alert(`Stock updated for ${row.item_name}: ${row.quantity} → ${newQuantity}`);
            }
        }

        function deleteItem(row) {
            console.log('Delete item:', row);
            if (confirm('Are you sure you want to delete item ' + row.id + '? This action cannot be undone.')) {
                alert('Item ' + row.id + ' deleted');
            }
        }

        function addInventoryItem() {
            console.log('Add new inventory item');
            alert('Add new inventory item form would open here');
        }
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/admin/inventory/index.blade.php ENDPATH**/ ?>