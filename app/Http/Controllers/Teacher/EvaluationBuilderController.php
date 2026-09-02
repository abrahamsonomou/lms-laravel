<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Concerns\ResolvesCourseOwnership;
use App\Http\Controllers\Controller;
use App\Models\Evaluation\Evaluation;
use App\Models\Evaluation\Question;
use App\Models\Evaluation\Reponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationBuilderController extends Controller
{
    use ResolvesCourseOwnership;

    public function show(Request $request, Evaluation $evaluation): View
    {
        $this->assertOwnsEvaluation($evaluation->load('formation'), $request->user()->id);

        $evaluation->load(['questions' => fn ($q) => $q->orderBy('ordre'), 'questions.reponses' => fn ($q) => $q->orderBy('ordre')]);

        return view('teacher.evaluations.builder', [
            'evaluation' => $evaluation,
            'typesQuestion' => Question::TYPES,
        ]);
    }

    public function storeQuestion(Request $request, Evaluation $evaluation): RedirectResponse
    {
        $this->assertOwnsEvaluation($evaluation->load('formation'), $request->user()->id);

        $data = $request->validate([
            'question' => ['required', 'string'],
            'type' => ['required', 'in:'.implode(',', Question::TYPES)],
            'points' => ['nullable', 'numeric', 'min:0'],
            'explication' => ['nullable', 'string'],
        ]);

        $evaluation->questions()->create([
            'question' => $data['question'],
            'type' => $data['type'],
            'points' => $data['points'] ?? 1,
            'explication' => $data['explication'] ?? null,
            'ordre' => $evaluation->questions()->max('ordre') + 1,
        ]);

        return back()->with('success', 'Question ajoutée.');
    }

    public function destroyQuestion(Request $request, Question $question): RedirectResponse
    {
        $this->assertOwnsQuestion($question->load('evaluation.formation'), $request->user()->id);
        $question->delete();

        return back()->with('success', 'Question supprimée.');
    }

    public function storeReponse(Request $request, Question $question): RedirectResponse
    {
        $this->assertOwnsQuestion($question->load('evaluation.formation'), $request->user()->id);

        $data = $request->validate([
            'libelle' => ['required', 'string', 'max:500'],
        ]);

        $question->reponses()->create([
            'libelle' => $data['libelle'],
            'correcte' => $request->boolean('correcte'),
            'points' => 0,
            'ordre' => $question->reponses()->max('ordre') + 1,
        ]);

        return back()->with('success', 'Réponse ajoutée.');
    }

    public function destroyReponse(Request $request, Reponse $reponse): RedirectResponse
    {
        $this->assertOwnsQuestion($reponse->load('question.evaluation.formation')->question, $request->user()->id);
        $reponse->delete();

        return back()->with('success', 'Réponse supprimée.');
    }
}
