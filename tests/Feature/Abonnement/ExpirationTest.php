<?php

use App\Models\Abonnement\Plan;
use App\Models\Etudiant\Etudiant;
use App\Models\Facturation\Facture;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Rbac\Role;
use App\Models\User;
use App\Services\AbonnementService;
use Database\Seeders\PaiementSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(PaiementSeeder::class);
});

function abonneEchu(bool $autoRenew): array
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    $plan = Plan::query()->create(['code' => 'M', 'nom' => 'Mensuel', 'prix' => 20, 'duree' => 30, 'type' => 'MENSUEL', 'active' => true]);
    $moyen = MoyenPaiement::query()->first();

    $abonnement = app(AbonnementService::class)->subscribe($user, $plan, $moyen);
    $abonnement->update(['date_fin' => now()->subDay(), 'auto_renew' => $autoRenew]);

    return [$user, $abonnement];
}

it('expire un abonnement échu sans auto-renouvellement', function () {
    [$user, $abonnement] = abonneEchu(false);

    $this->artisan('abonnements:expirer')->assertSuccessful();

    expect($abonnement->fresh()->statut)->toBe('EXPIRE')
        ->and($user->fresh()->hasAbonnementActif())->toBeFalse();
});

it('renouvelle un abonnement échu en auto-renouvellement', function () {
    [$user, $abonnement] = abonneEchu(true);
    $facturesAvant = Facture::query()->count();

    $this->artisan('abonnements:expirer')->assertSuccessful();

    $abonnement->refresh();

    expect($abonnement->statut)->toBe('ACTIF')
        ->and($abonnement->date_fin->isFuture())->toBeTrue()
        ->and($user->fresh()->hasAbonnementActif())->toBeTrue()
        ->and(Facture::query()->count())->toBe($facturesAvant + 1);
});

it('permet à l\'étudiant de basculer le renouvellement auto', function () {
    [$user, $abonnement] = abonneEchu(false);

    actingAs($user)->post(route('student.abonnements.toggleRenew', $abonnement))->assertRedirect();

    expect($abonnement->fresh()->auto_renew)->toBeTrue();
});
