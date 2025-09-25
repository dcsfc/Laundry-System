<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'columns' => [],
    'data' => [],
    'tableData' => [],
    'actions' => [],
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'emptyMessage' => 'No data available',
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'colorScheme' => 'blue',
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
    'tableData' => [],
    'actions' => [],
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'emptyMessage' => 'No data available',
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'colorScheme' => 'blue',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Use tableData if provided, otherwise fall back to data
    $actualData = !empty($tableData) ? $tableData : $data;
?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/data-table.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/table-headers.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/data-table.js')); ?>"></script>
<script src="<?php echo e(asset('js/table-headers.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php
    // Ensure columns have proper structure
    $columns = collect($columns)->map(function($column) {
        if (is_string($column)) {
            return ['key' => $column, 'label' => ucfirst($column)];
        }
        return array_merge(['key' => 'id', 'label' => 'Column'], $column);
    })->toArray();
    
    // Ensure actions have proper structure
    $actions = collect($actions)->map(function($action) {
        if (is_string($action)) {
            return ['key' => $action, 'label' => ucfirst($action)];
        }
        // Handle actions with onclick property
        if (isset($action['onclick'])) {
            return [
                'key' => $action['onclick'],
                'label' => $action['label'] ?? ucfirst($action['onclick']),
                'onclick' => $action['onclick']
            ];
        }
        return array_merge(['key' => 'action', 'label' => 'Action'], $action);
    })->toArray();
    
    // Ensure data is properly formatted - more robust approach
    if (is_null($actualData)) {
        $actualData = [];
    } elseif (!is_array($actualData)) {
        $actualData = collect($actualData)->toArray();
    }
?>

<?php
    // Get user role for color scheme
    $userRole = Auth::user()->role->name ?? 'customer';
    $isSuperAdmin = $userRole === 'superadmin';
    $isAdmin = $userRole === 'administrator';
    $isStaff = $userRole === 'staff';
    $isCustomer = $userRole === 'customer';
    
    // Define color schemes based on role
    if ($isSuperAdmin) {
        $primaryColor = 'indigo';
        $secondaryColor = 'purple';
        $accentColor = 'indigo';
        $gradientFrom = 'from-indigo-600';
        $gradientTo = 'to-purple-600';
        $gradientHoverFrom = 'from-indigo-500/20';
        $gradientHoverTo = 'to-purple-500/20';
        $shadowColor = 'indigo';
        $textColor = 'indigo';
    } elseif ($isAdmin || $isStaff) {
        $primaryColor = 'sky';
        $secondaryColor = 'cyan';
        $accentColor = 'sky';
        $gradientFrom = 'from-sky-600';
        $gradientTo = 'to-cyan-600';
        $gradientHoverFrom = 'from-sky-500/20';
        $gradientHoverTo = 'to-cyan-500/20';
        $shadowColor = 'sky';
        $textColor = 'sky';
    } else {
        $primaryColor = 'emerald';
        $secondaryColor = 'teal';
        $accentColor = 'emerald';
        $gradientFrom = 'from-emerald-500';
        $gradientTo = 'to-teal-600';
        $gradientHoverFrom = 'from-emerald-500/20';
        $gradientHoverTo = 'to-teal-500/20';
        $shadowColor = 'emerald';
        $textColor = 'emerald';
    }
