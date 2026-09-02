<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('fournisseurs_sms')]
#[Fillable(['code', 'nom', 'provider', 'api_url', 'username', 'password', 'api_key', 'sender_id', 'active'])]
class FournisseurSms extends Model
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

    public function messages(): HasMany
    {
        return $this->hasMany(SmsMessage::class, 'fournisseur_id');
    }
}
