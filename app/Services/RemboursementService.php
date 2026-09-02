<?php

namespace App\Services;

use App\Models\Abonnement\Abonnement;
use App\Models\Facturation\Facture;
use App\Models\Paiement\Remboursement;
use App\Models\Paiement\TransactionPaiement;
use App\Models\Progression\FormationEtudiant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RemboursementService
{
    /**
     * Refund a paid invoice: record the refund, revoke access to purchased
     * formations and cancel any subscription tied to the invoice.
     */
    public function refundFacture(Facture $facture, string $motif): ?Remboursement
    {
        return DB::transaction(function () use ($facture, $motif): ?Remboursement {
            $transaction = TransactionPaiement::query()
                ->where('facture_id', $facture->id)
                ->where('statut', 'REUSSI')
                ->latest()
                ->first();

            $remboursement = null;

            if ($transaction !== null) {
                $remboursement = Remboursement::query()->create([
                    'transaction_id' => $transaction->id,
                    'reference' => $this->uniqueReference(),
                    'montant' => $transaction->montant,
                    'devise_id' => $transaction->devise_id,
                    'motif' => $motif,
                    'statut' => 'REUSSI',
                    'date_remboursement' => now(),
                ]);

                $transaction->update(['statut' => 'REMBOURSE']);
            }

            $facture->update(['statut' => 'REMBOURSEE']);

            $this->revoquerAcces($facture);

            Abonnement::query()
                ->where('facture_id', $facture->id)
                ->update(['statut' => 'ANNULE', 'date_fin' => now()]);

            return $remboursement;
        });
    }

    private function revoquerAcces(Facture $facture): void
    {
        $etudiant = $facture->client?->etudiant;

        if ($etudiant === null) {
            return;
        }

        $formationIds = $facture->lignes()->whereNotNull('formation_id')->pluck('formation_id');

        FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->whereIn('formation_id', $formationIds)
            ->delete();
    }

    private function uniqueReference(): string
    {
        do {
            $reference = 'REMB-'.Str::upper(Str::random(10));
        } while (Remboursement::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
