<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Core\Devise;
use App\Models\Core\Langue;
use App\Models\Core\Pays;
use App\Models\Core\Ville;
use App\Models\Organisation\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrganisationController extends Controller
{
    public function index(Request $request): View
    {
        $organisations = Organisation::query()
            ->with(['pays', 'devise'])
            ->withCount('etablissements')
            ->when($request->filled('search'), fn ($query) => $query->where('nom', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backoffice.organisations.index', compact('organisations'));
    }

    public function create(): View
    {
        return view('backoffice.organisations.create', $this->references());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOrganisation($request);
        $validated['active'] = $request->boolean('active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('organisations', 'public');
        }

        Organisation::query()->create($validated);

        return redirect()->route('admin.organisations.index')->with('success', 'Organisation créée avec succès.');
    }

    public function edit(Organisation $organisation): View
    {
        return view('backoffice.organisations.edit', [
            'organisation' => $organisation,
            ...$this->references(),
        ]);
    }

    public function update(Request $request, Organisation $organisation): RedirectResponse
    {
        $validated = $this->validateOrganisation($request, $organisation);
        $validated['active'] = $request->boolean('active');

        if ($request->hasFile('logo')) {
            if ($organisation->logo !== null) {
                Storage::disk('public')->delete($organisation->logo);
            }
            $validated['logo'] = $request->file('logo')->store('organisations', 'public');
        }

        $organisation->update($validated);

        return redirect()->route('admin.organisations.index')->with('success', 'Organisation mise à jour.');
    }

    public function destroy(Organisation $organisation): RedirectResponse
    {
        $organisation->delete();

        return redirect()->route('admin.organisations.index')->with('success', 'Organisation supprimée.');
    }

    /**
     * @return array<string, mixed>
     */
    private function references(): array
    {
        return [
            'pays' => Pays::query()->orderBy('nom')->get(),
            'villes' => Ville::query()->orderBy('nom')->get(),
            'devises' => Devise::query()->orderBy('code')->get(),
            'langues' => Langue::query()->orderBy('nom')->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateOrganisation(Request $request, ?Organisation $organisation = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('organisations', 'code')->ignore($organisation?->id)],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'pays_id' => ['nullable', 'integer', 'exists:pays,id'],
            'ville_id' => ['nullable', 'integer', 'exists:villes,id'],
            'devise_id' => ['nullable', 'integer', 'exists:devises,id'],
            'langue_id' => ['nullable', 'integer', 'exists:langues,id'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
