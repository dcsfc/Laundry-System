<?php
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
        $textColor = 'indigo';
    } elseif ($isAdmin || $isStaff) {
        $primaryColor = 'sky';
        $secondaryColor = 'cyan';
        $accentColor = 'sky';
        $textColor = 'sky';
    } else {
        $primaryColor = 'emerald';
        $secondaryColor = 'teal';
        $accentColor = 'emerald';
        $textColor = 'emerald';
    }
?>
<!-- Premium SaaS Header -->
<header class="fixed top-0 left-0 w-full h-14 bg-slate-900 text-slate-50 shadow-lg flex items-center justify-between px-6 z-50 border-b border-<?php echo e($accentColor); ?>-500/20">
    <!-- Left: Logo & Sidebar Toggle -->
    <div class="flex items-center gap-3">
        <!-- Sidebar Toggle -->
        <button id="sidebar-toggle" class="p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-<?php echo e($textColor); ?>-400 hover:bg-slate-700 transition-all group">
            <i class="fas fa-bars group-hover:scale-110 transition-transform text-sm"></i>
        </button>
        
        <span class="font-semibold text-lg tracking-tight text-slate-50">Latino Laundry</span>
    </div>

    <!-- Middle: Search -->
    <div class="hidden md:flex items-center relative w-80">
        <input type="text" placeholder="Search anything... (Ctrl+K)"
            class="w-full rounded-lg bg-slate-800 border border-slate-700 px-3 py-1.5 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:border-<?php echo e($textColor); ?>-400 focus:ring-2 focus:ring-<?php echo e($textColor); ?>-400/20 transition-all">
        <i class="fas fa-search absolute right-3 text-slate-500 text-xs"></i>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-3">
        <!-- Dark Mode Toggle -->
        <button id="theme-toggle" class="p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-<?php echo e($textColor); ?>-400 hover:bg-slate-700 transition-all group">
            <i class="fa fa-moon group-hover:scale-110 transition-transform text-sm"></i>
        </button>

        <!-- Help -->
        <a href="#" class="p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-<?php echo e($textColor); ?>-400 hover:bg-slate-700 transition-all group">
            <i class="fa fa-question-circle group-hover:scale-110 transition-transform text-sm"></i>
        </a>

        <!-- Notifications -->
        <button class="relative p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-<?php echo e($textColor); ?>-400 hover:bg-slate-700 transition-all group">
            <i class="fa fa-bell group-hover:scale-110 transition-transform text-sm"></i>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-<?php echo e($primaryColor); ?>-500 rounded-full animate-pulse"></span>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-<?php echo e($primaryColor); ?>-500 via-<?php echo e($secondaryColor); ?>-400 to-<?php echo e($accentColor); ?>-500 flex items-center justify-center cursor-pointer font-bold text-white shadow-lg hover:shadow-<?php echo e($primaryColor); ?>-500/25 transition-all group-hover:scale-105">
                <?php echo e(strtoupper(substr(Auth::user()->name ?? 'U', 0, 1))); ?>

            </div>
            <div
                class="absolute right-0 mt-2 w-64 bg-slate-800 rounded-xl shadow-xl border border-slate-700 opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 translate-y-2">
                <div class="px-4 py-3 border-b border-slate-700">
                    <p class="text-sm font-semibold text-slate-50"><?php echo e(Auth::user()->name ?? 'User'); ?></p>
                    <p class="text-xs text-slate-400"><?php echo e(Auth::user()->email ?? 'user@example.com'); ?></p>
                </div>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-slate-700 hover:text-<?php echo e($textColor); ?>-400 transition-all">
                    <i class="fa fa-user w-4"></i> Profile Settings
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-slate-700 hover:text-<?php echo e($textColor); ?>-400 transition-all">
                    <i class="fa fa-cog w-4"></i> Preferences
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-slate-700 hover:text-<?php echo e($textColor); ?>-400 transition-all">
                    <i class="fa fa-shield-alt w-4"></i> Security
                </a>
                <div class="border-t border-slate-700"></div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all">
                        <i class="fa fa-sign-out-alt w-4"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Dummy Spacer (to prevent content being hidden under fixed header) -->
<div class="h-14"></div>

<!-- Dark Mode & Sidebar Toggle Script -->
<script>
    document.getElementById('theme-toggle').addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
    });

    // Sidebar Toggle Functionality
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const body = document.body;
        
        // Toggle sidebar collapsed state
        sidebar.classList.toggle('sidebar-collapsed');
        mainContent.classList.toggle('sidebar-collapsed');
        body.classList.toggle('sidebar-collapsed');
    });
</script>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/header.blade.php ENDPATH**/ ?>