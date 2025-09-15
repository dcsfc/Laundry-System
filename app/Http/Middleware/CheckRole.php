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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
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

        return $next($request);
    }
}

