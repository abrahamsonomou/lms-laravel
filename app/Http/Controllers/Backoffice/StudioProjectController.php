<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Studio\StudioProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudioProjectController extends Controller
{
    public function index(): View
    {
        $projects = StudioProject::query()->withCount('pages')->latest()->paginate(15);

        return view('backoffice.studio.index', compact('projects'));
    }

    public function create(): View
    {
        return view('backoffice.studio.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProject($request);

        $project = StudioProject::query()->create([
            ...$validated,
            'type' => 'LANDING',
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.studio.edit', $project)->with('success', 'Projet créé. Ajoutez des pages.');
    }

    public function edit(StudioProject $studio): View
    {
        return view('backoffice.studio.edit', [
            'project' => $studio->load(['pages' => fn ($query) => $query->orderBy('ordre')]),
        ]);
    }

    public function update(Request $request, StudioProject $studio): RedirectResponse
    {
        $studio->update($this->validateProject($request));

        return redirect()->route('admin.studio.edit', $studio)->with('success', 'Projet mis à jour.');
    }

    public function destroy(StudioProject $studio): RedirectResponse
    {
        $studio->delete();

        return redirect()->route('admin.studio.index')->with('success', 'Projet supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProject(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'statut' => ['required', 'in:BROUILLON,PUBLIE'],
        ]);
    }
}
