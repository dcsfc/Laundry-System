@php
    $userRole = Auth::user()->role->name ?? 'customer';
    $isSuperAdmin = $userRole === 'superadmin';
    $isAdmin = $userRole === 'administrator';
    $isStaff = $userRole === 'staff';
    $isCustomer = $userRole === 'customer';
    
    // Debug: Check if user is authenticated
    if (!Auth::check()) {
        $userRole = 'customer';
        $isSuperAdmin = false;
        $isAdmin = false;
        $isStaff = false;
        $isCustomer = true;
    }

    // Define color schemes based on role
    // Debug: Log the role for debugging
    \Log::info('User Role: ' . $userRole . ', isSuperAdmin: ' . ($isSuperAdmin ? 'true' : 'false'));
    
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
