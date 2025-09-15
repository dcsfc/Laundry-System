<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Allow access to login page even if authenticated
                if ($request->routeIs('login')) {
                    return $next($request);
                }
                
                // Redirect based on user role for other routes
                $user = Auth::user();
                $role = $user->role->name ?? 'customer';
                
                // Debug: Log the role information
                \Log::info('RedirectIfAuthenticated - User ID: ' . $user->id . ', Name: ' . $user->name . ', Role: ' . $role);
                
                switch ($role) {
                    case 'superadmin':
                        \Log::info('RedirectIfAuthenticated: Redirecting superadmin to superadmin dashboard');
                        return redirect()->route('superadmin.dashboard');
                    case 'administrator':
                        \Log::info('RedirectIfAuthenticated: Redirecting administrator to admin dashboard');
                        return redirect()->route('admin.dashboard');
                    case 'staff':
                        \Log::info('RedirectIfAuthenticated: Redirecting staff to staff dashboard');
                        return redirect()->route('staff.dashboard');
                    case 'customer':
                        \Log::info('RedirectIfAuthenticated: Redirecting customer to customer dashboard');
                        return redirect()->route('customer.dashboard');
                    default:
                        \Log::info('RedirectIfAuthenticated: Unknown role, redirecting to customer dashboard. Role: ' . $role);
                        return redirect()->route('customer.dashboard');
                }
            }
        }

        return $next($request);
    }
}
