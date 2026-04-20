<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Compte;
use App\Models\Adresse;
use App\Models\Telephone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'rue' => 'nullable|string|max:255',
            'numero' => 'nullable|integer',
            'ville' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:20',
        ];

        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $rules['role'] = 'required|exists:roles,name';
        }

        $request->validate($rules);

        $user = null;

        DB::transaction(function () use ($request, &$user) {
            // 1️⃣ Création user
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $compte = Compte::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naissance' => $request->date_naissance,
            ]);

            $role = 'patient';

            if (Auth::check() && Auth::user()->hasRole('admin')) {
                $role = $request->role;
            }

            $user->assignRole($role);

            if ($request->rue || $request->ville || $request->code_postal) {
                $adresse = Adresse::create([
                    'rue' => $request->rue,
                    'numero' => $request->numero,
                    'ville' => $request->ville,
                    'province' => $request->province,
                    'code_postal' => $request->code_postal,
                    'type' => $request->type,
                ]);

                $compte->adresses()->attach(
                    $adresse->id,
                    ['type' => $request->type === 'travail' ? 'work' : 'home']
                );
            }

            if (!empty($request->telephone)) {
                $telephone = Telephone::create([
                    'numero' => $request->telephone
                ]);

                $compte->telephones()->attach($telephone->id);
            }
        });

        event(new Registered($user));

        if (!Auth::check()) {
            Auth::login($user);
        }

        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admin')
                ->with('success', 'Utilisateur créé');
        }

        return redirect()->route('dashboard');
    }
}
