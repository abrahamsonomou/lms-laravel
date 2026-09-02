<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Organisation;
use App\Models\Rbac\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('roles')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $terme = $request->string('search');
                $query->where(function ($q) use ($terme): void {
                    $q->where('name', 'like', "%{$terme}%")
                        ->orWhere('email', 'like', "%{$terme}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backoffice.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('backoffice.users.create', [
            'roles' => Role::query()->orderBy('nom')->get(),
            'organisations' => Organisation::query()->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        $user = User::query()->create([
            'organisation_id' => $validated['organisation_id'] ?? null,
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'name' => trim($validated['prenom'].' '.$validated['nom']),
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'password' => Hash::make($validated['password']),
            'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
            'active' => $request->boolean('active'),
        ]);

        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user): View
    {
        return view('backoffice.users.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::query()->orderBy('nom')->get(),
            'organisations' => Organisation::query()->orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validateUser($request, $user);

        if ($request->hasFile('avatar') && $user->avatar !== null) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update([
            'organisation_id' => $validated['organisation_id'] ?? null,
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'name' => trim($validated['prenom'].' '.$validated['nom']),
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'active' => $request->boolean('active'),
            ...($request->hasFile('avatar') ? ['avatar' => $request->file('avatar')->store('avatars', 'public')] : []),
            ...(! empty($validated['password']) ? ['password' => Hash::make($validated['password'])] : []),
        ]);

        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'organisation_id' => ['nullable', 'integer', 'exists:organisations,id'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => [$user === null ? 'required' : 'nullable', 'confirmed', Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['integer', 'exists:roles,id'],
            'active' => ['boolean'],
        ]);
    }
}
