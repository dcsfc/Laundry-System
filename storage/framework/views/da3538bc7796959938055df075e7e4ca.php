<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => null,
    'name' => 'name',
    'type' => 'text',
    'label' => 'Label',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'error' => false,
    'icon' => null
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
    'id' => null,
    'name' => 'name',
    'type' => 'text',
    'label' => 'Label',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'error' => false,
    'icon' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="floating-input-group <?php echo e($error ? 'error' : ''); ?>">
    <?php if($icon): ?>
        <div class="input-icon">
            <?php echo $icon; ?>

        </div>
    <?php endif; ?>
    
    <input 
        type="<?php echo e($type); ?>"
        id="<?php echo e($id ?? $name); ?>"
        name="<?php echo e($name); ?>"
        value="<?php echo e($value); ?>"
        placeholder=" "
        <?php echo e($required ? 'required' : ''); ?>

        class="floating-input <?php echo e($error ? 'error' : ''); ?>"
        <?php echo e($attributes); ?>

    >
    
    <label for="<?php echo e($id ?? $name); ?>" class="floating-label">
        <?php echo e($label); ?>

    </label>
    
    <?php if($error): ?>
        <span class="error-text"><?php echo e($error); ?></span>
    <?php endif; ?>
</div>

<style>
.floating-input-group {
    position: relative;
    margin-bottom: 1.5rem;
    width: 100%;
}

.floating-input {
    width: 100%;
    height: 3rem;
    padding: 1rem 1rem 0.5rem 2.75rem;
    border: 1px solid var(--border-color, #e0e0e0);
    border-radius: 0.25rem;
    background-color: var(--bg-secondary, #fff);
    color: var(--text-primary, #333);
    font-size: 1rem;
    font-weight: 400;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    line-height: 1.5;
}

.floating-input:focus {
    border-color: var(--border-focus, #1976d2);
    border-width: 2px;
    box-shadow: 0 0 0 1px var(--border-focus, #1976d2);
}

.floating-input:hover {
    border-color: var(--border-focus, #1976d2);
}

.floating-label {
    position: absolute;
    left: 2.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted, #999);
    font-size: 1rem;
    font-weight: 400;
    pointer-events: none;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
    background: transparent;
    padding: 0;
}

.floating-input:focus + .floating-label,
.floating-input:not(:placeholder-shown) + .floating-label {
    top: -0.5rem;
    left: 2.5rem;
    font-size: 0.75rem;
    color: var(--border-focus, #1976d2);
    font-weight: 500;
    background: var(--bg-secondary, #fff);
    padding: 0 0.25rem;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1rem;
    height: 1rem;
    color: var(--text-muted, #999);
    z-index: 2;
    transition: color 0.15s ease;
}

.floating-input:focus ~ .input-icon {
    color: var(--border-focus, #1976d2);
}

.error-text {
    font-size: 0.75rem;
    color: var(--error-color, #d32f2f);
    font-weight: 400;
    margin-top: 0.25rem;
    display: block;
}

.floating-input-group.error .floating-input {
    border-color: var(--error-color, #d32f2f);
    box-shadow: 0 0 0 1px var(--error-color, #d32f2f);
}

.floating-input-group.error .floating-label {
    color: var(--error-color, #d32f2f);
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .floating-input {
        font-size: 1rem;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
    }
    
    .floating-label {
        font-size: 0.875rem;
    }
    
    .floating-input:focus + .floating-label,
    .floating-input:not(:placeholder-shown) + .floating-label {
        font-size: 0.75rem;
    }
}
</style>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/floating-input.blade.php ENDPATH**/ ?>