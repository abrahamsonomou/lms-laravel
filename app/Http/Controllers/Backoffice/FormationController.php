<?php

namespace App\Http\Controllers\Backoffice;

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
            ->with(['categorie', 'devise', 'createur'])
            ->when($request->filled('search'), fn ($query) => $query->where('titre', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('statut'), fn ($query) => $query->where('statut', $request->string('statut')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backoffice.formations.index', [
            'formations' => $formations,
            'statuts' => Formation::STATUTS,
        ]);
    }

    public function create(): View
    {
        return view('backoffice.formations.create', $this->references());
    }

    public function store(FormationRequest $request): RedirectResponse
    {
        $data = $this->buildFormationData($request);
        $data['created_by'] = $request->user()->id;

        Formation::query()->create($data);

        return redirect()->route('admin.formations.index')->with('success', 'Formation créée avec succès.');
    }

    public function edit(Formation $formation): View
    {
        return view('backoffice.formations.edit', [
            'formation' => $formation,
            ...$this->references(),
        ]);
    }

    public function update(FormationRequest $request, Formation $formation): RedirectResponse
    {
        $data = $this->buildFormationData($request, $formation);
        $data['updated_by'] = $request->user()->id;

        $formation->update($data);

        return redirect()->route('admin.formations.index')->with('success', 'Formation mise à jour.');
    }

    public function destroy(Formation $formation): RedirectResponse
    {
        if ($formation->image !== null) {
            Storage::disk('public')->delete($formation->image);
        }

        $formation->delete();

        return redirect()->route('admin.formations.index')->with('success', 'Formation supprimée.');
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
