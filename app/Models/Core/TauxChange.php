<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('taux_change')]
#[Fillable(['devise_source_id', 'devise_cible_id', 'taux', 'date_effet', 'date_fin', 'source', 'active'])]
class TauxChange extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'taux' => 'decimal:8',
            'date_effet' => 'date',
            'date_fin' => 'date',
            'active' => 'boolean',
        ];
    }

    public function deviseSource(): BelongsTo
    {
        return $this->belongsTo(Devise::class, 'devise_source_id');
    }

    public function deviseCible(): BelongsTo
    {
        return $this->belongsTo(Devise::class, 'devise_cible_id');
    }
}
