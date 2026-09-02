<?php

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

function userRole(string $code): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', $code)->value('id'));

    return $user;
}

it('affiche le chiffre d\'affaires sur le dashboard admin', function () {
    Facture::query()->create(['numero' => 'FAC-1', 'date_facture' => now(), 'total_ttc' => 150, 'statut' => 'PAYEE']);

    actingAs(userRole('SUPER_ADMIN'))
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('150,00');
});

it('affiche les formations du formateur sur son dashboard', function () {
    $prof = userRole('FORMATEUR');
    Formation::query()->create(['titre' => 'Ma Super Formation', 'slug' => 'msf', 'code' => 'MSF', 'type' => 'GRATUITE', 'statut' => 'PUBLIE', 'prix' => 0, 'created_by' => $prof->id]);

    actingAs($prof)
        ->get(route('teacher.dashboard'))
        ->assertOk()
        ->assertSee('Ma Super Formation');
});

it('affiche la progression moyenne sur le dashboard étudiant', function () {
    $user = userRole('ETUDIANT');
    $etudiant = Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);
    $formation = Formation::query()->create(['titre' => 'F', 'slug' => 'f', 'code' => 'F', 'type' => 'GRATUITE', 'statut' => 'PUBLIE', 'prix' => 0]);
    FormationEtudiant::query()->create(['etudiant_id' => $etudiant->id, 'formation_id' => $formation->id, 'progression' => 50, 'statut' => 'EN_COURS']);

    actingAs($user)
        ->get(route('student.dashboard'))
        ->assertOk()
        ->assertSee('50%');
});
