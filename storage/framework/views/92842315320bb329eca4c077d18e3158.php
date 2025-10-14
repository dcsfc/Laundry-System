<!-- Loading Row -->
<template x-for="i in pageSize" :key="i">
    <tr class="loading-row">
        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td class="data-cell">
            <div class="skeleton skeleton-text"></div>
        </td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <?php if($actions): ?>
        <td class="actions-cell">
            <div class="skeleton skeleton-actions"></div>
        </td>
        <?php endif; ?>
    </tr>
</template>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/tables/loading-row.blade.php ENDPATH**/ ?>