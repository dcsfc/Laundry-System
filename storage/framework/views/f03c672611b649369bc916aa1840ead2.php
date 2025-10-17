<?php $__env->startSection('title', 'Customer Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/status-badges.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-50 mb-2">Customer Dashboard</h1>
                <p class="text-slate-400">Track your laundry schedules, manage pickups, and view your service history.</p>
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-emerald-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Schedules</p>
                        <p class="text-slate-50 text-2xl font-bold"><?php echo e($totalOrders); ?></p>
                        <p class="text-emerald-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+<?php echo e($monthlyOrderGrowth); ?> this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-shopping-bag text-white text-lg"></i>
                    </div>
                </div>
                <div id="ordersSparkline" class="h-12"></div>
            </div>

            <!-- Completed Orders Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-teal-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Completed Schedules</p>
                        <p class="text-slate-50 text-2xl font-bold"><?php echo e($completedOrders); ?></p>
                        <p class="text-teal-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-check mr-1 text-xs"></i><?php echo e($completionRate); ?>% completion rate
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
                <div id="completedSparkline" class="h-12"></div>
            </div>

            <!-- Pending Orders Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-emerald-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">In Progress</p>
                        <p class="text-slate-50 text-2xl font-bold"><?php echo e($pendingOrders); ?></p>
                        <p class="text-emerald-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-clock mr-1 text-xs"></i>Processing now
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-spinner text-white text-lg"></i>
                    </div>
                </div>
                <div id="pendingSparkline" class="h-12"></div>
            </div>

            <!-- Total Spent Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-teal-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Spent</p>
                        <p class="text-slate-50 text-2xl font-bold">₱<?php echo e(number_format($totalSpent, 2)); ?></p>
                        <p class="text-teal-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+<?php echo e($monthlyGrowth); ?>% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-dollar-sign text-white text-lg"></i>
                    </div>
                </div>
                <div id="spentSparkline" class="h-12"></div>
            </div>
        </div>

        <!-- Announcements Section -->
        <div class="mb-8">
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-50">Important Announcements</h3>
                    </div>
                    <?php if($announcements && count($announcements) > 0): ?>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-sm text-emerald-400 font-medium"><?php echo e(count($announcements)); ?> announcement<?php echo e(count($announcements) !== 1 ? 's' : ''); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Announcements List -->
                <?php if($announcements && count($announcements) > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $announcements->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="group bg-slate-700/30 border border-slate-600/50 rounded-lg p-4 hover:bg-slate-700/50 hover:border-slate-600/70 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer" 
                                 onclick="openAnnouncementModal(<?php echo e($announcement->id); ?>)">
                                <div class="flex items-start gap-3">
                                    <!-- Status Indicator -->
                                    <div class="flex-shrink-0 mt-1">
                                        <?php if($announcement->is_pinned): ?>
                                            <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                                        <?php else: ?>
                                            <div class="w-2 h-2 bg-emerald-400 rounded-full opacity-60"></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Title and Badges -->
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <h4 class="text-slate-50 font-semibold text-sm leading-tight group-hover:text-emerald-400 transition-colors">
                                                <?php echo e($announcement->title); ?>

                                            </h4>
                                            <div class="flex items-center gap-1 flex-shrink-0">
                                                <?php if($announcement->is_pinned): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        Pinned
                                                    </span>
                                                <?php endif; ?>
                                                <?php if($announcement->type): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($announcement->type_badge_class); ?>">
                                                        <?php echo e(ucfirst($announcement->type)); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Message Preview -->
                                        <p class="text-slate-400 text-xs leading-relaxed mb-3 line-clamp-2">
                                            <?php echo e(Str::limit($announcement->message, 120)); ?>

                                        </p>
                                        
                                        <!-- Meta Information -->
                                        <div class="flex items-center gap-3 text-xs text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <?php echo e($announcement->createdBy->name ?? 'System'); ?>

                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <?php echo e($announcement->created_at->diffForHumans()); ?>

                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Read More Indicator -->
                                    <?php if(strlen($announcement->message) > 120): ?>
                                        <div class="flex-shrink-0 mt-1">
                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-6 pt-4 border-t border-slate-700">
                        <a href="<?php echo e(route('customer.announcements')); ?>" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 text-sm font-medium transition-colors group">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            View all announcements
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <h4 class="text-slate-300 font-medium mb-2">No announcements yet</h4>
                        <p class="text-slate-500 text-sm">Check back soon for important updates and news.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Order History Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Order History</h3>
                    <div class="flex items-center gap-3">
                        <select class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-slate-200 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                            <option>Last 6 months</option>
                            <option>Last year</option>
                            <option>All time</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-emerald-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Order trend</span>
                        </div>
                    </div>
                </div>
                <div id="orderHistoryChart" class="h-72"></div>
            </div>

            <!-- Service Usage Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Service Usage</h3>
                    <div class="flex items-center gap-3">
                        <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-teal-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Service breakdown</span>
                        </div>
                    </div>
                </div>
                <div id="serviceUsageChart" class="h-72"></div>
            </div>
        </div>

        <!-- Activity Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Recent Schedules</h3>
                    <a href="<?php echo e(route('customer.schedules.index')); ?>" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // Use consistent status colors - simplified approach
                            $statusColors = \App\Helpers\StatusHelper::getStatusColor($order['status']);
                            $statusColor = $statusColors['badge'];
                            $dotColor = $statusColors['dot'];
                            
                            // Set border color based on status
                            $borderColor = 'border-slate-400'; // Default
                            if (str_contains($statusColor, 'yellow')) {
                                $borderColor = 'border-yellow-400';
                            } elseif (str_contains($statusColor, 'green')) {
                                $borderColor = 'border-green-400';
                            } elseif (str_contains($statusColor, 'blue')) {
                                $borderColor = 'border-blue-400';
                            } elseif (str_contains($statusColor, 'purple')) {
                                $borderColor = 'border-purple-400';
                            } elseif (str_contains($statusColor, 'red')) {
                                $borderColor = 'border-red-400';
                            } elseif (str_contains($statusColor, 'gray')) {
                                $borderColor = 'border-gray-400';
                            }
                        ?>
                        <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 <?php echo e($borderColor); ?>">
                            <div class="w-2 h-2 <?php echo e($dotColor); ?> rounded-full mt-2"></div>
                            <div class="flex-1">
                                <h4 class="text-slate-50 font-medium">Order #<?php echo e($order['id']); ?> - <?php echo e($order['status']); ?></h4>
                                <p class="text-slate-400 text-sm"><?php echo e($order['service_type']); ?> - ₱<?php echo e($order['total_price']); ?></p>
                                <p class="text-slate-500 text-xs mt-1"><?php echo e($order['created_at']); ?></p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border <?php echo e($statusColor); ?>"><?php echo e($order['status']); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-bag text-4xl text-slate-500 mb-4"></i>
                            <h3 class="text-lg font-medium text-slate-300 mb-2">No recent orders</h3>
                            <p class="text-slate-400">You haven't placed any orders yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upcoming Schedules -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Upcoming Schedules</h3>
                    <button class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                        Schedule new
                    </button>
                </div>
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $upcomingSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // Use consistent status colors - same as Recent Schedules
                            $statusColors = \App\Helpers\StatusHelper::getStatusColor($schedule['status']);
                            $statusColor = $statusColors['badge'];
                            $dotColor = $statusColors['dot'];
                            
                            // Set border color based on status
                            $borderColor = 'border-slate-400'; // Default
                            if (str_contains($statusColor, 'yellow')) {
                                $borderColor = 'border-yellow-400';
                            } elseif (str_contains($statusColor, 'green')) {
                                $borderColor = 'border-green-400';
                            } elseif (str_contains($statusColor, 'blue')) {
                                $borderColor = 'border-blue-400';
                            } elseif (str_contains($statusColor, 'purple')) {
                                $borderColor = 'border-purple-400';
                            } elseif (str_contains($statusColor, 'red')) {
                                $borderColor = 'border-red-400';
                            } elseif (str_contains($statusColor, 'gray')) {
                                $borderColor = 'border-gray-400';
                            }
                        ?>
                        <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 <?php echo e($borderColor); ?>">
                            <div class="w-2 h-2 <?php echo e($dotColor); ?> rounded-full mt-2"></div>
                            <div class="flex-1">
                                <h4 class="text-slate-50 font-medium">Order #<?php echo e($schedule['id']); ?> - <?php echo e($schedule['status']); ?></h4>
                                <p class="text-slate-400 text-sm"><?php echo e($schedule['service_type']); ?> - Drop-off: <?php echo e($schedule['dropoff_date']); ?></p>
                                <p class="text-slate-500 text-xs mt-1">Pickup: <?php echo e($schedule['pickup_date']); ?></p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border <?php echo e($statusColor); ?>"><?php echo e($schedule['status']); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-alt text-4xl text-slate-500 mb-4"></i>
                            <h3 class="text-lg font-medium text-slate-300 mb-2">No upcoming schedules</h3>
                            <p class="text-slate-400">You don't have any scheduled appointments.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
            <h3 class="text-xl font-semibold text-slate-50 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">New Order</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Schedule laundry service</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-teal-500 hover:border-teal-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar text-teal-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Schedule Pickup</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Arrange pickup time</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-history text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Order History</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">View past orders</p>
                    </div>
                </button>

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

        // Order History Chart with enhanced animations
        const orderHistoryOptions = {
            series: [{
                name: 'Orders',
                data: <?php echo json_encode($orderHistoryData['data'], 15, 512) ?>
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
            colors: ['#10B981'],
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
                categories: <?php echo json_encode($orderHistoryData['categories'], 15, 512) ?>,
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
                        return val + " orders"
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#10B981'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };

        // Service Usage Chart with enhanced styling
        const serviceUsageOptions = {
            series: <?php echo json_encode($serviceUsageData['data'], 15, 512) ?>,
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
            colors: ['#10B981', '#14B8A6', '#059669', '#047857', '#065F46', '#064E3B'],
            labels: <?php echo json_encode($serviceUsageData['labels'], 15, 512) ?>,
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
                                label: 'Total Orders',
                                color: '#F8FAFC',
                                fontSize: '14px',
                                fontWeight: 600,
                                fontFamily: 'Inter, sans-serif',
                                formatter: function (w) {
                                    return '<?php echo e($serviceUsageData["total"]); ?>'
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
                        return val + ' orders (' + percentage + '%)';
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
            const orderHistoryChart = new ApexCharts(document.querySelector("#orderHistoryChart"), orderHistoryOptions);
            orderHistoryChart.render();

            const serviceUsageChart = new ApexCharts(document.querySelector("#serviceUsageChart"), serviceUsageOptions);
            serviceUsageChart.render();

            // Sparkline charts for KPI cards
            const ordersSparkline = new ApexCharts(document.querySelector("#ordersSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($sparklineData['orders'], 15, 512) ?>
                }],
                colors: ['#10B981']
            });
            ordersSparkline.render();

            const completedSparkline = new ApexCharts(document.querySelector("#completedSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($sparklineData['completed'], 15, 512) ?>
                }],
                colors: ['#14B8A6']
            });
            completedSparkline.render();

            const pendingSparkline = new ApexCharts(document.querySelector("#pendingSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($sparklineData['pending'], 15, 512) ?>
                }],
                colors: ['#10B981']
            });
            pendingSparkline.render();

            const spentSparkline = new ApexCharts(document.querySelector("#spentSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: <?php echo json_encode($sparklineData['spent'], 15, 512) ?>
                }],
                colors: ['#14B8A6']
            });
            spentSparkline.render();

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });

        // Announcement modal functionality
        function openAnnouncementModal(announcementId) {
            // Option 1: Redirect to announcements page with anchor (current implementation)
            window.location.href = `<?php echo e(route('customer.announcements')); ?>#announcement-${announcementId}`;
            
            // Option 2: Open modal (uncomment to use instead of redirect)
            // fetch(`/api/announcements/${announcementId}`)
            //     .then(response => response.json())
            //     .then(data => {
            //         showAnnouncementModal(data);
            //     })
            //     .catch(error => {
            //         console.error('Error fetching announcement:', error);
            //         // Fallback to redirect
            //         window.location.href = `<?php echo e(route('customer.announcements')); ?>#announcement-${announcementId}`;
            //     });
        }
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
            outline: 2px solid #10B981;
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

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Announcement card hover effects */
        .announcement-card {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .announcement-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/dashboard/customer.blade.php ENDPATH**/ ?>