<?php

namespace App\Models\Certification;

use App\Models\Etudiant\Etudiant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Table('badges')]
#[Fillable(['code', 'nom', 'description', 'image', 'conditions', 'active'])]
class Badge extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'active' => 'boolean',
        ];
    }

    public function etudiants(): BelongsToMany
    {
        return $this->belongsToMany(Etudiant::class, 'etudiant_badges')
            ->withPivot('date_obtention')
            ->withTimestamps();
    }
}
