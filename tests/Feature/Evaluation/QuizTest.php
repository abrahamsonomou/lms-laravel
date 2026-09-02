<?php

use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Evaluation\Evaluation;
use App\Models\Evaluation\Reponse;
use App\Models\Progression\FormationEtudiant;
use App\Models\Rbac\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function prof(): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'FORMATEUR')->value('id'));

    return $user;
}

function etudiantInscrit(Formation $formation): User
{
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    $etudiant = Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);
    FormationEtudiant::query()->create(['etudiant_id' => $etudiant->id, 'formation_id' => $formation->id, 'progression' => 0, 'statut' => 'INSCRIT']);

    return $user;
}

/**
 * @return array{0: Formation, 1: Evaluation, 2: array<int, int>} formation, evaluation, [questionId => correctReponseId]
 */
function quizDe(User $auteur): array
{
    $formation = Formation::query()->create([
        'titre' => 'Q', 'slug' => 'q-'.$auteur->id, 'code' => 'Q'.$auteur->id,
        'type' => 'GRATUITE', 'statut' => Formation::STATUT_PUBLIE, 'prix' => 0, 'created_by' => $auteur->id,
    ]);

    $evaluation = $formation->evaluations()->create([
        'titre' => 'Quiz', 'type' => 'QUIZ', 'note_max' => 20, 'note_min' => 10, 'active' => true,
    ]);

    $corrects = [];
    foreach (['Q1', 'Q2'] as $i => $texte) {
        $question = $evaluation->questions()->create(['question' => $texte, 'type' => 'QCM', 'points' => 1, 'ordre' => $i + 1]);
        $bonne = $question->reponses()->create(['libelle' => 'Bonne', 'correcte' => true, 'ordre' => 1]);
        $question->reponses()->create(['libelle' => 'Mauvaise', 'correcte' => false, 'ordre' => 2]);
        $corrects[$question->id] = $bonne->id;
    }

    return [$formation, $evaluation, $corrects];
}

it('rend les pages du module évaluation', function () {
    $auteur = prof();
    [$formation, $evaluation, $corrects] = quizDe($auteur);

    actingAs($auteur);
    get(route('teacher.formations.evaluations.index', $formation))->assertOk();
    get(route('teacher.formations.evaluations.create', $formation))->assertOk();
    get(route('teacher.evaluations.edit', $evaluation))->assertOk();
    get(route('teacher.evaluations.builder', $evaluation))->assertOk();

    $user = etudiantInscrit($formation);
    actingAs($user);
    get(route('student.formations.evaluations.index', $formation))->assertOk();

    $tentative = $evaluation->tentatives()->create(['etudiant_id' => $user->etudiant->id, 'numero' => 1, 'date_debut' => now(), 'statut' => 'EN_COURS']);
    get(route('student.tentatives.take', $tentative))->assertOk();

    $tentative->update(['statut' => 'REUSSI', 'note' => 20]);
    get(route('student.tentatives.result', $tentative))->assertOk();
});

it('permet au formateur de créer une évaluation', function () {
    $auteur = prof();
    $formation = Formation::query()->create(['titre' => 'F', 'slug' => 'f', 'code' => 'F', 'type' => 'GRATUITE', 'statut' => 'PUBLIE', 'prix' => 0, 'created_by' => $auteur->id]);

    actingAs($auteur)->post(route('teacher.formations.evaluations.store', $formation), ['titre' => 'Mon quiz', 'type' => 'QUIZ'])
        ->assertRedirect();

    expect(Evaluation::query()->where('titre', 'Mon quiz')->exists())->toBeTrue();
});

it('interdit d\'ajouter une question à l\'évaluation d\'un autre', function () {
    [, $evaluation] = quizDe(prof());

    actingAs(prof())->post(route('teacher.evaluations.questions.store', $evaluation), ['question' => 'X', 'type' => 'QCM'])
        ->assertForbidden();
});

it('refuse le démarrage à un étudiant non inscrit', function () {
    [, $evaluation] = quizDe(prof());
    $user = User::factory()->create();
    $user->roles()->attach(Role::query()->where('code', 'ETUDIANT')->value('id'));
    Etudiant::query()->create(['user_id' => $user->id, 'active' => true]);

    actingAs($user)->post(route('student.evaluations.start', $evaluation))->assertForbidden();
});

it('note 20/20 un quiz entièrement réussi', function () {
    [$formation, $evaluation, $corrects] = quizDe(prof());
    $user = etudiantInscrit($formation);

    $tentative = $evaluation->tentatives()->create(['etudiant_id' => $user->etudiant->id, 'numero' => 1, 'date_debut' => now(), 'statut' => 'EN_COURS']);

    $answers = [];
    foreach ($corrects as $questionId => $reponseId) {
        $answers[$questionId] = $reponseId;
    }

    actingAs($user)->post(route('student.tentatives.submit', $tentative), ['answers' => $answers])
        ->assertRedirect(route('student.tentatives.result', $tentative));

    $tentative->refresh();
    expect((float) $tentative->note)->toBe(20.0)
        ->and($tentative->statut)->toBe('REUSSI');
});

it('note 0 un quiz entièrement faux', function () {
    [$formation, $evaluation, $corrects] = quizDe(prof());
    $user = etudiantInscrit($formation);
    $tentative = $evaluation->tentatives()->create(['etudiant_id' => $user->etudiant->id, 'numero' => 1, 'date_debut' => now(), 'statut' => 'EN_COURS']);

    // Choisir volontairement une mauvaise réponse pour chaque question
    $answers = [];
    foreach (array_keys($corrects) as $questionId) {
        $mauvaise = Reponse::query()->where('question_id', $questionId)->where('correcte', false)->value('id');
        $answers[$questionId] = $mauvaise;
    }

    actingAs($user)->post(route('student.tentatives.submit', $tentative), ['answers' => $answers]);

    $tentative->refresh();
    expect((float) $tentative->note)->toBe(0.0)
        ->and($tentative->statut)->toBe('ECHOUE');
});
