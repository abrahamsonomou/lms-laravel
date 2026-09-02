<?php

namespace App\Models\Organisation;

use App\Models\Core\Devise;
use App\Models\Core\Langue;
use App\Models\Core\Pays;
use App\Models\Core\Ville;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('organisations')]
#[Fillable(['code', 'nom', 'email', 'telephone', 'pays_id', 'ville_id', 'devise_id', 'langue_id', 'logo', 'adresse', 'active'])]
class Organisation extends Model
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

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class, 'devise_id');
    }

    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }

    public function etablissements(): HasMany
    {
        return $this->hasMany(Etablissement::class, 'organisation_id');
    }

    public function departements(): HasMany
    {
        return $this->hasMany(Departement::class, 'organisation_id');
    }
}
