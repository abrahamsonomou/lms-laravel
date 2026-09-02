<?php

namespace App\Services;

use App\Models\Catalogue\Formation;
use App\Models\Facturation\Facture;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Paiement\TransactionPaiement;
use App\Models\Progression\FormationEtudiant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaiementProcessor
{
    /**
     * Create a draft invoice with a single line for the given formation.
     */
    public function createFacture(User $client, Formation $formation, float $remise = 0): Facture
    {
        return DB::transaction(function () use ($client, $formation, $remise): Facture {
            $montant = (float) $formation->prix;
            $remise = min($remise, $montant);
            $net = round($montant - $remise, 2);

            $facture = Facture::query()->create([
                'client_id' => $client->id,
                'numero' => $this->uniqueNumero(),
                'date_facture' => now(),
                'sous_total' => $montant,
                'taxe' => 0,
                'remise' => $remise,
                'total_ht' => $net,
                'total_ttc' => $net,
                'devise_id' => $formation->devise_id,
                'statut' => 'BROUILLON',
            ]);

            $facture->lignes()->create([
                'formation_id' => $formation->id,
                'description' => $formation->titre,
                'quantite' => 1,
                'prix_unitaire' => $montant,
                'remise' => $remise,
                'taxe' => 0,
                'total' => $net,
            ]);

            return $facture;
        });
    }

    /**
     * Create a draft invoice with a single free-form line (not tied to a formation).
     */
    public function createFactureLibre(User $client, string $description, float $montant, ?int $deviseId = null): Facture
    {
        return DB::transaction(function () use ($client, $description, $montant, $deviseId): Facture {
            $facture = Facture::query()->create([
                'client_id' => $client->id,
                'numero' => $this->uniqueNumero(),
                'date_facture' => now(),
                'sous_total' => $montant,
                'taxe' => 0,
                'remise' => 0,
                'total_ht' => $montant,
                'total_ttc' => $montant,
                'devise_id' => $deviseId,
                'statut' => 'BROUILLON',
            ]);

            $facture->lignes()->create([
                'description' => $description,
                'quantite' => 1,
                'prix_unitaire' => $montant,
                'remise' => 0,
                'taxe' => 0,
                'total' => $montant,
            ]);

            return $facture;
        });
    }

    /**
     * Process a (simulated) successful payment: record the transaction, mark the
     * invoice as paid and enroll the student in each purchased formation.
     */
    public function payer(Facture $facture, MoyenPaiement $moyen, User $user): TransactionPaiement
    {
        return DB::transaction(function () use ($facture, $moyen, $user): TransactionPaiement {
            $transaction = TransactionPaiement::query()->create([
                'reference' => $this->uniqueReference(),
                'user_id' => $user->id,
                'facture_id' => $facture->id,
                'fournisseur_id' => $moyen->fournisseur_id,
                'moyen_paiement_id' => $moyen->id,
                'montant' => $facture->total_ttc,
                'devise_id' => $facture->devise_id,
                'statut' => 'REUSSI',
                'transaction_externe' => 'SIM-'.Str::upper(Str::random(10)),
                'date_transaction' => now(),
            ]);

            $facture->update(['statut' => 'PAYEE']);

            $etudiant = $user->etudiant;

            if ($etudiant !== null) {
                foreach ($facture->lignes()->whereNotNull('formation_id')->get() as $ligne) {
                    FormationEtudiant::query()->firstOrCreate(
                        ['etudiant_id' => $etudiant->id, 'formation_id' => $ligne->formation_id],
                        ['date_inscription' => now(), 'date_debut' => now(), 'progression' => 0, 'statut' => 'INSCRIT'],
                    );
                }
            }

            return $transaction;
        });
    }

    private function uniqueNumero(): string
    {
        do {
            $numero = 'FAC-'.now()->format('Y').'-'.Str::upper(Str::random(6));
        } while (Facture::query()->where('numero', $numero)->exists());

        return $numero;
    }

    private function uniqueReference(): string
    {
        do {
            $reference = 'TXN-'.Str::upper(Str::random(12));
        } while (TransactionPaiement::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
