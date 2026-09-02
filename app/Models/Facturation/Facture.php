<?php

namespace App\Models\Facturation;

use App\Models\Core\Devise;
use App\Models\Organisation\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('factures')]
#[Fillable(['organisation_id', 'client_id', 'numero', 'date_facture', 'date_echeance', 'sous_total', 'taxe', 'remise', 'total_ht', 'total_ttc', 'devise_id', 'taux_change', 'statut'])]
class Facture extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_facture' => 'date',
            'date_echeance' => 'date',
            'sous_total' => 'decimal:2',
            'taxe' => 'decimal:2',
            'remise' => 'decimal:2',
            'total_ht' => 'decimal:2',
            'total_ttc' => 'decimal:2',
            'taux_change' => 'decimal:8',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneFacture::class);
    }
}
