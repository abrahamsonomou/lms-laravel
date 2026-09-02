<?php

namespace App\Console\Commands;

use App\Models\Abonnement\Abonnement;
use App\Services\AbonnementService;
use App\Services\Notifier;
use Illuminate\Console\Command;

class ExpirerAbonnements extends Command
{
    protected $signature = 'abonnements:expirer';

    protected $description = 'Renouvelle les abonnements en auto-renouvellement et expire les autres arrivés à échéance.';

    public function handle(AbonnementService $service, Notifier $notifier): int
    {
        $echus = Abonnement::query()
            ->where('statut', 'ACTIF')
            ->whereNotNull('date_fin')
            ->where('date_fin', '<', now())
            ->with('plan')
            ->get();

        $renouveles = 0;
        $expires = 0;

        foreach ($echus as $abonnement) {
            $user = $abonnement->utilisateurs()->first();

            if ($abonnement->auto_renew) {
                $service->renew($abonnement);
                $renouveles++;

                if ($user !== null) {
                    $notifier->notify($user, 'abonnement', 'Abonnement renouvelé', "Votre abonnement « {$abonnement->plan?->nom} » a été renouvelé.", ['abonnement_id' => $abonnement->id]);
                }

                continue;
            }

            $abonnement->update(['statut' => 'EXPIRE']);
            $expires++;

            if ($user !== null) {
                $notifier->notify($user, 'abonnement', 'Abonnement expiré', "Votre abonnement « {$abonnement->plan?->nom} » a expiré. Renouvelez-le pour garder l'accès.", ['abonnement_id' => $abonnement->id]);
            }
        }

        $this->info("Abonnements renouvelés : {$renouveles} — expirés : {$expires}.");

        return self::SUCCESS;
    }
}
