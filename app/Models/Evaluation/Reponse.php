<?php

namespace App\Models\Evaluation;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('reponses')]
#[Fillable(['question_id', 'libelle', 'correcte', 'points', 'ordre'])]
class Reponse extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'correcte' => 'boolean',
            'points' => 'decimal:2',
            'ordre' => 'integer',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
