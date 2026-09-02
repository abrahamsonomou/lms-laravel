<?php

use App\Models\Catalogue\Formation;
use App\Models\Cours\Cours;
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

function formateur(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'FORMATEUR')->value('id'));

    return $user;
}

function coursDe(User $auteur): Cours
{
    $formation = Formation::query()->create([
        'titre' => 'F', 'slug' => 'f-'.$auteur->id, 'code' => 'F'.$auteur->id,
        'type' => 'GRATUITE', 'statut' => Formation::STATUT_PUBLIE, 'prix' => 0,
        'created_by' => $auteur->id,
    ]);

    return $formation->cours()->create(['titre' => 'Cours 1', 'code' => 'C1', 'ordre' => 1, 'statut' => 'PUBLIE', 'active' => true]);
}

it('permet au formateur d\'ajouter un module à son cours', function () {
    $auteur = formateur();
    $cours = coursDe($auteur);

    actingAs($auteur)->post(route('teacher.cours.modules.store', $cours), ['titre' => 'Module A'])
        ->assertRedirect();

    expect($cours->modules()->where('titre', 'Module A')->exists())->toBeTrue();
});

it('interdit d\'ajouter un module au cours d\'un autre formateur', function () {
    $cours = coursDe(formateur());

    actingAs(formateur())->post(route('teacher.cours.modules.store', $cours), ['titre' => 'Pirate'])
        ->assertForbidden();
});

it('rend les pages cours et builder du formateur', function () {
    $auteur = formateur();
    $cours = coursDe($auteur);
    $formation = $cours->formation;

    actingAs($auteur);

    get(route('teacher.formations.cours.index', $formation))->assertOk();
    get(route('teacher.formations.cours.create', $formation))->assertOk();
    get(route('teacher.cours.edit', $cours))->assertOk();
    get(route('teacher.cours.builder', $cours))->assertOk();
});

it('permet d\'ajouter un chapitre puis une leçon', function () {
    $auteur = formateur();
    $cours = coursDe($auteur);
    $module = $cours->modules()->create(['titre' => 'M', 'ordre' => 1, 'active' => true]);

    actingAs($auteur)->post(route('teacher.modules.chapitres.store', $module), ['titre' => 'Chapitre 1'])
        ->assertRedirect();

    $chapitre = $module->chapitres()->first();
    expect($chapitre)->not->toBeNull();

    actingAs($auteur)->post(route('teacher.chapitres.lecons.store', $chapitre), [
        'titre' => 'Leçon 1', 'type' => 'VIDEO', 'url' => 'https://example.com/v',
    ])->assertRedirect();

    $lecon = $chapitre->lecons()->first();
    expect($lecon)->not->toBeNull()
        ->and($lecon->contenus()->count())->toBe(1);
});
