<?php

use App\Models\Abonnement\Abonnement;
use App\Models\Abonnement\Plan;
use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Facturation\Facture;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Progression\FormationEtudiant;
use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\PaiementSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(PaiementSeeder::class);
});

function abonne(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

function planMensuel(): Plan
{
    return Plan::query()->create(['code' => 'M', 'nom' => 'Mensuel', 'prix' => 20, 'duree' => 30, 'type' => 'MENSUEL', 'active' => true]);
}

it('permet à un admin de créer un plan', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));

    actingAs($admin)->post(route('admin.plans.store'), ['code' => 'PRO', 'nom' => 'Pro', 'prix' => 99, 'type' => 'ANNUEL'])
        ->assertRedirect(route('admin.plans.index'));

    expect(Plan::query()->where('code', 'PRO')->exists())->toBeTrue();
});

it('active un abonnement après souscription payée', function () {
    $user = abonne();
    $plan = planMensuel();
    $moyen = MoyenPaiement::query()->first();

    actingAs($user)->post(route('student.abonnements.subscribe', $plan), ['moyen_paiement_id' => $moyen->id])
        ->assertRedirect(route('student.abonnements.index'));

    $abonnement = Abonnement::query()->where('plan_id', $plan->id)->first();

    expect($abonnement)->not->toBeNull()
        ->and($abonnement->statut)->toBe('ACTIF')
        ->and($abonnement->utilisateurs()->whereKey($user->id)->exists())->toBeTrue()
        ->and(Facture::query()->where('client_id', $user->id)->where('statut', 'PAYEE')->exists())->toBeTrue()
        ->and($user->fresh()->hasAbonnementActif())->toBeTrue();
});

it('donne accès direct aux formations payantes pour un abonné', function () {
    $user = abonne();
    $plan = planMensuel();
    $moyen = MoyenPaiement::query()->first();
    actingAs($user)->post(route('student.abonnements.subscribe', $plan), ['moyen_paiement_id' => $moyen->id]);

    $formation = Formation::query()->create(['titre' => 'Payante', 'slug' => 'pay', 'code' => 'PAY', 'type' => 'PAYANTE', 'statut' => 'PUBLIE', 'prix' => 80]);

    actingAs($user)->post(route('student.formations.enroll', $formation))
        ->assertRedirect(route('student.formations.show', $formation));

    expect(FormationEtudiant::query()->where('etudiant_id', $user->etudiant->id)->where('formation_id', $formation->id)->exists())->toBeTrue();
});

it('redirige un non-abonné vers le paiement pour une formation payante', function () {
    $user = abonne();
    $formation = Formation::query()->create(['titre' => 'Payante', 'slug' => 'pay2', 'code' => 'PAY2', 'type' => 'PAYANTE', 'statut' => 'PUBLIE', 'prix' => 80]);

    actingAs($user)->post(route('student.formations.enroll', $formation))
        ->assertRedirect(route('student.checkout.show', $formation));
});

it('empêche une double souscription active', function () {
    $user = abonne();
    $plan = planMensuel();
    $moyen = MoyenPaiement::query()->first();
    actingAs($user)->post(route('student.abonnements.subscribe', $plan), ['moyen_paiement_id' => $moyen->id]);

    actingAs($user)->get(route('student.abonnements.checkout', $plan))
        ->assertRedirect(route('student.abonnements.index'));
});
