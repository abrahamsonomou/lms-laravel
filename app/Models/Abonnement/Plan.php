<?php

namespace App\Models\Abonnement;

use App\Models\Core\Devise;
use App\Models\Organisation\Organisation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('plans')]
#[Fillable(['organisation_id', 'code', 'nom', 'description', 'prix', 'devise_id', 'duree', 'type', 'active'])]
class Plan extends Model
{
    /** @var array<int, string> */
    public const TYPES = ['MENSUEL', 'ANNUEL'];

    /**
     * Duration in days: the explicit `duree` if set, otherwise derived from the type.
     */
    public function dureeEnJours(): int
    {
        if ($this->duree !== null && $this->duree > 0) {
            return $this->duree;
        }

        return $this->type === 'ANNUEL' ? 365 : 30;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'duree' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }

    public function abonnements(): HasMany
    {
        return $this->hasMany(Abonnement::class);
    }
}
