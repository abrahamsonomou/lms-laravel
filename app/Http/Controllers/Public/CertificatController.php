<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certification\Certificat;
use Illuminate\View\View;

class CertificatController extends Controller
{
    public function verify(string $hash): View
    {
        $certificat = Certificat::query()
            ->where('hash_verification', $hash)
            ->with(['formation', 'etudiant.user'])
            ->first();

        return view('public.certificats.verify', compact('certificat'));
    }
}
