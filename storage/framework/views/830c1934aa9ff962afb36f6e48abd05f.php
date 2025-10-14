<!-- Table Row -->
<tr class="data-row">
    
    <!-- Data Columns -->
    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td class="data-cell">
        <?php if(isset($column['type']) && $column['type'] === 'status'): ?>
        <span 
            class="status-badge" 
            :class="{ 
                'status-active': (row.<?php echo e($column['key']); ?> || '').toLowerCase() === 'active', 
                'status-inactive': (row.<?php echo e($column['key']); ?> || '').toLowerCase() !== 'active' 
            }"
            x-text="(row.<?php echo e($column['key']); ?> || 'inactive').charAt(0).toUpperCase() + (row.<?php echo e($column['key']); ?> || 'inactive').slice(1)"
        ></span>
        <?php elseif(isset($column['type']) && $column['type'] === 'date'): ?>
        <span class="cell-text date-text" x-text="formatDate(row.<?php echo e($column['key']); ?>)"></span>
        <?php elseif(isset($column['type']) && $column['type'] === 'badge'): ?>
        <span 
            class="status-badge" 
            :class="'status-' + (row.<?php echo e($column['key']); ?> || 'default').toLowerCase().replace(/\s+/g, '-')"
            x-text="row.<?php echo e($column['key']); ?> || 'N/A'"
        ></span>
        <?php else: ?>
        <span class="cell-text" x-text="row.<?php echo e($column['key']); ?> || 'N/A'"></span>
        <?php endif; ?>
    </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
    <!-- Actions Column -->
    <?php if($actions && count($actions) > 0): ?>
    <td class="actions-cell">
        <?php echo $__env->make('components.tables.actions', ['actions' => $actions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </td>
    <?php endif; ?>
</tr>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/tables/row.blade.php ENDPATH**/ ?>