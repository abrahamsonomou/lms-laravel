<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Paiement\TransactionPaiement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = TransactionPaiement::query()
            ->with(['user', 'moyenPaiement.fournisseur', 'deviseSource'])
            ->when($request->filled('search'), fn ($query) => $query->where('reference', 'like', '%'.$request->string('search').'%'))
            ->latest('date_transaction')
            ->paginate(20)
            ->withQueryString();

        $totalReussi = TransactionPaiement::query()->where('statut', 'REUSSI')->sum('montant');

        return view('backoffice.transactions.index', compact('transactions', 'totalReussi'));
    }
}
