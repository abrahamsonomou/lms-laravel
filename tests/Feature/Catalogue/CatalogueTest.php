<?php

use App\Models\Catalogue\Formation;
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

function withRole(string $code): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', $code)->value('id'));

    return $user;
}

function publiee(array $attributes = []): Formation
{
    return Formation::query()->create(array_merge([
        'titre' => 'Formation publiée',
        'slug' => 'formation-publiee',
        'code' => 'FP',
        'type' => 'PAYANTE',
        'statut' => Formation::STATUT_PUBLIE,
        'prix' => 10,
        'date_publication' => now(),
    ], $attributes));
}

it('permet à un admin de créer une formation avec slug auto', function () {
    $admin = withRole('SUPER_ADMIN');

    actingAs($admin)->post('/admin/formations', [
        'titre' => 'Introduction à Laravel',
        'type' => 'PAYANTE',
        'statut' => 'BROUILLON',
        'prix' => 25,
    ])->assertRedirect(route('admin.formations.index'));

    $formation = Formation::query()->where('titre', 'Introduction à Laravel')->first();

    expect($formation)->not->toBeNull()
        ->and($formation->slug)->toBe('introduction-a-laravel')
        ->and($formation->created_by)->toBe($admin->id);
});

it('n\'affiche que les formations publiées dans le catalogue public', function () {
    publiee(['titre' => 'Cours Public', 'slug' => 'cours-public']);
    Formation::query()->create([
        'titre' => 'Cours Brouillon', 'slug' => 'cours-brouillon', 'code' => 'CB',
        'type' => 'PAYANTE', 'statut' => Formation::STATUT_BROUILLON, 'prix' => 5,
    ]);

    get('/formations')
        ->assertOk()
        ->assertSee('Cours Public')
        ->assertDontSee('Cours Brouillon');
});

it('affiche une formation publiée et 404 pour un brouillon', function () {
    $publiee = publiee(['titre' => 'Détail Public', 'slug' => 'detail-public']);
    $brouillon = Formation::query()->create([
        'titre' => 'Détail Brouillon', 'slug' => 'detail-brouillon', 'code' => 'DB',
        'type' => 'PAYANTE', 'statut' => Formation::STATUT_BROUILLON, 'prix' => 5,
    ]);

    get("/formations/{$publiee->slug}")->assertOk()->assertSee('Détail Public');
    get("/formations/{$brouillon->slug}")->assertNotFound();
});

it('empêche un formateur de modifier la formation d\'un autre', function () {
    $auteur = withRole('FORMATEUR');
    $autre = withRole('FORMATEUR');

    $formation = publiee(['slug' => 'appartient-auteur', 'created_by' => $auteur->id]);

    actingAs($autre)->get("/teacher/formations/{$formation->id}/edit")->assertForbidden();
});
