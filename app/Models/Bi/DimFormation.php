<?php

namespace App\Models\Bi;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('dim_formation')]
#[Fillable(['formation_id', 'titre', 'categorie'])]
class DimFormation extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'formation_id' => 'integer',
        ];
    }
}
