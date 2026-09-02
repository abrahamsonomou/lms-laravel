<?php

namespace App\Models\Cours;

use App\Models\Catalogue\Formation;
use App\Models\Formateur\Formateur;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('cours')]
#[Fillable(['formation_id', 'formateur_id', 'code', 'titre', 'description', 'ordre', 'duree', 'statut', 'active'])]
class Cours extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(ModuleCours::class, 'cours_id');
    }
}
