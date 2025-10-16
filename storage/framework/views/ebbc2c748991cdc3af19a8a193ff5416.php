<?php $__env->startSection('title', 'Inventory Management - Staff'); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Inventory Management using Reusable Data Table -->
<?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'data' => $inventory,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'emptyMessage' => 'No inventory items found','emptyDescription' => 'Add your first inventory item to get started','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-slate-800 text-slate-200','title' => 'Inventory Management','description' => 'Manage supplies, track stock levels, and monitor thresholds','addButton' => true,'addButtonLabel' => 'Add New Item','addButtonAction' => 'addInventoryItem','formType' => 'inventory','colorScheme' => 'sky'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle inventory-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewInventoryItem(row);
                break;
            case 'edit':
                editInventoryItem(row);
                break;
            case 'update_stock':
                updateStock(row);
                break;
            case 'delete':
                deleteInventoryItem(row);
                break;
        }
    });
});

// Inventory Management Functions
function viewInventoryItem(row) {
    console.log('View inventory item:', row);
    
    // Fetch detailed item information
    fetch(`/staff/inventory/${row.id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.item;
                alert(`Inventory Item Details:\n\n` +
                      `Name: ${item.item_name}\n` +
                      `Quantity: ${item.quantity} ${item.unit}\n` +
                      `Threshold: ${item.threshold}\n` +
                      `Created: ${item.created_at}\n` +
                      `Updated: ${item.updated_at}`);
            }
        })
        .catch(error => {
            console.error('Error fetching item details:', error);
            alert('Error loading item details');
        });
}

function editInventoryItem(row) {
    console.log('Edit inventory item:', row);
    window.location.href = `/staff/inventory/${row.id}/edit`;
}

function updateStock(row) {
    console.log('Update stock for item:', row);
    const newQuantity = prompt(`Enter new quantity for ${row.item_name}:`, row.quantity);
    
    if (newQuantity !== null && newQuantity !== '') {
        fetch(`/staff/inventory/${row.id}/update-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: parseInt(newQuantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Stock updated successfully for ${row.item_name}: ${row.quantity} → ${newQuantity}`);
                // Refresh the table
                location.reload();
            } else {
                alert('Error updating stock');
            }
        })
        .catch(error => {
            console.error('Error updating stock:', error);
            alert('Error updating stock');
        });
    }
}

function deleteInventoryItem(row) {
    console.log('Delete inventory item:', row);
    
    if (confirm(`Are you sure you want to delete "${row.item_name}"? This action cannot be undone.`)) {
        fetch(`/staff/inventory/${row.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Inventory item "${row.item_name}" deleted successfully`);
                // Refresh the table
                location.reload();
            } else {
                alert('Error deleting inventory item');
            }
        })
        .catch(error => {
            console.error('Error deleting item:', error);
            alert('Error deleting inventory item');
        });
    }
}

function addInventoryItem() {
    console.log('Add new inventory item');
    window.location.href = '/staff/inventory/create';
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/staff/inventory/index.blade.php ENDPATH**/ ?>