<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Cours\Lecon;
use App\Models\Progression\FormationEtudiant;
use App\Models\Progression\Progression;
use App\Services\ProgressionCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgressionController extends Controller
{
    public function complete(Request $request, Lecon $lecon, ProgressionCalculator $calculator): RedirectResponse
    {
        $etudiant = $request->user()->etudiant;
        abort_if($etudiant === null, 403, 'Profil étudiant introuvable.');

        $lecon->load('chapitre.moduleCours.cours.formation');
        $cours = $lecon->chapitre?->moduleCours?->cours;
        $formation = $cours?->formation;

        abort_if($formation === null, 404);

        $inscrit = FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->exists();

        abort_unless($inscrit, 403, "Vous n'êtes pas inscrit à cette formation.");

        Progression::query()->updateOrCreate(
            ['etudiant_id' => $etudiant->id, 'lecon_id' => $lecon->id],
            [
                'formation_id' => $formation->id,
                'cours_id' => $cours->id,
                'terminee' => true,
                'progression' => 100,
                'date_derniere_activite' => now(),
                'date_completion' => now(),
            ],
        );

        $calculator->recalculate($etudiant, $formation);

        return redirect()
            ->route('student.formations.show', ['formation' => $formation, 'lecon' => $lecon->id])
            ->with('success', 'Leçon marquée comme terminée.');
    }
}
