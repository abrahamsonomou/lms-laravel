<?php

namespace App\Models\Sms;

use App\Models\Organisation\Organisation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('sms_quotas')]
#[Fillable(['organisation_id', 'fournisseur_id', 'quota', 'consomme', 'reste', 'date_debut', 'date_fin'])]
class SmsQuota extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quota' => 'integer',
            'consomme' => 'integer',
            'reste' => 'integer',
            'date_debut' => 'date',
            'date_fin' => 'date',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(FournisseurSms::class, 'fournisseur_id');
    }
}
