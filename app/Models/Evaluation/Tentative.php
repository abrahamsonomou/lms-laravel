<?php

namespace App\Models\Evaluation;

use App\Models\Etudiant\Etudiant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('tentatives')]
#[Fillable(['evaluation_id', 'etudiant_id', 'numero', 'date_debut', 'date_fin', 'score', 'note', 'statut'])]
class Tentative extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'numero' => 'integer',
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'score' => 'decimal:2',
            'note' => 'decimal:2',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    public function reponsesEtudiants(): HasMany
    {
        return $this->hasMany(ReponseEtudiant::class);
    }
}
