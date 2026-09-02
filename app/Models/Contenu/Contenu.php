<?php

namespace App\Models\Contenu;

use App\Models\Cours\Lecon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Table('contenus')]
#[Fillable(['lecon_id', 'type', 'titre', 'url', 'fichier', 'mime_type', 'taille', 'duree', 'ordre', 'active'])]
class Contenu extends Model
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

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class, 'lecon_id');
    }

    public function video(): HasOne
    {
        return $this->hasOne(Video::class, 'contenu_id');
    }
}
