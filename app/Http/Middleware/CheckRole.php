<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated with any guard
        $user = null;
        $guard = null;
        
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            $guard = 'admin';
        } elseif (Auth::guard('customer')->check()) {
            $user = Auth::guard('customer')->user();
            $guard = 'customer';
        } elseif (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $guard = 'web';
        }

        if (!$user) {
            // Redirect to appropriate login based on the role being checked
            if (in_array($role, ['superadmin', 'admin', 'administrator', 'staff'])) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('login');
        }

        $userRole = $user->role->name ?? 'customer';

        // Map role names to expected values
        $roleMap = [
            'superadmin' => 'superadmin',
            'admin' => 'administrator',  // Route uses 'admin' but DB stores 'administrator'
            'administrator' => 'administrator',
            'staff' => 'staff',
            'customer' => 'customer'
        ];

        $expectedRole = $roleMap[$role] ?? $role;

        if ($userRole !== $expectedRole) {
            // Redirect to appropriate dashboard based on actual role
            switch ($userRole) {
                case 'superadmin':
                    return redirect()->route('superadmin.dashboard');
                case 'administrator':
                    return redirect()->route('admin.dashboard');
                case 'staff':
                    return redirect()->route('staff.dashboard');
                case 'customer':
                    return redirect()->route('customer.dashboard');
                default:
                    return redirect()->route('customer.dashboard');
            }
        }

        // Ensure admin users are using the admin guard and customers are using customer guard
        if ($userRole === 'customer' && $guard !== 'customer') {
            return redirect()->route('login');
        }
        
        if (in_array($userRole, ['superadmin', 'administrator', 'staff']) && $guard !== 'admin') {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

