<?php

use App\Models\Chat\Conversation;
use App\Models\Notification\Notification;
use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function withRoleS(string $code): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', $code)->value('id'));

    return $user;
}

it('permet à un étudiant d\'ouvrir une demande de support', function () {
    $user = withRoleS('ETUDIANT');

    actingAs($user)->post(route('support.store'), ['titre' => 'Problème de connexion', 'message' => 'Je ne peux pas me connecter.'])
        ->assertRedirect();

    $conversation = Conversation::query()->where('type', 'SUPPORT')->where('created_by', $user->id)->first();

    expect($conversation)->not->toBeNull()
        ->and($conversation->titre)->toBe('Problème de connexion')
        ->and($conversation->messages()->count())->toBe(1);
});

it('interdit à un étudiant de voir la conversation d\'un autre', function () {
    $conversation = Conversation::query()->create(['type' => 'SUPPORT', 'titre' => 'X', 'created_by' => withRoleS('ETUDIANT')->id, 'active' => true]);

    actingAs(withRoleS('ETUDIANT'))->get(route('support.show', $conversation))->assertForbidden();
});

it('permet à un admin de répondre et notifie le client', function () {
    $client = withRoleS('ETUDIANT');
    $conversation = Conversation::query()->create(['type' => 'SUPPORT', 'titre' => 'Aide', 'created_by' => $client->id, 'active' => true]);
    $conversation->messages()->create(['user_id' => $client->id, 'type' => 'TEXTE', 'contenu' => 'Bonjour', 'date_envoi' => now()]);

    actingAs(withRoleS('SUPPORT'))->get(route('support.show', $conversation))->assertOk();

    actingAs(withRoleS('SUPPORT'))->post(route('support.reply', $conversation), ['contenu' => 'Nous vous aidons.'])
        ->assertRedirect();

    expect($conversation->messages()->count())->toBe(2)
        ->and(Notification::query()->where('user_id', $client->id)->where('type', 'support')->exists())->toBeTrue();
});

it('liste toutes les conversations support pour un admin', function () {
    Conversation::query()->create(['type' => 'SUPPORT', 'titre' => 'Ticket A', 'created_by' => withRoleS('ETUDIANT')->id, 'active' => true]);

    actingAs(withRoleS('SUPPORT'))->get(route('support.index'))->assertOk()->assertSee('Ticket A');
});
