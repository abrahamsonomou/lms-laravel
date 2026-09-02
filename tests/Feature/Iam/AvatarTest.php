<?php

use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

it('téléverse un avatar depuis le profil étudiant', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));

    actingAs($user);

    put('/student/profil', [
        'prenom' => 'Awa',
        'nom' => 'Bah',
        'email' => $user->email,
        'avatar' => UploadedFile::fake()->image('avatar.jpg'),
    ])->assertRedirect();

    $user->refresh();

    expect($user->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar);
});

it('réinitialise la vérification email quand l\'adresse change', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));

    actingAs($user);

    put('/student/profil', [
        'prenom' => 'Awa',
        'nom' => 'Bah',
        'email' => 'nouvelle@example.com',
    ])->assertRedirect();

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
