<?php

use App\Models\Rbac\Role;
use App\Models\Studio\StudioPage;
use App\Models\Studio\StudioProject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function adminStudio(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'SUPER_ADMIN')->value('id'));

    return $user;
}

function projetAvecPage(): StudioPage
{
    $project = StudioProject::query()->create(['nom' => 'P', 'type' => 'LANDING', 'statut' => 'PUBLIE']);

    return $project->pages()->create(['nom' => 'Accueil', 'slug' => 'accueil', 'contenu_json' => [], 'ordre' => 1, 'active' => true]);
}

it('permet à un admin de créer un projet', function () {
    actingAs(adminStudio())->post(route('admin.studio.store'), ['nom' => 'Vitrine', 'statut' => 'BROUILLON'])
        ->assertRedirect();

    expect(StudioProject::query()->where('nom', 'Vitrine')->exists())->toBeTrue();
});

it('ajoute puis retire un bloc à une page', function () {
    $page = projetAvecPage();

    actingAs(adminStudio())->post(route('admin.studio.pages.addBlock', $page), ['type' => 'hero', 'titre' => 'Bonjour'])
        ->assertRedirect();

    expect($page->fresh()->contenu_json)->toHaveCount(1)
        ->and($page->fresh()->contenu_json[0]['titre'])->toBe('Bonjour');

    actingAs(adminStudio())->post(route('admin.studio.pages.removeBlock', ['page' => $page, 'index' => 0]));

    expect($page->fresh()->contenu_json)->toHaveCount(0);
});

it('rend publiquement une page active et 404 une page masquée', function () {
    $page = projetAvecPage();
    $page->update(['contenu_json' => [['type' => 'hero', 'titre' => 'Page Publique']]]);

    get('/p/accueil')->assertOk()->assertSee('Page Publique');

    $page->update(['active' => false]);
    get('/p/accueil')->assertNotFound();
});

it('génère un slug unique pour les pages', function () {
    $project = StudioProject::query()->create(['nom' => 'P', 'type' => 'LANDING', 'statut' => 'PUBLIE']);

    actingAs(adminStudio())->post(route('admin.studio.pages.store', $project), ['nom' => 'Accueil']);
    actingAs(adminStudio())->post(route('admin.studio.pages.store', $project), ['nom' => 'Accueil']);

    expect(StudioPage::query()->pluck('slug')->all())->toContain('accueil')->toContain('accueil-2');
});
