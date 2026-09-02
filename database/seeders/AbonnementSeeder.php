<?php

namespace Database\Seeders;

use App\Models\Abonnement\Plan;
use App\Models\Core\Devise;
use Illuminate\Database\Seeder;

class AbonnementSeeder extends Seeder
{
    public function run(): void
    {
        $devise = Devise::query()->where('code', 'EUR')->first();

        $plans = [
            ['code' => 'MENSUEL', 'nom' => 'Mensuel', 'prix' => 19.99, 'duree' => 30, 'type' => 'MENSUEL', 'description' => 'Accès illimité à toutes les formations, facturé chaque mois.'],
            ['code' => 'ANNUEL', 'nom' => 'Annuel', 'prix' => 199.00, 'duree' => 365, 'type' => 'ANNUEL', 'description' => 'Accès illimité à toutes les formations, 2 mois offerts.'],
        ];

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(
                ['code' => $plan['code']],
                [...$plan, 'devise_id' => $devise?->id, 'active' => true],
            );
        }
    }
}
