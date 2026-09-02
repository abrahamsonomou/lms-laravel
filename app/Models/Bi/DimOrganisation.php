<?php

namespace App\Models\Bi;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('dim_organisation')]
#[Fillable(['organisation_id', 'code', 'nom'])]
class DimOrganisation extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'organisation_id' => 'integer',
        ];
    }
}
