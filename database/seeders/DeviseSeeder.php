<?php

namespace Database\Seeders;

use App\Models\Core\Devise;
use Illuminate\Database\Seeder;

class DeviseSeeder extends Seeder
{
    public function run(): void
    {
        $devises = [
            ['code' => 'GNF', 'symbole' => 'FG', 'nom' => 'Franc guinéen', 'nombre_decimales' => 0],
            ['code' => 'XOF', 'symbole' => 'CFA', 'nom' => 'Franc CFA (BCEAO)', 'nombre_decimales' => 0],
            ['code' => 'CDF', 'symbole' => 'FC', 'nom' => 'Franc congolais', 'nombre_decimales' => 2],
            ['code' => 'USD', 'symbole' => '$', 'nom' => 'Dollar américain', 'nombre_decimales' => 2],
            ['code' => 'EUR', 'symbole' => '€', 'nom' => 'Euro', 'nombre_decimales' => 2],
        ];

        foreach ($devises as $devise) {
            Devise::query()->updateOrCreate(
                ['code' => $devise['code']],
                [...$devise, 'active' => true],
            );
        }
    }
}
