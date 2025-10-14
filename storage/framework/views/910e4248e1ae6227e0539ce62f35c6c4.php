<?php $__env->startSection('title', 'My Schedules'); ?>

<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
                <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent mb-3">My Schedules</h1>
                <p class="text-slate-400 text-lg">Manage your laundry pickup and delivery appointments</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="openScheduleModal()" 
                        class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-6 py-3 rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-emerald-500/25">
                    <i class="fas fa-plus mr-2"></i>
                    New Schedule
                    </button>
                </div>
            </div>
        </div>

    <!-- Summary Stats -->
    <?php if($allSchedules->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Pending Orders -->
            <div class="group bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 hover:border-amber-500/50 transition-all hover:shadow-lg hover:shadow-amber-500/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-amber-400 text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-amber-400 bg-amber-500/10 px-3 py-1 rounded-lg">Pending</span>
                </div>
                <div class="text-3xl font-bold text-white mb-1"><?php echo e($allSchedules->where('status', 'Pending Approval')->count()); ?></div>
                <div class="text-sm text-slate-400">Awaiting staff approval</div>
                </div>

            <!-- Confirmed Orders -->
            <div class="group bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 hover:border-blue-500/50 transition-all hover:shadow-lg hover:shadow-blue-500/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-blue-400 text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-blue-400 bg-blue-500/10 px-3 py-1 rounded-lg">Confirmed</span>
                </div>
                <div class="text-3xl font-bold text-white mb-1"><?php echo e($allSchedules->where('status', 'Confirmed')->count()); ?></div>
                <div class="text-sm text-slate-400">Confirmed orders</div>
                </div>

            <!-- In Progress Orders -->
            <div class="group bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 hover:border-orange-500/50 transition-all hover:shadow-lg hover:shadow-orange-500/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cogs text-orange-400 text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-orange-400 bg-orange-500/10 px-3 py-1 rounded-lg">Processing</span>
                </div>
                <div class="text-3xl font-bold text-white mb-1"><?php echo e($allSchedules->where('status', 'In Progress')->count()); ?></div>
                <div class="text-sm text-slate-400">Currently processing</div>
        </div>

            <!-- Ready for Pickup -->
            <div class="group bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 hover:border-green-500/50 transition-all hover:shadow-lg hover:shadow-green-500/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-box text-green-400 text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-400 bg-green-500/10 px-3 py-1 rounded-lg">Ready</span>
                </div>
                <div class="text-3xl font-bold text-white mb-1"><?php echo e($allSchedules->where('status', 'Ready for Pickup')->count()); ?></div>
                <div class="text-sm text-slate-400">Ready for pickup</div>
            </div>
        </div>
    <?php endif; ?>

        <!-- Schedules Table -->
    <div class="bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <?php if($schedules->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700/50">
                    <thead class="bg-slate-700/50 border-b border-slate-600/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-hashtag text-slate-400"></i>
                                    Order ID
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-slate-400"></i>
                                    Customer
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-shopping-basket text-slate-400"></i>
                                    Service
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-arrow-down text-slate-400"></i>
                                    Drop-off
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-arrow-up text-slate-400"></i>
                                    Pickup
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-weight text-slate-400"></i>
                                    Weight
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-peso-sign text-slate-400"></i>
                                    Price
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-info-circle text-slate-400"></i>
                                    Status
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user-check text-slate-400"></i>
                                    Approved-by
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fas fa-cog text-slate-400"></i>
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-800/50 divide-y divide-slate-700/50">
                        <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-700/30 transition-all duration-200 group" data-schedule-id="<?php echo e($schedule['id']); ?>">
                                <!-- ID -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-slate-700/50 flex items-center justify-center">
                                                <span class="text-xs font-medium text-slate-300">#<?php echo e($schedule['id']); ?></span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-white">Order #<?php echo e($schedule['id']); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Customer -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                                                <i class="fas fa-user text-emerald-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                        <div class="text-sm font-medium text-white"><?php echo e($schedule['customer_name']); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Service -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-teal-500/20 flex items-center justify-center">
                                                <i class="fas fa-shopping-basket text-teal-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                        <div class="text-sm font-medium text-white"><?php echo e($schedule['service_type']); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Drop-off -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                <i class="fas fa-arrow-down text-blue-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                        <div class="text-sm font-medium text-white"><?php echo e(\Carbon\Carbon::parse($schedule['dropoff_date'])->format('M j, Y')); ?></div>
                                        <div class="text-xs text-slate-400"><?php echo e($schedule['dropoff_time']); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pickup -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                                                <i class="fas fa-arrow-up text-emerald-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                        <div class="text-sm font-medium text-white"><?php echo e(\Carbon\Carbon::parse($schedule['pickup_date'])->format('M j, Y')); ?></div>
                                        <div class="text-xs text-slate-400"><?php echo e($schedule['pickup_time']); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Weight -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-purple-500/20 flex items-center justify-center">
                                                <i class="fas fa-weight text-purple-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                        <?php if($schedule['weight']): ?>
                                            <div class="text-sm font-medium text-white"><?php echo e($schedule['weight']); ?> kg</div>
                                            <div class="text-xs text-slate-400">Weighed</div>
                                        <?php else: ?>
                                                <div class="text-sm font-medium text-slate-400">-</div>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Price -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                                <i class="fas fa-peso-sign text-yellow-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                        <?php if($schedule['total_price']): ?>
                                            <div class="text-sm font-medium text-white">₱<?php echo e(number_format($schedule['total_price'], 2)); ?></div>
                                            <div class="text-xs text-slate-400">Final</div>
                                        <?php else: ?>
                                                <div class="text-sm font-medium text-slate-400">-</div>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                        $status = $schedule['status'] ?? 'Scheduled';
                                        // Use consistent status colors
                                        $statusColors = \App\Helpers\StatusHelper::getStatusColor($status);
                                        $statusColor = $statusColors['badge'];
                                        $dotColor = $statusColors['dot'];
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border <?php echo e($statusColor); ?>">
                                        <div class="w-2 h-2 rounded-full mr-2 <?php echo e($dotColor); ?>"></div>
                                        <?php echo e($status); ?>

                                    </span>
                                </td>

                                <!-- Approved By -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-sky-500/20 flex items-center justify-center">
                                                <i class="fas fa-user-check text-sky-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <?php if($schedule['approved_by_name'] ?? null): ?>
                                                <div class="text-sm font-medium text-white"><?php echo e($schedule['approved_by_name']); ?></div>
                                                <?php if($schedule['approved_at'] ?? null): ?>
                                                    <div class="text-xs text-slate-400"><?php echo e(\Carbon\Carbon::parse($schedule['approved_at'])->format('M j, Y')); ?></div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="text-sm font-medium text-slate-400">-</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="viewSchedule(<?php echo e($schedule['id']); ?>)" 
                                                class="inline-flex items-center px-3 py-1.5 bg-slate-700/50 hover:bg-emerald-500/20 border border-slate-600/50 hover:border-emerald-500/50 rounded-lg text-slate-400 hover:text-emerald-400 transition-all text-xs font-medium flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                                                title="View Details">
                                            <i class="fas fa-eye text-xs"></i>
                                            View
                                        </button>
                                        <?php if($schedule['status'] === 'Pending Approval'): ?>
                                            <button onclick="editSchedule(<?php echo e($schedule['id']); ?>)" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-slate-700/50 hover:bg-blue-500/20 border border-slate-600/50 hover:border-blue-500/50 rounded-lg text-slate-400 hover:text-blue-400 transition-all text-xs font-medium flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                                    title="Edit Schedule">
                                                <i class="fas fa-edit text-xs"></i>
                                                Edit
                                            </button>
                                        <?php else: ?>
                                            <button disabled
                                                    class="inline-flex items-center px-3 py-1.5 bg-slate-800/30 border border-slate-700/30 rounded-lg text-slate-500 cursor-not-allowed opacity-50 text-xs font-medium flex items-center gap-1.5"
                                                    title="Not available in this status">
                                                <i class="fas fa-edit text-xs"></i>
                                                Edit
                                            </button>
                                        <?php endif; ?>
                                        <?php if($schedule['status'] === 'Pending Approval'): ?>
                                            <button onclick="cancelSchedule(<?php echo e($schedule['id']); ?>)" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-slate-700/50 hover:bg-red-500/20 border border-slate-600/50 hover:border-red-500/50 rounded-lg text-slate-400 hover:text-red-400 transition-all text-xs font-medium flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-red-500/50"
                                                    title="Cancel Schedule">
                                                <i class="fas fa-times text-xs"></i>
                                                Cancel
                                            </button>
                                        <?php else: ?>
                                            <button disabled
                                                    class="inline-flex items-center px-3 py-1.5 bg-slate-800/30 border border-slate-700/30 rounded-lg text-slate-500 cursor-not-allowed opacity-50 text-xs font-medium flex items-center gap-1.5"
                                                    title="Not available in this status">
                                                <i class="fas fa-times text-xs"></i>
                                                Cancel
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="p-12">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-emerald-500/30">
                        <i class="fas fa-calendar-plus text-emerald-400 text-3xl"></i>
        </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No Schedules Yet</h3>
                    <p class="text-slate-400 mb-6">Get started by scheduling your first laundry pickup and delivery appointment.</p>
                    <button onclick="openScheduleModal()" 
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-6 py-3 rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-emerald-500/25">
                        <i class="fas fa-plus"></i>
                        <span>Schedule Now</span>
                    </button>
                </div>
            </div>
        <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($schedules->hasPages()): ?>
            <div class="mt-8 flex items-center justify-between bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 shadow-2xl">
                <div class="text-sm text-slate-300">
                    Showing <span class="font-medium text-white"><?php echo e($schedules->firstItem()); ?></span> to <span class="font-medium text-white"><?php echo e($schedules->lastItem()); ?></span> of <span class="font-medium text-white"><?php echo e($schedules->total()); ?></span> schedules
                </div>
                <div class="flex items-center gap-2">
                    <?php if($schedules->onFirstPage()): ?>
                        <button class="px-4 py-2 bg-slate-700/50 text-slate-500 rounded-lg cursor-not-allowed opacity-50" disabled>
                            <i class="fas fa-chevron-left mr-1"></i>
                            Previous
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e($schedules->previousPageUrl()); ?>" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>
                            Previous
                        </a>
                    <?php endif; ?>

                    <div class="flex items-center gap-1">
                        <?php $__currentLoopData = $schedules->getUrlRange(1, $schedules->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $schedules->currentPage()): ?>
                                <span class="px-3 py-2 bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 rounded-lg font-medium"><?php echo e($page); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($url); ?>" class="px-3 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors"><?php echo e($page); ?></a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($schedules->hasMorePages()): ?>
                        <a href="<?php echo e($schedules->nextPageUrl()); ?>" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors">
                            Next
                            <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    <?php else: ?>
                        <button class="px-4 py-2 bg-slate-700/50 text-slate-500 rounded-lg cursor-not-allowed opacity-50" disabled>
                            Next
                            <i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    <!-- Include Modal Components -->
    <?php echo $__env->make('customer.schedules.modals.new-schedule', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('customer.schedules.modals.edit-schedule', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('customer.schedules.modals.cancel-schedule', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('customer.schedules.modals.view-schedule', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[60] space-y-3 max-w-sm">
        <!-- Toast notifications will be dynamically inserted here -->
    </div>

    <!-- Include CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/customer-schedules.css')); ?>?v=<?php echo e(time()); ?>">

    <!-- Include JavaScript -->
    <script>
        // Set the routes for form submission
        window.scheduleStoreRoute = '<?php echo e(route("customer.schedules.store")); ?>';
        window.scheduleUpdateRoute = '<?php echo e(route("customer.schedules.update", ":id")); ?>';
        window.scheduleDeleteRoute = '<?php echo e(route("customer.schedules.cancel", ":id")); ?>';
    </script>
    <script src="<?php echo e(asset('js/customer-schedules.js')); ?>?v=<?php echo e(time()); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/customer/schedules/index.blade.php ENDPATH**/ ?>