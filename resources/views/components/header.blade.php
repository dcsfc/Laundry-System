@php
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
@endphp
<!-- Premium SaaS Header -->
<header class="fixed top-0 left-0 w-full h-14 bg-slate-900 text-slate-50 shadow-lg flex items-center justify-between px-6 z-50 border-b border-{{ $accentColor }}-500/20">
    <!-- Left: Logo & Sidebar Toggle -->
    <div class="flex items-center gap-3">
        <!-- Sidebar Toggle -->
        <button id="sidebar-toggle" class="p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-{{ $textColor }}-400 hover:bg-slate-700 transition-all group">
            <i class="fas fa-bars group-hover:scale-110 transition-transform text-sm"></i>
        </button>
        
        <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Latino Laundry" class="h-20 w-auto">
    </div>


    <!-- Right: Actions -->
    <div class="flex items-center gap-3">
        <!-- Dark Mode Toggle -->
        <button id="theme-toggle" class="p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-{{ $textColor }}-400 hover:bg-slate-700 transition-all group">
            <i id="theme-icon" class="fa fa-moon group-hover:scale-110 transition-transform text-sm"></i>
        </button>

        <!-- Help -->
        <a href="#" class="p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-{{ $textColor }}-400 hover:bg-slate-700 transition-all group">
            <i class="fa fa-question-circle group-hover:scale-110 transition-transform text-sm"></i>
        </a>

        <!-- Notifications -->
        <button class="relative p-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 hover:text-{{ $textColor }}-400 hover:bg-slate-700 transition-all group">
            <i class="fa fa-bell group-hover:scale-110 transition-transform text-sm"></i>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-{{ $primaryColor }}-500 rounded-full animate-pulse"></span>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-{{ $primaryColor }}-500 via-{{ $secondaryColor }}-400 to-{{ $accentColor }}-500 flex items-center justify-center cursor-pointer font-bold text-white shadow-lg hover:shadow-{{ $primaryColor }}-500/25 transition-all group-hover:scale-105">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div
                class="absolute right-0 mt-2 w-64 bg-slate-800 rounded-xl shadow-xl border border-slate-700 opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 translate-y-2">
                <div class="px-4 py-3 border-b border-slate-700">
                    <p class="text-sm font-semibold text-slate-50">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-slate-400">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-slate-700 hover:text-{{ $textColor }}-400 transition-all">
                    <i class="fa fa-user w-4"></i> Profile Settings
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-slate-700 hover:text-{{ $textColor }}-400 transition-all">
                    <i class="fa fa-cog w-4"></i> Preferences
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-slate-700 hover:text-{{ $textColor }}-400 transition-all">
                    <i class="fa fa-shield-alt w-4"></i> Security
                </a>
                <div class="border-t border-slate-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
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
    // Dark Mode Toggle Functionality
    function initTheme() {
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const html = document.documentElement;
        
        // Check for saved theme preference or default to 'dark'
        const savedTheme = localStorage.getItem('theme') || 'dark';
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const currentTheme = savedTheme === 'auto' ? (prefersDark ? 'dark' : 'light') : savedTheme;
        
        // Apply theme
        if (currentTheme === 'dark') {
            html.classList.add('dark');
            themeIcon.className = 'fa fa-sun group-hover:scale-110 transition-transform text-sm';
        } else {
            html.classList.remove('dark');
            themeIcon.className = 'fa fa-moon group-hover:scale-110 transition-transform text-sm';
        }
        
        // Toggle theme on click
        themeToggle.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                themeIcon.className = 'fa fa-moon group-hover:scale-110 transition-transform text-sm';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                themeIcon.className = 'fa fa-sun group-hover:scale-110 transition-transform text-sm';
                localStorage.setItem('theme', 'dark');
            }
        });
    }

    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', initTheme);

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
