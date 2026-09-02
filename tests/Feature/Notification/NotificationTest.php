<?php

use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Notification\Notification;
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

function apprenant(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

it('notifie l\'inscription à une formation gratuite', function () {
    $formation = Formation::query()->create(['titre' => 'Gratos', 'slug' => 'gratos', 'code' => 'G', 'type' => 'GRATUITE', 'statut' => 'PUBLIE', 'prix' => 0]);
    $user = apprenant();

    actingAs($user)->post(route('student.formations.enroll', $formation));

    expect(Notification::query()->where('user_id', $user->id)->where('type', 'inscription')->exists())->toBeTrue();
});

it('notifie l\'obtention d\'un certificat', function () {
    $formation = Formation::query()->create(['titre' => 'Finie', 'slug' => 'finie', 'code' => 'FI', 'type' => 'GRATUITE', 'statut' => 'PUBLIE', 'prix' => 0]);
    $user = apprenant();
    FormationEtudiant::query()->create(['etudiant_id' => $user->etudiant->id, 'formation_id' => $formation->id, 'progression' => 100, 'statut' => 'TERMINE']);

    actingAs($user)->post(route('student.certificats.store', $formation));

    expect(Notification::query()->where('user_id', $user->id)->where('type', 'certificat')->exists())->toBeTrue();
});

it('affiche la page des notifications', function () {
    $user = apprenant();
    Notification::query()->create(['user_id' => $user->id, 'type' => 'inscription', 'titre' => 'Bienvenue à bord', 'message' => 'Message test', 'lu' => false]);

    actingAs($user)->get(route('notifications.index'))
        ->assertOk()
        ->assertSee('Bienvenue à bord');
});

it('marque une notification comme lue', function () {
    $user = apprenant();
    $notif = Notification::query()->create(['user_id' => $user->id, 'type' => 'paiement', 'titre' => 'T', 'message' => 'M', 'lu' => false]);

    actingAs($user)->post(route('notifications.read', $notif))->assertRedirect();

    expect($notif->fresh()->lu)->toBeTrue();
});

it('interdit de marquer comme lue la notification d\'un autre', function () {
    $notif = Notification::query()->create(['user_id' => apprenant()->id, 'type' => 'paiement', 'titre' => 'T', 'message' => 'M', 'lu' => false]);

    actingAs(apprenant())->post(route('notifications.read', $notif))->assertForbidden();
});

it('marque toutes les notifications comme lues', function () {
    $user = apprenant();
    Notification::query()->create(['user_id' => $user->id, 'type' => 'inscription', 'titre' => 'A', 'message' => 'M', 'lu' => false]);
    Notification::query()->create(['user_id' => $user->id, 'type' => 'certificat', 'titre' => 'B', 'message' => 'M', 'lu' => false]);

    actingAs($user)->post(route('notifications.readAll'))->assertRedirect();

    expect($user->appNotifications()->where('lu', false)->count())->toBe(0);
});
