<?php

namespace Database\Seeders;

use App\Models\Core\Langue;
use Illuminate\Database\Seeder;

class LangueSeeder extends Seeder
{
    public function run(): void
    {
        $langues = [
            ['code' => 'fr', 'nom' => 'Français', 'locale' => 'fr_FR'],
            ['code' => 'en', 'nom' => 'English', 'locale' => 'en_US'],
            ['code' => 'pt', 'nom' => 'Português', 'locale' => 'pt_PT'],
            ['code' => 'es', 'nom' => 'Español', 'locale' => 'es_ES'],
            ['code' => 'ar', 'nom' => 'العربية', 'locale' => 'ar_SA'],
        ];

        foreach ($langues as $langue) {
            Langue::query()->updateOrCreate(
                ['code' => $langue['code']],
                [...$langue, 'active' => true],
            );
        }
    }
}
