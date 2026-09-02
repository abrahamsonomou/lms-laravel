<?php

namespace App\Models\Catalogue;

use App\Models\Core\Langue;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('formation_traductions')]
#[Fillable(['formation_id', 'langue_id', 'titre', 'description', 'objectifs', 'prerequis', 'contenu'])]
class FormationTraduction extends Model
{
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }
}
