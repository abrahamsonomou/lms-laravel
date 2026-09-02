<?php

namespace App\Models\Bi;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('fact_inscriptions')]
#[Fillable(['date_id', 'formation_id', 'organisation_id', 'pays_id', 'nombre_inscriptions', 'nombre_termines', 'nombre_abandons'])]
class FactInscription extends Model
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
            'pays_id' => 'integer',
            'nombre_inscriptions' => 'integer',
            'nombre_termines' => 'integer',
            'nombre_abandons' => 'integer',
        ];
    }
}
