<?php

namespace App\Models\Bi;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('dim_temps')]
#[Fillable(['date', 'jour', 'mois', 'trimestre', 'annee', 'jour_semaine', 'semaine', 'nom_mois'])]
class DimTemps extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
