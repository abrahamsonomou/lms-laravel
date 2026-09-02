<?php

namespace Database\Seeders;

use App\Models\Catalogue\Formation;
use App\Models\Progression\FormationEtudiant;
use App\Models\User;
use App\Services\CertificatIssuer;
use App\Services\Notifier;
use Illuminate\Database\Seeder;

class PedagogieSeeder extends Seeder
{
    public function run(): void
    {
        $formation = Formation::query()->where('slug', 'laravel-de-zero-a-heros')->first();

        if ($formation === null) {
            return;
        }

        $cours = $formation->cours()->firstOrCreate(
            ['code' => 'C1'],
            ['titre' => 'Prise en main de Laravel', 'ordre' => 1, 'statut' => 'PUBLIE', 'active' => true],
        );

        $module = $cours->modules()->firstOrCreate(
            ['titre' => 'Fondamentaux'],
            ['ordre' => 1, 'active' => true],
        );

        $chapitre = $module->chapitres()->firstOrCreate(
            ['titre' => 'Installation & routing'],
            ['ordre' => 1, 'active' => true],
        );

        $lecons = [
            ['titre' => 'Installer Laravel', 'type' => 'VIDEO', 'url' => 'https://laravel.com/docs'],
            ['titre' => 'Le routing', 'type' => 'TEXTE', 'url' => null],
            ['titre' => 'Les contrôleurs', 'type' => 'VIDEO', 'url' => 'https://laravel.com/docs/controllers'],
        ];

        foreach ($lecons as $ordre => $data) {
            $lecon = $chapitre->lecons()->firstOrCreate(
                ['titre' => $data['titre']],
                ['type' => $data['type'], 'ordre' => $ordre + 1, 'obligatoire' => true, 'active' => true],
            );

            if ($data['url'] !== null) {
                $lecon->contenus()->firstOrCreate(
                    ['url' => $data['url']],
                    ['type' => $data['type'], 'titre' => $data['titre'], 'ordre' => 1, 'active' => true],
                );
            }
        }

        $evaluation = $formation->evaluations()->firstOrCreate(
            ['titre' => 'Quiz — Fondamentaux Laravel'],
            ['type' => 'QUIZ', 'duree' => 15, 'note_max' => 20, 'note_min' => 10, 'tentatives_max' => 3, 'active' => true],
        );

        $questions = [
            [
                'question' => 'Quelle commande lance le serveur de développement Laravel ?',
                'reponses' => [['php artisan serve', true], ['php run dev', false], ['laravel start', false]],
            ],
            [
                'question' => 'Quel dossier contient les migrations ?',
                'reponses' => [['database/migrations', true], ['app/Migrations', false], ['routes/', false]],
            ],
        ];

        foreach ($questions as $ordre => $q) {
            $question = $evaluation->questions()->firstOrCreate(
                ['question' => $q['question']],
                ['type' => 'QCM', 'points' => 1, 'ordre' => $ordre + 1],
            );

            foreach ($q['reponses'] as $index => [$libelle, $correcte]) {
                $question->reponses()->firstOrCreate(
                    ['libelle' => $libelle],
                    ['correcte' => $correcte, 'points' => 0, 'ordre' => $index + 1],
                );
            }
        }

        $user = User::query()->where('email', 'student@lms.test')->first();
        $etudiant = $user?->etudiant;

        if ($etudiant !== null) {
            FormationEtudiant::query()->updateOrCreate(
                ['etudiant_id' => $etudiant->id, 'formation_id' => $formation->id],
                ['date_inscription' => now(), 'date_debut' => now(), 'progression' => 100, 'statut' => 'TERMINE', 'date_fin' => now()],
            );

            $certificat = app(CertificatIssuer::class)->issueFor($etudiant, $formation);

            $notifier = app(Notifier::class);
            $notifier->notify($user, 'inscription', 'Inscription confirmée', "Vous êtes inscrit à la formation « {$formation->titre} ».", ['formation_id' => $formation->id], email: false);
            $notifier->notify($user, 'certificat', 'Certificat obtenu 🎓', "Votre certificat pour « {$formation->titre} » est disponible.", ['certificat_id' => $certificat->id], email: false);
        }
    }
}
