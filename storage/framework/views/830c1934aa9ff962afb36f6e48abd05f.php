<!-- Table Row -->
<tr class="data-row" :class="{ 'opacity-50 pointer-events-none': rowLoadingId === row.id }">
    
    <!-- Data Columns -->
    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td class="data-cell" :class="{ 'relative': rowLoadingId === row.id }">
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
        <!-- Show spinner when this specific row is loading -->
        <div x-show="rowLoadingId === row.id" class="flex items-center justify-center">
            <svg class="animate-spin h-5 w-5 text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <!-- Show actions when row is not loading -->
        <div x-show="rowLoadingId !== row.id">
            <?php echo $__env->make('components.tables.actions', ['actions' => $actions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </td>
    <?php endif; ?>
</tr>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/tables/row.blade.php ENDPATH**/ ?>