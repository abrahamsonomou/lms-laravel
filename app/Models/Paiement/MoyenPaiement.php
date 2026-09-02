<?php

namespace App\Models\Paiement;

use App\Models\Core\Devise;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('moyens_paiement')]
#[Fillable(['fournisseur_id', 'code', 'nom', 'type', 'devise_id', 'active'])]
class MoyenPaiement extends Model
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

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(FournisseurPaiement::class, 'fournisseur_id');
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }
}
