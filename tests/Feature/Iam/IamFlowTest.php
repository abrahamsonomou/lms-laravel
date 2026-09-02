<?php

use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function userWithRole(string $code): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', $code)->value('id'));

    return $user;
}

it('inscrit un nouvel étudiant, le connecte et le redirige vers son espace', function () {
    post('/register', [
        'prenom' => 'Awa',
        'nom' => 'Diallo',
        'email' => 'awa@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('student.dashboard'));

    $user = User::query()->where('email', 'awa@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->isEtudiant())->toBeTrue()
        ->and($user->etudiant)->not->toBeNull();
});

it('redirige un admin vers le backoffice après connexion', function () {
    $admin = userWithRole('SUPER_ADMIN');

    post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.dashboard'));
});

it('redirige un formateur vers son espace après connexion', function () {
    $teacher = userWithRole('FORMATEUR');

    post('/login', ['email' => $teacher->email, 'password' => 'password'])
        ->assertRedirect(route('teacher.dashboard'));
});

it('interdit au student le backoffice', function () {
    actingAs(userWithRole('ETUDIANT'))
        ->get('/admin/users')
        ->assertForbidden();
});

it('autorise un admin à lister les utilisateurs', function () {
    actingAs(userWithRole('SUPER_ADMIN'))
        ->get('/admin/users')
        ->assertOk()
        ->assertSee('Utilisateurs');
});

it('redirige un invité vers la connexion', function () {
    get('/admin')->assertRedirect(route('login'));
});
