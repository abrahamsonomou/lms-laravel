<?php

namespace App\Models\Evaluation;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('reponses_etudiants')]
#[Fillable(['tentative_id', 'question_id', 'reponse_id', 'reponse_texte', 'points', 'correcte'])]
class ReponseEtudiant extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points' => 'decimal:2',
            'correcte' => 'boolean',
        ];
    }

    public function tentative(): BelongsTo
    {
        return $this->belongsTo(Tentative::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function reponse(): BelongsTo
    {
        return $this->belongsTo(Reponse::class);
    }
}
