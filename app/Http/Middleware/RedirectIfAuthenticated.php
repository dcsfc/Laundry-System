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
                
                switch ($role) {
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
        }

        return $next($request);
    }
}
