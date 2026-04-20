<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function addRole(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->hasRole($request->role)) {
            return back()->with('status', 'Cet utilisateur a déjà ce rôle');
        }

        $user->assignRole($request->role);

        return back()->with('status', 'Rôle ajouté');
    }

    public function removeRole(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (! $user->hasRole($request->role)) {
            return back()->with('status', 'Cet utilisateur n’a pas ce rôle');
        }

        $user->removeRole($request->role);

        return back()->with('status', 'Rôle retiré');
    }
}
