<?php

namespace App\Models\Coupon;

use App\Models\Catalogue\Formation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Table('coupons')]
#[Fillable(['code', 'nom', 'type_remise', 'valeur', 'montant_minimum', 'date_debut', 'date_fin', 'nombre_utilisations', 'utilisations', 'active'])]
class Coupon extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'valeur' => 'decimal:2',
            'montant_minimum' => 'decimal:2',
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'nombre_utilisations' => 'integer',
            'utilisations' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(Formation::class, 'coupon_formations');
    }
}
