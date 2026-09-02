<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Rbac\Permission;
use App\Models\Rbac\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()
            ->withCount(['permissions', 'users'])
            ->orderBy('nom')
            ->paginate(15);

        return view('backoffice.roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('backoffice.roles.create', [
            'permissions' => $this->groupedPermissions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRole($request);

        $role = Role::query()->create([
            'code' => $validated['code'],
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'active' => $request->boolean('active'),
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role): View
    {
        return view('backoffice.roles.edit', [
            'role' => $role->load('permissions'),
            'permissions' => $this->groupedPermissions(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $this->validateRole($request, $role);

        $role->update([
            'code' => $validated['code'],
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'active' => $request->boolean('active'),
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Rôle mis à jour.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Rôle supprimé.');
    }

    /**
     * @return Collection<string, \Illuminate\Database\Eloquent\Collection<int, Permission>>
     */
    private function groupedPermissions(): Collection
    {
        return Permission::query()->orderBy('code')->get()->groupBy('module');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateRole(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('roles', 'code')->ignore($role?->id)],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
            'active' => ['boolean'],
        ]);
    }
}
