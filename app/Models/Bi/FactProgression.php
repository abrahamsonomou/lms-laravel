<?php

namespace App\Models\Bi;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('fact_progressions')]
#[Fillable(['date_id', 'formation_id', 'etudiant_id', 'progression_moyenne', 'temps_total'])]
class FactProgression extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_id' => 'integer',
            'formation_id' => 'integer',
            'etudiant_id' => 'integer',
            'progression_moyenne' => 'decimal:2',
            'temps_total' => 'integer',
        ];
    }
}
