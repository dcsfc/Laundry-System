@php
    // Get user from appropriate guard
    $user = null;
    if (Auth::guard('admin')->check()) {
        $user = Auth::guard('admin')->user();
    } elseif (Auth::guard('customer')->check()) {
        $user = Auth::guard('customer')->user();
    } elseif (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
    }
    
    $userRole = $user->role->name ?? 'customer';
    $isSuperAdmin = $userRole === 'superadmin';
    $isAdmin = $userRole === 'administrator';
    $isStaff = $userRole === 'staff';
    $isCustomer = $userRole === 'customer';
    
    if (!$user) {
        $userRole = 'customer';
        $isSuperAdmin = false;
        $isAdmin = false;
        $isStaff = false;
        $isCustomer = true;
    }

    // Define color schemes based on role
    if ($isSuperAdmin) {
        // Superadmin → Premium & Authority (Indigo / Purple)
        $primaryColor = 'indigo';
        $secondaryColor = 'purple';
        $accentColor = 'indigo';
        $hoverColor = 'purple';
        $gradientFrom = 'from-indigo-600';
        $gradientTo = 'to-purple-600';
        $gradientHoverFrom = 'from-indigo-500/20';
        $gradientHoverTo = 'to-purple-500/20';
        $shadowColor = 'indigo';
        $textColor = 'indigo';
    } elseif ($isAdmin || $isStaff) {
        // Admin / Staff → Trust & Structure (Blue / Cyan)
        $primaryColor = 'sky';
        $secondaryColor = 'cyan';
        $accentColor = 'sky';
        $hoverColor = 'cyan';
        $gradientFrom = 'from-sky-600';
        $gradientTo = 'to-cyan-600';
        $gradientHoverFrom = 'from-sky-500/20';
        $gradientHoverTo = 'to-cyan-500/20';
        $shadowColor = 'sky';
        $textColor = 'sky';
    } else {
        // Customer → Friendly & Growth (Green / Teal)
        $primaryColor = 'emerald';
        $secondaryColor = 'teal';
        $accentColor = 'emerald';
        $hoverColor = 'teal';
        $gradientFrom = 'from-emerald-500';
        $gradientTo = 'to-teal-600';
        $gradientHoverFrom = 'from-emerald-500/20';
        $gradientHoverTo = 'to-teal-500/20';
        $shadowColor = 'emerald';
        $textColor = 'emerald';
    }
    
    // Ensure all variables are defined with fallbacks
    $primaryColor = $primaryColor ?? 'emerald';
    $secondaryColor = $secondaryColor ?? 'teal';
    $accentColor = $accentColor ?? 'emerald';
    $hoverColor = $hoverColor ?? 'teal';
    $gradientFrom = $gradientFrom ?? 'from-emerald-500';
    $gradientTo = $gradientTo ?? 'to-teal-600';
    $gradientHoverFrom = $gradientHoverFrom ?? 'from-emerald-500/20';
    $gradientHoverTo = $gradientHoverTo ?? 'to-teal-500/20';
    $shadowColor = $shadowColor ?? 'emerald';
    $textColor = $textColor ?? 'emerald';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Laundry System')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  
  {{-- Load Vite compiled JavaScript BEFORE Alpine.js --}}
  @vite(['resources/js/app.js'])
  
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    :root {
      --primary-color: {{ $primaryColor === 'indigo' ? '#6366f1' : ($primaryColor === 'sky' ? '#0ea5e9' : '#10b981') }};
      --secondary-color: {{ $secondaryColor === 'purple' ? '#8b5cf6' : ($secondaryColor === 'cyan' ? '#06b6d4' : '#14b8a6') }};
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      margin: 0;
      background: #0f172a;
      color: #f8fafc;
    }
    
    /* Alpine.js x-cloak directive - only hide until Alpine is initialized */
    [x-cloak] {
      display: none !important;
    }
    
    /* Ensure x-show can override x-cloak after Alpine initialization */
    [x-data] [x-cloak] {
      display: none !important;
    }
    .main-content {
      margin-left: 240px;
      margin-top: 70px;
      padding: 20px;
      min-height: calc(100vh - 70px);
      background: #0f172a;
      transition: margin-left 0.3s ease-in-out;
    }
    .main-content.sidebar-collapsed {
      margin-left: 80px;
    }
    
    /* Override Tailwind container for sidebar layout */
    .main-content .container {
      max-width: none !important;
      margin: 0 !important;
      padding: 0 !important;
      background: transparent !important;
    }
    
    /* Sidebar collapsed state */
    .sidebar.sidebar-collapsed {
      width: 80px;
    }
    
    .sidebar.sidebar-collapsed .sidebar-text {
      display: none;
    }
    
    .sidebar.sidebar-collapsed .sidebar-nav a {
      justify-content: center;
      padding: 12px;
    }
    
    .sidebar.sidebar-collapsed .sidebar-icon {
      margin: 0;
    }
    /* Hide scrollbar completely */
    .scrollbar-thin {
      scrollbar-width: none; /* Firefox */
      -ms-overflow-style: none; /* Internet Explorer 10+ */
    }
    .scrollbar-thin::-webkit-scrollbar {
      display: none; /* WebKit */
    }
  </style>
  @stack('styles')
