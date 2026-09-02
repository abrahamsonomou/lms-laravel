<?php

namespace App\Models\Abonnement;

use App\Models\Core\Devise;
use App\Models\Facturation\Facture;
use App\Models\Organisation\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('abonnements')]
#[Fillable(['organisation_id', 'plan_id', 'facture_id', 'date_debut', 'date_fin', 'prix', 'devise_id', 'statut', 'auto_renew'])]
class Abonnement extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'prix' => 'decimal:2',
            'auto_renew' => 'boolean',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class, 'facture_id');
    }

    public function devise(): BelongsTo
    {
        return $this->belongsTo(Devise::class);
    }

    public function utilisateurs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'abonnement_utilisateurs', 'abonnement_id', 'user_id');
    }
}
