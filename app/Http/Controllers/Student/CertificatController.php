<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Certification\Certificat;
use App\Models\Etudiant\Etudiant;
use App\Services\CertificatIssuer;
use App\Services\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificatController extends Controller
{
    public function index(Request $request): View
    {
        $etudiant = $this->etudiant($request);

        $certificats = Certificat::query()
            ->where('etudiant_id', $etudiant->id)
            ->with('formation')
            ->latest('date_emission')
            ->paginate(12);

        return view('student.certificats.index', compact('certificats'));
    }

    public function store(Request $request, Formation $formation, CertificatIssuer $issuer, Notifier $notifier): RedirectResponse
    {
        $etudiant = $this->etudiant($request);

        if (! $issuer->isEligible($etudiant, $formation)) {
            return back()->with('error', 'Vous devez terminer la formation à 100 % pour obtenir le certificat.');
        }

        $certificat = $issuer->issueFor($etudiant, $formation);

        if ($certificat->wasRecentlyCreated) {
            $notifier->notify(
                $request->user(),
                'certificat',
                'Certificat obtenu 🎓',
                "Votre certificat pour « {$formation->titre} » est disponible (mention {$certificat->mention}).",
                ['certificat_id' => $certificat->id],
                actionUrl: route('student.certificats.show', $certificat),
                actionText: 'Voir mon certificat',
            );
        }

        return redirect()->route('student.certificats.show', $certificat)->with('success', 'Félicitations, votre certificat a été généré !');
    }

    public function show(Request $request, Certificat $certificat): View
    {
        abort_unless($certificat->etudiant_id === $this->etudiant($request)->id, 403);

        $certificat->load(['formation', 'etudiant.user']);

        return view('student.certificats.show', compact('certificat'));
    }

    private function etudiant(Request $request): Etudiant
    {
        $etudiant = $request->user()->etudiant;
        abort_if($etudiant === null, 403, 'Profil étudiant introuvable.');

        return $etudiant;
    }
}
