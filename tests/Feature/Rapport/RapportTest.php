<?php

use App\Models\Facturation\Facture;
use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function admin3(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));

    return $user;
}

it('affiche le rapport avec le CA de la période', function () {
    Facture::query()->create(['numero' => 'FAC-R1', 'date_facture' => now(), 'total_ttc' => 250, 'statut' => 'PAYEE']);

    actingAs(admin3())->get(route('admin.rapports.index'))
        ->assertOk()
        ->assertSee('250,00');
});

it('exporte les factures en CSV', function () {
    Facture::query()->create(['numero' => 'FAC-CSV', 'date_facture' => now(), 'total_ttc' => 30, 'statut' => 'PAYEE']);

    $response = actingAs(admin3())->get(route('admin.exports.factures'));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('text/csv');
    expect($response->streamedContent())->toContain('FAC-CSV')->toContain('Numero');
});

it('exporte les inscriptions en CSV', function () {
    $response = actingAs(admin3())->get(route('admin.exports.inscriptions'));

    $response->assertOk();
    expect($response->streamedContent())->toContain('Etudiant');
});
