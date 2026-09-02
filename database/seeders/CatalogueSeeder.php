<?php

namespace Database\Seeders;

use App\Models\Catalogue\CategorieFormation;
use App\Models\Catalogue\Formation;
use App\Models\Core\Devise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $devise = Devise::query()->where('code', 'EUR')->first();
        $createur = User::query()->where('email', 'teacher@lms.test')->first();

        $categories = collect([
            ['code' => 'DEV', 'nom' => 'Développement'],
            ['code' => 'DESIGN', 'nom' => 'Design'],
            ['code' => 'MARKETING', 'nom' => 'Marketing'],
        ])->map(fn (array $data): CategorieFormation => CategorieFormation::query()->updateOrCreate(
            ['code' => $data['code']],
            ['nom' => $data['nom'], 'active' => true],
        ));

        $formations = [
            ['titre' => 'Laravel de zéro à héros', 'cat' => 'DEV', 'niveau' => 'INTERMEDIAIRE', 'prix' => 49.99, 'statut' => Formation::STATUT_PUBLIE],
            ['titre' => 'React & TypeScript', 'cat' => 'DEV', 'niveau' => 'AVANCE', 'prix' => 59.99, 'statut' => Formation::STATUT_PUBLIE],
            ['titre' => 'UI/UX avec Figma', 'cat' => 'DESIGN', 'niveau' => 'DEBUTANT', 'prix' => 39.00, 'statut' => Formation::STATUT_PUBLIE],
            ['titre' => 'SEO & Growth', 'cat' => 'MARKETING', 'niveau' => 'DEBUTANT', 'prix' => 0, 'statut' => Formation::STATUT_PUBLIE],
            ['titre' => 'DevOps avec Docker', 'cat' => 'DEV', 'niveau' => 'AVANCE', 'prix' => 69.00, 'statut' => Formation::STATUT_BROUILLON],
        ];

        foreach ($formations as $item) {
            $categorie = $categories->firstWhere('code', $item['cat']);

            Formation::query()->updateOrCreate(
                ['slug' => Str::slug($item['titre'])],
                [
                    'categorie_id' => $categorie?->id,
                    'code' => Str::upper(Str::slug($item['titre'], '_')),
                    'titre' => $item['titre'],
                    'description' => 'Une formation complète et pratique : '.$item['titre'].'.',
                    'objectifs' => "Maîtriser les fondamentaux\nRéaliser un projet concret\nObtenir un certificat",
                    'niveau' => $item['niveau'],
                    'duree' => 20,
                    'prix' => $item['prix'],
                    'devise_id' => $devise?->id,
                    'type' => $item['prix'] > 0 ? 'PAYANTE' : 'GRATUITE',
                    'statut' => $item['statut'],
                    'date_publication' => $item['statut'] === Formation::STATUT_PUBLIE ? now() : null,
                    'created_by' => $createur?->id,
                ],
            );
        }
    }
}
