<?php

namespace App\Services;

use App\Models\Catalogue\Formation;
use App\Models\Certification\Certificat;
use App\Models\Etudiant\Etudiant;
use App\Models\Evaluation\Tentative;
use App\Models\Progression\FormationEtudiant;
use Illuminate\Support\Str;

class CertificatIssuer
{
    /**
     * A certificate can be issued once the enrollment reaches 100% progression.
     */
    public function isEligible(Etudiant $etudiant, Formation $formation): bool
    {
        return FormationEtudiant::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->where('progression', '>=', 100)
            ->exists();
    }

    /**
     * Issue (or return the existing) certificate for a completed formation.
     */
    public function issueFor(Etudiant $etudiant, Formation $formation): Certificat
    {
        $existant = Certificat::query()
            ->where('etudiant_id', $etudiant->id)
            ->where('formation_id', $formation->id)
            ->first();

        if ($existant !== null) {
            return $existant;
        }

        $score = $this->score($etudiant, $formation);

        return Certificat::query()->create([
            'etudiant_id' => $etudiant->id,
            'formation_id' => $formation->id,
            'numero' => $this->uniqueNumero(),
            'date_emission' => now(),
            'score' => $score,
            'mention' => $this->mention($score),
            'hash_verification' => $this->uniqueHash(),
            'statut' => 'VALIDE',
        ]);
    }

    /**
     * Average quiz result on a /20 scale, or 20 when the formation has no graded attempt.
     */
    private function score(Etudiant $etudiant, Formation $formation): float
    {
        $notes = Tentative::query()
            ->where('etudiant_id', $etudiant->id)
            ->whereHas('evaluation', fn ($q) => $q->where('formation_id', $formation->id))
            ->whereNotNull('note')
            ->with('evaluation:id,note_max')
            ->get()
            ->groupBy('evaluation_id')
            ->map(function ($tentatives) {
                $meilleure = $tentatives->sortByDesc('note')->first();
                $noteMax = (float) ($meilleure->evaluation->note_max ?? 20);

                return $noteMax > 0 ? (float) $meilleure->note / $noteMax * 20 : 0.0;
            });

        return $notes->isEmpty() ? 20.0 : round($notes->avg(), 2);
    }

    private function mention(float $score): string
    {
        return match (true) {
            $score >= 16 => 'Très bien',
            $score >= 14 => 'Bien',
            $score >= 12 => 'Assez bien',
            default => 'Passable',
        };
    }

    private function uniqueNumero(): string
    {
        do {
            $numero = 'CERT-'.now()->format('Y').'-'.Str::upper(Str::random(6));
        } while (Certificat::query()->where('numero', $numero)->exists());

        return $numero;
    }

    private function uniqueHash(): string
    {
        do {
            $hash = Str::lower(Str::random(40));
        } while (Certificat::query()->where('hash_verification', $hash)->exists());

        return $hash;
    }
}
