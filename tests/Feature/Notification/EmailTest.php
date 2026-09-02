<?php

use App\Mail\EvenementMail;
use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Progression\FormationEtudiant;
use App\Models\Rbac\Role;
use App\Models\User;
use App\Services\Notifier;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function mailUser(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    return $user;
}

it('envoie un email lors de l\'inscription à une formation', function () {
    Mail::fake();
    $formation = Formation::query()->create(['titre' => 'Gratos', 'slug' => 'gratos', 'code' => 'G', 'type' => 'GRATUITE', 'statut' => 'PUBLIE', 'prix' => 0]);
    $user = mailUser();

    actingAs($user)->post(route('student.formations.enroll', $formation));

    Mail::assertSent(EvenementMail::class, fn (EvenementMail $mail) => $mail->hasTo($user->email) && $mail->titre === 'Inscription confirmée');
});

it('envoie un email lors de l\'obtention d\'un certificat', function () {
    Mail::fake();
    $formation = Formation::query()->create(['titre' => 'Finie', 'slug' => 'finie', 'code' => 'FI', 'type' => 'GRATUITE', 'statut' => 'PUBLIE', 'prix' => 0]);
    $user = mailUser();
    FormationEtudiant::query()->create(['etudiant_id' => $user->etudiant->id, 'formation_id' => $formation->id, 'progression' => 100, 'statut' => 'TERMINE']);

    actingAs($user)->post(route('student.certificats.store', $formation));

    Mail::assertSent(EvenementMail::class, fn (EvenementMail $mail) => $mail->titre === 'Certificat obtenu 🎓');
});

it('n\'envoie pas d\'email quand désactivé', function () {
    Mail::fake();
    $user = mailUser();

    app(Notifier::class)->notify($user, 'test', 'Titre', 'Message', email: false);

    Mail::assertNothingSent();
});
