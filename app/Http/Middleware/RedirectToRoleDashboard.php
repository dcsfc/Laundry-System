<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectToRoleDashboard
{
    public function handle($request, Closure $next)
    {
        // Skip redirect for logout route and other auth routes
        if ($request->is('logout') || $request->is('login') || $request->is('register') || $request->is('password/*')) {
            return $next($request);
        }
        
        if (Auth::check()) {
            $role = Auth::user()->role->name;
            switch ($role) {
                case 'Super Admin':
                    return redirect()->route('superadmin.dashboard');
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'Staff':
                    return redirect()->route('staff.dashboard');
                case 'Customer':
                    return redirect()->route('customer.dashboard');
            }
        }
        return $next($request);
    }
}
