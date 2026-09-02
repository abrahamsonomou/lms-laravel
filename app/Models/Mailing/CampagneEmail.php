<?php

namespace App\Models\Mailing;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('campagnes_email')]
#[Fillable(['nom', 'objet', 'template_id', 'date_planification', 'date_envoi', 'statut'])]
class CampagneEmail extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_planification' => 'datetime',
            'date_envoi' => 'datetime',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function envois(): HasMany
    {
        return $this->hasMany(EmailEnvoi::class, 'campagne_id');
    }
}
