<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Evaluation\Evaluation;
use App\Models\Facturation\LigneFacture;
use App\Models\Progression\FormationEtudiant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $userId = $request->user()->id;
        $formationIds = Formation::query()->where('created_by', $userId)->pluck('id');

        $stats = [
            'formations' => $formationIds->count(),
            'etudiants' => FormationEtudiant::query()->whereIn('formation_id', $formationIds)->distinct('etudiant_id')->count('etudiant_id'),
            'evaluations' => Evaluation::query()->whereIn('formation_id', $formationIds)->count(),
            'revenus' => (float) LigneFacture::query()
                ->whereIn('formation_id', $formationIds)
                ->whereHas('facture', fn ($q) => $q->where('statut', 'PAYEE'))
                ->sum('total'),
        ];

        $formations = Formation::query()
            ->where('created_by', $userId)
            ->withCount('cours')
            ->latest()
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact('stats', 'formations'));
    }
}
