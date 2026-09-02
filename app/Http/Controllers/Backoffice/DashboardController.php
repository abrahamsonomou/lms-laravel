<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Certification\Certificat;
use App\Models\Etudiant\Etudiant;
use App\Models\Facturation\Facture;
use App\Models\Formateur\Formateur;
use App\Models\Paiement\TransactionPaiement;
use App\Models\Progression\FormationEtudiant;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'utilisateurs' => User::query()->count(),
            'etudiants' => Etudiant::query()->count(),
            'formateurs' => Formateur::query()->count(),
            'formations' => Formation::query()->count(),
            'inscriptions' => FormationEtudiant::query()->count(),
            'certificats' => Certificat::query()->count(),
            'ventes' => Facture::query()->where('statut', 'PAYEE')->count(),
            'ca' => (float) Facture::query()->where('statut', 'PAYEE')->sum('total_ttc'),
        ];

        $derniersUtilisateurs = User::query()->with('roles')->latest()->take(5)->get();

        $dernieresTransactions = TransactionPaiement::query()
            ->with(['user', 'moyenPaiement.fournisseur', 'deviseSource'])
            ->where('statut', 'REUSSI')
            ->latest('date_transaction')
            ->take(5)
            ->get();

        return view('backoffice.dashboard', compact('stats', 'derniersUtilisateurs', 'dernieresTransactions'));
    }
}
