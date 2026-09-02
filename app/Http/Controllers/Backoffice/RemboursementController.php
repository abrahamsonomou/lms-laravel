<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Facturation\Facture;
use App\Models\Paiement\Remboursement;
use App\Services\Notifier;
use App\Services\RemboursementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RemboursementController extends Controller
{
    public function index(): View
    {
        $remboursements = Remboursement::query()
            ->with(['transaction.facture.client', 'devise'])
            ->latest('date_remboursement')
            ->paginate(20);

        $total = (float) Remboursement::query()->where('statut', 'REUSSI')->sum('montant');

        return view('backoffice.remboursements.index', compact('remboursements', 'total'));
    }

    public function store(Request $request, Facture $facture, RemboursementService $service, Notifier $notifier): RedirectResponse
    {
        $validated = $request->validate(['motif' => ['required', 'string', 'max:255']]);

        if ($facture->statut !== 'PAYEE') {
            return back()->with('error', 'Seule une facture payée peut être remboursée.');
        }

        $service->refundFacture($facture, $validated['motif']);

        if ($facture->client !== null) {
            $notifier->notify(
                $facture->client,
                'remboursement',
                'Remboursement effectué',
                "Votre facture {$facture->numero} a été remboursée. Motif : {$validated['motif']}.",
                ['facture_id' => $facture->id],
                actionUrl: route('student.factures.show', $facture),
                actionText: 'Voir la facture',
            );
        }

        return back()->with('success', 'Facture remboursée avec succès.');
    }
}
