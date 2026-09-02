<?php

namespace App\Models\Bi;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('dim_etudiant')]
#[Fillable(['etudiant_id', 'matricule', 'pays'])]
class DimEtudiant extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'etudiant_id' => 'integer',
        ];
    }
}
