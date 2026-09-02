<?php

namespace App\Models\Catalogue;

use App\Models\Core\Langue;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('categorie_traductions')]
#[Fillable(['categorie_id', 'langue_id', 'nom', 'description'])]
class CategorieTraduction extends Model
{
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieFormation::class, 'categorie_id');
    }

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }
}
