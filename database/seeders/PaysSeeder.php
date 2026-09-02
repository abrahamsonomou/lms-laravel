<?php

namespace Database\Seeders;

use App\Models\Core\Pays;
use Illuminate\Database\Seeder;

class PaysSeeder extends Seeder
{
    public function run(): void
    {
        $pays = [
            ['code' => 'GN', 'iso2' => 'GN', 'iso3' => 'GIN', 'nom' => 'Guinée', 'indicatif_telephone' => '+224'],
            ['code' => 'SN', 'iso2' => 'SN', 'iso3' => 'SEN', 'nom' => 'Sénégal', 'indicatif_telephone' => '+221'],
            ['code' => 'CI', 'iso2' => 'CI', 'iso3' => 'CIV', 'nom' => "Côte d'Ivoire", 'indicatif_telephone' => '+225'],
            ['code' => 'ML', 'iso2' => 'ML', 'iso3' => 'MLI', 'nom' => 'Mali', 'indicatif_telephone' => '+223'],
            ['code' => 'CD', 'iso2' => 'CD', 'iso3' => 'COD', 'nom' => 'RD Congo', 'indicatif_telephone' => '+243'],
            ['code' => 'FR', 'iso2' => 'FR', 'iso3' => 'FRA', 'nom' => 'France', 'indicatif_telephone' => '+33'],
            ['code' => 'US', 'iso2' => 'US', 'iso3' => 'USA', 'nom' => 'États-Unis', 'indicatif_telephone' => '+1'],
        ];

        foreach ($pays as $item) {
            Pays::query()->updateOrCreate(
                ['code' => $item['code']],
                [...$item, 'active' => true],
            );
        }
    }
}
