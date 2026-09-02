<?php

namespace App\Models\Mailing;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('fournisseurs_email')]
#[Fillable(['nom', 'provider', 'host', 'port', 'username', 'password', 'from_email', 'from_name', 'active'])]
class FournisseurEmail extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'port' => 'integer',
            'active' => 'boolean',
        ];
    }
}
