<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'columns' => [],
    'data' => [],
    'actions' => [],
    'bulkActions' => false,
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'currentPage' => 1,
    'pageSizeOptions' => [10, 25, 50, 100],
    'emptyMessage' => 'No data found',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'stickyHeader' => false,
    'customClass' => 'bg-slate-800 text-slate-200',
    'title' => null,
    'description' => null,
    'addButton' => false,
    'addButtonLabel' => 'Add New',
    'addButtonAction' => 'addNew',
    'formType' => null,
    'colorScheme' => 'sky',
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
    'data' => [],
    'actions' => [],
    'bulkActions' => false,
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'currentPage' => 1,
    'pageSizeOptions' => [10, 25, 50, 100],
    'emptyMessage' => 'No data found',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'stickyHeader' => false,
    'customClass' => 'bg-slate-800 text-slate-200',
    'title' => null,
    'description' => null,
    'addButton' => false,
    'addButtonLabel' => 'Add New',
    'addButtonAction' => 'addNew',
    'formType' => null,
    'colorScheme' => 'sky',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
// Ensure data is always an array
if (!is_array($data)) {
    $data = collect($data)->toArray();
}

// Process columns to ensure they have proper structure
$processedColumns = collect($columns)->map(function($column) {
    if (is_string($column)) {
        return ['key' => $column, 'label' => ucfirst(str_replace('_', ' ', $column)), 'sortable' => true];
    }
    return array_merge(['key' => 'id', 'label' => 'Column', 'sortable' => true], $column);
})->toArray();

// Process actions
$processedActions = collect($actions)->map(function($action) {
    if (is_string($action)) {
        return ['key' => $action, 'label' => ucfirst($action), 'icon' => 'fas fa-edit'];
    }
    return array_merge(['key' => 'action', 'label' => 'Action', 'icon' => 'fas fa-edit'], $action);
})->toArray();
?>

<div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
    <?php if($title || $description || $addButton): ?>
    <div class="px-6 py-4 border-b border-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <?php if($title): ?>
                <h3 class="text-lg font-semibold text-slate-50"><?php echo e($title); ?></h3>
                <?php endif; ?>
                <?php if($description): ?>
                <p class="text-sm text-slate-400 mt-1"><?php echo e($description); ?></p>
                <?php endif; ?>
            </div>
            <?php if($addButton): ?>
            <button class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i><?php echo e($addButtonLabel); ?>

            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($searchable): ?>
    <div class="px-6 py-4 border-b border-slate-700">
        <div class="flex items-center gap-4">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input type="text" 
                       class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:ring-2 focus:ring-sky-400 focus:border-sky-400" 
                       placeholder="Search...">
            </div>
            <select class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-slate-200 focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                <?php $__currentLoopData = $pageSizeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($size); ?>"><?php echo e($size); ?> per page</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-700/50">
                <tr>
                    <?php if($bulkActions): ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-slate-600 bg-slate-700 text-sky-500 focus:ring-sky-400">
                    </th>
                    <?php endif; ?>
                    
                    <?php $__currentLoopData = $processedColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        <?php if($sortable && ($column['sortable'] ?? true)): ?>
                        <button class="flex items-center gap-1 hover:text-slate-100 transition-colors">
                            <?php echo e($column['label']); ?>

                            <div class="flex flex-col">
                                <i class="fas fa-chevron-up text-xs opacity-50"></i>
                                <i class="fas fa-chevron-down text-xs opacity-50"></i>
                            </div>
                        </button>
                        <?php else: ?>
                        <?php echo e($column['label']); ?>

                        <?php endif; ?>
                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if(count($processedActions) > 0): ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                        Actions
                    </th>
                    <?php endif; ?>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-slate-700">
                <?php if(count($data) > 0): ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <?php if($bulkActions): ?>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="rounded border-slate-600 bg-slate-700 text-sky-500 focus:ring-sky-400">
                        </td>
                        <?php endif; ?>
                        
                        <?php $__currentLoopData = $processedColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                            <?php
                                $value = data_get($row, $column['key'], 'N/A');
                                
                                // Format different data types
                                if (is_array($value)) {
                                    $value = json_encode($value);
                                } elseif (is_object($value)) {
                                    $value = $value->name ?? $value->id ?? 'N/A';
                                } elseif (is_bool($value)) {
                                    $value = $value ? 'Yes' : 'No';
                                } elseif (is_null($value)) {
                                    $value = 'N/A';
                                }
                            ?>
                            
                            <?php if($column['key'] === 'status'): ?>
                                <?php
                                    $statusColors = [
                                        'active' => 'bg-emerald-500/20 text-emerald-400',
                                        'inactive' => 'bg-slate-500/20 text-slate-400',
                                        'pending' => 'bg-amber-500/20 text-amber-400',
                                        'completed' => 'bg-sky-500/20 text-sky-400',
                                        'cancelled' => 'bg-red-500/20 text-red-400',
                                    ];
                                    $statusClass = $statusColors[strtolower($value)] ?? 'bg-slate-500/20 text-slate-400';
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                                    <?php echo e(ucfirst($value)); ?>

                                </span>
                            <?php elseif(in_array($column['key'], ['created_at', 'updated_at', 'date'])): ?>
                                <?php echo e(\Carbon\Carbon::parse($value)->format('M d, Y')); ?>

                            <?php elseif(in_array($column['key'], ['price', 'amount', 'total'])): ?>
                                ₱<?php echo e(number_format($value, 2)); ?>

                            <?php else: ?>
                                <?php echo e($value); ?>

                            <?php endif; ?>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(count($processedActions) > 0): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <?php $__currentLoopData = $processedActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button class="text-sky-400 hover:text-sky-300 transition-colors" 
                                        title="<?php echo e($action['label']); ?>">
                                    <i class="<?php echo e($action['icon']); ?>"></i>
                                </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo e(count($processedColumns) + ($bulkActions ? 1 : 0) + (count($processedActions) > 0 ? 1 : 0)); ?>" 
                            class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl text-slate-500 mb-4"></i>
                                <h3 class="text-lg font-medium text-slate-300 mb-2">No data available</h3>
                                <p class="text-slate-500"><?php echo e($emptyMessage); ?></p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($pagination && count($data) > 10): ?>
    <div class="px-6 py-4 border-t border-slate-700">
        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-400">
                Showing 1 to <?php echo e(min(10, count($data))); ?> of <?php echo e(count($data)); ?> results
            </div>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1 text-sm bg-slate-700 border border-slate-600 rounded text-slate-300 hover:bg-slate-600 transition-colors">
                    Previous
                </button>
                <button class="px-3 py-1 text-sm bg-sky-500 text-white rounded">
                    1
                </button>
                <button class="px-3 py-1 text-sm bg-slate-700 border border-slate-600 rounded text-slate-300 hover:bg-slate-600 transition-colors">
                    Next
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/data-table.blade.php ENDPATH**/ ?>