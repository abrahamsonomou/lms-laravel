<?php

namespace App\Models\Paiement;

use App\Models\Core\Pays;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('fournisseurs_paiement')]
#[Fillable(['code', 'nom', 'type', 'pays_id', 'api_url', 'public_key', 'secret_key', 'active'])]
class FournisseurPaiement extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class);
    }

    public function moyensPaiement(): HasMany
    {
        return $this->hasMany(MoyenPaiement::class, 'fournisseur_id');
    }
}
