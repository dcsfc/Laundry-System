<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function create(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::guard('admin')->user();
        $role = $user->role->name ?? 'customer';
        
        // Redirect based on role
        switch ($role) {
            case 'superadmin':
                return redirect()->intended(route('superadmin.dashboard', absolute: false));
            case 'administrator':
                return redirect()->intended(route('admin.dashboard', absolute: false));
            case 'staff':
                return redirect()->intended(route('staff.dashboard', absolute: false));
            default:
                // If somehow a customer tries to login via admin route, redirect to customer login
                Auth::guard('admin')->logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Please use the customer login for customer accounts.'
                ]);
        }
    }

    /**
     * Destroy an authenticated admin session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}

