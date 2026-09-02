<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('pays')]
#[Fillable(['code', 'iso2', 'iso3', 'nom', 'indicatif_telephone', 'active'])]
class Pays extends Model
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

    public function villes(): HasMany
    {
        return $this->hasMany(Ville::class, 'pays_id');
    }
}
