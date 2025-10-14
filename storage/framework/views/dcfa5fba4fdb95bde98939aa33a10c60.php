<!-- Main Table Structure -->
<div class="table-scroll-container">
    <table class="data-table">
        <thead class="table-head">
            <tr>
                <!-- Headers with sorting -->
                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th class="table-header-cell">
                    <?php if($sortable ?? true): ?>
                    <button type="button" class="sort-button" @click="sort('<?php echo e($column['key']); ?>')">
                        <?php echo e($column['label']); ?>

                        <?php echo $__env->make('components.tables.sort-arrows', ['column' => $column['key']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </button>
                    <?php else: ?>
                    <?php echo e($column['label']); ?>

                    <?php endif; ?>
                </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <?php if($actions ?? false): ?>
                <th class="actions-header">Actions</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
            <!-- Loading State -->
            <template x-if="isLoading">
                <?php echo $__env->make('components.tables.loading-row', ['columns' => $columns, 'bulkActions' => $bulkActions ?? false, 'actions' => $actions ?? false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </template>

            <!-- Data Rows -->
            <template x-for="(row, index) in paginatedData" :key="row.id || index">
                <?php echo $__env->make('components.tables.row', ['columns' => $columns, 'actions' => $actions ?? [], 'bulkActions' => $bulkActions ?? false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </template>
        </tbody>

        <!-- Empty State -->
        <tfoot x-show="paginatedData.length === 0 && !isLoading">
            <tr>
                <td :colspan="<?php echo e(count($columns) + ($actions ? 1 : 0)); ?>" class="empty-content">
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3><?php echo e($emptyMessage ?? 'No data found'); ?></h3>
                    <p><?php echo e($emptyDescription ?? 'Start by adding your first item to the system.'); ?></p>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/tables/table.blade.php ENDPATH**/ ?>