

<?php $__env->startSection('title', 'Super Admin Dashboard'); ?>

<?php
    use Illuminate\Support\Str;
?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-50 mb-2">Dashboard</h1>
                <p class="text-slate-400">Monitor system health, user activity, and manage your laundry platform.</p>
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-indigo-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Users</p>
                        <p class="text-slate-50 text-2xl font-bold"><?php echo e(number_format($totalUsers)); ?></p>
                        <p class="text-indigo-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-<?php echo e($totalUsersGrowth >= 0 ? 'up' : 'down'); ?> mr-1 text-xs"></i><?php echo e($totalUsersGrowth >= 0 ? '+' : ''); ?><?php echo e($totalUsersGrowth); ?>% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <div id="usersSparkline" class="h-12"></div>
            </div>

            <!-- Administrators Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-purple-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Administrators</p>
                        <p class="text-slate-50 text-2xl font-bold"><?php echo e(number_format($admins)); ?></p>
                        <p class="text-purple-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-<?php echo e($adminsGrowth >= 0 ? 'up' : 'down'); ?> mr-1 text-xs"></i><?php echo e($adminsGrowth >= 0 ? '+' : ''); ?><?php echo e($adminsGrowth); ?>% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-shield text-white text-lg"></i>
                    </div>
                </div>
                <div id="adminsSparkline" class="h-12"></div>
            </div>

            <!-- Staff Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-indigo-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Staff</p>
                        <p class="text-slate-50 text-2xl font-bold"><?php echo e(number_format($staff)); ?></p>
                        <p class="text-indigo-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-<?php echo e($staffGrowth >= 0 ? 'up' : 'down'); ?> mr-1 text-xs"></i><?php echo e($staffGrowth >= 0 ? '+' : ''); ?><?php echo e($staffGrowth); ?>% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie text-white text-lg"></i>
                    </div>
                </div>
                <div id="staffSparkline" class="h-12"></div>
            </div>

            <!-- Customers Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-purple-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Customers</p>
                        <p class="text-slate-50 text-2xl font-bold"><?php echo e(number_format($customers)); ?></p>
                        <p class="text-purple-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-<?php echo e($customersGrowth >= 0 ? 'up' : 'down'); ?> mr-1 text-xs"></i><?php echo e($customersGrowth >= 0 ? '+' : ''); ?><?php echo e($customersGrowth); ?>% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                </div>
                <div id="customersSparkline" class="h-12"></div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- User Growth Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">User Growth</h3>
                    <div class="flex items-center gap-3">
                        <select class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-slate-200 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option>Last 6 months</option>
                            <option>Last year</option>
                            <option>All time</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-indigo-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Growth trend</span>
                        </div>
                    </div>
                </div>
                <div id="userGrowthChart" class="h-72"></div>
            </div>

            <!-- Role Distribution Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Role Distribution</h3>
                    <div class="flex items-center gap-3">
                        <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Current users</span>
                        </div>
                    </div>
                </div>
                <div id="roleDistributionChart" class="h-72"></div>
            </div>
        </div>

        <!-- System Governance Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Announcements -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Recent Announcements</h3>
                    <a href="<?php echo e(route('superadmin.announcements.index')); ?>" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $recentAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-slate-800 border border-slate-700 rounded-lg p-3 hover:bg-slate-750 transition-colors duration-200">
                            <div class="flex items-start space-x-3">
                                <!-- Simple Action Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded flex items-center justify-center text-white text-xs bg-indigo-500">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-slate-300">
                                            <?php echo e($announcement->title); ?>

                                        </span>
                                        <span class="text-xs text-slate-500">
                                            <?php echo e($announcement->created_at->diffForHumans()); ?>

                                        </span>
                                    </div>
                                    
                                    <!-- Description -->
                                    <div class="mb-2">
                                        <div class="text-slate-200 text-sm line-clamp-2">
                                            <?php echo e(Str::limit($announcement->message, 60)); ?>

                                        </div>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span class="font-medium"><?php echo e($announcement->createdBy ? $announcement->createdBy->name : 'System'); ?></span>
                                        <span><?php echo e($announcement->created_at->format('H:i')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex items-center justify-center p-8 text-slate-400">
                            <div class="text-center">
                                <i class="fas fa-bullhorn text-4xl mb-2"></i>
                                <p>No announcements yet</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Recent Activity</h3>
                    <a href="<?php echo e(route('superadmin.audit-logs.index')); ?>" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $recentAuditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if (isset($component)) { $__componentOriginalfb4ad35b1696b15efc82b5ab44443a78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb4ad35b1696b15efc82b5ab44443a78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-audit-log-item','data' => ['log' => $log]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-audit-log-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['log' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($log)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb4ad35b1696b15efc82b5ab44443a78)): ?>
<?php $attributes = $__attributesOriginalfb4ad35b1696b15efc82b5ab44443a78; ?>
<?php unset($__attributesOriginalfb4ad35b1696b15efc82b5ab44443a78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb4ad35b1696b15efc82b5ab44443a78)): ?>
<?php $component = $__componentOriginalfb4ad35b1696b15efc82b5ab44443a78; ?>
<?php unset($__componentOriginalfb4ad35b1696b15efc82b5ab44443a78); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex items-center justify-center p-8 text-slate-400">
                            <div class="text-center">
                                <i class="fas fa-history text-4xl mb-2"></i>
                                <p>No recent activity</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Latest Registered Users -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Latest Registered Users</h3>
                    <a href="<?php echo e(route('superadmin.users.index')); ?>" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-slate-800 border border-slate-700 rounded-lg p-3 hover:bg-slate-750 transition-colors duration-200">
                            <div class="flex items-start space-x-3">
                                <!-- Simple Action Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded flex items-center justify-center text-white text-xs
                                        <?php if($user->role->name === 'administrator'): ?> bg-indigo-500
                                        <?php elseif($user->role->name === 'staff'): ?> bg-emerald-500
                                        <?php else: ?> bg-amber-500
                                        <?php endif; ?>">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-slate-300">
                                            <?php echo e($user->name); ?>

                                        </span>
                                        <span class="text-xs text-slate-500">
                                            <?php echo e($user->created_at->diffForHumans()); ?>

                                        </span>
                                    </div>
                                    
                                    <!-- Description -->
                                    <div class="mb-2">
                                        <div class="text-slate-200 text-sm line-clamp-2">
                                            <?php echo e($user->email); ?>

                                        </div>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span class="font-medium"><?php echo e(ucfirst($user->role->name)); ?></span>
                                        <span><?php echo e($user->created_at->format('H:i')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex items-center justify-center p-8 text-slate-400">
                            <div class="text-center">
                                <i class="fas fa-users text-4xl mb-2"></i>
                                <p>No users registered yet</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Role Changes Activity -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Role Changes</h3>
                    <a href="<?php echo e(route('superadmin.audit-logs.index')); ?>" class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $roleChanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-slate-800 border border-slate-700 rounded-lg p-3 hover:bg-slate-750 transition-colors duration-200">
                            <div class="flex items-start space-x-3">
                                <!-- Simple Action Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded flex items-center justify-center text-white text-xs bg-amber-500">
                                        <i class="fas fa-exchange-alt"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-slate-300">
                                            <?php echo e($change['title']); ?>

                                        </span>
                                        <span class="text-xs text-slate-500">
                                            <?php echo e($change['time']); ?>

                                        </span>
                                    </div>
                                    
                                    <!-- Description -->
                                    <div class="mb-2">
                                        <div class="text-slate-200 text-sm line-clamp-2">
                                            <?php echo e($change['description']); ?>

                                        </div>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span class="font-medium"><?php echo e($change['admin']); ?></span>
                                        <span><?php echo e(now()->format('H:i')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex items-center justify-center p-8 text-slate-400">
                            <div class="text-center">
                                <i class="fas fa-exchange-alt text-4xl mb-2"></i>
                                <p>No role changes yet</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
            <h3 class="text-xl font-semibold text-slate-50 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="<?php echo e(route('superadmin.users.create')); ?>" class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-sky-500 hover:border-sky-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-sky-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-sky-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Add User</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Create new user account</p>
                    </div>
                </a>

                <a href="<?php echo e(route('superadmin.announcements.create')); ?>" class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-indigo-500 hover:border-indigo-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-bullhorn text-indigo-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Send Announcement</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Notify all users</p>
                    </div>
                </a>

                <a href="<?php echo e(route('superadmin.settings.index')); ?>" class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Manage Settings</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">System configuration</p>
                    </div>
                </a>

                <a href="<?php echo e(route('superadmin.audit-logs.index')); ?>" class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-purple-500 hover:border-purple-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-alt text-purple-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Audit Logs</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Monitor system activity</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        // Sparkline configurations for KPI cards
        const sparklineConfig = {
            chart: {
                type: 'area',
                sparkline: {
                    enabled: true
                },
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            stroke: {
                curve: 'smooth',
                width: 1.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.2,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            tooltip: {
                enabled: false
            },
            grid: {
                show: false
            },
            xaxis: {
                labels: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            }
        };

        // User Growth Chart with enhanced animations
        const userGrowthOptions = {
            series: [{
                name: 'Users',
                data: <?php echo json_encode(array_values($userGrowthData), 15, 512) ?>
            }],
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                },
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    }
                }
            },
            colors: ['#6366F1'],
            stroke: {
                curve: 'smooth',
                width: 3,
                lineCap: 'round'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            grid: {
                borderColor: 'rgba(148,163,184,0.2)',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            xaxis: {
                categories: <?php echo json_encode(array_keys($userGrowthData), 15, 512) ?>,
                axisBorder: {
                    color: '#334155'
                },
                axisTicks: {
                    color: '#334155'
                },
                labels: {
                    style: {
                        colors: '#94A3B8',
                        fontFamily: 'Inter, sans-serif'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#94A3B8',
                        fontFamily: 'Inter, sans-serif'
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                y: {
                    formatter: function (val) {
                        return val.toLocaleString() + " users"
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#6366F1'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };

        // Role Distribution Chart with enhanced styling
        const roleDistributionOptions = {
            series: <?php echo json_encode(array_values($roleDistribution), 15, 512) ?>,
            chart: {
                type: 'donut',
                height: 300,
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#6366F1', '#8B5CF6', '#A855F7'],
            labels: <?php echo json_encode(array_keys($roleDistribution), 15, 512) ?>,
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontFamily: 'Inter, sans-serif',
                labels: {
                    colors: '#94A3B8'
                },
                markers: {
                    width: 8,
                    height: 8,
                    radius: 2
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Users',
                                color: '#F8FAFC',
                                fontSize: '14px',
                                fontWeight: 600,
                                fontFamily: 'Inter, sans-serif',
                                formatter: function (w) {
                                    return '<?php echo e(number_format($totalUsers)); ?>'
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                y: {
                    formatter: function (val, { seriesIndex, w }) {
                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        const percentage = ((val / total) * 100).toFixed(1);
                        return val.toLocaleString() + ' users (' + percentage + '%)';
                    }
                }
            },
            dataLabels: {
                enabled: false
            }
        };

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Main charts
            const userGrowthChart = new ApexCharts(document.querySelector("#userGrowthChart"), userGrowthOptions);
            userGrowthChart.render();

            const roleDistributionChart = new ApexCharts(document.querySelector("#roleDistributionChart"), roleDistributionOptions);
            roleDistributionChart.render();

            // Sparkline charts for KPI cards
            const usersSparkline = new ApexCharts(document.querySelector("#usersSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($sparklineData, 15, 512) ?>
                }],
                colors: ['#6366F1']
            });
            usersSparkline.render();

            const adminsSparkline = new ApexCharts(document.querySelector("#adminsSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($adminsSparklineData, 15, 512) ?>
                }],
                colors: ['#8B5CF6']
            });
            adminsSparkline.render();

            const staffSparkline = new ApexCharts(document.querySelector("#staffSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($staffSparklineData, 15, 512) ?>
                }],
                colors: ['#A855F7']
            });
            staffSparkline.render();

            const customersSparkline = new ApexCharts(document.querySelector("#customersSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($customersSparklineData, 15, 512) ?>
                }],
                colors: ['#8B5CF6']
            });
            customersSparkline.render();


            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });
    </script>

    <!-- Custom CSS for enhanced animations and micro-interactions -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Enhanced hover effects for cards */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Smooth transitions for all interactive elements */
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0F172A;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Focus states for accessibility */
        input:focus, button:focus, select:focus {
            outline: 2px solid #38BDF8;
            outline-offset: 2px;
        }

        /* Loading animation for charts */
        .chart-loading {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        /* Enhanced table row hover effects */
        .group:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Status dot animations */
        .status-dot {
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Dashboard audit logs widget -->
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/dashboard/superadmin.blade.php ENDPATH**/ ?>