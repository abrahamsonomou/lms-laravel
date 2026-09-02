<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Facturation\Facture;
use App\Models\Paiement\Remboursement;
use App\Models\Progression\FormationEtudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class RapportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->periode($request);

        $factures = Facture::query()
            ->where('statut', 'PAYEE')
            ->whereBetween('date_facture', [$from, $to])
            ->get(['total_ttc', 'date_facture']);

        $parJour = $factures
            ->groupBy(fn ($facture) => $facture->date_facture?->format('Y-m-d'))
            ->map(fn ($groupe) => ['ventes' => $groupe->count(), 'ca' => (float) $groupe->sum('total_ttc')])
            ->sortKeys();

        $stats = [
            'ca' => (float) $factures->sum('total_ttc'),
            'ventes' => $factures->count(),
            'panier_moyen' => $factures->count() > 0 ? (float) $factures->avg('total_ttc') : 0.0,
            'inscriptions' => FormationEtudiant::query()->whereBetween('date_inscription', [$from, $to])->count(),
            'remboursements' => (float) Remboursement::query()->where('statut', 'REUSSI')->whereBetween('date_remboursement', [$from, $to])->sum('montant'),
        ];

        return view('backoffice.rapports.index', [
            'stats' => $stats,
            'parJour' => $parJour,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
        ]);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function periode(Request $request): array
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->string('from')->toString())->startOfDay()
            : now()->subDays(30)->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->string('to')->toString())->endOfDay()
            : now()->endOfDay();

        return [$from, $to];
    }
}
