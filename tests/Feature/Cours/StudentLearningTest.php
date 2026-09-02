<?php

use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Progression\FormationEtudiant;
use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function etudiantUser(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

function formationAvecLecon(): array
{
    $formation = Formation::query()->create([
        'titre' => 'Apprendre', 'slug' => 'apprendre', 'code' => 'AP',
        'type' => 'GRATUITE', 'statut' => Formation::STATUT_PUBLIE, 'prix' => 0,
    ]);
    $cours = $formation->cours()->create(['titre' => 'C', 'code' => 'C', 'ordre' => 1, 'statut' => 'PUBLIE', 'active' => true]);
    $module = $cours->modules()->create(['titre' => 'M', 'ordre' => 1, 'active' => true]);
    $chapitre = $module->chapitres()->create(['titre' => 'Ch', 'ordre' => 1, 'active' => true]);
    $lecon = $chapitre->lecons()->create(['titre' => 'L', 'type' => 'TEXTE', 'ordre' => 1, 'obligatoire' => true, 'active' => true]);

    return [$formation, $lecon];
}

it('inscrit un étudiant à une formation publiée', function () {
    [$formation] = formationAvecLecon();
    $user = etudiantUser();

    actingAs($user)->post(route('student.formations.enroll', $formation))
        ->assertRedirect(route('student.formations.show', $formation));

    expect(FormationEtudiant::query()->where('etudiant_id', $user->etudiant->id)->where('formation_id', $formation->id)->exists())->toBeTrue();
});

it('refuse le lecteur à un étudiant non inscrit', function () {
    [$formation] = formationAvecLecon();

    actingAs(etudiantUser())->get(route('student.formations.show', $formation))->assertForbidden();
});

it('affiche le lecteur à un étudiant inscrit', function () {
    [$formation] = formationAvecLecon();
    $user = etudiantUser();
    FormationEtudiant::query()->create(['etudiant_id' => $user->etudiant->id, 'formation_id' => $formation->id, 'progression' => 0, 'statut' => 'INSCRIT']);

    actingAs($user)->get(route('student.formations.show', $formation))->assertOk();
});

it('rend l\'index des formations suivies', function () {
    actingAs(etudiantUser())->get(route('student.formations.index'))->assertOk();
});

it('met à jour la progression à 100% après avoir terminé la seule leçon', function () {
    [$formation, $lecon] = formationAvecLecon();
    $user = etudiantUser();
    FormationEtudiant::query()->create(['etudiant_id' => $user->etudiant->id, 'formation_id' => $formation->id, 'progression' => 0, 'statut' => 'INSCRIT']);

    actingAs($user)->post(route('student.lecons.complete', $lecon))->assertRedirect();

    $inscription = FormationEtudiant::query()->where('etudiant_id', $user->etudiant->id)->where('formation_id', $formation->id)->first();

    expect((float) $inscription->progression)->toBe(100.0)
        ->and($inscription->statut)->toBe('TERMINE');
});
