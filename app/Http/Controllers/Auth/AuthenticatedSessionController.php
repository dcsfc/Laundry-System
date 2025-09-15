<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        $role = $user->role->name ?? 'customer';
        
        // Debug: Log the role information
        \Log::info('User login - ID: ' . $user->id . ', Name: ' . $user->name . ', Role: ' . $role);
        
        switch ($role) {
            case 'superadmin':
                \Log::info('Redirecting superadmin to superadmin dashboard');
                return redirect()->intended(route('superadmin.dashboard', absolute: false));
            case 'administrator':
                \Log::info('Redirecting administrator to admin dashboard');
                return redirect()->intended(route('admin.dashboard', absolute: false));
            case 'staff':
                \Log::info('Redirecting staff to staff dashboard');
                return redirect()->intended(route('staff.dashboard', absolute: false));
            case 'customer':
                \Log::info('Redirecting customer to customer dashboard');
                return redirect()->route('customer.dashboard', absolute: false);
            default:
                \Log::info('Unknown role, redirecting to customer dashboard. Role: ' . $role);
                return redirect()->route('customer.dashboard', absolute: false);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log the logout attempt for debugging
        \Log::info('User logout attempt - ID: ' . (Auth::id() ?? 'unknown') . ', IP: ' . $request->ip());
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        \Log::info('User logout successful - redirecting to home');
        
        return redirect('/');
    }
}
