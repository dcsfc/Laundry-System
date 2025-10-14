<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['log']));

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

foreach (array_filter((['log']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bg-slate-800 border border-slate-700 rounded-lg p-3 hover:bg-slate-750 transition-colors duration-200">
    <div class="flex items-start space-x-3">
        <!-- Simple Action Icon -->
        <div class="flex-shrink-0">
            <div class="w-6 h-6 rounded flex items-center justify-center text-white text-xs
                <?php if(str_contains($log->action, 'CREATED')): ?> bg-green-500
                <?php elseif(str_contains($log->action, 'DELETED')): ?> bg-red-500
                <?php elseif(str_contains($log->action, 'UPDATED')): ?> bg-blue-500
                <?php elseif(str_contains($log->action, 'LOGIN')): ?> bg-indigo-500
                <?php else: ?> bg-slate-500
                <?php endif; ?>">
                <?php if(str_contains($log->action, 'CREATED')): ?>
                    <i class="fas fa-plus"></i>
                <?php elseif(str_contains($log->action, 'DELETED')): ?>
                    <i class="fas fa-trash"></i>
                <?php elseif(str_contains($log->action, 'UPDATED')): ?>
                    <i class="fas fa-edit"></i>
                <?php elseif(str_contains($log->action, 'LOGIN')): ?>
                    <i class="fas fa-sign-in-alt"></i>
                <?php else: ?>
                    <i class="fas fa-cog"></i>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
            <!-- Header -->
            <div class="flex items-center justify-between mb-1">
                <span class="text-sm font-medium text-slate-300">
                    <?php echo e(str_replace('_', ' ', $log->action)); ?>

                </span>
                <span class="text-xs text-slate-500">
                    <?php echo e($log->created_at->diffForHumans()); ?>

                </span>
            </div>
            
            <!-- Description -->
            <div class="mb-2">
                <div class="text-slate-200 text-sm line-clamp-2">
                    <?php echo Str::limit(strip_tags($log->description), 60); ?>

                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-medium"><?php echo e($log->user ? $log->user->name : 'System'); ?></span>
                <span><?php echo e($log->created_at->format('H:i')); ?></span>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/dashboard-audit-log-item.blade.php ENDPATH**/ ?>