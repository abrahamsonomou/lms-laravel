<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certification\Certificat;
use App\Models\Progression\FormationEtudiant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $etudiant = $request->user()->etudiant;

        $stats = [
            'cours' => 0,
            'certificats' => 0,
            'progression' => 0.0,
            'terminees' => 0,
        ];

        $enCours = collect();

        if ($etudiant !== null) {
            $inscriptions = FormationEtudiant::query()->where('etudiant_id', $etudiant->id);

            $stats = [
                'cours' => (clone $inscriptions)->count(),
                'certificats' => Certificat::query()->where('etudiant_id', $etudiant->id)->count(),
                'progression' => round((float) (clone $inscriptions)->avg('progression'), 0),
                'terminees' => (clone $inscriptions)->where('statut', 'TERMINE')->count(),
            ];

            $enCours = FormationEtudiant::query()
                ->where('etudiant_id', $etudiant->id)
                ->where('statut', '!=', 'TERMINE')
                ->with('formation')
                ->latest('date_inscription')
                ->take(5)
                ->get();
        }

        return view('student.dashboard', compact('etudiant', 'stats', 'enCours'));
    }
}
