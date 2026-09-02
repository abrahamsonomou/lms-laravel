<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Progression\FormationEtudiant;
use App\Services\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function store(Request $request, Formation $formation, Notifier $notifier): RedirectResponse
    {
        abort_unless($formation->isPublie(), 404);

        $etudiant = $request->user()->etudiant;
        abort_if($etudiant === null, 403, 'Profil étudiant introuvable.');

        $dejaInscrit = FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->exists();

        // Les formations payantes passent par le tunnel de paiement,
        // sauf si l'étudiant dispose d'un abonnement actif (accès illimité).
        if ($formation->estPayante() && ! $dejaInscrit && ! $request->user()->hasAbonnementActif()) {
            return redirect()->route('student.checkout.show', $formation);
        }

        $inscription = FormationEtudiant::query()->firstOrCreate(
            ['etudiant_id' => $etudiant->id, 'formation_id' => $formation->id],
            ['date_inscription' => now(), 'date_debut' => now(), 'progression' => 0, 'statut' => 'INSCRIT'],
        );

        if ($inscription->wasRecentlyCreated) {
            $notifier->notify(
                $request->user(),
                'inscription',
                'Inscription confirmée',
                "Vous êtes inscrit à la formation « {$formation->titre} ».",
                ['formation_id' => $formation->id],
                actionUrl: route('student.formations.show', $formation),
                actionText: 'Accéder à la formation',
            );
        }

        return redirect()->route('student.formations.show', $formation)
            ->with('success', 'Vous êtes inscrit à cette formation.');
    }
}
