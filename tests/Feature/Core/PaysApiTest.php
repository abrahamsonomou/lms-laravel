<?php

use App\Models\Core\Pays;
use App\Models\Core\Ville;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

it('liste les pays paginés', function () {
    Pays::query()->create(['code' => 'GN', 'iso2' => 'GN', 'iso3' => 'GIN', 'nom' => 'Guinée']);
    Pays::query()->create(['code' => 'SN', 'iso2' => 'SN', 'iso3' => 'SEN', 'nom' => 'Sénégal']);

    getJson('/api/v1/pays')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure(['data' => [['id', 'code', 'iso2', 'iso3', 'nom', 'active']], 'meta', 'links']);
});

it('crée un pays', function () {
    postJson('/api/v1/pays', [
        'code' => 'CI',
        'iso2' => 'CI',
        'iso3' => 'CIV',
        'nom' => "Côte d'Ivoire",
        'indicatif_telephone' => '+225',
    ])
        ->assertCreated()
        ->assertJsonPath('data.code', 'CI');

    expect(Pays::query()->where('code', 'CI')->exists())->toBeTrue();
});

it('valide les champs requis et le format iso2', function () {
    postJson('/api/v1/pays', ['iso2' => 'XXX'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code', 'iso3', 'nom', 'iso2']);
});

it('empêche un code dupliqué', function () {
    Pays::query()->create(['code' => 'FR', 'iso2' => 'FR', 'iso3' => 'FRA', 'nom' => 'France']);

    postJson('/api/v1/pays', ['code' => 'FR', 'iso2' => 'FR', 'iso3' => 'FRA', 'nom' => 'France'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code']);
});

it('affiche un pays avec ses villes', function () {
    $pays = Pays::query()->create(['code' => 'ML', 'iso2' => 'ML', 'iso3' => 'MLI', 'nom' => 'Mali']);
    Ville::query()->create(['pays_id' => $pays->id, 'nom' => 'Bamako']);

    getJson("/api/v1/pays/{$pays->id}")
        ->assertOk()
        ->assertJsonPath('data.nom', 'Mali')
        ->assertJsonPath('data.villes.0.nom', 'Bamako');
});

it('met à jour un pays', function () {
    $pays = Pays::query()->create(['code' => 'US', 'iso2' => 'US', 'iso3' => 'USA', 'nom' => 'USA']);

    putJson("/api/v1/pays/{$pays->id}", ['nom' => 'États-Unis'])
        ->assertOk()
        ->assertJsonPath('data.nom', 'États-Unis');
});

it('supprime un pays', function () {
    $pays = Pays::query()->create(['code' => 'CD', 'iso2' => 'CD', 'iso3' => 'COD', 'nom' => 'RD Congo']);

    deleteJson("/api/v1/pays/{$pays->id}")->assertNoContent();

    expect(Pays::query()->find($pays->id))->toBeNull();
});
