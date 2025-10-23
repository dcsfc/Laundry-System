<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Determine default customer role id (create if missing to avoid DB constraint errors)
        $customerRoleId = Role::where('name', 'customer')->value('id');
        if (!$customerRoleId) {
            $customerRoleId = Role::create([
                'name' => 'customer',
                'description' => 'Default customer role',
            ])->id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role_id' => $customerRoleId,
            'status' => 'active',
        ]);

        event(new Registered($user));

        // Don't auto-login after registration
        // Auth::login($user);

        // Stay on register page with success message
        return redirect()->route('register')->with('success', 'Account created successfully! Please log in to continue.');
    }
}
