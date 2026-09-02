<?php

use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function asRole(string $code): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', $code)->value('id'));

    return $user;
}

it('rend les pages publiques et d\'authentification', function (string $url) {
    get($url)->assertOk();
})->with(['/', '/a-propos', '/contact', '/formations', '/login', '/register', '/forgot-password']);

it('rend les pages du backoffice', function () {
    $admin = asRole('SUPER_ADMIN');
    $role = Role::query()->first();

    actingAs($admin);

    get('/admin')->assertOk();
    get('/admin/users')->assertOk();
    get('/admin/users/create')->assertOk();
    get("/admin/users/{$admin->id}/edit")->assertOk();
    get('/admin/roles')->assertOk();
    get('/admin/roles/create')->assertOk();
    get("/admin/roles/{$role->id}/edit")->assertOk();
    get('/admin/permissions')->assertOk();
    get('/admin/organisations')->assertOk();
    get('/admin/organisations/create')->assertOk();
    get('/admin/etablissements')->assertOk();
    get('/admin/etablissements/create')->assertOk();
    get('/admin/categories')->assertOk();
    get('/admin/categories/create')->assertOk();
    get('/admin/formations')->assertOk();
    get('/admin/formations/create')->assertOk();
});

it('rend l\'espace formateur', function () {
    actingAs(asRole('FORMATEUR'));

    get('/teacher')->assertOk();
    get('/teacher/formations')->assertOk();
    get('/teacher/formations/create')->assertOk();
    get('/teacher/profil')->assertOk();
});

it('rend l\'espace étudiant', function () {
    actingAs(asRole('ETUDIANT'));

    get('/student')->assertOk();
    get('/student/profil')->assertOk();
});
