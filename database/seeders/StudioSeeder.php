<?php

namespace Database\Seeders;

use App\Models\Studio\StudioProject;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@lms.test')->first();

        $project = StudioProject::query()->updateOrCreate(
            ['nom' => 'Site vitrine'],
            ['type' => 'LANDING', 'description' => 'Pages marketing de la plateforme.', 'statut' => 'PUBLIE', 'created_by' => $admin?->id],
        );

        $project->pages()->updateOrCreate(
            ['slug' => 'bienvenue'],
            [
                'nom' => 'Bienvenue',
                'ordre' => 1,
                'active' => true,
                'contenu_json' => [
                    ['type' => 'hero', 'titre' => 'Apprenez à votre rythme', 'sous_titre' => 'Des centaines de formations accessibles partout.', 'bouton_texte' => 'Découvrir', 'bouton_url' => '/formations'],
                    ['type' => 'texte', 'titre' => 'Pourquoi nous choisir ?', 'contenu' => "Contenus de qualité, certifications reconnues et suivi personnalisé.\nRejoignez une communauté d'apprenants motivés."],
                    ['type' => 'cta', 'titre' => 'Prêt à commencer ?', 'bouton_texte' => 'Créer un compte', 'bouton_url' => '/register'],
                ],
            ],
        );
    }
}
