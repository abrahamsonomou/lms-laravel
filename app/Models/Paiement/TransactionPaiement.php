<?php

namespace App\Models\Paiement;

use App\Models\Core\Devise;
use App\Models\Facturation\Facture;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('transactions_paiement')]
#[Fillable(['reference', 'user_id', 'facture_id', 'fournisseur_id', 'moyen_paiement_id', 'montant', 'devise_id', 'montant_converti', 'devise_base_id', 'taux_change', 'statut', 'transaction_externe', 'date_transaction'])]
class TransactionPaiement extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'montant_converti' => 'decimal:2',
            'taux_change' => 'decimal:8',
            'date_transaction' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(FournisseurPaiement::class, 'fournisseur_id');
    }

    public function moyenPaiement(): BelongsTo
    {
        return $this->belongsTo(MoyenPaiement::class, 'moyen_paiement_id');
    }

    public function deviseSource(): BelongsTo
    {
        return $this->belongsTo(Devise::class, 'devise_id');
    }

    public function deviseBase(): BelongsTo
    {
        return $this->belongsTo(Devise::class, 'devise_base_id');
    }

    public function remboursements(): HasMany
    {
        return $this->hasMany(Remboursement::class, 'transaction_id');
    }
}
