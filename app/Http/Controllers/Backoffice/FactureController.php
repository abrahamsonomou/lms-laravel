<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Facturation\Facture;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FactureController extends Controller
{
    public function index(Request $request): View
    {
        $factures = Facture::query()
            ->with(['client', 'devise'])
            ->when($request->filled('search'), fn ($query) => $query->where('numero', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('statut'), fn ($query) => $query->where('statut', $request->string('statut')))
            ->latest('date_facture')
            ->paginate(20)
            ->withQueryString();

        $totalPaye = Facture::query()->where('statut', 'PAYEE')->sum('total_ttc');

        return view('backoffice.factures.index', compact('factures', 'totalPaye'));
    }
}
