<?php

namespace Database\Seeders;

use App\Models\Core\Devise;
use App\Models\Coupon\Coupon;
use App\Models\Paiement\FournisseurPaiement;
use Illuminate\Database\Seeder;

class PaiementSeeder extends Seeder
{
    public function run(): void
    {
        $devise = Devise::query()->where('code', 'EUR')->first();

        Coupon::query()->updateOrCreate(
            ['code' => 'BIENVENUE10'],
            ['nom' => 'Remise de bienvenue', 'type_remise' => 'POURCENTAGE', 'valeur' => 10, 'utilisations' => 0, 'active' => true],
        );

        $fournisseurs = [
            ['code' => 'ORANGE', 'nom' => 'Orange Money', 'type' => 'MOBILE_MONEY', 'moyens' => [['OM', 'Orange Money']]],
            ['code' => 'MTN', 'nom' => 'MTN Mobile Money', 'type' => 'MOBILE_MONEY', 'moyens' => [['MOMO', 'MTN MoMo']]],
            ['code' => 'STRIPE', 'nom' => 'Stripe', 'type' => 'CARTE', 'moyens' => [['VISA', 'Carte Visa'], ['MASTERCARD', 'Mastercard']]],
        ];

        foreach ($fournisseurs as $data) {
            $fournisseur = FournisseurPaiement::query()->updateOrCreate(
                ['code' => $data['code']],
                ['nom' => $data['nom'], 'type' => $data['type'], 'active' => true],
            );

            foreach ($data['moyens'] as [$code, $nom]) {
                $fournisseur->moyensPaiement()->updateOrCreate(
                    ['code' => $code],
                    ['nom' => $nom, 'type' => $data['type'], 'devise_id' => $devise?->id, 'active' => true],
                );
            }
        }
    }
}
