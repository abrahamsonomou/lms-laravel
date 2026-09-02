<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Progression\FormationEtudiant;
use App\Services\CouponValidator;
use App\Services\Notifier;
use App\Services\PaiementProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request, Formation $formation, CouponValidator $validator): View|RedirectResponse
    {
        abort_unless($formation->isPublie() && $formation->estPayante(), 404);

        if ($this->dejaInscrit($request, $formation)) {
            return redirect()->route('student.formations.show', $formation)->with('info', 'Vous êtes déjà inscrit à cette formation.');
        }

        if ($request->user()->hasAbonnementActif()) {
            return redirect()->route('catalogue.show', $formation)->with('info', 'Votre abonnement couvre cette formation : cliquez sur « S\'inscrire ».');
        }

        $formation->load('devise');
        $moyens = MoyenPaiement::query()->where('active', true)->with('fournisseur')->get();

        $prix = (float) $formation->prix;
        $codeCoupon = $request->query('coupon');
        $coupon = $codeCoupon !== null ? $validator->validate($codeCoupon, $formation, $prix) : null;
        $remise = $coupon['valid'] ?? false ? $coupon['remise'] : 0.0;

        return view('student.checkout.show', [
            'formation' => $formation,
            'moyens' => $moyens,
            'prix' => $prix,
            'remise' => $remise,
            'total' => round($prix - $remise, 2),
            'codeCoupon' => $codeCoupon,
            'coupon' => $coupon,
        ]);
    }

    public function store(Request $request, Formation $formation, PaiementProcessor $processor, CouponValidator $validator, Notifier $notifier): RedirectResponse
    {
        abort_unless($formation->isPublie() && $formation->estPayante(), 404);

        if ($this->dejaInscrit($request, $formation)) {
            return redirect()->route('student.formations.show', $formation)->with('info', 'Vous êtes déjà inscrit à cette formation.');
        }

        $validated = $request->validate([
            'moyen_paiement_id' => ['required', 'integer', 'exists:moyens_paiement,id'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ]);

        $moyen = MoyenPaiement::query()->where('active', true)->findOrFail($validated['moyen_paiement_id']);

        $remise = 0.0;
        $coupon = null;

        if (! empty($validated['coupon_code'])) {
            $resultat = $validator->validate($validated['coupon_code'], $formation, (float) $formation->prix);

            if (! $resultat['valid']) {
                return redirect()
                    ->route('student.checkout.show', ['formation' => $formation, 'coupon' => $validated['coupon_code']])
                    ->with('error', $resultat['message']);
            }

            $remise = $resultat['remise'];
            $coupon = $resultat['coupon'];
        }

        $facture = $processor->createFacture($request->user(), $formation, $remise);
        $processor->payer($facture, $moyen, $request->user());

        if ($coupon !== null) {
            $validator->consume($coupon);
        }

        $notifier->notify(
            $request->user(),
            'paiement',
            'Paiement confirmé',
            "Votre paiement de {$facture->total_ttc} pour « {$formation->titre} » a été accepté. Bonne formation !",
            ['facture_id' => $facture->id, 'formation_id' => $formation->id],
            actionUrl: route('student.factures.show', $facture),
            actionText: 'Voir ma facture',
        );

        return redirect()->route('student.factures.show', $facture)
            ->with('success', 'Paiement accepté ! Vous êtes désormais inscrit à la formation.');
    }

    private function dejaInscrit(Request $request, Formation $formation): bool
    {
        $etudiant = $request->user()->etudiant;

        return $etudiant !== null && FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->exists();
    }
}
