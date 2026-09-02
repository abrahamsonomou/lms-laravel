<?php

namespace App\Models\Cours;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('modules_cours')]
#[Fillable(['cours_id', 'titre', 'description', 'ordre', 'duree', 'active'])]
class ModuleCours extends Model
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

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class, 'module_cours_id');
    }
}
