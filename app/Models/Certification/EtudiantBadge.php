<?php

namespace App\Models\Certification;

use App\Models\Etudiant\Etudiant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('etudiant_badges')]
#[Fillable(['etudiant_id', 'badge_id', 'date_obtention'])]
class EtudiantBadge extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_obtention' => 'datetime',
        ];
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }
}
