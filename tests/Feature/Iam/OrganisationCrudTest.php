<?php

use App\Models\Organisation\Etablissement;
use App\Models\Organisation\Organisation;
use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));
    actingAs($admin);
});

it('crée une organisation avec logo', function () {
    Storage::fake('public');

    post('/admin/organisations', [
        'code' => 'ORG1',
        'nom' => 'Institut Numérique',
        'active' => '1',
        'logo' => UploadedFile::fake()->image('logo.png'),
    ])->assertRedirect(route('admin.organisations.index'));

    $organisation = Organisation::query()->where('code', 'ORG1')->first();

    expect($organisation)->not->toBeNull();
    Storage::disk('public')->assertExists($organisation->logo);
});

it('valide le code unique d\'organisation', function () {
    Organisation::query()->create(['code' => 'DUP', 'nom' => 'A', 'active' => true]);

    post('/admin/organisations', ['code' => 'DUP', 'nom' => 'B'])
        ->assertSessionHasErrors('code');
});

it('met à jour puis supprime une organisation', function () {
    $organisation = Organisation::query()->create(['code' => 'ORG2', 'nom' => 'Ancien', 'active' => true]);

    put("/admin/organisations/{$organisation->id}", ['code' => 'ORG2', 'nom' => 'Nouveau'])
        ->assertRedirect(route('admin.organisations.index'));

    expect($organisation->fresh()->nom)->toBe('Nouveau');

    delete("/admin/organisations/{$organisation->id}")
        ->assertRedirect(route('admin.organisations.index'));

    expect(Organisation::query()->find($organisation->id))->toBeNull();
});

it('crée un établissement rattaché à une organisation', function () {
    $organisation = Organisation::query()->create(['code' => 'ORG3', 'nom' => 'Campus', 'active' => true]);

    post('/admin/etablissements', [
        'organisation_id' => $organisation->id,
        'code' => 'ETB1',
        'nom' => 'Campus Centre',
        'active' => '1',
    ])->assertRedirect(route('admin.etablissements.index'));

    expect(Etablissement::query()->where('code', 'ETB1')->value('organisation_id'))->toBe($organisation->id);
});
