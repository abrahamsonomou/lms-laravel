<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Concerns\ResolvesCourseOwnership;
use App\Http\Controllers\Controller;
use App\Models\Contenu\Contenu;
use App\Models\Cours\Chapitre;
use App\Models\Cours\Cours;
use App\Models\Cours\Lecon;
use App\Models\Cours\ModuleCours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoursBuilderController extends Controller
{
    use ResolvesCourseOwnership;

    public function show(Request $request, Cours $cours): View
    {
        $this->assertOwnsCours($cours->load('formation'), $request->user()->id);

        $cours->load(['modules' => fn ($q) => $q->orderBy('ordre'),
            'modules.chapitres' => fn ($q) => $q->orderBy('ordre'),
            'modules.chapitres.lecons' => fn ($q) => $q->orderBy('ordre'),
            'modules.chapitres.lecons.contenus']);

        return view('teacher.cours.builder', [
            'cours' => $cours,
            'typesLecon' => Lecon::TYPES,
        ]);
    }

    public function storeModule(Request $request, Cours $cours): RedirectResponse
    {
        $this->assertOwnsCours($cours->load('formation'), $request->user()->id);

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $cours->modules()->create([
            ...$data,
            'ordre' => $cours->modules()->max('ordre') + 1,
            'active' => true,
        ]);

        return back()->with('success', 'Module ajouté.');
    }

    public function destroyModule(Request $request, ModuleCours $module): RedirectResponse
    {
        $this->assertOwnsModule($module->load('cours.formation'), $request->user()->id);
        $module->delete();

        return back()->with('success', 'Module supprimé.');
    }

    public function storeChapitre(Request $request, ModuleCours $module): RedirectResponse
    {
        $this->assertOwnsModule($module->load('cours.formation'), $request->user()->id);

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $module->chapitres()->create([
            ...$data,
            'ordre' => $module->chapitres()->max('ordre') + 1,
            'active' => true,
        ]);

        return back()->with('success', 'Chapitre ajouté.');
    }

    public function destroyChapitre(Request $request, Chapitre $chapitre): RedirectResponse
    {
        $this->assertOwnsChapitre($chapitre->load('moduleCours.cours.formation'), $request->user()->id);
        $chapitre->delete();

        return back()->with('success', 'Chapitre supprimé.');
    }

    public function storeLecon(Request $request, Chapitre $chapitre): RedirectResponse
    {
        $this->assertOwnsChapitre($chapitre->load('moduleCours.cours.formation'), $request->user()->id);

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:'.implode(',', Lecon::TYPES)],
            'duree' => ['nullable', 'integer', 'min:0'],
            'url' => ['nullable', 'url', 'max:2048'],
            'fichier' => ['nullable', 'file', 'max:51200'],
        ]);

        $lecon = $chapitre->lecons()->create([
            'titre' => $data['titre'],
            'type' => $data['type'],
            'duree' => $data['duree'] ?? null,
            'ordre' => $chapitre->lecons()->max('ordre') + 1,
            'obligatoire' => true,
            'active' => true,
        ]);

        $this->attachContenu($request, $lecon, $data);

        return back()->with('success', 'Leçon ajoutée.');
    }

    public function destroyLecon(Request $request, Lecon $lecon): RedirectResponse
    {
        $this->assertOwnsLecon($lecon->load('chapitre.moduleCours.cours.formation'), $request->user()->id);
        $lecon->delete();

        return back()->with('success', 'Leçon supprimée.');
    }

    /**
     * Create a Contenu record from a URL or an uploaded file when provided.
     *
     * @param  array<string, mixed>  $data
     */
    private function attachContenu(Request $request, Lecon $lecon, array $data): void
    {
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $lecon->contenus()->create([
                'type' => $data['type'],
                'titre' => $data['titre'],
                'fichier' => $file->store('contenus', 'public'),
                'mime_type' => $file->getClientMimeType(),
                'taille' => $file->getSize(),
                'ordre' => 1,
                'active' => true,
            ]);

            return;
        }

        if (! empty($data['url'])) {
            $lecon->contenus()->create([
                'type' => $data['type'],
                'titre' => $data['titre'],
                'url' => $data['url'],
                'ordre' => 1,
                'active' => true,
            ]);
        }
    }
}
