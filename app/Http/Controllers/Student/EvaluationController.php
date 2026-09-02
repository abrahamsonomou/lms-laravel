<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use App\Models\Evaluation\Evaluation;
use App\Models\Evaluation\Tentative;
use App\Models\Progression\FormationEtudiant;
use App\Services\QuizGrader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function index(Request $request, Formation $formation): View
    {
        $etudiant = $this->etudiant($request);
        $this->assertInscrit($etudiant->id, $formation->id);

        $formation->load(['evaluations' => fn ($q) => $q->where('active', true)->withCount('questions')]);

        $tentatives = Tentative::query()
            ->where('etudiant_id', $etudiant->id)
            ->whereIn('evaluation_id', $formation->evaluations->pluck('id'))
            ->get()
            ->groupBy('evaluation_id');

        return view('student.evaluations.index', compact('formation', 'tentatives'));
    }

    public function start(Request $request, Evaluation $evaluation): RedirectResponse
    {
        $etudiant = $this->etudiant($request);
        $evaluation->loadMissing('formation');
        $this->assertInscrit($etudiant->id, $evaluation->formation_id);

        $dejaFaites = Tentative::query()->where('evaluation_id', $evaluation->id)->where('etudiant_id', $etudiant->id)->count();

        if ($evaluation->tentatives_max !== null && $dejaFaites >= $evaluation->tentatives_max) {
            return redirect()->route('student.formations.evaluations.index', $evaluation->formation_id)
                ->with('error', 'Nombre maximum de tentatives atteint.');
        }

        $tentative = $evaluation->tentatives()->create([
            'etudiant_id' => $etudiant->id,
            'numero' => $dejaFaites + 1,
            'date_debut' => now(),
            'statut' => 'EN_COURS',
        ]);

        return redirect()->route('student.tentatives.take', $tentative);
    }

    public function take(Request $request, Tentative $tentative): View|RedirectResponse
    {
        $this->assertOwnsTentative($request, $tentative);

        if ($tentative->statut !== 'EN_COURS') {
            return redirect()->route('student.tentatives.result', $tentative);
        }

        $tentative->load(['evaluation.questions' => fn ($q) => $q->orderBy('ordre'), 'evaluation.questions.reponses' => fn ($q) => $q->orderBy('ordre')]);

        return view('student.evaluations.take', compact('tentative'));
    }

    public function submit(Request $request, Tentative $tentative, QuizGrader $grader): RedirectResponse
    {
        $this->assertOwnsTentative($request, $tentative);

        abort_unless($tentative->statut === 'EN_COURS', 403);

        /** @var array<int, mixed> $raw */
        $raw = $request->input('answers', []);
        $answers = collect($raw)->map(fn ($value): array => array_map('intval', (array) $value))->all();

        $grader->grade($tentative, $answers);

        return redirect()->route('student.tentatives.result', $tentative)->with('success', 'Évaluation soumise.');
    }

    public function result(Request $request, Tentative $tentative): View
    {
        $this->assertOwnsTentative($request, $tentative);

        $tentative->load([
            'evaluation.questions' => fn ($q) => $q->orderBy('ordre'),
            'evaluation.questions.reponses' => fn ($q) => $q->orderBy('ordre'),
            'reponsesEtudiants',
        ]);

        return view('student.evaluations.result', compact('tentative'));
    }

    private function etudiant(Request $request): Etudiant
    {
        $etudiant = $request->user()->etudiant;
        abort_if($etudiant === null, 403, 'Profil étudiant introuvable.');

        return $etudiant;
    }

    private function assertInscrit(int $etudiantId, int $formationId): void
    {
        $inscrit = FormationEtudiant::query()->where('etudiant_id', $etudiantId)->where('formation_id', $formationId)->exists();
        abort_unless($inscrit, 403, "Vous n'êtes pas inscrit à cette formation.");
    }

    private function assertOwnsTentative(Request $request, Tentative $tentative): void
    {
        abort_unless($tentative->etudiant_id === $this->etudiant($request)->id, 403);
    }
}
