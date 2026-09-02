<?php

namespace App\Models\Formateur;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Table('specialites')]
#[Fillable(['code', 'nom', 'description', 'active'])]
class Specialite extends Model
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

    public function formateurs(): BelongsToMany
    {
        return $this->belongsToMany(Formateur::class, 'formateur_specialites');
    }
}
