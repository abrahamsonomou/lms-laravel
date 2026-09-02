<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\CategorieFormation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategorieController extends Controller
{
    public function index(): View
    {
        $categories = CategorieFormation::query()
            ->with('parent')
            ->withCount(['children', 'formations'])
            ->orderBy('nom')
            ->paginate(15);

        return view('backoffice.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('backoffice.categories.create', [
            'parents' => CategorieFormation::query()->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCategorie($request);
        $validated['active'] = $request->boolean('active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        CategorieFormation::query()->create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(CategorieFormation $categorie): View
    {
        return view('backoffice.categories.edit', [
            'categorie' => $categorie,
            'parents' => CategorieFormation::query()->whereKeyNot($categorie->id)->orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, CategorieFormation $categorie): RedirectResponse
    {
        $validated = $this->validateCategorie($request, $categorie);
        $validated['active'] = $request->boolean('active');

        if ($request->hasFile('image')) {
            if ($categorie->image !== null) {
                Storage::disk('public')->delete($categorie->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $categorie->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(CategorieFormation $categorie): RedirectResponse
    {
        $categorie->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateCategorie(Request $request, ?CategorieFormation $categorie = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('categories_formations', 'code')->ignore($categorie?->id)],
            'nom' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:categories_formations,id', Rule::notIn([$categorie?->id])],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
