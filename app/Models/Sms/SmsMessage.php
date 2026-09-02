<?php

namespace App\Models\Sms;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('sms_messages')]
#[Fillable(['fournisseur_id', 'user_id', 'telephone', 'contenu', 'reference', 'statut', 'date_envoi', 'date_livraison', 'erreur'])]
class SmsMessage extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_envoi' => 'datetime',
            'date_livraison' => 'datetime',
        ];
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(FournisseurSms::class, 'fournisseur_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
