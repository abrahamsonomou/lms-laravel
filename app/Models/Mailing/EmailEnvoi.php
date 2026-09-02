<?php

namespace App\Models\Mailing;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('email_envois')]
#[Fillable(['campagne_id', 'user_id', 'email', 'statut', 'date_envoi', 'date_lecture', 'date_clic', 'erreur'])]
class EmailEnvoi extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_envoi' => 'datetime',
            'date_lecture' => 'datetime',
            'date_clic' => 'datetime',
        ];
    }

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneEmail::class, 'campagne_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
