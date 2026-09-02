<?php

namespace App\Models\Cours;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('chapitres')]
#[Fillable(['module_cours_id', 'titre', 'description', 'ordre', 'active'])]
class Chapitre extends Model
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

    public function moduleCours(): BelongsTo
    {
        return $this->belongsTo(ModuleCours::class, 'module_cours_id');
    }

    public function lecons(): HasMany
    {
        return $this->hasMany(Lecon::class, 'chapitre_id');
    }
}
