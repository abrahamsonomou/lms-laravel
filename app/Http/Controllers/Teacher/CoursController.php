<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Concerns\ResolvesCourseOwnership;
use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Cours\Cours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoursController extends Controller
{
    use ResolvesCourseOwnership;

    public function index(Request $request, Formation $formation): View
    {
        abort_unless($formation->created_by === $request->user()->id, 403);

        $formation->load(['cours' => fn ($query) => $query->orderBy('ordre')->withCount('modules')]);

        return view('teacher.cours.index', compact('formation'));
    }

    public function create(Request $request, Formation $formation): View
    {
        abort_unless($formation->created_by === $request->user()->id, 403);

        return view('teacher.cours.create', compact('formation'));
    }

    public function store(Request $request, Formation $formation): RedirectResponse
    {
        abort_unless($formation->created_by === $request->user()->id, 403);

        $validated = $this->validateCours($request);

        $formation->cours()->create([
            ...$validated,
            'formateur_id' => $request->user()->formateur?->id,
            'active' => $request->boolean('active'),
            'ordre' => $validated['ordre'] ?? ($formation->cours()->max('ordre') + 1),
        ]);

        return redirect()->route('teacher.formations.cours.index', $formation)->with('success', 'Cours ajouté.');
    }

    public function edit(Request $request, Cours $cours): View
    {
        $this->assertOwnsCours($cours->load('formation'), $request->user()->id);

        return view('teacher.cours.edit', compact('cours'));
    }

    public function update(Request $request, Cours $cours): RedirectResponse
    {
        $this->assertOwnsCours($cours->load('formation'), $request->user()->id);

        $cours->update([
            ...$this->validateCours($request),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('teacher.formations.cours.index', $cours->formation_id)->with('success', 'Cours mis à jour.');
    }

    public function destroy(Request $request, Cours $cours): RedirectResponse
    {
        $this->assertOwnsCours($cours->load('formation'), $request->user()->id);

        $formationId = $cours->formation_id;
        $cours->delete();

        return redirect()->route('teacher.formations.cours.index', $formationId)->with('success', 'Cours supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateCours(Request $request): array
    {
        return $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duree' => ['nullable', 'integer', 'min:0'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'statut' => ['required', 'in:BROUILLON,PUBLIE'],
        ]);
    }
}
