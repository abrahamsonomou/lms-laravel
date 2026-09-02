<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Cours\Lecon;
use App\Models\Progression\FormationEtudiant;
use App\Services\ProgressionCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormationController extends Controller
{
    public function index(Request $request): View
    {
        $etudiant = $request->user()->etudiant;
        abort_if($etudiant === null, 403, 'Profil étudiant introuvable.');

        $inscriptions = FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->with(['formation.categorie', 'formation.devise'])
            ->latest('date_inscription')
            ->paginate(9);

        return view('student.formations.index', compact('inscriptions'));
    }

    public function show(Request $request, Formation $formation, ProgressionCalculator $calculator): View
    {
        $etudiant = $request->user()->etudiant;
        abort_if($etudiant === null, 403, 'Profil étudiant introuvable.');

        $inscription = FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->first();

        abort_if($inscription === null, 403, "Vous n'êtes pas inscrit à cette formation.");

        $formation->load([
            'cours' => fn ($q) => $q->orderBy('ordre'),
            'cours.modules' => fn ($q) => $q->orderBy('ordre'),
            'cours.modules.chapitres' => fn ($q) => $q->orderBy('ordre'),
            'cours.modules.chapitres.lecons' => fn ($q) => $q->orderBy('ordre'),
            'cours.modules.chapitres.lecons.contenus',
        ]);

        $completed = $calculator->completedLeconIds($etudiant, $formation);

        $leconCourante = $request->filled('lecon')
            ? Lecon::query()->with('contenus')->find($request->integer('lecon'))
            : $this->premiereLecon($formation);

        return view('student.formations.show', [
            'formation' => $formation,
            'inscription' => $inscription,
            'completedIds' => $completed,
            'leconCourante' => $leconCourante,
            'progression' => (float) $inscription->progression,
        ]);
    }

    private function premiereLecon(Formation $formation): ?Lecon
    {
        foreach ($formation->cours as $cours) {
            foreach ($cours->modules as $module) {
                foreach ($module->chapitres as $chapitre) {
                    if ($chapitre->lecons->isNotEmpty()) {
                        return $chapitre->lecons->first();
                    }
                }
            }
        }

        return null;
    }
}
