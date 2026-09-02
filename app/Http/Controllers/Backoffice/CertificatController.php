<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Certification\Certificat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificatController extends Controller
{
    public function index(Request $request): View
    {
        $certificats = Certificat::query()
            ->with(['formation', 'etudiant.user'])
            ->when($request->filled('search'), fn ($query) => $query->where('numero', 'like', '%'.$request->string('search').'%'))
            ->latest('date_emission')
            ->paginate(20)
            ->withQueryString();

        return view('backoffice.certificats.index', compact('certificats'));
    }
}
