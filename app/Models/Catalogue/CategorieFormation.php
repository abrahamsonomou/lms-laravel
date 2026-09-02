<?php

namespace App\Models\Catalogue;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('categories_formations')]
#[Fillable(['parent_id', 'code', 'nom', 'description', 'image', 'active'])]
class CategorieFormation extends Model
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CategorieFormation::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CategorieFormation::class, 'parent_id');
    }

    public function traductions(): HasMany
    {
        return $this->hasMany(CategorieTraduction::class, 'categorie_id');
    }

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class, 'categorie_id');
    }
}
