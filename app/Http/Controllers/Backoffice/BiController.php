<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Bi\DimFormation;
use App\Models\Bi\FactInscription;
use App\Models\Bi\FactVente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class BiController extends Controller
{
    public function index(): View
    {
        $dimensions = DimFormation::query()->get()->keyBy('formation_id');

        $topVentes = FactVente::query()->orderByDesc('revenu')->take(10)->get();
        $topInscriptions = FactInscription::query()->orderByDesc('nombre_inscriptions')->take(10)->get();

        $totaux = [
            'revenu' => (float) FactVente::query()->sum('revenu'),
            'ventes' => (int) FactVente::query()->sum('nombre_ventes'),
            'inscriptions' => (int) FactInscription::query()->sum('nombre_inscriptions'),
            'termines' => (int) FactInscription::query()->sum('nombre_termines'),
        ];

        return view('backoffice.bi.index', compact('dimensions', 'topVentes', 'topInscriptions', 'totaux'));
    }

    public function rebuild(): RedirectResponse
    {
        Artisan::call('bi:rebuild');

        return redirect()->route('admin.bi.index')->with('success', 'Entrepôt décisionnel reconstruit.');
    }
}
