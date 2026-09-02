<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Concerns\ResolvesCourseOwnership;
use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Evaluation\Evaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    use ResolvesCourseOwnership;

    public function index(Request $request, Formation $formation): View
    {
        abort_unless($formation->created_by === $request->user()->id, 403);

        $formation->load(['evaluations' => fn ($q) => $q->withCount('questions')]);

        return view('teacher.evaluations.index', compact('formation'));
    }

    public function create(Request $request, Formation $formation): View
    {
        abort_unless($formation->created_by === $request->user()->id, 403);

        return view('teacher.evaluations.create', [
            'formation' => $formation,
            'types' => Evaluation::TYPES,
        ]);
    }

    public function store(Request $request, Formation $formation): RedirectResponse
    {
        abort_unless($formation->created_by === $request->user()->id, 403);

        $evaluation = $formation->evaluations()->create([
            ...$this->validateEvaluation($request),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('teacher.evaluations.builder', $evaluation)->with('success', 'Évaluation créée. Ajoutez vos questions.');
    }

    public function edit(Request $request, Evaluation $evaluation): View
    {
        $this->assertOwnsEvaluation($evaluation->load('formation'), $request->user()->id);

        return view('teacher.evaluations.edit', [
            'evaluation' => $evaluation,
            'types' => Evaluation::TYPES,
        ]);
    }

    public function update(Request $request, Evaluation $evaluation): RedirectResponse
    {
        $this->assertOwnsEvaluation($evaluation->load('formation'), $request->user()->id);

        $evaluation->update([
            ...$this->validateEvaluation($request),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('teacher.formations.evaluations.index', $evaluation->formation_id)->with('success', 'Évaluation mise à jour.');
    }

    public function destroy(Request $request, Evaluation $evaluation): RedirectResponse
    {
        $this->assertOwnsEvaluation($evaluation->load('formation'), $request->user()->id);

        $formationId = $evaluation->formation_id;
        $evaluation->delete();

        return redirect()->route('teacher.formations.evaluations.index', $formationId)->with('success', 'Évaluation supprimée.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateEvaluation(Request $request): array
    {
        return $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:'.implode(',', Evaluation::TYPES)],
            'duree' => ['nullable', 'integer', 'min:0'],
            'note_max' => ['nullable', 'numeric', 'min:0'],
            'note_min' => ['nullable', 'numeric', 'min:0'],
            'tentatives_max' => ['nullable', 'integer', 'min:1'],
        ]);
    }
}
