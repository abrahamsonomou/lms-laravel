<?php

namespace App\Models\Paiement;

use App\Models\Core\Devise;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('remboursements')]
#[Fillable(['transaction_id', 'reference', 'montant', 'devise_id', 'motif', 'statut', 'date_remboursement'])]
class Remboursement extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'date_remboursement' => 'datetime',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(TransactionPaiement::class, 'transaction_id');
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }
}
