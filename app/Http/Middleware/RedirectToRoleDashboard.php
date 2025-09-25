<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectToRoleDashboard
{
    public function handle($request, Closure $next)
    {
        // Skip redirect for auth-related routes
        if ($request->is('logout') || $request->is('login') || $request->is('register') || $request->is('password/*')) {
            return $next($request);
        }

        if (Auth::check()) {
            $role = strtolower(Auth::user()->role->name ?? 'customer');
            switch ($role) {
                case 'superadmin':
                    return redirect()->route('superadmin.dashboard');
                case 'administrator':
                    return redirect()->route('admin.dashboard');
                case 'staff':
                    return redirect()->route('staff.dashboard');
                case 'customer':
                default:
                    return redirect()->route('customer.dashboard');
            }
        }

        return $next($request);
    }
}
