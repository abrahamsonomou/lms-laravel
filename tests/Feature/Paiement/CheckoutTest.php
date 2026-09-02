<?php

use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Facturation\Facture;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Paiement\TransactionPaiement;
use App\Models\Progression\FormationEtudiant;
use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\PaiementSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(PaiementSeeder::class);
});

function acheteur(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

function formationPayante(float $prix = 50): Formation
{
    return Formation::query()->create([
        'titre' => 'Payante', 'slug' => 'payante-'.uniqid(), 'code' => 'PAY'.random_int(1, 99999),
        'type' => 'PAYANTE', 'statut' => Formation::STATUT_PUBLIE, 'prix' => $prix,
    ]);
}

it('redirige l\'inscription à une formation payante vers le checkout', function () {
    $formation = formationPayante();

    actingAs(acheteur())->post(route('student.formations.enroll', $formation))
        ->assertRedirect(route('student.checkout.show', $formation));
});

it('inscrit directement à une formation gratuite sans passer par le checkout', function () {
    $formation = Formation::query()->create([
        'titre' => 'Gratuite', 'slug' => 'gratuite', 'code' => 'GRAT',
        'type' => 'GRATUITE', 'statut' => Formation::STATUT_PUBLIE, 'prix' => 0,
    ]);
    $user = acheteur();

    actingAs($user)->post(route('student.formations.enroll', $formation))
        ->assertRedirect(route('student.formations.show', $formation));

    expect(FormationEtudiant::query()->where('etudiant_id', $user->etudiant->id)->where('formation_id', $formation->id)->exists())->toBeTrue();
});

it('traite le paiement : facture payée, transaction réussie et inscription', function () {
    $formation = formationPayante(75);
    $user = acheteur();
    $moyen = MoyenPaiement::query()->first();

    actingAs($user)->post(route('student.checkout.store', $formation), ['moyen_paiement_id' => $moyen->id])
        ->assertRedirect();

    $facture = Facture::query()->where('client_id', $user->id)->first();

    expect($facture)->not->toBeNull()
        ->and($facture->statut)->toBe('PAYEE')
        ->and((float) $facture->total_ttc)->toBe(75.0)
        ->and(TransactionPaiement::query()->where('facture_id', $facture->id)->where('statut', 'REUSSI')->exists())->toBeTrue()
        ->and(FormationEtudiant::query()->where('etudiant_id', $user->etudiant->id)->where('formation_id', $formation->id)->exists())->toBeTrue();
});

it('interdit de consulter la facture d\'un autre', function () {
    $formation = formationPayante();
    $user = acheteur();
    $moyen = MoyenPaiement::query()->first();
    actingAs($user)->post(route('student.checkout.store', $formation), ['moyen_paiement_id' => $moyen->id]);
    $facture = Facture::query()->where('client_id', $user->id)->firstOrFail();

    actingAs(acheteur())->get(route('student.factures.show', $facture))->assertForbidden();
});

it('rend les pages du module paiement', function () {
    $formation = formationPayante();
    $user = acheteur();

    actingAs($user);
    get(route('student.checkout.show', $formation))->assertOk();
    get(route('student.factures.index'))->assertOk();

    $admin = User::factory()->create();
    $admin->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));
    actingAs($admin);
    get(route('admin.factures.index'))->assertOk();
    get(route('admin.transactions.index'))->assertOk();
});
