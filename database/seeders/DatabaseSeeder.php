<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LangueSeeder::class,
            DeviseSeeder::class,
            PaysSeeder::class,
            RolePermissionSeeder::class,
            DemoUsersSeeder::class,
            CatalogueSeeder::class,
            PaiementSeeder::class,
            AbonnementSeeder::class,
            PedagogieSeeder::class,
            StudioSeeder::class,
        ]);

        // Matérialise l'entrepôt décisionnel à partir des données seedées.
        Artisan::call('bi:rebuild');
    }
}
