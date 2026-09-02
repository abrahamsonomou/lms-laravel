<?php

use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function unverifiedWithRole(string $code): User
{
    $user = User::factory()->unverified()->create();
    $user->roles()->attach(Role::query()->where('code', $code)->value('id'));

    return $user;
}

it('redirige un utilisateur non vérifié vers la notice de vérification', function () {
    actingAs(unverifiedWithRole('ETUDIANT'))
        ->get('/student')
        ->assertRedirect(route('verification.notice'));
});

it('affiche la notice de vérification', function () {
    actingAs(unverifiedWithRole('ETUDIANT'))
        ->get('/email/verify')
        ->assertOk()
        ->assertSee('Vérifiez votre email');
});

it('vérifie l\'email via le lien signé', function () {
    Event::fake();
    $user = unverifiedWithRole('ETUDIANT');

    $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
        'id' => $user->id,
        'hash' => sha1($user->email),
    ]);

    actingAs($user)->get($url)->assertRedirect(route('student.dashboard'));

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    Event::assertDispatched(Verified::class);
});

it('renvoie un lien de vérification', function () {
    Notification::fake();

    actingAs(unverifiedWithRole('ETUDIANT'))
        ->post('/email/verification-notification')
        ->assertRedirect();

    Notification::assertSentTo(
        User::query()->latest('id')->first(),
        VerifyEmail::class
    );
});