?>

    <div 
        x-data="dataTable(<?php echo e(json_encode($actualData)); ?>, <?php echo e(json_encode($columns)); ?>, <?php echo e(json_encode($actions)); ?>, <?php echo e($pageSize); ?>)"
    x-init="init()"
        class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 backdrop-blur-sm"
    >
    
    <div class="px-8 py-6 bg-gradient-to-r <?php echo e($gradientFrom); ?> <?php echo e($gradientTo); ?> border-b border-<?php echo e($accentColor); ?>-500/20">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white tracking-tight whitespace-nowrap"><?php echo e($title); ?></h3>
                    <p class="text-sm text-white/80 mt-1 font-medium whitespace-nowrap"><?php echo e($description); ?></p>
                </div>
            </div>
            <button 
                @click="addNew()"
                class="group inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm text-white text-sm font-semibold rounded-xl border border-white/20 hover:bg-white/20 hover:border-white/30 hover:shadow-lg hover:shadow-<?php echo e($shadowColor); ?>-500/25 transform hover:-translate-y-0.5 transition-all duration-300"
            >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New
            </button>
        </div>
    </div>

    
    <?php if($searchable): ?>
    <div class="px-8 py-6 bg-slate-700/50 border-b border-slate-600/50 backdrop-blur-sm">
        <div class="flex items-center space-x-4">
            
            <div class="flex-1 max-w-md">
                <div class="relative group">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input="search()"
                        placeholder="Search..."
                        class="w-full pl-12 pr-4 py-3.5 bg-slate-800/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-<?php echo e($accentColor); ?>-500 focus:border-<?php echo e($accentColor); ?>-500 text-slate-50 placeholder-slate-400 backdrop-blur-sm transition-all duration-300 text-sm font-medium"
                    >
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus:text-<?php echo e($accentColor); ?>-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button 
                        x-show="searchQuery"
                        @click="searchQuery = ''; search()"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-<?php echo e($accentColor); ?>-400 transition-colors"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            
            <div class="relative">
                <select 
                    x-model="statusFilter"
                    @change="filterByStatus()"
                    class="appearance-none bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3.5 pr-10 text-slate-50 text-sm font-medium focus:ring-2 focus:ring-<?php echo e($accentColor); ?>-500 focus:border-<?php echo e($accentColor); ?>-500 transition-all duration-300 min-w-[140px]"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            
            <div class="relative">
                <select 
                    x-model="roleFilter"
                    @change="filterByRole()"
                    class="appearance-none bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3.5 pr-10 text-slate-50 text-sm font-medium focus:ring-2 focus:ring-<?php echo e($accentColor); ?>-500 focus:border-<?php echo e($accentColor); ?>-500 transition-all duration-300 min-w-[140px]"
                >
                    <option value="">All Roles</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="administrator">Administrator</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="overflow-x-auto bg-slate-800 rounded-lg border border-slate-700 shadow-sm relative">
        <div class="py-2 px-4">
            <table class="min-w-full divide-y divide-slate-700">
            <thead class="bg-slate-700 border-b border-slate-600">
                <tr>
                    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th 
                        scope="col" 
                        class="px-6 py-4 text-left text-sm font-semibold text-slate-200 tracking-wide uppercase select-none whitespace-nowrap"
                    >
                        <?php if(isset($column['sortable']) && $column['sortable']): ?>
                        <button 
                            type="button" 
                            class="flex items-center gap-2 text-slate-300 hover:text-white focus:outline-none"
                            @click="sort('<?php echo e($column['key']); ?>')"
                        >
                            <span><?php echo e($column['label']); ?></span>

                            <!-- Sorting icons container -->
                            <div class="flex flex-col">
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="w-3 h-3"
                                    :class="{
                                        'text-blue-500': sortColumn === '<?php echo e($column['key']); ?>' && sortDirection === 'asc',
                                        'text-slate-400': sortColumn !== '<?php echo e($column['key']); ?>' || sortDirection !== 'asc'
                                    }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>

                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="w-3 h-3 -mt-1"
                                    :class="{
                                        'text-blue-500': sortColumn === '<?php echo e($column['key']); ?>' && sortDirection === 'desc',
                                        'text-slate-400': sortColumn !== '<?php echo e($column['key']); ?>' || sortDirection !== 'desc'
                                    }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                        <?php else: ?>
                        <span><?php echo e($column['label']); ?></span>
                        <?php endif; ?>
                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(count($actions) > 0): ?>
                        <th 
                            scope="col" 
                            class="px-6 py-4 text-sm font-semibold text-slate-200 uppercase whitespace-nowrap"
                        >
                        Actions
                    </th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                <!-- Loading Skeleton -->
                <template x-if="isLoading">
                    <template x-for="i in 5" :key="'skeleton-' + i">
                        <tr class="border-b border-slate-700">
                            <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="px-6 py-4">
                                <div class="h-4 bg-slate-700 rounded animate-pulse"></div>
                            </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(count($actions) > 0): ?>
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 bg-slate-700 rounded-lg animate-pulse"></div>
                            </td>
                            <?php endif; ?>
                        </tr>
                    </template>
                </template>
                
                <!-- Actual Data -->
                <template x-for="(row, index) in paginatedData" :key="row.id || index" x-show="!isLoading">
                    <tr class="hover:bg-slate-700/50 transition-colors duration-200 group">
                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                            <div class="flex items-center">
                                <?php if($column['key'] === 'status'): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold"
                                          :class="{
                                              'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30': row.status === 'Active',
                                              'bg-red-500/20 text-red-400 border border-red-500/30': row.status === 'Inactive',
                                              'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30': row.status === 'pending'
                                          }"
                                          x-text="row.status"></span>
                                <?php elseif($column['key'] === 'role'): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-<?php echo e($accentColor); ?>-500/20 text-<?php echo e($accentColor); ?>-400 border border-<?php echo e($accentColor); ?>-500/30"
                                          x-text="row.role"></span>
                                <?php elseif($column['key'] === 'created_at'): ?>
                                    <span class="text-slate-300 font-medium" x-text="new Date(row.created_at).toLocaleDateString()"></span>
                                <?php elseif($column['key'] === 'account_age'): ?>
                                    <span class="text-center block w-full text-slate-300 font-medium" x-text="row.account_age"></span>
                                <?php elseif($column['key'] === 'id'): ?>
                                    <span class="text-<?php echo e($accentColor); ?>-400 font-bold" x-text="row.<?php echo e($column['key'] ?? 'id'); ?>"></span>
                                <?php else: ?>
                                    <span class="text-slate-200 font-medium" x-text="row.<?php echo e($column['key'] ?? 'id'); ?>"></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(count($actions) > 0): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                            <div class="relative" x-data="{ 
                                open: false, 
                                position: 'bottom-left',
                                calculatePosition() {
                                    if (!this.$refs.button) return;
                                    
                                    const button = this.$refs.button;
                                    const rect = button.getBoundingClientRect();
                                    const viewportHeight = window.innerHeight;
                                    const viewportWidth = window.innerWidth;
                                    
                                    // Check available space
                                    const spaceBelow = viewportHeight - rect.bottom;
                                    const spaceAbove = rect.top;
                                    const spaceRight = viewportWidth - rect.right;
                                    const spaceLeft = rect.left;
                                    
                                    // Menu dimensions (approximate)
                                    const menuHeight = 200; // Approximate height for 4-5 menu items
                                    const menuWidth = 192; // w-48 = 12rem = 192px
                                    
                                    let verticalPos = 'bottom';
                                    let horizontalPos = 'left'; // Default to left positioning
                                    
                                    // Determine vertical position - prefer bottom, but use top if not enough space
                                    if (spaceBelow < menuHeight && spaceAbove > menuHeight) {
                                        verticalPos = 'top';
                                    }
                                    
                                    // Determine horizontal position - prefer left, but use right if not enough space
                                    // Add buffer to ensure menu doesn't get clipped
                                    if (spaceLeft < (menuWidth + 20) && spaceRight > (menuWidth + 20)) {
                                        horizontalPos = 'right';
                                    }
                                    
                                    this.position = `${verticalPos}-${horizontalPos}`;
                                },
                                init() {
                                    // Recalculate position on window resize
                                    window.addEventListener('resize', () => {
                                        if (this.open) {
                                            this.calculatePosition();
                                        }
                                    });
                                    
                                    // Recalculate position on scroll
                                    window.addEventListener('scroll', () => {
                                        if (this.open) {
                                            this.calculatePosition();
                                        }
                                    });
                                }
                            }">
                                <!-- 3 Dots Button -->
                                <button 
                                    x-ref="button"
                                    @click="open = !open; if (open) calculatePosition()"
                                    @click.away="open = false"
                                    class="inline-flex items-center justify-center w-8 h-8 text-slate-400 hover:text-slate-200 hover:bg-slate-600 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                >
                                    <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div 
                                    x-ref="menu"
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    :class="{
                                        'absolute right-0 mt-2': position === 'bottom-right',
                                        'absolute right-0 mb-2 bottom-full': position === 'top-right',
                                        'absolute left-0 mt-2': position === 'bottom-left',
                                        'absolute left-0 mb-2 bottom-full': position === 'top-left'
                                    }"
                                    class="w-48 bg-slate-800 rounded-lg shadow-xl border border-slate-700 max-h-64 overflow-y-auto action-dropdown"
                                >
                                    <div class="py-2">
                                        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button 
                                            @click="handleAction(row, '<?php echo e($action['key']); ?>'); open = false"
                                            class="w-full flex items-center px-4 py-3 text-sm text-slate-300 hover:text-white hover:bg-slate-700 transition-colors duration-200"
                                        >
                                            <?php if($action['key'] === 'viewUser'): ?>
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-<?php echo e($accentColor); ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            <?php elseif($action['key'] === 'editUser'): ?>
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-<?php echo e($accentColor); ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            <?php elseif($action['key'] === 'toggleUserStatus'): ?>
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-<?php echo e($accentColor); ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            <?php elseif($action['key'] === 'deleteUser'): ?>
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-<?php echo e($accentColor); ?>-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            <?php endif; ?>
                                            <span class="font-medium"><?php echo e($action['label']); ?></span>
                                        </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                </template>
            </tbody>
        </table>
        </div>
    </div>

    
    <div x-show="originalData.length === 0" class="text-center py-20">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br <?php echo e($gradientFrom); ?> <?php echo e($gradientTo); ?> rounded-2xl flex items-center justify-center shadow-2xl">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-100 mb-3">No data available</h3>
        <p class="text-sm text-slate-400 mb-8 max-w-md mx-auto"><?php echo e($emptyMessage); ?></p>
        <button 
            @click="addNew()"
            class="group inline-flex items-center px-6 py-3 bg-gradient-to-r <?php echo e($gradientFrom); ?> <?php echo e($gradientTo); ?> text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-<?php echo e($shadowColor); ?>-500/25 transform hover:-translate-y-0.5 transition-all duration-300"
        >
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add First Record
        </button>
    </div>

    
    <div x-show="originalData.length > 0 && filteredData.length === 0" class="text-center py-20">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br <?php echo e($gradientFrom); ?> <?php echo e($gradientTo); ?> rounded-2xl flex items-center justify-center shadow-2xl">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-100 mb-3">No results found</h3>
        <p class="text-sm text-slate-400 mb-8 max-w-md mx-auto">Try adjusting your search or filter criteria</p>
        <button 
            @click="searchQuery = ''; search()"
            class="group inline-flex items-center px-6 py-3 bg-slate-700 text-slate-200 text-sm font-semibold rounded-xl border border-slate-600 hover:bg-slate-600 hover:border-<?php echo e($accentColor); ?>-500/50 transition-all duration-300"
        >
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Clear Search
        </button>
    </div>

    
    <?php if($pagination): ?>
    <div class="px-8 py-6 bg-gradient-to-r from-slate-700 to-slate-600 border-t border-slate-600 backdrop-blur-sm">
        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-300">
                Showing <span class="font-bold text-white" x-text="startRecord">0</span> to 
                <span class="font-bold text-white" x-text="endRecord">0</span> of 
                <span class="font-bold text-white" x-text="totalRecords">0</span> results
            </div>
            <div class="flex items-center space-x-2">
                <button 
                    @click="previousPage()"
                    :disabled="currentPage === 1"
                    class="group inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-300 bg-slate-800/50 border border-slate-600 rounded-xl hover:bg-slate-700 hover:text-white hover:border-<?php echo e($accentColor); ?>-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                >
                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Previous
                </button>
                
                <div class="flex items-center space-x-1">
                    <template x-for="page in getPageNumbers()" :key="page">
                        <button 
                            @click="goToPage(page)"
                            class="px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300"
                            :class="page === currentPage ? 
                                'bg-gradient-to-r <?php echo e($gradientFrom); ?> <?php echo e($gradientTo); ?> text-white shadow-lg shadow-<?php echo e($shadowColor); ?>-500/25' : 
                                'text-slate-300 hover:bg-slate-700 hover:text-white hover:border hover:border-<?php echo e($accentColor); ?>-500/50'"
                            x-text="page"
                        ></button>
                    </template>
                </div>
                
                <button 
                    @click="nextPage()"
                    :disabled="currentPage === totalPages"
                    class="group inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-300 bg-slate-800/50 border border-slate-600 rounded-xl hover:bg-slate-700 hover:text-white hover:border-<?php echo e($accentColor); ?>-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                >
                    Next
                    <svg class="w-4 h-4 ml-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/data-table.blade.php ENDPATH**/ ?>