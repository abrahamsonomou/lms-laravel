<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Studio\StudioPage;
use App\Models\Studio\StudioProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudioPageController extends Controller
{
    /** @var array<int, string> */
    public const BLOCS = ['hero', 'texte', 'cta'];

    public function store(Request $request, StudioProject $studio): RedirectResponse
    {
        $validated = $request->validate(['nom' => ['required', 'string', 'max:255']]);

        $page = $studio->pages()->create([
            'nom' => $validated['nom'],
            'slug' => $this->uniqueSlug($validated['nom']),
            'contenu_json' => [],
            'ordre' => (int) $studio->pages()->max('ordre') + 1,
            'active' => true,
        ]);

        return redirect()->route('admin.studio.pages.builder', $page)->with('success', 'Page créée.');
    }

    public function builder(StudioPage $page): View
    {
        return view('backoffice.studio.builder', [
            'page' => $page->load('project'),
            'blocs' => self::BLOCS,
        ]);
    }

    public function update(Request $request, StudioPage $page): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('studio_pages', 'slug')->ignore($page->id)],
            'active' => ['boolean'],
        ]);

        $page->update([
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['slug']),
            'active' => $request->boolean('active'),
        ]);

        return back()->with('success', 'Page mise à jour.');
    }

    public function destroy(StudioPage $page): RedirectResponse
    {
        $projectId = $page->project_id;
        $page->delete();

        return redirect()->route('admin.studio.edit', $projectId)->with('success', 'Page supprimée.');
    }

    public function addBlock(Request $request, StudioPage $page): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(self::BLOCS)],
            'titre' => ['nullable', 'string', 'max:255'],
            'sous_titre' => ['nullable', 'string', 'max:255'],
            'contenu' => ['nullable', 'string'],
            'bouton_texte' => ['nullable', 'string', 'max:100'],
            'bouton_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $blocs = $page->contenu_json ?? [];
        $blocs[] = array_filter($validated, fn ($value) => $value !== null);

        $page->update(['contenu_json' => array_values($blocs)]);

        return back()->with('success', 'Bloc ajouté.');
    }

    public function removeBlock(StudioPage $page, int $index): RedirectResponse
    {
        $blocs = $page->contenu_json ?? [];

        if (array_key_exists($index, $blocs)) {
            array_splice($blocs, $index, 1);
            $page->update(['contenu_json' => array_values($blocs)]);
        }

        return back()->with('success', 'Bloc supprimé.');
    }

    private function uniqueSlug(string $nom): string
    {
        $base = Str::slug($nom) ?: 'page';
        $slug = $base;
        $i = 1;

        while (StudioPage::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-".(++$i);
        }

        return $slug;
    }
}
