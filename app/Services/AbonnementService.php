<?php

namespace App\Services;

use App\Models\Abonnement\Abonnement;
use App\Models\Abonnement\Plan;
use App\Models\Paiement\MoyenPaiement;
use App\Models\Paiement\TransactionPaiement;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AbonnementService
{
    public function __construct(private PaiementProcessor $processor) {}

    /**
     * Subscribe a user to a plan: process the payment and create the active subscription.
     */
    public function subscribe(User $user, Plan $plan, MoyenPaiement $moyen): Abonnement
    {
        return DB::transaction(function () use ($user, $plan, $moyen): Abonnement {
            $facture = $this->processor->createFactureLibre(
                $user,
                "Abonnement — {$plan->nom}",
                (float) $plan->prix,
                $plan->devise_id,
            );

            $this->processor->payer($facture, $moyen, $user);

            $abonnement = Abonnement::query()->create([
                'organisation_id' => $plan->organisation_id,
                'plan_id' => $plan->id,
                'facture_id' => $facture->id,
                'date_debut' => now(),
                'date_fin' => now()->addDays($plan->dureeEnJours()),
                'prix' => $plan->prix,
                'devise_id' => $plan->devise_id,
                'statut' => 'ACTIF',
                'auto_renew' => false,
            ]);

            $abonnement->utilisateurs()->attach($user->id);

            return $abonnement;
        });
    }

    /**
     * Renew a subscription: charge the plan again and extend the end date.
     */
    public function renew(Abonnement $abonnement): Abonnement
    {
        return DB::transaction(function () use ($abonnement): Abonnement {
            $plan = $abonnement->plan;
            $client = $abonnement->utilisateurs()->first();

            if ($plan === null || $client === null) {
                return $abonnement;
            }

            $moyen = $this->dernierMoyen($abonnement) ?? MoyenPaiement::query()->where('active', true)->first();

            if ($moyen === null) {
                return $abonnement;
            }

            $facture = $this->processor->createFactureLibre($client, "Renouvellement — {$plan->nom}", (float) $plan->prix, $plan->devise_id);
            $this->processor->payer($facture, $moyen, $client);

            $base = $abonnement->date_fin instanceof Carbon && $abonnement->date_fin->isFuture()
                ? $abonnement->date_fin->copy()
                : now();

            $abonnement->update([
                'facture_id' => $facture->id,
                'date_fin' => $base->addDays($plan->dureeEnJours()),
                'statut' => 'ACTIF',
            ]);

            return $abonnement;
        });
    }

    private function dernierMoyen(Abonnement $abonnement): ?MoyenPaiement
    {
        $moyenId = TransactionPaiement::query()
            ->where('facture_id', $abonnement->facture_id)
            ->where('statut', 'REUSSI')
            ->latest()
            ->value('moyen_paiement_id');

        return $moyenId !== null ? MoyenPaiement::query()->find($moyenId) : null;
    }
}
