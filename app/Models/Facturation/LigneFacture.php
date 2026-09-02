<?php

namespace App\Models\Facturation;

use App\Models\Catalogue\Formation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('lignes_factures')]
#[Fillable(['facture_id', 'formation_id', 'description', 'quantite', 'prix_unitaire', 'remise', 'taxe', 'total'])]
class LigneFacture extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantite' => 'decimal:2',
            'prix_unitaire' => 'decimal:2',
            'remise' => 'decimal:2',
            'taxe' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }
}
