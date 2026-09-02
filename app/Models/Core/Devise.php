<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('devises')]
#[Fillable(['code', 'symbole', 'nom', 'nombre_decimales', 'active'])]
class Devise extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nombre_decimales' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function tauxChangeSource(): HasMany
    {
        return $this->hasMany(TauxChange::class, 'devise_source_id');
    }

    public function tauxChangeCible(): HasMany
    {
        return $this->hasMany(TauxChange::class, 'devise_cible_id');
    }
}
