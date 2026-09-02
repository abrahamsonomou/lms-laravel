<?php

namespace App\Services;

use App\Models\Catalogue\Formation;
use App\Models\Cours\Lecon;
use App\Models\Etudiant\Etudiant;
use App\Models\Progression\FormationEtudiant;
use App\Models\Progression\Progression;

class ProgressionCalculator
{
    /**
     * Total number of lessons belonging to a formation (across cours/modules/chapitres).
     */
    public function totalLecons(Formation $formation): int
    {
        return Lecon::query()
            ->whereHas('chapitre.moduleCours.cours', fn ($query) => $query->where('formation_id', $formation->id))
            ->count();
    }

    /**
     * Ids of lessons already completed by the student for a formation.
     *
     * @return array<int, int>
     */
    public function completedLeconIds(Etudiant $etudiant, Formation $formation): array
    {
        return Progression::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->where('terminee', true)
            ->pluck('lecon_id')
            ->filter()
            ->all();
    }

    /**
     * Recompute and persist the overall progression percentage on the enrollment.
     */
    public function recalculate(Etudiant $etudiant, Formation $formation): float
    {
        $total = $this->totalLecons($formation);
        $done = count($this->completedLeconIds($etudiant, $formation));
        $pourcentage = $total > 0 ? round($done / $total * 100, 2) : 0.0;

        FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->update([
                'progression' => $pourcentage,
                'statut' => $pourcentage >= 100 ? 'TERMINE' : 'EN_COURS',
                'date_fin' => $pourcentage >= 100 ? now() : null,
            ]);

        return $pourcentage;
    }
}
