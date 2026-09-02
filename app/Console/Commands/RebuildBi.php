<?php

namespace App\Console\Commands;

use App\Models\Bi\DimFormation;
use App\Models\Bi\FactInscription;
use App\Models\Bi\FactProgression;
use App\Models\Bi\FactVente;
use App\Models\Catalogue\Formation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RebuildBi extends Command
{
    protected $signature = 'bi:rebuild';

    protected $description = 'Reconstruit l\'entrepôt décisionnel (dimensions et faits) à partir des données transactionnelles.';

    public function handle(): int
    {
        DB::transaction(function (): void {
            DimFormation::query()->delete();
            FactVente::query()->delete();
            FactInscription::query()->delete();
            FactProgression::query()->delete();

            Formation::query()->with('categorie')->chunk(200, function ($formations): void {
                foreach ($formations as $formation) {
                    DimFormation::query()->create([
                        'formation_id' => $formation->id,
                        'titre' => $formation->titre,
                        'categorie' => $formation->categorie?->nom,
                    ]);
                }
            });

            $ventes = DB::table('lignes_factures')
                ->join('factures', 'factures.id', '=', 'lignes_factures.facture_id')
                ->where('factures.statut', 'PAYEE')
                ->whereNotNull('lignes_factures.formation_id')
                ->groupBy('lignes_factures.formation_id')
                ->selectRaw('lignes_factures.formation_id, COUNT(*) as nb, SUM(lignes_factures.total) as revenu')
                ->get();

            foreach ($ventes as $vente) {
                FactVente::query()->create([
                    'formation_id' => $vente->formation_id,
                    'nombre_ventes' => (int) $vente->nb,
                    'revenu' => (float) $vente->revenu,
                    'panier_moyen' => $vente->nb > 0 ? round((float) $vente->revenu / $vente->nb, 2) : 0,
                ]);
            }

            $inscriptions = DB::table('formations_etudiants')
                ->groupBy('formation_id')
                ->selectRaw("formation_id, COUNT(*) as total, SUM(CASE WHEN statut = 'TERMINE' THEN 1 ELSE 0 END) as termines, SUM(CASE WHEN statut = 'ABANDONNE' THEN 1 ELSE 0 END) as abandons, AVG(progression) as progression_moyenne")
                ->get();

            foreach ($inscriptions as $ligne) {
                FactInscription::query()->create([
                    'formation_id' => $ligne->formation_id,
                    'nombre_inscriptions' => (int) $ligne->total,
                    'nombre_termines' => (int) $ligne->termines,
                    'nombre_abandons' => (int) $ligne->abandons,
                ]);

                FactProgression::query()->create([
                    'formation_id' => $ligne->formation_id,
                    'progression_moyenne' => round((float) $ligne->progression_moyenne, 2),
                    'temps_total' => 0,
                ]);
            }
        });

        $this->info('Entrepôt BI reconstruit : '.DimFormation::query()->count().' formations, '
            .FactVente::query()->count().' faits ventes, '
            .FactInscription::query()->count().' faits inscriptions.');

        return self::SUCCESS;
    }
}
