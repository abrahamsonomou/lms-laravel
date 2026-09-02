<?php

use App\Models\Catalogue\Formation;
use App\Models\Coupon\Coupon;
use App\Models\Etudiant\Etudiant;
use App\Models\Facturation\Facture;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Rbac\Role;
use App\Models\User;
use App\Services\CouponValidator;
use Database\Seeders\PaiementSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(PaiementSeeder::class);
});

function client(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

function formationA(float $prix = 100): Formation
{
    return Formation::query()->create([
        'titre' => 'Payante', 'slug' => 'p-'.uniqid(), 'code' => 'P'.random_int(1, 99999),
        'type' => 'PAYANTE', 'statut' => Formation::STATUT_PUBLIE, 'prix' => $prix,
    ]);
}

it('calcule une remise en pourcentage', function () {
    $formation = formationA(100);
    $coupon = Coupon::query()->create(['code' => 'P10', 'type_remise' => 'POURCENTAGE', 'valeur' => 10, 'utilisations' => 0, 'active' => true]);

    $resultat = app(CouponValidator::class)->validate('P10', $formation, 100);

    expect($resultat['valid'])->toBeTrue()
        ->and($resultat['remise'])->toBe(10.0);
});

it('rejette un coupon expiré', function () {
    $formation = formationA();
    Coupon::query()->create(['code' => 'OLD', 'type_remise' => 'MONTANT', 'valeur' => 5, 'utilisations' => 0, 'active' => true, 'date_fin' => now()->subDay()]);

    expect(app(CouponValidator::class)->validate('OLD', $formation, 100)['valid'])->toBeFalse();
});

it('rejette un coupon dont le quota est atteint', function () {
    $formation = formationA();
    Coupon::query()->create(['code' => 'MAX', 'type_remise' => 'MONTANT', 'valeur' => 5, 'nombre_utilisations' => 1, 'utilisations' => 1, 'active' => true]);

    expect(app(CouponValidator::class)->validate('MAX', $formation, 100)['valid'])->toBeFalse();
});

it('rejette un coupon sous le montant minimum', function () {
    $formation = formationA(50);
    Coupon::query()->create(['code' => 'MIN', 'type_remise' => 'MONTANT', 'valeur' => 5, 'montant_minimum' => 80, 'utilisations' => 0, 'active' => true]);

    expect(app(CouponValidator::class)->validate('MIN', $formation, 50)['valid'])->toBeFalse();
});

it('limite un coupon à des formations spécifiques', function () {
    $ciblee = formationA();
    $autre = formationA();
    $coupon = Coupon::query()->create(['code' => 'CIBLE', 'type_remise' => 'MONTANT', 'valeur' => 5, 'utilisations' => 0, 'active' => true]);
    $coupon->formations()->attach($ciblee->id);

    $validator = app(CouponValidator::class);
    expect($validator->validate('CIBLE', $ciblee, 100)['valid'])->toBeTrue()
        ->and($validator->validate('CIBLE', $autre, 100)['valid'])->toBeFalse();
});

it('applique le coupon au checkout et incrémente son usage', function () {
    $formation = formationA(100);
    $user = client();
    $moyen = MoyenPaiement::query()->first();

    actingAs($user)->post(route('student.checkout.store', $formation), [
        'moyen_paiement_id' => $moyen->id,
        'coupon_code' => 'BIENVENUE10',
    ])->assertRedirect();

    $facture = Facture::query()->where('client_id', $user->id)->first();

    expect((float) $facture->remise)->toBe(10.0)
        ->and((float) $facture->total_ttc)->toBe(90.0)
        ->and(Coupon::query()->where('code', 'BIENVENUE10')->value('utilisations'))->toBe(1);
});

it('permet à un admin de créer un coupon', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));

    actingAs($admin)->post(route('admin.coupons.store'), [
        'code' => 'NOEL', 'type_remise' => 'POURCENTAGE', 'valeur' => 25, 'active' => '1',
    ])->assertRedirect(route('admin.coupons.index'));

    expect(Coupon::query()->where('code', 'NOEL')->exists())->toBeTrue();
});
