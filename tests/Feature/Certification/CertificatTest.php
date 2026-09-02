<?php

use App\Models\Catalogue\Formation;
use App\Models\Certification\Certificat;
use App\Models\Etudiant\Etudiant;
use App\Models\Progression\FormationEtudiant;
use App\Models\Rbac\Role;
use App\Models\User;
use App\Services\CertificatIssuer;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function etudiant(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

function formationTerminee(User $user, float $progression = 100): Formation
{
    $formation = Formation::query()->create([
        'titre' => 'Terminée', 'slug' => 'terminee-'.$user->id, 'code' => 'T'.$user->id,
        'type' => 'GRATUITE', 'statut' => Formation::STATUT_PUBLIE, 'prix' => 0,
    ]);
    FormationEtudiant::query()->create(['etudiant_id' => $user->etudiant->id, 'formation_id' => $formation->id, 'progression' => $progression, 'statut' => 'EN_COURS']);

    return $formation;
}

it('délivre un certificat pour une formation terminée à 100%', function () {
    $user = etudiant();
    $formation = formationTerminee($user);

    actingAs($user)->post(route('student.certificats.store', $formation))
        ->assertRedirect();

    $certificat = Certificat::query()->where('etudiant_id', $user->etudiant->id)->where('formation_id', $formation->id)->first();

    expect($certificat)->not->toBeNull()
        ->and($certificat->numero)->toStartWith('CERT-')
        ->and($certificat->hash_verification)->not->toBeEmpty()
        ->and($certificat->statut)->toBe('VALIDE');
});

it('refuse le certificat si la formation n\'est pas terminée', function () {
    $user = etudiant();
    $formation = formationTerminee($user, 40);

    actingAs($user)->post(route('student.certificats.store', $formation));

    expect(Certificat::query()->count())->toBe(0);
});

it('est idempotent : pas de doublon de certificat', function () {
    $user = etudiant();
    $formation = formationTerminee($user);
    $issuer = app(CertificatIssuer::class);

    $a = $issuer->issueFor($user->etudiant, $formation);
    $b = $issuer->issueFor($user->etudiant, $formation);

    expect($a->id)->toBe($b->id)
        ->and(Certificat::query()->count())->toBe(1);
});

it('interdit de consulter le certificat d\'un autre', function () {
    $proprietaire = etudiant();
    $formation = formationTerminee($proprietaire);
    $certificat = app(CertificatIssuer::class)->issueFor($proprietaire->etudiant, $formation);

    actingAs(etudiant())->get(route('student.certificats.show', $certificat))->assertForbidden();
});

it('vérifie publiquement un certificat valide et rejette un hash inconnu', function () {
    $user = etudiant();
    $formation = formationTerminee($user);
    $certificat = app(CertificatIssuer::class)->issueFor($user->etudiant, $formation);

    get(route('certificats.verify', $certificat->hash_verification))
        ->assertOk()
        ->assertSee('Certificat authentique');

    get(route('certificats.verify', 'hash-bidon'))
        ->assertOk()
        ->assertSee('Certificat introuvable');
});

it('rend les pages certificats (étudiant + admin)', function () {
    $user = etudiant();
    $formation = formationTerminee($user);
    $certificat = app(CertificatIssuer::class)->issueFor($user->etudiant, $formation);

    actingAs($user);
    get(route('student.certificats.index'))->assertOk();
    get(route('student.certificats.show', $certificat))->assertOk()->assertSee($certificat->numero);

    $admin = User::factory()->create();
    $admin->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));
    actingAs($admin)->get(route('admin.certificats.index'))->assertOk();
});
