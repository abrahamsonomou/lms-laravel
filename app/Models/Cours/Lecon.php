<?php

namespace App\Models\Cours;

use App\Models\Contenu\Contenu;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('lecons')]
#[Fillable(['chapitre_id', 'type', 'titre', 'description', 'ordre', 'duree', 'obligatoire', 'active'])]
class Lecon extends Model
{
    /** @var array<int, string> */
    public const TYPES = ['VIDEO', 'PDF', 'AUDIO', 'TEXTE', 'QUIZ', 'EXERCICE', 'LIEN'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'obligatoire' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class, 'chapitre_id');
    }

    public function contenus(): HasMany
    {
        return $this->hasMany(Contenu::class, 'lecon_id');
    }
}
