<?php

use App\Models\Bi\FactInscription;
use App\Models\Bi\FactVente;
use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Facturation\Facture;
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

function adminBi(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));

    return $user;
}

it('reconstruit l\'entrepôt à partir des ventes et inscriptions', function () {
    $formation = Formation::query()->create(['titre' => 'BI Formation', 'slug' => 'bi', 'code' => 'BI', 'type' => 'PAYANTE', 'statut' => 'PUBLIE', 'prix' => 100]);
    $facture = Facture::query()->create(['numero' => 'FAC-BI', 'date_facture' => now(), 'total_ttc' => 100, 'statut' => 'PAYEE']);
    $facture->lignes()->create(['formation_id' => $formation->id, 'description' => 'BI', 'quantite' => 1, 'prix_unitaire' => 100, 'total' => 100]);

    $user = User::factory()->create();
    $etudiant = Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);
    FormationEtudiant::query()->create(['etudiant_id' => $etudiant->id, 'formation_id' => $formation->id, 'progression' => 100, 'statut' => 'TERMINE']);

    $this->artisan('bi:rebuild')->assertSuccessful();

    $vente = FactVente::query()->where('formation_id', $formation->id)->first();
    $insc = FactInscription::query()->where('formation_id', $formation->id)->first();

    expect((float) $vente->revenu)->toBe(100.0)
        ->and($vente->nombre_ventes)->toBe(1)
        ->and($insc->nombre_inscriptions)->toBe(1)
        ->and($insc->nombre_termines)->toBe(1);
});

it('affiche le tableau de bord BI', function () {
    $formation = Formation::query()->create(['titre' => 'Analytics 101', 'slug' => 'a', 'code' => 'A', 'type' => 'PAYANTE', 'statut' => 'PUBLIE', 'prix' => 50]);
    $facture = Facture::query()->create(['numero' => 'FAC-A', 'date_facture' => now(), 'total_ttc' => 50, 'statut' => 'PAYEE']);
    $facture->lignes()->create(['formation_id' => $formation->id, 'description' => 'A', 'quantite' => 1, 'prix_unitaire' => 50, 'total' => 50]);
    $this->artisan('bi:rebuild');

    actingAs(adminBi())->get(route('admin.bi.index'))->assertOk()->assertSee('Analytics 101');
});
