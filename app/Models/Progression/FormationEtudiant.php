<?php

namespace App\Models\Progression;

use App\Models\Catalogue\Formation;
use App\Models\Etudiant\Etudiant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('formations_etudiants')]
#[Fillable(['etudiant_id', 'formation_id', 'date_inscription', 'date_debut', 'date_fin', 'progression', 'statut'])]
class FormationEtudiant extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_inscription' => 'datetime',
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'progression' => 'decimal:2',
        ];
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
}
