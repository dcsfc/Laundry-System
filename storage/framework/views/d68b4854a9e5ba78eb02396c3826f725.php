<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'columns' => [],
    'items' => [],  // Renamed from 'data' to 'items' to avoid conflict with data-* attributes
    'actions' => [],
    'bulkActions' => false,
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'pageSizeOptions' => [10, 25, 50, 100],
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'emptyMessage' => 'No data found',
    'emptyDescription' => 'Start by adding your first item to the system.',
    'colorScheme' => 'sky',
    'showRoleFilter' => false,
    'availableRoles' => [],
    'customClass' => 'bg-slate-800 text-slate-200',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'stickyHeader' => true,
    'addButton' => false,
    'addButtonLabel' => 'Add New Item',
    'addButtonAction' => 'addItem',
    'formType' => 'default'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'columns' => [],
    'items' => [],  // Renamed from 'data' to 'items' to avoid conflict with data-* attributes
    'actions' => [],
    'bulkActions' => false,
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'pageSizeOptions' => [10, 25, 50, 100],
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'emptyMessage' => 'No data found',
    'emptyDescription' => 'Start by adding your first item to the system.',
    'colorScheme' => 'sky',
    'showRoleFilter' => false,
    'availableRoles' => [],
    'customClass' => 'bg-slate-800 text-slate-200',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'stickyHeader' => true,
    'addButton' => false,
    'addButtonLabel' => 'Add New Item',
    'addButtonAction' => 'addItem',
    'formType' => 'default'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // CRITICAL: Ensure items are properly handled
    // Process the items
    if (is_array($items)) {
        $tableData = $items;
    } elseif ($items instanceof \Illuminate\Support\Collection) {
        $tableData = $items->toArray();
    } else {
        $tableData = [];
    }
    
    // Color scheme mapping
    $colorClasses = [
        'sky' => [
            'primary' => 'from-sky-600 to-cyan-600',
            'accent' => 'sky-400',
            'hover' => 'sky-500',
            'bg' => 'sky-500/20',
            'border' => 'sky-500/30'
        ],
        'indigo' => [
            'primary' => 'from-indigo-600 to-purple-600',
            'accent' => 'indigo-400',
            'hover' => 'indigo-500',
            'bg' => 'indigo-500/20',
            'border' => 'indigo-500/30'
        ],
        'emerald' => [
            'primary' => 'from-emerald-500 to-teal-600',
            'accent' => 'emerald-400',
            'hover' => 'emerald-500',
            'bg' => 'emerald-500/20',
            'border' => 'emerald-500/30'
        ]
    ];
    
    $colors = $colorClasses[$colorScheme] ?? $colorClasses['sky'];
?>

<!-- DEBUG INFO -->
<!-- RECEIVED $items: type=<?php echo e(gettype($items)); ?>, count=<?php echo e(is_countable($items) ? count($items) : 'N/A'); ?> -->
<!-- PROCESSED $tableData: count=<?php echo e(count($tableData)); ?> -->
<!-- First item: <?php echo e(count($tableData) > 0 ? substr(json_encode($tableData[0]), 0, 200) : 'EMPTY'); ?> -->
<!-- END DEBUG -->

<div class="table-container <?php echo e($customClass); ?>" 
     x-data="dataTable(<?php echo e(json_encode($tableData)); ?>, <?php echo e(json_encode($columns)); ?>, <?php echo e(json_encode($actions)); ?>, <?php echo e($pageSize); ?>)"
     data-color-scheme="<?php echo e($colorScheme); ?>"
     data-datatable>

    <!-- Header -->
    <div class="table-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-table text-<?php echo e($colors['accent']); ?>"></i>
                </div>
                <div class="header-text">
                    <h2 class="table-title"><?php echo e($title); ?></h2>
                    <p class="table-description"><?php echo e($description); ?></p>
                </div>
            </div>

            <?php if($addButton): ?>
            <div class="header-actions">
                <button class="btn btn-primary bg-gradient-to-r <?php echo e($colors['primary']); ?> hover:opacity-90" 
                        @click="<?php echo e($addButtonAction); ?>()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <?php echo e($addButtonLabel); ?>

                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search and Filters -->
    <?php if($searchable): ?>
        <?php echo $__env->make('components.tables.search-filters', [
            'showRoleFilter' => $showRoleFilter,
            'availableRoles' => $availableRoles,
            'colorScheme' => $colorScheme
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <!-- Table -->
    <div class="table-scroll-container">
        <table class="data-table">
            <thead class="table-head">
                <tr>
                    <!-- Headers with sorting -->
                    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="table-header-cell">
                        <?php if($sortable): ?>
                        <button type="button" class="sort-button" @click="sort('<?php echo e($column['key']); ?>')">
                            <?php echo e($column['label']); ?>

                            <?php echo $__env->make('components.tables.sort-arrows', ['column' => $column['key']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </button>
                        <?php else: ?>
                        <?php echo e($column['label']); ?>

                        <?php endif; ?>
                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if($actions && count($actions) > 0): ?>
                    <th class="actions-header">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <!-- Loading State - only show when no data exists AND loading -->
                <template x-if="isLoading && paginatedData.length === 0">
                    <?php echo $__env->make('components.tables.loading-row', [
                        'columns' => $columns, 
                        'bulkActions' => $bulkActions, 
                        'actions' => $actions
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </template>

                <!-- Data Rows -->
                <template x-for="(row, index) in paginatedData" :key="row.id || index">
                    <?php echo $__env->make('components.tables.row', [
                        'columns' => $columns, 
                        'actions' => $actions, 
                        'bulkActions' => $bulkActions,
                        'colorScheme' => $colorScheme
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </template>
            </tbody>

            <!-- Empty State -->
            <tfoot x-show="paginatedData.length === 0 && !isLoading">
                <tr>
                    <td :colspan="<?php echo e(count($columns) + (count($actions) > 0 ? 1 : 0)); ?>" class="empty-content">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3><?php echo e($emptyMessage); ?></h3>
                        <p><?php echo e($emptyDescription); ?></p>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($pagination): ?>
        <?php echo $__env->make('components.tables.pagination', [
            'itemName' => strtolower($title),
            'pageSizeOptions' => $pageSizeOptions,
            'colorScheme' => $colorScheme
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
</div>



<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/data-table.blade.php ENDPATH**/ ?>