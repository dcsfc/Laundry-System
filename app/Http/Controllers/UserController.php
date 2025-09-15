<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ...existing code...

    public function store(Request $request)
    {
        // ...existing code...

        $user = User::create([
            // ...existing code...
            'role_id' => Role::where('name', 'Customer')->first()->id,
        ]);

        // ...existing code...
    }

    // ...existing code...
}