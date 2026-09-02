<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Facturation\Facture;
use App\Models\Paiement\TransactionPaiement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FactureController extends Controller
{
    public function index(Request $request): View
    {
        $factures = Facture::query()
            ->where('client_id', $request->user()->id)
            ->with(['lignes', 'devise'])
            ->latest('date_facture')
            ->paginate(12);

        return view('student.factures.index', compact('factures'));
    }

    public function show(Request $request, Facture $facture): View
    {
        abort_unless($facture->client_id === $request->user()->id, 403);

        $facture->load(['lignes.formation', 'devise', 'client']);

        $transaction = TransactionPaiement::query()
            ->where('facture_id', $facture->id)
            ->with('moyenPaiement.fournisseur')
            ->latest()
            ->first();

        return view('student.factures.show', compact('facture', 'transaction'));
    }
}
