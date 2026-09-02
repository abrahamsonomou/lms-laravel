<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Core\Pays;
use App\Models\Core\Ville;
use App\Models\Organisation\Etablissement;
use App\Models\Organisation\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EtablissementController extends Controller
{
    public function index(Request $request): View
    {
        $etablissements = Etablissement::query()
            ->with(['organisation', 'pays'])
            ->when($request->filled('organisation_id'), fn ($query) => $query->where('organisation_id', $request->integer('organisation_id')))
            ->when($request->filled('search'), fn ($query) => $query->where('nom', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backoffice.etablissements.index', [
            'etablissements' => $etablissements,
            'organisations' => Organisation::query()->orderBy('nom')->get(),
        ]);
    }

    public function create(): View
    {
        return view('backoffice.etablissements.create', $this->references());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEtablissement($request);
        $validated['active'] = $request->boolean('active');

        Etablissement::query()->create($validated);

        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement créé avec succès.');
    }

    public function edit(Etablissement $etablissement): View
    {
        return view('backoffice.etablissements.edit', [
            'etablissement' => $etablissement,
            ...$this->references(),
        ]);
    }

    public function update(Request $request, Etablissement $etablissement): RedirectResponse
    {
        $validated = $this->validateEtablissement($request, $etablissement);
        $validated['active'] = $request->boolean('active');

        $etablissement->update($validated);

        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement mis à jour.');
    }

    public function destroy(Etablissement $etablissement): RedirectResponse
    {
        $etablissement->delete();

        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function references(): array
    {
        return [
            'organisations' => Organisation::query()->orderBy('nom')->get(),
            'pays' => Pays::query()->orderBy('nom')->get(),
            'villes' => Ville::query()->orderBy('nom')->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateEtablissement(Request $request, ?Etablissement $etablissement = null): array
    {
        return $request->validate([
            'organisation_id' => ['required', 'integer', 'exists:organisations,id'],
            'code' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'pays_id' => ['nullable', 'integer', 'exists:pays,id'],
            'ville_id' => ['nullable', 'integer', 'exists:villes,id'],
        ]);
    }
}
