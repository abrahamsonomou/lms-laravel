<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Etudiant\Etudiant;
use App\Models\Rbac\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $user = User::query()->create([
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'name' => trim($validated['prenom'].' '.$validated['nom']),
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'active' => true,
            ]);

            $etudiantRole = Role::query()->where('code', 'ETUDIANT')->first();

            if ($etudiantRole !== null) {
                $user->roles()->syncWithoutDetaching([$etudiantRole->id]);
            }

            Etudiant::query()->create([
                'user_id' => $user->id,
                'active' => true,
            ]);

            return $user;
        });

        // event(new Registered($user));

        Auth::login($user);

        return redirect()->route($user->homeRouteName());
    }
}
