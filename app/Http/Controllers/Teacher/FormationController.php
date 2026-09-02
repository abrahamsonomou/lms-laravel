<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Concerns\HandlesFormationPersistence;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogue\FormationRequest;
use App\Models\Catalogue\CategorieFormation;
use App\Models\Catalogue\Formation;
use App\Models\Core\Devise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FormationController extends Controller
{
    use HandlesFormationPersistence;

    public function index(Request $request): View
    {
        $formations = Formation::query()
            ->where('created_by', $request->user()->id)
            ->with(['categorie', 'devise'])
            ->when($request->filled('search'), fn ($query) => $query->where('titre', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('teacher.formations.index', compact('formations'));
    }

    public function create(): View
    {
        return view('teacher.formations.create', $this->references());
    }

    public function store(FormationRequest $request): RedirectResponse
    {
        $data = $this->buildFormationData($request);
        $data['created_by'] = $request->user()->id;

        Formation::query()->create($data);

        return redirect()->route('teacher.formations.index')->with('success', 'Formation créée avec succès.');
    }

    public function edit(Request $request, Formation $formation): View
    {
        $this->authorizeOwner($request, $formation);

        return view('teacher.formations.edit', [
            'formation' => $formation,
            ...$this->references(),
        ]);
    }

    public function update(FormationRequest $request, Formation $formation): RedirectResponse
    {
        $this->authorizeOwner($request, $formation);

        $data = $this->buildFormationData($request, $formation);
        $data['updated_by'] = $request->user()->id;

        $formation->update($data);

        return redirect()->route('teacher.formations.index')->with('success', 'Formation mise à jour.');
    }

    public function destroy(Request $request, Formation $formation): RedirectResponse
    {
        $this->authorizeOwner($request, $formation);

        if ($formation->image !== null) {
            Storage::disk('public')->delete($formation->image);
        }

        $formation->delete();

        return redirect()->route('teacher.formations.index')->with('success', 'Formation supprimée.');
    }

    private function authorizeOwner(Request $request, Formation $formation): void
    {
        abort_unless($formation->created_by === $request->user()->id, 403);
    }

    /**
     * @return array<string, mixed>
     */
    private function references(): array
    {
        return [
            'categories' => CategorieFormation::query()->orderBy('nom')->get(),
            'devises' => Devise::query()->orderBy('code')->get(),
            'statuts' => Formation::STATUTS,
            'types' => Formation::TYPES,
            'niveaux' => Formation::NIVEAUX,
        ];
    }
}