</head>
<body>
  @include('components.header')
  <!-- Sidebar -->
  <div class="sidebar fixed top-14 left-0 w-60 h-[calc(100vh-3.5rem)] bg-slate-900 border-r border-{{ $accentColor }}-500/20 z-[100] flex flex-col shadow-2xl transition-all duration-300 ease-in-out" id="sidebar">
    <!-- Navigation -->
    <div class="sidebar-nav flex-1 overflow-y-auto py-6 px-3 scrollbar-thin scrollbar-track-slate-800 scrollbar-thumb-slate-600">

      <!-- Dashboard - All Roles -->
      @if($isSuperAdmin)
        <a href="{{ route('superadmin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('superadmin.dashboard') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-tachometer-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('superadmin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Dashboard</span>
        </a>
      @elseif($isAdmin)
        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-tachometer-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Dashboard</span>
        </a>
      @elseif($isStaff)
        <a href="{{ route('staff.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('staff.dashboard') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-tachometer-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('staff.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Dashboard</span>
        </a>
      @elseif($isCustomer)
        <a href="{{ route('customer.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('customer.dashboard') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-tachometer-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('customer.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Dashboard</span>
        </a>
      @endif

      <!-- User Management - Super Admin & Admin -->
      @if($isSuperAdmin)
        <a href="{{ route('superadmin.users.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('superadmin.users.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-users w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('superadmin.users.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">User Management</span>
        </a>
      @elseif($isAdmin)
        <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-users w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">User Management</span>
        </a>
      @endif

      <!-- Service Management - Admin Only -->
      @if($isAdmin)
        <a href="{{ route('admin.services.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('admin.services.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-concierge-bell w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('admin.services.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Service Management</span>
        </a>
      @endif


      <!-- Schedules - Admin, Staff & Customer Only -->
      @if($isAdmin)
        <a href="{{ route('admin.schedules.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('admin.schedules.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-calendar-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('admin.schedules.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Schedules</span>
        </a>
      @elseif($isStaff)
        <a href="{{ route('staff.schedules.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('staff.schedules.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-calendar-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('staff.schedules.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Schedules</span>
        </a>
      @elseif($isCustomer)
        <a href="{{ route('customer.schedules.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('customer.schedules.index') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-calendar-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('customer.schedules.index') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Schedule Laundry</span>
        </a>
      @endif

      <!-- Inventory - Admin & Staff Only -->
      @if($isAdmin || $isStaff)
        <a href="{{ $isAdmin ? route('admin.inventory.index') : route('staff.inventory.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs(($isAdmin ? 'admin' : 'staff') . '.inventory.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-boxes w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs(($isAdmin ? 'admin' : 'staff') . '.inventory.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Inventory</span>
        </a>
      @endif

      <!-- Reports - Admin & Staff Only (Different Access Levels) -->
      @if($isAdmin)
        <a href="{{ route('admin.reports.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-chart-bar w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Reports</span>
        </a>
      @elseif($isStaff)
        <a href="{{ route('staff.reports.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('staff.reports.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-chart-bar w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('staff.reports.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Weekly Reports</span>
        </a>
      @endif

      <!-- Announcements - Super Admin Only -->
      @if($isSuperAdmin)
        <a href="{{ route('superadmin.announcements.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('superadmin.announcements.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-bullhorn w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('superadmin.announcements.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Announcements</span>
        </a>
      @endif

      <!-- System Settings - Super Admin Only -->
      @if($isSuperAdmin)
        <a href="{{ route('superadmin.settings.index') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('superadmin.settings.*') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-cogs w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('superadmin.settings.*') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">System Settings</span>
        </a>
      @endif

      <!-- Customer-specific menu items -->
      @if($isCustomer)
        <a href="{{ route('customer.schedules.history') }}" class="group flex items-center gap-3 px-4 py-3 mb-1 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:{{ $gradientHoverFrom }} hover:{{ $gradientHoverTo }} hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/10 hover:translate-x-1 {{ request()->routeIs('customer.schedules.history') ? 'bg-gradient-to-r ' . $gradientFrom . ' ' . $gradientTo . ' text-white shadow-lg shadow-' . $shadowColor . '-500/25 font-semibold' : '' }}">
          <i class="sidebar-icon fas fa-history w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 {{ request()->routeIs('customer.schedules.history') ? 'text-white' : 'text-slate-400' }}"></i>
          <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Order History</span>
        </a>
      @endif

      <!-- Logout Section -->
      <div class="mt-auto pt-4 border-t border-slate-700/50">
        @php
            $logoutRoute = 'logout';
            if (Auth::guard('admin')->check()) {
                $logoutRoute = 'admin.logout';
            }
        @endphp
        
        <form method="POST" action="{{ route($logoutRoute) }}" class="w-full">
            @csrf
            <button type="submit" class="group flex items-center gap-3 px-4 py-3 w-full text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all duration-300 ease-in-out hover:bg-gradient-to-r hover:from-red-500/20 hover:to-red-600/20 hover:shadow-lg hover:shadow-red-500/10 hover:translate-x-1">
                <i class="sidebar-icon fas fa-sign-out-alt w-5 text-center text-base group-hover:scale-110 transition-transform duration-300 flex-shrink-0 text-slate-400 group-hover:text-red-400"></i>
                <span class="sidebar-text group-hover:translate-x-1 transition-transform duration-300">Log Out</span>
            </button>
        </form>
      </div>
    </div>
  </div>
  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    @yield('content')
  </div>
  @include('components.footer')
  @stack('scripts')

</body>
</html>
