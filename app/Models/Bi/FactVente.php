<?php

namespace App\Models\Bi;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('fact_ventes')]
#[Fillable(['date_id', 'formation_id', 'organisation_id', 'devise_id', 'nombre_ventes', 'revenu', 'panier_moyen'])]
class FactVente extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_id' => 'integer',
            'formation_id' => 'integer',
            'organisation_id' => 'integer',
            'devise_id' => 'integer',
            'nombre_ventes' => 'integer',
            'revenu' => 'decimal:2',
            'panier_moyen' => 'decimal:2',
        ];
    }
}
