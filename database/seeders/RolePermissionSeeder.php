<?php

namespace Database\Seeders;

use App\Models\Rbac\Permission;
use App\Models\Rbac\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Rôles métier de la plateforme.
     *
     * @var array<int, array{code: string, nom: string, description: string}>
     */
    private array $roles = [
        ['code' => 'SUPER_ADMIN', 'nom' => 'Super administrateur', 'description' => 'Accès total à la plateforme'],
        ['code' => 'ADMIN_ORGANISATION', 'nom' => 'Administrateur organisation', 'description' => 'Gère une organisation'],
        ['code' => 'ADMIN_ETABLISSEMENT', 'nom' => 'Administrateur établissement', 'description' => 'Gère un établissement'],
        ['code' => 'FORMATEUR', 'nom' => 'Formateur', 'description' => 'Crée et anime des formations'],
        ['code' => 'ETUDIANT', 'nom' => 'Étudiant', 'description' => 'Suit des formations'],
        ['code' => 'TUTEUR', 'nom' => 'Tuteur', 'description' => 'Accompagne les étudiants'],
        ['code' => 'COMPTABLE', 'nom' => 'Comptable', 'description' => 'Gère la facturation et les paiements'],
        ['code' => 'SUPPORT', 'nom' => 'Support', 'description' => 'Assistance et support utilisateurs'],
    ];

    /**
     * Modules soumis aux permissions CRUD.
     *
     * @var array<int, string>
     */
    private array $modules = [
        'organisations', 'utilisateurs', 'roles', 'etudiants', 'formateurs',
        'formations', 'cours', 'evaluations', 'certificats', 'abonnements',
        'factures', 'paiements', 'chat', 'chatbots', 'notifications', 'rapports',
    ];

    /**
     * @var array<int, string>
     */
    private array $actions = ['view', 'create', 'update', 'delete'];

    public function run(): void
    {
        $permissions = [];

        foreach ($this->modules as $module) {
            foreach ($this->actions as $action) {
                $permission = Permission::query()->updateOrCreate(
                    ['code' => "{$module}.{$action}"],
                    ['nom' => Str::headline("{$action} {$module}"), 'module' => $module],
                );

                $permissions[] = $permission->id;
            }
        }

        foreach ($this->roles as $role) {
            $model = Role::query()->updateOrCreate(
                ['code' => $role['code']],
                ['nom' => $role['nom'], 'description' => $role['description'], 'active' => true],
            );

            if ($role['code'] === 'SUPER_ADMIN') {
                $model->permissions()->sync($permissions);
            }
        }
    }
}
