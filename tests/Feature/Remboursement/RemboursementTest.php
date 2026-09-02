<?php

use App\Models\Abonnement\Abonnement;
use App\Models\Abonnement\Plan;
use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Facturation\Facture;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Paiement\Remboursement;
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

function adminUser(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));

    return $user;
}

function client2(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

it('rembourse un achat de formation et révoque l\'accès', function () {
    $user = client2();
    $moyen = MoyenPaiement::query()->first();
    $formation = Formation::query()->create(['titre' => 'Payante', 'slug' => 'pay', 'code' => 'PAY', 'type' => 'PAYANTE', 'statut' => 'PUBLIE', 'prix' => 60]);

    actingAs($user)->post(route('student.checkout.store', $formation), ['moyen_paiement_id' => $moyen->id]);
    $facture = Facture::query()->where('client_id', $user->id)->firstOrFail();

    actingAs(adminUser())->post(route('admin.factures.refund', $facture), ['motif' => 'Demande client'])->assertRedirect();

    expect($facture->fresh()->statut)->toBe('REMBOURSEE')
        ->and(Remboursement::query()->count())->toBe(1)
        ->and(FormationEtudiant::query()->where('etudiant_id', $user->etudiant->id)->where('formation_id', $formation->id)->exists())->toBeFalse();
});

it('rembourse un abonnement et l\'annule', function () {
    $user = client2();
    $moyen = MoyenPaiement::query()->first();
    $plan = Plan::query()->create(['code' => 'M', 'nom' => 'Mensuel', 'prix' => 20, 'duree' => 30, 'type' => 'MENSUEL', 'active' => true]);

    actingAs($user)->post(route('student.abonnements.subscribe', $plan), ['moyen_paiement_id' => $moyen->id]);
    $abonnement = Abonnement::query()->where('plan_id', $plan->id)->firstOrFail();

    actingAs(adminUser())->post(route('admin.factures.refund', $abonnement->facture_id), ['motif' => 'Insatisfaction']);

    expect($abonnement->fresh()->statut)->toBe('ANNULE')
        ->and($user->fresh()->hasAbonnementActif())->toBeFalse();
});

it('refuse de rembourser une facture non payée', function () {
    $facture = Facture::query()->create(['numero' => 'FAC-X', 'date_facture' => now(), 'total_ttc' => 10, 'statut' => 'BROUILLON']);

    actingAs(adminUser())->post(route('admin.factures.refund', $facture), ['motif' => 'Test'])
        ->assertSessionHas('error');

    expect(Remboursement::query()->count())->toBe(0);
});

it('rend la liste des remboursements', function () {
    actingAs(adminUser())->get(route('admin.remboursements.index'))->assertOk();
});
