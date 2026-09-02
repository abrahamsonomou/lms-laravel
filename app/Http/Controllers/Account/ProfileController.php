<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $view = $user->isFormateur() ? 'teacher.profile' : 'student.profile';

        return view($view, compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $emailChanged = $validated['email'] !== $user->email;

        $payload = [
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'name' => trim($validated['prenom'].' '.$validated['nom']),
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            ...(! empty($validated['password']) ? ['password' => Hash::make($validated['password'])] : []),
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar !== null) {
                Storage::disk('public')->delete($user->avatar);
            }
            $payload['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($payload);

        if ($emailChanged) {
            $user->forceFill(['email_verified_at' => null])->save();
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
