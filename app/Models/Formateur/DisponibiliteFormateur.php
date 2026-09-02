<?php

namespace App\Models\Formateur;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('disponibilites_formateurs')]
#[Fillable(['formateur_id', 'jour', 'heure_debut', 'heure_fin', 'active'])]
class DisponibiliteFormateur extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class);
    }
}
